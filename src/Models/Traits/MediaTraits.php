<?php

namespace Habib\Dashboard\Models\Traits;

use Habib\Dashboard\Models\Media;
use Habib\Dashboard\Services\Upload\FileInfo;

trait MediaTraits
{

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
     * @param FileInfo $fileInfo
     * @return Model|bool
     */
    public function addMedia(FileInfo $fileInfo): Model|bool
    {
        $attributes = $this->parseMediaInfo($fileInfo);

        return $this->attachMedia(new Media($attributes));
    }

}
