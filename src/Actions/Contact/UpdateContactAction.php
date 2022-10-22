<?php

namespace Habib\Dashboard\Actions\Blog;

use App\Helpers\Slugger;
use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Models\Blog;
use Habib\Dashboard\Services\Upload\UploadService;

class UpdateBlogAction implements ActionInterface
{
    public function __construct(public Blog $model)
    {
    }

    /**
     * @param array $data
     * @return false|Blog
     */
    public function handle(array $data)
    {
        if (isset($data['image'])) {
            $image = UploadService::new()->upload($data['image'], 'blogs');
            $data['image'] = $image->getPath();
        }
        $this->model->fill($data);
        if (!$this->model->isDirty('title')) {
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
