<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

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
            set: function ($value) {
                return trim($value);
            },
        );
    }

    protected function createdAtFormatted(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return date('d.m.Y H:i', strtotime($attributes['created_at']));
            },
        );
    }
}