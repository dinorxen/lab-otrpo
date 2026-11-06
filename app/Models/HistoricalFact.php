<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricalFact extends Model
{
    public const PHOTO_DIRECTORY = 'historical-facts';

    public const FORM_RULES = [
        'year' => 'required|integer|between:-9999,9999|not_in:0',
        'text' => 'required|string|max:5000',
        'photo' => 'bail|nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ];

    public const FORM_MESSAGES = [
        'year.*' => 'Введите целый год от −9999 до 9999, кроме нуля.',
        'text.required' => 'Введите текст исторического факта.',
        'text.max' => 'Текст должен быть не длиннее 5000 символов.',
        'photo.*' => 'Загрузите изображение JPEG, PNG или WebP размером до 2 МБ.',
    ];

    protected $fillable = [
        'country_id',
        'year',
        'text',
        'photo_path',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
