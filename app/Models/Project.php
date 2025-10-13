<?php

namespace App\Models;

use App\Domain\Admin\Entities\Enums\ProjectStatus;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
