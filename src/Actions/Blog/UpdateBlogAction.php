<?php

namespace Habib\Dashboard\Actions\Blog;

use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Events\Blog\BlogUpdatedEvent;
use Habib\Dashboard\Events\Blog\BlogUpdatingEvent;
use Habib\Dashboard\Models\Blog;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($data) {

            if (isset($data['image'])) {
                $image = $this->model->upload($data['image'], 'blogs', [
                    'dir' => 'blogs',
                ]);
                $data['image_id'] = $image->id;
            }

            $this->model->fill($data);

            if (!$this->model->isDirty('title')) {
                $this->model->sluggerByLocals($data, 'title', 'slug');
            }

            event(new BlogUpdatingEvent($this->model));

            if (!$this->model->save()) {
                return false;
            }
            return $this->model->tap(fn($model) => event(new BlogUpdatedEvent($model)));
        });
    }
}
