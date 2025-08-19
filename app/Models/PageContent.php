<?php

namespace App\Models;

use Database\Factories\PageContentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TFactory of PageContentFactory
 *
 * @implements HasFactory<TFactory>
 */
class PageContent extends Model
{
    /** @use HasFactory<TFactory> */
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
