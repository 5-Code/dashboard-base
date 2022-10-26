<?php

namespace Habib\Dashboard\Actions\Blog;

use DB;
use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Events\Blog\BlogCreatedEvent;
use Habib\Dashboard\Events\Blog\BlogCreatingEvent;
use Habib\Dashboard\Models\Blog;

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

            $model = new Blog($data);

            $model->sluggerByLocals($data, 'title', 'slug');

            event(new BlogCreatingEvent($model));

            if (!$model->save()) {
                return false;
            }
            $model->addMedia($data['image'])->toMediaCollection('blogs');

            return $model->tap(fn($model) => event(new BlogCreatedEvent($model)));
        });
    }
}
