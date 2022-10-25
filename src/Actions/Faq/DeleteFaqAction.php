<?php

namespace Habib\Dashboard\Actions\Faq;

use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Models\Faq;

class DeleteFaqAction implements ActionInterface
{
    public function __construct(public Faq $model)
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
