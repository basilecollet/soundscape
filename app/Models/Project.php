<?php

namespace App\Models;

use App\Domain\Admin\Entities\Enums\ProjectStatus;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Project
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $status
 * @property string|null $description
 * @property string|null $short_description
 * @property string|null $client_name
 * @property Carbon|null $project_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Project extends Model implements HasMedia
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    use InteractsWithMedia;

    protected $table = 'projects';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'slug',
        'status',
        'description',
        'short_description',
        'client_name',
        'project_date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'project_date' => 'date:Y-m-d',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Thumbnail pour listes admin (optimisation espace disque)
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('featured', 'gallery');

        // Version optimisée web (balance qualité/taille)
        $this->addMediaConversion('web')
            ->width(1200)
            ->height(900)
            ->quality(85)
            ->optimize()
            ->performOnCollections('featured', 'gallery');

        // Preview pour formulaire admin
        $this->addMediaConversion('preview')
            ->width(800)
            ->height(600)
            ->quality(90)
            ->performOnCollections('featured', 'gallery');

        // Responsive images (multiple tailles)
        $this->addMediaConversion('responsive')
            ->withResponsiveImages()
            ->performOnCollections('featured', 'gallery');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
