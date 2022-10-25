<?php

namespace Habib\Dashboard\Services\Upload;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\ImageManager;

class FileInfo implements Arrayable
{
    public function __construct(
        private string $name,
        private string $originalName,
        private string $mime,
        private string $path,
        private string $disk,
        private string $hash,
        private int $size,
        private bool $visibility = true,
        private null|string $collection = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'file_name' => $this->getOriginalName(),
            'mime_type' => $this->getMime(),
            'path' => $this->getPath(),
            'disk' => $this->getDisk(),
            'size' => $this->getSize(),
            'file_hash' => $this->getHash(),
            'collection' => $this->getCollection(),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @return string
     */
    public function getMime(): string
    {
        return $this->mime;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getDisk(): string
    {
        return $this->disk;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getCollection(): string
    {
        return $this->collection ?? 'default';
    }

    /**
     * @return bool
     */
    public function isVisibility(): bool
    {
        return $this->visibility;
    }


    /**
     * @return Image
     */
    public function image(): Image
    {
        $file = new File(
            Storage::disk($this->getDisk())->path($this->getPath() . DIRECTORY_SEPARATOR . $this->getName())
        );

        return (new ImageManager('imagick'))->make($file);
    }
}
