<?php

namespace Habib\Dashboard\Events\Blog;

use Habib\Dashboard\Models\Blog;
use Illuminate\Foundation\Events\Dispatchable;

class BlogCreatedEvent
{
    use Dispatchable;

    public function __construct(public Blog $blog)
    {
    }
}
