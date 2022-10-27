<?php

namespace Habib\Dashboard\Models\Traits;

trait PrefixModelTableTrait
{
    public function getTable()
    {
        $prefix = config('dashboard.table_prefix', 'dashboard_');

        $table = parent::getTable();

        return str_starts_with($table, $prefix) ? $table : $prefix.$table;
    }
}
