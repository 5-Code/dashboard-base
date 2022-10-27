<?php

namespace Habib\Dashboard\Actions\Faq;

use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Helpers\Slugger;
use Habib\Dashboard\Models\Faq;

class UpdateFaqAction implements ActionInterface
{
    public function __construct(public Faq $model)
    {
    }

    /**
     * @param  array  $data
     * @return false|Faq
     */
    public function handle(array $data)
    {
        if (! $this->model->fill($data)->isDirty('title')) {
            $slug = [];
            foreach (locals() as $local) {
                $slug[$local] = Slugger::new()->slug($this->model, "slug->{$local}", $data['title'][$local]);
            }
            $this->model->setAttribute('slug', $slug);
        }

        if (! $this->model->save()) {
            return false;
        }

        return $this->model;
    }
}
