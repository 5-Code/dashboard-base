<?php

namespace Habib\Dashboard\Services\Upload;


use Illuminate\Http\UploadedFile;

interface UploadServiceContract
{
    /**
     * @param UploadedFile $file
     * @param string|null $collection
     * @param array $options
     * @return FileInfo
     */
    public function upload(UploadedFile $file, null|string $collection = null, array $options = []): FileInfo;

    /**
     * @param UploadedFile $file
     * @param string|null $collection
     * @param array $options
     * @return FileInfo
     */
    public function avatar(UploadedFile $file, ?string $collection = null, array $options = []): FileInfo;

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
     * @return FileInfo
     */
    public function uploadBase64(string $file, null|string $collection = null, array $options = []): FileInfo;

    /**
     * @param string $file
     * @return UploadedFile
     */
    public function base64ToFile(string $file): UploadedFile;
}
