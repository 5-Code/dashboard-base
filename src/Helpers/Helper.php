<?php

namespace Habib\Dashboard\Helpers;

use Habib\Dashboard\Http\Request\RequestClient;
use Habib\Dashboard\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class Helper
{
    public static function notify(array $notify, bool $toast = false)
    {
        return match ($toast) {
            true => toast($notify['message'], $notify['type'], $notify['position'] ?? 'top-right'),
            default => alert($notify['title'], $notify['message'], $notify['type'] ?? 'success')
        };
    }

    /**
     * @param  Request  $request
     * @return Visitor
     */
    public static function visitor(Request $request): Visitor
    {
        $reqClient = RequestClient::new();

        $location = $reqClient->locationByIp($request->ip());

        return Visitor::firstOrCreate([
            'ip' => $request->ip(),
            'owner_type' => $request->user()?->getMorphClass(),
            'owner_id' => $request->user()?->id,
        ], [
            'country' => $location?->countryName ?? 'egypt',
            'device' => $reqClient->getCurrentDevice(),
            'operating_system' => $reqClient->getOs(),
            'browser' => $reqClient->getCurrentUserAgent(),
        ]);
    }

    public function getVisitor(Request $request): Visitor
    {
        return Visitor::where('ip', $request->ip())->first();
    }
    public static function fromBase64(string $base64File): UploadedFile
    {
        // Get file data base64 string
        $fileData = base64_decode(Arr::last(explode(',', $base64File)));

        // Create temp file and get its absolute path
        $tempFile = tmpfile();
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];

        // Save file data in file
        file_put_contents($tempFilePath, $fileData);

        $tempFileObject = new File($tempFilePath);
        $file = new UploadedFile(
            $tempFileObject->getPathname(),
            $tempFileObject->getFilename(),
            $tempFileObject->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );

        // Close this file after response is sent.
        // Closing the file will cause to remove it from temp director!
        app()->terminating(function () use ($tempFile) {
            fclose($tempFile);
        });

        // return UploadedFile object
        return $file;
    }
}
