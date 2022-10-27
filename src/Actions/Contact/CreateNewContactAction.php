<?php

namespace Habib\Dashboard\Actions\Contact;

use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Models\Contact;

class CreateNewContactAction implements ActionInterface
{
    /**
     * @param  array  $data
     * @return bool|Contact
     */
    public function handle(array $data)
    {
        $model = new Contact($data);
        if (! $model->save()) {
            return false;
        }

        return $model;
    }
}
