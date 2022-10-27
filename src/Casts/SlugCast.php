<?php

namespace Habib\Dashboard\Casts;

use DB;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class SlugCast implements CastsAttributes
{
    public function __construct(private string $name = 'name')
    {
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  string  $value
     * @param  array  $attributes
     * @return string
     */
    public function get($model, $key, $value, $attributes)
    {
        $value = str($value);

        return $value->isJson() ? json_decode($value, true) : $value;
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        if ($model->isDirty($key) || is_null($value)) {
            $getValue = str(data_get($attributes, $this->name, ''));
            $slug = [];
            if ($getValue->isJson()) {
                $getValue = json_decode($getValue, true);
                foreach ($getValue as $k => $item) {
                    $slug[$k] = $this->createUnique($model, $item, "{$key}->{$k}");
                }
            } else {
                $slug = $this->createUnique($model, $getValue, $key);
            }

            return $this->jsonCast($slug);
        }

        return $this->jsonCast($value);
    }

    private function createUnique($model, $value, $key): array|string|null
    {
        $value = createSlug(str($value)->replace('.', '')->limit(500));
        $i = 1;
        $slug = strip_tags($value ?? $model->id);
        while (DB::table($model->getTable())->where($key, $slug)->exists()) {
            $slug = "$value-$i";
            $i++;
        }

        return $slug;
    }

    private function jsonCast(array|string $array): bool|string
    {
        return json_encode($array, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
