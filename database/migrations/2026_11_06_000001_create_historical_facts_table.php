<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Some databases may already have the table from the original countries migration.
        if (Schema::hasTable('historical_facts')) {
            return;
        }

        Schema::create('historical_facts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->index()->constrained()->cascadeOnDelete();
            $table->integer('year');
            $table->text('text');
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historical_facts');
    }
};
