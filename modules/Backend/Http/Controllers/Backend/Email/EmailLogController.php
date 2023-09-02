<?php

namespace Juzaweb\Backend\Http\Controllers\Backend\Email;

use Illuminate\Contracts\View\View;
use Juzaweb\Backend\Http\Datatables\EmailLogDatatable;
use Juzaweb\CMS\Http\Controllers\BackendController;

class EmailLogController extends BackendController
{
    public function index(): View
    {
        $dataTable = new EmailLogDatatable();
        $title = trans('cms::app.email_logs');

        return view(
            'cms::backend.logs.email',
            compact(
                'title',
                'dataTable'
            )
        );
    }
}
