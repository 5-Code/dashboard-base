<?php

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

if (!function_exists('createSlug')) {
    /**
     * @param string|null $string
     * @param string $separator
     * @return array|string|null
     */
    function createSlug(?string $string, string $separator = '-'): array|string|null
    {
        if (is_null($string)) {
            return "";
        }

        // Remove spaces from the beginning and from the end of the string
        $string = trim($string);

        // Lower case everything
        // using mb_strtolower() function is important for non-Latin UTF-8 string | more info: https://www.php.net/manual/en/function.mb-strtolower.php
        $string = mb_strtolower($string, "UTF-8");

        // Make alphanumeric (removes all other characters)
        // this makes the string safe especially when used as a part of a URL
        // this keeps latin characters and arabic charactrs as well
        $string = preg_replace(
            "/[^a-z0-9_\s\-اآؤئبپتثجچحخدذرزژسشصضطظعغفقكکگلمنوةيإأۀءهی۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩]#u/",
            "",
            $string
        );

        // Remove multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);

        // Convert whitespaces and underscore to the given separator
        return preg_replace("/[\s_]/", $separator, $string);
    }
}

if (!function_exists('setting')) {
    /**
     * @param string $name
     * @param string $default
     * @param string|null $type
     * @param string|null $group_by
     * @param string|null $locale
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    function setting(
        string $name,
        $default = '',
        string $type = null,
        string $group_by = null,
        string $locale = null
    ): string {

        $locale = $locale ?? substr(app()->getLocale(), 0, 2);
        $type = $type ?? 'string';
        if (cache()->has('settings')) {
            $settings = cache()->get('settings');
        } else {
            $settings = cache()->remember('settings', now()->addMinutes(5), function () {
                return Setting::all();
            });
        }

        if ($setting = $settings->where('name', $name)->firstWhere('locale', '=', $locale)) {
            return $setting->value ?? $default;
        }

        return Setting::firstOrCreate(
            ['name' => $name, 'locale' => $locale],
            [
                'name' => $name,
                'type' => $type,
                'locale' => $locale,
                'value' => $default ?? $name,
                'group_by' => $group_by
            ]
        )->value ?? $default;
    }
}


if (!function_exists('uploader')) {
    /**
     * @param $input
     * @param string|null $folder
     * @param array|null $validation
     * @return array|string
     */
    function uploader($input, string|null $folder = null, array|null $validation = null): array|string
    {
        $validation ??= [];
        $folder ??= "";
        $request = request();
        $files = [];
        if (is_array($input)) {
            foreach ($input as $key => $item) {
                if (is_numeric($key)) {
                    $files[] = uploader($item, $folder, $validation);
                } else {
                    $files[$key] = uploader($item, $folder, $validation);
                }
            }
            return $files;
        }
        $isFile = $input instanceof UploadedFile;
        // remove any / char form var
        $path = rtrim($folder, '/');
        $defaultDir = "uploads";
        // validate Image
        if (!$isFile) {
            if (empty($validation)) {
                $request->validate([$input => ['required', 'image', 'mimes:jpeg,jpg,png']]);
            } else {
                $request->validate([$input => $validation]);
            }
        }

        // get file if not getting before
        $file = $isFile ? $input : $request->file($input);

        // this line if true throw Exception 400 with errors
        if (blank($file->getClientOriginalExtension())) {
            response()->json([
                "status" => false,
                "errors" => [(is_string($input) ? $input : "file") => "file without Extension try by other way please ♥."]
            ], 400)->send();
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $disk = Storage::build([
            'driver' => 'local',
            'root' => public_path((string)$defaultDir),
            'url' => config('app.url') . "/{$defaultDir}",
            'visibility' => 'public',
            'throw' => false,
            'permissions' => [
                'file' => [
                    'public' => 0644,
                    'private' => 0600,
                ],
                'dir' => [
                    'public' => 0755,
                    'private' => 0700,
                ],
            ],
        ]);

        $disk->put("{$path}/{$filename}", $file->getContent());

        return str_replace('//', '/', "{$defaultDir}/{$path}/{$filename}");
    }
}

if (!function_exists('locals')) {
    /**
     * @return string[]
     */
    function locals(): array
    {
        return config('app.locales', ['ar', 'en',]);
    }
}

if (!function_exists('current_local')) {
    /**
     * @return string
     */
    function current_local(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('current_dir')) {
    /**
     * @return string
     */
    function current_dir(): string
    {
        return current_local() == "ar" ? 'rtl' : 'ltr';
    }
}

if (!function_exists('default_time_zone')) {
    /**
     * @return string
     */
    function default_time_zone(): string
    {
        return config('app.timezone', 'Africa/Cairo');
    }
}
