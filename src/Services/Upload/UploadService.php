<?php

namespace Habib\Dashboard\Services\Upload;

use Habib\Dashboard\Services\Upload\File as FileTemplate;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation\File\File as FileFromUrl;

class UploadService implements UploadServiceContract
{
    use Macroable;

    public static function new(): static
    {
        return new static();
    }

    /**
     * @param $path
     * @param $width
     * @param $height
     * @param $type
     * @return string
     */
    public static function thumb($path, $width = null, $height = null, $type = "fit"): string
    {
        $thumb_images_path = config('dashboard.thumb_images_path', 'thumbs');
        $images_path = config('dashboard.images_path', '');
        $path = ltrim($path, "/");

        //if path exists and is image
        if (!File::exists(public_path("{$images_path}/" . $path))) {
            $width = is_null($width) ? 400 : $width;
            $height = is_null($height) ? 400 : $height;

            // returns an image placeholder generated from placehold.it
            return "http://placehold.it/{$width}x{$height}";
        }

        $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png'];
        $contentType = mime_content_type(public_path("{$images_path}/" . $path));

        if (!in_array($contentType, $allowedMimeTypes)) {
            $width = is_null($width) ? 400 : $width;
            $height = is_null($height) ? 400 : $height;

            // returns an image placeholder generated from placehold.it
            return "http://placehold.it/{$width}x{$height}";
        }

        //returns the original image if no width and height
        if (is_null($width) && is_null($height)) {
            return "{$images_path}/" . $path;
        }

        //if thumbnail exist returns it
        if (File::exists(public_path("{$images_path}/$thumb_images_path/" . "{$width}x{$height}_{$type}/" . $path))) {
            return "{$images_path}/$thumb_images_path/" . "{$width}x{$height}_{$type}/" . $path;
        }


        $image = Image::make(public_path("{$images_path}/" . $path));

        switch ($type) {
            case "fit":
            {
                $image->fit($width, $height, function ($constraint) {
                });
                break;
            }
            case "resize":
            {
                //stretched
                $image->resize($width, $height);
            }
            case "background":
            {
                $image->resize($width, $height, function ($constraint) {
                    //keeps aspect ratio and sets black background
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            case "resizeCanvas":
            {
                $image->resizeCanvas($width, $height, 'center', false, 'rgba(0, 0, 0, 0)'); //gets the center part
            }
        }

        //relative directory path starting from main directory of images
        $dir_path = (dirname($path) == '.') ? "" : dirname($path);

        //Create the directory if it doesn't exist
        if (!File::exists(public_path("{$images_path}/$thumb_images_path/" . "{$width}x{$height}_{$type}/" . $dir_path))) {
            File::makeDirectory(public_path("{$images_path}/$thumb_images_path/" . "{$width}x{$height}_{$type}/" . $dir_path), 0775, true);
        }

        //Save the thumbnail
        $image->save(public_path("{$images_path}/$thumb_images_path/" . "{$width}x{$height}_{$type}/" . $path));

        //return the url of the thumbnail
        return "{$images_path}/$thumb_images_path/" . "{$width}x{$height}_{$type}/" . $path;
    }

    /**
     * @param UploadedFile $file
     * @param string|null $collection
     * @param array $options
     * @return \Habib\Dashboard\Services\Upload\File
     */
    public function avatar(UploadedFile $file, ?string $collection = null, array $options = []): \Habib\Dashboard\Services\Upload\File
    {
        $options = [
            'name' => $file->hashName(),
            'disk' => $options['disk'] ?? 'public',
            'dir' => $options['dir'] ?? $collection ?? 'avatars',
        ];

        return $this->upload($file, $collection, $options);
    }

    /**
     * @param UploadedFile $file
     * @param string|null $collection
     * @param array $options
     * @return \Habib\Dashboard\Services\Upload\File
     */
    public function upload(UploadedFile $file, null|string $collection = null, array $options = []): \Habib\Dashboard\Services\Upload\File
    {
        $name = $options['name'] ?? $file->hashName();
        $disk = $options['disk'] ?? config('filesystems.default', 'public');
        $baseDir = $options['dir'] ?? $collection ?? config('app.base_file_upload', 'files');
        $path = $file->storeAs($baseDir, $name, compact('disk'));
        $hash = hash_file(
            config('app.uploads.hash', config('app.key', 'habib')),
            storage_path(
                path: "$baseDir/{$name}",
            ),
        );
        $size = $file->getSize();
        $mime = $file->getMimeType();
        $originalName = $file->getClientOriginalName();

        return new FileTemplate(
            name: $name,
            originalName: $originalName,
            mime: $mime,
            path: $path,
            disk: $disk,
            hash: $hash,
            size: $size,
            collection: $collection,
        );
    }

    /**
     * @param array $files
     * @param string|null $collection
     * @param array $options
     * @return array
     */
    public function multiUpload(array $files, null|string $collection = null, array $options = []): array
    {
        $uploadedFiles = [];

        foreach ($files as $key => $file) {
            if ($file instanceof UploadedFile) {
                $uploadedFiles[] = $this->upload($file, $collection, $options);
            } elseif (is_array($file)) {
                $uploadedFiles[$key] = $this->multiUpload($file, $collection, $options);
            }
        }

        return $uploadedFiles;
    }

    /**
     * @param string $keyName
     * @param string|null $collection
     * @param array $options
     * @return \Habib\Dashboard\Services\Upload\File
     */
    public function uploadFormRequest(string $keyName, null|string $collection = null, array $options = []): \Habib\Dashboard\Services\Upload\File
    {
        return $this->upload(request()->file($keyName), $collection, $options);
    }

    /**
     * @param string $file
     * @param string|null $collection
     * @param array $options
     * @return \Habib\Dashboard\Services\Upload\File
     */
    public function uploadBase64(string $file, null|string $collection = null, array $options = []): \Habib\Dashboard\Services\Upload\File
    {
        return $this->upload($this->base64ToFile($file), $collection, $options);
    }

    //type:
    // fit - best fit possible for given width & height - by default
    //resize - exact resize of image
    //background - fit image perfectly keeping ratio and adding black background
    //resizeCanvas - keep only center

    /**
     * @param string $file
     * @return UploadedFile
     */
    public function base64ToFile(string $file): UploadedFile
    {

        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $file));

        // save it to temporary dir first.
        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
        file_put_contents($tmpFilePath, $fileData);

        // this just to help us get file info.
        $tmpFile = new FileFromUrl($tmpFilePath);

        return new UploadedFile(
            $tmpFile->getPathname(),
            $tmpFile->getFilename(),
            $tmpFile->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );
    }
//    /**
//     * @param array $options
//     * @return Filesystem
//     */
//    public function getDisk(array $options = []): Filesystem
//    {
//        return Storage::build($options + [
//                'driver' => 'local',
//                'root' => public_path('uploads'),
//                'url' => config('app.url') . "/uploads",
//                'visibility' => 'public',
//                'throw' => false,
//                'permissions' => [
//                    'file' => [
//                        'public' => 0644,
//                        'private' => 0600,
//                    ],
//                    'dir' => [
//                        'public' => 0755,
//                        'private' => 0700,
//                    ],
//                ],
//            ]);
//    }
}
