<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            'fr' => ['Франция', 'Президентско-парламентская республика', 643801, 'Столица — Париж. Франция расположена в Западной Европе. На юго-востоке находятся Альпы, а на границе с Испанией — Пиренеи.'],
            'at' => ['Австрия', 'Федеративная парламентская республика', 83879, 'Столица — Вена. Австрия расположена в Центральной Европе, значительную часть её территории занимают Альпы. В Зальцбурге родился Моцарт, а в Вене находится дворец Шёнбрунн.'],
            'nl' => ['Нидерланды', 'Конституционная монархия', 41543, 'Столица — Амстердам. Нидерланды расположены у Северного моря. Часть страны лежит ниже уровня моря и защищена дамбами. Амстердам известен своими каналами и музеями.'],
            'jp' => ['Япония', 'Парламентская монархия', 372800, 'Столица — Токио. Япония — островное государство в Восточной Азии. Четыре крупнейших острова — Хонсю, Хоккайдо, Кюсю и Сикоку. Один из символов страны — гора Фудзи.'],
            'it' => ['Италия', 'Парламентская республика', 302073, 'Столица — Рим. Италия расположена в Южной Европе, на Апеннинском полуострове и прилегающих островах. В Риме находятся Колизей и Пантеон, а Венеция известна своими каналами.'],
        ];

        $disk = Storage::disk('public');

        DB::transaction(function () use ($countries, $disk) {
            foreach ($countries as $code => [$name, $governmentForm, $area, $description]) {
                $country = Country::withTrashed()->firstOrNew(['name' => $name], [
                    'government_form' => $governmentForm,
                    'area' => $area,
                    'description' => $description,
                ]);

                if ($country->trashed() || ($country->flag_path && $disk->exists($country->flag_path))) {
                    continue;
                }

                $path = "flags/$code.png";
                if (!$disk->exists($path)) {
                    $image = file_get_contents(__DIR__."/flags/$code.png");
                    if ($image === false || !$disk->put($path, $image)) {
                        throw new RuntimeException("Не удалось сохранить флаг страны «$name».");
                    }
                }

                $country->flag_path = $path;
                $country->save();
            }
        });
    }
}
