<?php

use App\Models\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->text('name_key')->nullable();
        });

        DB::table('countries')->select('id', 'name')->chunkById(100, function ($countries) {
            foreach ($countries as $country) {
                DB::table('countries')->where('id', $country->id)->update([
                    'name_key' => Country::nameKey($country->name),
                ]);
            }
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->text('name_key')->change();
        });

        DB::statement('CREATE UNIQUE INDEX countries_active_name_key_unique ON countries (name_key) WHERE deleted_at IS NULL');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX countries_active_name_key_unique');

        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('name_key');
        });
    }
};
