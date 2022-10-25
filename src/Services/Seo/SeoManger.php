<?php

namespace Habib\Dashboard\Services\Seo;

use MadeITBelgium\SeoAnalyzer\SeoFacade;

class SeoManger
{
    /**
     * @param string $url
     * @param string|null $content
     * @return false|string|array
     */
    public static function analyze(string $url, string $content = null)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        return SeoFacade::analyze($url, $content);
    }

}
