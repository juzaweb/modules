<?php

namespace Juzaweb\Backend\Http\Controllers\Backend\PostType;

use Juzaweb\Backend\Models\Post;
use Juzaweb\CMS\Http\Controllers\BackendController;
use Juzaweb\CMS\Traits\PostTypeController;

class PostController extends BackendController
{
    use PostTypeController;

    protected string $viewPrefix = 'cms::backend.post';

    protected function getModel(...$params): string
    {
        return Post::class;
    }
}
