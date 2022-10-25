<?php

namespace Habib\Dashboard\Actions\Blog;

use DB;
use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Events\Blog\BlogCreatedEvent;
use Habib\Dashboard\Events\Blog\BlogCreatingEvent;
use Habib\Dashboard\Models\Blog;
use Habib\Dashboard\Models\Media;
use Habib\Dashboard\Services\Upload\UploadService;

class CreateNewBlogAction implements ActionInterface
{
    /**
     * @param array $data
     * @return bool|Blog
     */
    public function handle(array $data)
    {
        return DB::transaction(function () {
            $data['owner_id'] = auth()->id();
            $data['owner_type'] = auth()->user()->getMorphClass();
            $image = UploadService::new()->upload($data['image'], 'blogs');
            $model = new Blog($data);
            $image = Media::create($model->parseMediaInfo($image));
            $model->image_id = $image->id;
            $model->sluggerByLocals($data, 'title', 'slug');
            event(new BlogCreatingEvent($model));

            if (!$model->save()) {
                return false;
            }
            $image->model()->associate($model);
            return tap($model, fn($model) => event(new BlogCreatedEvent($model)));
        });
    }
}
