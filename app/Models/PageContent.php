<?php

namespace App\Models;

use Database\Factories\PageContentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    /** @use HasFactory<PageContentFactory> */
    use HasFactory;

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
