<?php

namespace Habib\Dashboard\Actions\Blog;

use App\Helpers\Slugger;
use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Models\Blog;
use Habib\Dashboard\Services\Upload\UploadService;

class CreateNewBlogAction implements ActionInterface
{
    /**
     * @param array $data
     * @return bool|Blog
     */
    public function handle(array $data)
    {
        $data['owner_id'] = auth()->id();
        $data['owner_type'] = auth()->user()->getMorphClass();
        $image = UploadService::new()->upload($data['image'], 'blogs');
        $data['image'] = $image->getPath();
        $model = new Blog($data);
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
