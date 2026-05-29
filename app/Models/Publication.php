<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Publication extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'tag',
        'category',
        'year',
        'description',
        'content',
        'image_path',
        'file_path',
        'external_url',
        'accent',
        'is_published',
        'published_at',
        'author_id',
    ];

    protected $casts = [
        'year' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected $appends = ['image_url', 'file_url'];

    protected static function booted(): void
    {
        static::creating(function (self $publication) {
            if (empty($publication->slug)) {
                $publication->slug = self::generateUniqueSlug($publication->title);
            }
        });

        static::updating(function (self $publication) {
            if ($publication->isDirty('title') && ! $publication->isDirty('slug')) {
                $publication->slug = self::generateUniqueSlug($publication->title, $publication->id);
            }
        });
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'publication';
        $slug = $base;
        $i = 1;

        while (
            self::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image_path
            ? Storage::disk('public')->url($this->image_path)
            : null);
    }

    protected function fileUrl(): Attribute
    {
        return Attribute::get(fn () => $this->file_path
            ? Storage::disk('public')->url($this->file_path)
            : null);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
