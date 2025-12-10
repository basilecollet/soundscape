<?php

namespace App\Models;

use Database\Factories\SectionSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionSetting extends Model
{
    /** @use HasFactory<SectionSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'section_key',
        'page',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
