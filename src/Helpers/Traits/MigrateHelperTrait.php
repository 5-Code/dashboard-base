<?php

namespace Habib\Dashboard\Helpers\Traits;

trait MigrateHelperTrait
{
    public function getTablePrefix(): string
    {
        return config('dashboard.table_prefix', 'dashboard_');
    }
}
