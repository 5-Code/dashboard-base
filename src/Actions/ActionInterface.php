<?php

namespace Habib\Dashboard\Actions;

use Illuminate\Database\Eloquent\Model;

interface ActionInterface
{
    /**
     * @param array $data
     * @return Model|boolean|null
     */
    public function handle(array $data);
}
