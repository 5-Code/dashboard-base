<?php

namespace Habib\Dashboard\Actions\Faq;

use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Helpers\Slugger;
use Habib\Dashboard\Models\Faq;

class CreateNewFaq implements ActionInterface
{
    public function handle(array $data)
    {
        $data['owner_id'] = auth()->id();
        $data['owner_type'] = auth()->user()->getMorphClass();
        $model = new Faq($data);
        $slug = [];
        foreach (locals() as $local) {
            $slug[$local] = Slugger::new()->slug($model, "slug->{$local}", $data['title'][$local]);
        }

        if (!$model->setAttribute('slug', $slug)->save()) {
            return false;
        }

        return $model;
    }
}
