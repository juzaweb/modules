<form
    action="{{ $action ?? '' }}"
    method="post"
    class="form-ajax"
    id="{{ random_string() }}"
    @if($success ?? false) data-success="{{ $success }}" @endif
>
    @csrf

    @if(isset($method) && $method == 'put')
        @method('PUT')
    @endif

    {{ $slot ?? '' }}

</form>