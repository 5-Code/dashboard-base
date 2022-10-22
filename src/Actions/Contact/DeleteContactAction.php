<?php

namespace Habib\Dashboard\Actions\Contact;

use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Actions\DeleteActionInterface;
use Habib\Dashboard\Models\Contact;

class DeleteContactAction implements ActionInterface
{
    public function __construct(public Contact $model)
    {
    }

    /**
     * @param array $data
     * @return bool|null
     */
    public function handle(array $data = [])
    {
        return $this->model->delete();
    }
}
