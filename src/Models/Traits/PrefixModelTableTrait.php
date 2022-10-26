<?php

namespace Habib\Dashboard\Models\Traits;

trait PrefixModelTableTrait
{
    public function getTable()
    {
        return config('dashboard.table_prefix', '') . parent::getTable();
    }
}
