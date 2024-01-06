<input
        type="{{ $type ?? 'text' }}"
        name="{{ $name }}"
        class="form-control {{ $class ?? '' }}"
        id="{{ $id ?? $name }}"
        value="{{ $value ?? $default ?? '' }}"
        autocomplete="{{ $autocomplete ?? 'off' }}"
        placeholder="{{ $placeholder ?? '' }}"
        @if($disabled ?? false) disabled @endif
        @if($required ?? false) required @endif
        @if ($readonly ?? false) readonly @endif
        @foreach ($data ?? [] as $key => $val)
            data-{{ $key }}="{{ $val }}"
        @endforeach
/>