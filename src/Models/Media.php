<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Casts\JsonCast;
use Habib\Dashboard\Models\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Storage;

class Media extends Model
{
    use HasOwner;
    protected $guarded = [];

    protected $casts = [
        'disk' => 'string',
        'file_name' => 'string',
        'file_hash' => 'string',
        'mime_type' => 'string',
        'name' => 'string',
        'path' => 'string',
        'visibility' => 'boolean',
        'size' => 'integer',
        'model_id' => 'integer',
        'model_type' => 'string',
        'owner_id' => 'integer',
        'owner_type' => 'string',
        'options' => JsonCast::class,
        'collection' => 'string',
    ];

    protected static function booted()
    {
        parent::booted();
        static::deleting(function (self $model) {
            $model->deleteFile();
        });

        static::creating(function (self $model) {
            if (auth()->check()) {
                $model->owner_id ??= auth()->id();
                $model->owner_type ??= auth()->user()->getMorphClass();
            }
            $model->collection ??= 'default';
        });
    }

    /**
     * @return bool
     */
    public function deleteFile(): bool
    {
        return Storage::disk($this->disk)->delete($this->path);
    }

    /**
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
