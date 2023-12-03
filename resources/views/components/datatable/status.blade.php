@switch($row->status)
    @case('publish')
    <span class="badge badge-success">{{ trans('cms::app.publish') }}</span>
    @break

    @case('approved')
    <span class="badge badge-success">{{ trans('cms::app.approved') }}</span>
    @break

    @case('private')
    <span class="badge badge-warning">{{ trans('cms::app.private') }}</span>
    @break

    @case('pending')
    <span class="badge badge-warning">{{ trans('cms::app.pending') }}</span>
    @break

    @case('draft')
    <span class="badge badge-secondary">{{ trans('cms::app.draft') }}</span>
    @break

    @case('trash')
    <span class="badge badge-danger">{{ trans('cms::app.trash') }}</span>
    @break

    @case('deny')
    <span class="badge badge-danger">{{ trans('cms::app.deny') }}</span>
    @break

    @default
    <span class="badge badge-secondary">{{ trans('cms::app.draft') }}</span>
    @break
@endswitch