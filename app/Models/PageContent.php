<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = [
        'key',
        'content',
        'title',
        'page',
    ];

    /**
     * Get content by key
     */
    public static function getContent(string $key, string $default = ''): string
    {
        $content = self::where('key', $key)->first();

        return $content ? $content->content : $default;
    }
}
