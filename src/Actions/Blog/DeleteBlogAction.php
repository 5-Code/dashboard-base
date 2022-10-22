<?php

namespace Habib\Dashboard\Actions\Blog;

use App\Helpers\Slugger;
use Habib\Dashboard\Actions\UpdateActionInterface;
use Habib\Dashboard\Models\Blog;
use Habib\Dashboard\Services\Upload\UploadService;

class UpdateBlogAction implements UpdateActionInterface
{
    /**
     * @param Blog $model
     * @param array $data
     * @return false|Blog
     */
    public function update($model, array $data)
    {
        if (isset($data['image'])) {
            $image = UploadService::new()->upload($data['image'], 'blogs');
            $data['image'] = $image->getPath();
        }
        $model->fill($data);
        if (!$model->isDirty('title')) {
            $slug = [];
            foreach (locals() as $local) {
                $slug[$local] = Slugger::new()->slug($model, "slug->{$local}", $data['title'][$local]);
            }
            $model->setAttribute('slug', $slug);
        }

        if (!$model->save()) {
            return false;
        }

        return $model;
    }
}
