<?php

namespace Habib\Dashboard\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Slugger
{
    public static function new()
    {
        return new static;
    }

    /**
     * @param string $string
     * @param string $separator
     * @return string
     */
    public function slugify(string $string, string $separator = '-'): string
    {
        $string = mb_strtolower($string, 'UTF-8');
        $string = preg_replace('/[^\p{L}\p{Nd}]+/u', $separator, $string);
        return trim($string, $separator);
    }

    /**
     * @param Model $model
     * @param string $key
     * @param string|null $value
     * @return string
     */
    public function slug(Model $model, string $key, ?string $value): string
    {
        $value ??= $model->id ?? uniqid('blog-');
        $value = strip_tags(str_replace('.', '', $value ?? ''));
        $value = $this->createSlug($value);
        $i = 1;
        $slug = $value;
        while (DB::table($model->getTable())->where($key, $slug)->exists()) {
            $slug = "$value-$i";
            $i++;
        }
        return $slug;
    }

    public function createSlug(?string $string, $separator = '-'): array|string|null
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
        $string = preg_replace("/[\s_]/", $separator, $string);

        return $string;
    }
}
