<?php

namespace Habib\Dashboard\Models\Traits;

use Habib\Dashboard\Models\Media;
use Habib\Dashboard\Services\Upload\FileInfo;
use Habib\Dashboard\Services\Upload\UploadService;
use Illuminate\Http\UploadedFile;

trait MediaTraits
{

    public function upload(UploadedFile $file, string $collections = null, array $options = []): Media
    {
        $options['dir'] ??= str($this->getTable())->pluralize()->snake()->toString();
        $image = UploadService::new()->upload($file, $collections ?? $options['dir'], $options);
        return $this->addMedia($image);
    }

    /**
     * @param FileInfo $fileInfo
     * @return Media|bool
     */
    public function addMedia(FileInfo $fileInfo): Media|bool
    {
        $attributes = $this->parseMediaInfo($fileInfo);

        return $this->attachMedia(new Media($attributes));
    }

    /**
     * @param FileInfo $fileInfo
     * @return array
     */
    public function parseMediaInfo(FileInfo $fileInfo): array
    {
        $attributes = [
            'name' => $fileInfo->getName(),
            'path' => $fileInfo->getPath(),
            'size' => $fileInfo->getSize(),
            'mime_type' => $fileInfo->getMime(),
            'file_hash' => $fileInfo->getHash(),
            'collection' => $fileInfo->getCollection(),
            'disk' => $fileInfo->getDisk(),
            'visibility' => $fileInfo->isVisibility(),
            'file_name' => $fileInfo->getOriginalName(),
        ];

        if (auth()->check()) {
            $attributes['owner_id'] = auth()->id();
            $attributes['owner_type'] = auth()->user()->getMorphClass();
        }

        return $attributes;
    }

    /**
     * @param Media $media
     * @return Media|bool
     */
    abstract public function attachMedia(Media $media): Media|bool;
}
