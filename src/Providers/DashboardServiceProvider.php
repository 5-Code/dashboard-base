<?php

namespace Habib\Dashboard\Providers;

use Closure;
use Form;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Http;
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
        $dirname = dirname(dirname(__DIR__));
        $this->mergeConfigFrom($dirname . '/config/dashboard.php', 'dashboard');
        $this->mergeConfigFrom($dirname . '/config/fcm.php', 'fcm');

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $dirname = dirname(dirname(__DIR__));
        if ($this->app->runningInConsole()) {

            $this->publishes([
                $dirname . '/config/dashboard.php' => config_path('dashboard.php'),
                $dirname . '/config/fcm.php' => config_path('fcm.php'),
            ], 'config');
            $this->publishes([
                $dirname . '/database/migrations/2020_08_04_195229_create_settings_table.php' => database_path('migrations/2020_08_04_195229_create_settings_table.php'),
//                $dirname . '/database/migrations/2022_10_19_120020_create_media_table.php' => database_path('migrations/2022_10_19_120020_create_media_table.php'),
                $dirname . '/database/migrations/2022_10_22_181058_create_faqs_table.php' => database_path('migrations/2022_10_22_181058_create_faqs_table.php'),
                $dirname . '/database/migrations/2022_10_22_202218_create_blogs_table.php' => database_path('migrations/2022_10_22_202218_create_blogs_table.php'),
                $dirname . '/database/migrations/2022_10_22_213058_create_contacts_table.php' => database_path('migrations/2022_10_22_213058_create_contacts_table.php'),
                $dirname . '/database/migrations/2022_10_22_223023_create_tickets_table.php' => database_path('migrations/2022_10_22_223023_create_tickets_table.php'),
                $dirname . '/database/migrations/2022_10_22_223347_create_ticket_messages_table.php' => database_path('migrations/2022_10_22_223347_create_ticket_messages_table.php'),
                $dirname . '/database/migrations/2022_10_23_000115_create_visitors_table.php' => database_path('migrations/2022_10_23_000115_create_visitors_table.php'),
            ], 'migrations');

        }

        $this->loadMigrationsFrom($dirname . '/database/migrations');

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
        Blueprint::macro('seo', function ($name = 'seo') {
            return $this->jsonb($name);
        });

        Blueprint::macro('status', function ($name = 'status') {
            return $this->boolean($name);
        });

        Blueprint::macro('active', function ($name = 'active') {
            return $this->boolean($name);
        });

        Blueprint::macro('lang', function ($columnName, $locales = [], Closure $closure = null): ColumnDefinition {
            /** @var Blueprint $this */
            $column = $this->addColumn('jsonb', $columnName, ["precision" => 0]);
            collect(count($locales) ? $locales : locals())->map(function ($locale) use ($columnName, $closure) {
                /** @var Blueprint $this */
                $localeColumn = $this->text("{$columnName}_{$locale}")
                    ->nullable()
                    ->always()
                    ->comment("this is always $columnName with locale fulltext : $locale")
                    ->fulltext();

                $database = config('database.default');

                $databaseDriver = config("database.connections.{$database}.driver");

                $localeColumn = match ($databaseDriver) {
                    'pgsql' => $localeColumn->storedAs("($columnName ->>'{$locale}')"),
                    'mysql' => $localeColumn->storedAs(
                        "json_unquote(JSON_EXTRACT($columnName,'$.{$locale}'))"
                    ),
                    default => $localeColumn,
                };

                if ($closure) {
                    $closure($localeColumn, $locale, $columnName, $this);
                }
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
