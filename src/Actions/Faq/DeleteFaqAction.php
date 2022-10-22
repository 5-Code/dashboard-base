<?php

namespace Habib\Dashboard\Actions\Blog;

use App\Helpers\Slugger;
use Habib\Dashboard\Actions\ActionInterface;
use Habib\Dashboard\Actions\DeleteActionInterface;
use Habib\Dashboard\Models\Blog;

class DeleteBlogAction implements ActionInterface
{
    public function __construct(public Blog $model){}

    /**
     * @param array $data
     * @return bool|null
     */
    public function handle(array  $data = [])
    {
        return $this->model->delete();
    }
}
