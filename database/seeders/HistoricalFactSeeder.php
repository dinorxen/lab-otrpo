<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoricalFactSeeder extends Seeder
{
    public function run(): void
    {
        $facts = [
            'Франция' => [
                // https://www.elysee.fr/la-presidence/la-declaration-des-droits-de-l-homme-et-du-citoyen
                1789 => '26 августа Учредительное собрание приняло Декларацию прав человека и гражданина. В ней были провозглашены свобода и равенство людей в правах.',
                // https://www.elections.interieur.gouv.fr/comprendre-elections/pourquoi-je-vote/institutions-democratiques
                1958 => 'Конституция от 4 октября положила начало Пятой республике. Она усилила исполнительную власть и учредила Конституционный совет.',
            ],
            'Австрия' => [
                // https://www.parlament.gv.at/kriegsende-staatsvertrag-eu-beitritt/index.html
                1955 => '15 мая в венском дворце Бельведер подписали Государственный договор, восстановивший полный суверенитет Австрии. После его вступления в силу начался вывод оккупационных войск.',
                // https://www.bmwet.gv.at/en/Topics/Europe-and-EU/Austria-in-the-European-Union.html?lang=en
                1995 => '1 января Австрия вступила в Европейский союз. Вместе с ней членами ЕС стали Финляндия и Швеция.',
            ],
            'Нидерланды' => [
                // https://www.nationaalarchief.nl/beleven/onderwijs/bronnenbox/eindelijk-vrede-1648
                1648 => 'Мюнстерский мир положил конец Восьмидесятилетней войне. Испания признала независимость Республики Соединённых провинций Нидерландов.',
                // https://www.rijkswaterstaat.nl/water/waterbeheer/bescherming-tegen-het-water/watersnoodramp-1953
                1953 => 'В ночь на 1 февраля сильный шторм и высокий прилив вызвали наводнение. После катастрофы Нидерланды начали сооружать систему дамб и заграждений «Дельта».',
            ],
            'Япония' => [
                // https://www.ndl.go.jp/modern/e/utility/chronology.html
                1868 => '3 января был издан указ о восстановлении императорской власти. Он положил начало новому правительству эпохи Мэйдзи.',
                // https://www.ndl.go.jp/constitution/e/outline/05outline.html
                1947 => '3 мая вступила в силу послевоенная Конституция Японии. Она закрепила народный суверенитет и определила императора как символ государства.',
            ],
            'Италия' => [
                // https://www.senato.it/CESUS/17marzo2026/
                1861 => '17 марта в Турине было провозглашено объединение Италии. Виктор Эммануил II принял титул короля Италии.',
                // https://www.senato.it/CESUS/2026/2giugno/
                1946 => 'На референдуме 2 июня большинство проголосовало за республику. Одновременно итальянцы избрали Учредительное собрание.',
            ],
        ];

        DB::transaction(function () use ($facts) {
            $countries = Country::query()->whereIn('name', array_keys($facts))->orderBy('id')->lockForUpdate()->get();

            foreach ($countries as $country) {
                foreach ($facts[$country->name] as $year => $text) {
                    $country->historicalFacts()->firstOrCreate(['year' => $year, 'text' => $text]);
                }
            }
        });
    }
}
