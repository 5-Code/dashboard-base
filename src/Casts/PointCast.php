<?php

namespace Habib\Dashboard\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class PointCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        if (!isset($attributes["{$key}_text"]) || !$attributes["{$key}_text"]) {
            return null;
        }

        [$lat, $lng] = explode(" ", trim(str_replace('POINT', '', $attributes["{$key}_text"]), '()'));
        $lng = floatval($lng);
        $lat = floatval($lat);
        return compact('lat', 'lng');
    }

    public function set($model, $key, $value, $attributes)
    {
        if (blank($value)) return null;

        if (is_array($value)) {
            if (isset($value['lat']) && isset($value['lng'])) {
                return "SRID=4326;POINT({$value['lat']} {$value['lng']})";
            }

            if (isset($value[1]) && isset($value[0])) {
                return "SRID=4326;POINT({$value[0]} {$value[1]})";
            }
        }

        if (is_string($value)) {

            if (str_starts_with($value, 'POINT(')) {
                return "SRID=4326;$value";
            }

            if (str_starts_with($value, 'SRID=4326;')) {
                return "$value";
            }
        }

        return null;
    }
}
