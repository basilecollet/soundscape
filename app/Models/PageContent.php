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

    /**
     * Check if content exists for a given key
     */
    public static function hasContent(string $key): bool
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Get content by key without fallback
     */
    public static function getContentOrNull(string $key): ?string
    {
        $content = self::where('key', $key)->first();

        return $content?->content;
    }
}
