<?php

namespace Habib\Dashboard\Services\Upload;


use Illuminate\Http\UploadedFile;

interface UploadServiceContract
{
    /**
     * @param UploadedFile $file
     * @param string|null $collection
     * @param array $options
     * @return File
     */
    public function upload(UploadedFile $file, null|string $collection = null, array $options = []): File;

    /**
     * @param UploadedFile $file
     * @param string|null $collection
     * @param array $options
     * @return File
     */
    public function avatar(UploadedFile $file, ?string $collection = null, array $options = []): File;

    /**
     * @param array $files
     * @param string|null $collection
     * @param array $options
     * @return array
     */
    public function multiUpload(array $files, null|string $collection = null, array $options = []): array;

    /**
     * @param string $file
     * @param string|null $collection
     * @param array $options
     * @return File
     */
    public function uploadBase64(string $file, null|string $collection = null, array $options = []): File;

    /**
     * @param string $file
     * @return UploadedFile
     */
    public function base64ToFile(string $file): UploadedFile;
}
