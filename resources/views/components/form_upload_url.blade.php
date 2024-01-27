@php
    $show_label = $show_label ?? true;
@endphp

@if($show_label)
<div class="form-group">

    <label class="col-form-label" for="{{ $id  ?? $name }}">
        {{ $label ?? $name }} @if($required ?? false)
            <abbr>*</abbr>
        @endif
    </label>
@endif

    <div class="row upload-url-row">
        <div class="col-md-9">
            <input
                    type="text"
                    name="{{ $name }}"
                    class="form-control"
                    id="{{ $id  ?? $name }}"
                    value="{{ $value ?? $default ?? '' }}"
                    autocomplete="off"
                    @if($required ?? false) required @endif
                    @if($placeholder ?? false) placeholder="{{ $placeholder }}" @endif
            >
        </div>

        <div class="col-md-3">
            <a href="javascript:void(0)"
               class="btn btn-primary file-manager"
               data-input="{{ $id  ?? $name }}"
               data-type="{{ $type ?? 'file' }}"
               data-disk="{{ $disk ?? config('juzaweb.filemanager.disk') }}"
            >
                <i class="fa fa-upload"></i> {{ trans('cms::app.upload') }}
            </a>
        </div>
    </div>
    @if($show_label)
</div>
@endif
