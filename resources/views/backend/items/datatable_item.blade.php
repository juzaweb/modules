<div class="row">
    @if(isset($thumbnail))
        <div class="col-md-3">
            <img class="lazyload w-100"
                 data-src="{{ $thumbnail }}"
                 src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="/>
        </div>
    @endif
    <div class="col-md-{{ isset($thumbnail) ? '9' : '12' }}">
        <a class="font-weight-bold" href="{{ $editUrl }}">
            {{ $value }}
        </a>

        <ul class="list-inline mb-0 list-actions mt-2 ">
            @foreach($actions as $key => $action)
                @php
                    $hasAction = !empty($action['action']);
                @endphp
                <li class="list-inline-item">
                    <a
                            href="{{ $action['url'] ?? 'javascript:void(0)' }}"
                            @if(isset($action['target'])) target="{{ $action['target'] }}" @endif
                            class="jw-table-row {{ $action['class'] ?? '' }} {{ $hasAction ? 'action-item' : '' }}"
                            data-id="{{ $row->id ?? '' }}"
                            @if(!empty($action['target']))
                                target="{{ $action['target'] }}"
                            @endif
                            @if($hasAction) data-action="{{ $action['action'] }}" @endif

                            @foreach($action['data'] ?? [] as $dataKey => $item)
                                data-{{ $dataKey }}="{{ (string) $item }}"
                            @endforeach
                    >
                        {{ $action['label'] ?? '' }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

