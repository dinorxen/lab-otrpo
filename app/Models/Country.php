<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Normalizer;

class Country extends Model
{
    use SoftDeletes;

    public const NAME_PATTERN = "\p{L}[\p{L}\p{M}]*(?:(?: +|['’\-])\p{L}[\p{L}\p{M}]*)*";

    public const DESCRIPTION_MIN = 50;

    public const DESCRIPTION_MAX = 10000;

    public const AREA_MAX = 2147483647;

    private const COUNTRY_NAMES = [
        'Австралия', 'Австрия', 'Азербайджан', 'Аргентина', 'Армения',
        'Беларусь', 'Бельгия', 'Бразилия', 'Великобритания', 'Венгрия',
        'Вьетнам', 'Гвинея-Бисау', 'Германия', 'Греция', 'Грузия',
        'Дания', 'Египет', 'Индия', 'Индонезия', 'Ирландия',
        'Исландия', 'Испания', 'Италия', 'Казахстан', 'Канада',
        'Китай', 'Кот-д’Ивуар', 'Кыргызстан', 'Мексика', 'Нидерланды',
        'Новая Зеландия', 'Норвегия', 'Польша', 'Португалия', 'Россия',
        'Сербия', 'США', 'Таджикистан', 'Турция', 'Узбекистан',
        'Украина', 'Финляндия', 'Франция', 'Чехия', 'Швейцария',
        'Швеция', 'Южная Корея', 'Япония',
    ];

    private const GOVERNMENT_FORMS = [
        'Республика',
        'Президентская республика',
        'Парламентская республика',
        'Президентско-парламентская республика',
        'Федеративная парламентская республика',
        'Конституционная монархия',
        'Парламентская монархия',
        'Абсолютная монархия',
    ];

    protected $fillable = [
        'name',
        'government_form',
        'area',
        'flag_path',
        'description',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ['name' => trim($value), 'name_key' => self::nameKey($value)],
        );
    }

    public static function nameKey(string $name): string
    {
        $name = Normalizer::normalize($name) ?: $name;
        $name = str_replace('’', "'", Str::squish($name));

        return mb_convert_case($name, MB_CASE_FOLD, 'UTF-8');
    }

    public function countryNameOptions(): array
    {
        $names = self::COUNTRY_NAMES;

        if ($this->name !== null && !in_array($this->name, $names, true)) {
            $names[] = $this->name;
        }

        return $names;
    }

    public function governmentFormOptions(): array
    {
        $forms = self::GOVERNMENT_FORMS;

        if ($this->government_form !== null && !in_array($this->government_form, $forms, true)) {
            $forms[] = $this->government_form;
        }

        return $forms;
    }

    protected function createdAtFormatted(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return date('d.m.Y H:i', strtotime($attributes['created_at']));
            },
        );
    }

    public function historicalFacts()
    {
        return $this->hasMany(HistoricalFact::class);
    }
}
    
