<?php

namespace Habib\Dashboard\Actions\Contact;

use Habib\Dashboard\Helpers\Slugger;
use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Models\Contact;
use Habib\Dashboard\Services\Upload\UploadService;

class UpdateContactAction implements ActionInterface
{
    public function __construct(public Contact $model)
    {
    }

    /**
     * @param array $data
     * @return false|Contact
     */
    public function handle(array $data)
    {

        if (!$this->model->fill($data)->isDirty('title')) {
            $slug = [];
            foreach (locals() as $local) {
                $slug[$local] = Slugger::new()->slug($this->model, "slug->{$local}", $data['title'][$local]);
            }
            $this->model->setAttribute('slug', $slug);
        }

        if (!$this->model->save()) {
            return false;
        }

        return $this->model;
    }
}
