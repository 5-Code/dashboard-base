<?php

namespace Habib\Dashboard\Actions\Blog;

use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Models\Blog;

class DeleteBlogAction implements ActionInterface
{
    public function __construct(public Blog $model)
    {
    }

    /**
     * @param  array  $data
     * @return bool|null
     */
    public function handle(array $data = [])
    {
        return $this->model->delete();
    }
}
