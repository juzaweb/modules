<?php

namespace Juzaweb\Backend\Http\Controllers\Backend\PostType;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Str;
use Juzaweb\Backend\Http\Datatables\PostType\CommentDatatable;
use Juzaweb\Backend\Models\Comment;
use Juzaweb\CMS\Abstracts\DataTable;
use Juzaweb\CMS\Http\Controllers\BackendController;
use Juzaweb\CMS\Traits\ResourceController;

class InteriaCommentController extends BackendController
{
    use ResourceController {
        ResourceController::getDataForIndex as DataForIndex;
    }

    protected string $template = 'inertia';

    protected function validator(array $attributes, ...$params): Validator|array
    {
        $statuses = array_keys(Comment::allStatuses());

        return [
            'email' => 'required|email',
            'name' => 'nullable',
            'website' => 'nullable',
            'content' => 'required',
            'status' => 'required|in:'.implode(',', $statuses),
        ];
    }

    protected function getModel(...$params): string
    {
        return Comment::class;
    }

    protected function getTitle(...$params): string
    {
        return trans('cms::app.comments');
    }

    protected function getDataTable(...$params): DataTable
    {
        $dataTable = new CommentDatatable();
        $dataTable->mountData($this->getPostType());
        return $dataTable;
    }

    protected function getDataForIndex(...$params): array
    {
        $type = $params[0];
        $postType = $this->getPostType();

        $data = $this->DataForIndex($type);
        $data['postType'] = $postType;
        return $data;
    }

    protected function getPostType(): string
    {
        return Str::plural(request()?->segment(3));
    }
}
