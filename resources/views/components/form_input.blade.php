@if(($type ?? 'text') == 'hidden')
    @include('cms::components.inputs.input_field')
@else
    <div class="form-group">
        <label class="col-form-label" for="{{ $id  ?? $name }}">
            {{ $label ?? $name }} @if($required ?? false)
                <abbr>*</abbr>
            @endif
        </label>

        @if(isset($prefix) || isset($suffix))
            <div class="input-group mb-2">
                @if(isset($prefix))
                    <div class="input-group-prepend">
                        <div class="input-group-text">{{ $prefix }}</div>
                    </div>
                @endif

                @include('cms::components.inputs.input_field')

                @if(isset($suffix))
                    <div class="input-group-prepend">
                        <div class="input-group-text">{{ $suffix }}</div>
                    </div>
                @endif

                @if(isset($description))
                    <small class="form-text text-muted">
                        {{ $description }}
                    </small>
                @endif
            </div>
        @else
            @include('cms::components.inputs.input_field')
        @endif
    </div>
@endif
