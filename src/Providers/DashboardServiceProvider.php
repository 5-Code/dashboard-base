<?php

namespace Habib\Dashboard\Providers;

use Form;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);

        Http::macro('getIp', function (string $ip) {
            return Http::get("http://www.geoplugin.net/json.gp?ip={$ip}")->body();
        });

        if (file_exists(base_path('app/Providers/RepositoryBinding.php'))) {
            require_once base_path('app/Providers/RepositoryBinding.php');
        }

//        EloquentDataTable::macro('addColumnView', function (string $name, string $view, $data = []) {
//            return $this->addColumn($name, function ($model) use ($data, $view) {
//                return view($view, $model->toArray() + compact('model') + value(is_callable($data) ? $data($model) : $data));
//            });
//        });

//        $this->loadViewsFrom(resource_path('views/dashboard'), 'dashboard');

//        Blade::anonymousComponentNamespace(resource_path('views/dashboard/layout'), 'layout');

        Blueprint::macro('slug', function ($name = 'slug', $length = null) {
            return $this->string($name, $length);
        });

        Blueprint::macro('slugJson', function ($name = 'slug') {
            return $this->jsonb($name);
        });


        Blueprint::macro('lang', function ($columnName, $locales = [], $unique = false): ColumnDefinition {
            /** @var Blueprint $this */
            $column = $this->addColumn('jsonb', $columnName, ["precision" => 0]);
            collect(count($locales) ? $locales : locals())->map(function ($locale) use ($columnName, $unique) {
                /** @var Blueprint $this */
                $localeColumn = $this->text("{$columnName}_{$locale}")
                    ->nullable()
                    ->always()
                    ->storedAs("json_unquote(JSON_EXTRACT($columnName,'$.$locale'))")
                    ->comment("this is $columnName with locale : $locale")
//                    ->index()
                    ->fulltext();
                if ($unique) $localeColumn->unique();
            });
            return $column;
        });

        Form::macro('tags', function ($name, $options = []) {
            $value = $this->getValueAttribute($name, null);
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            return Form::text($name, $value, $options);
        });

    }
}
