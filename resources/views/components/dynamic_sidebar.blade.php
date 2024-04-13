@foreach ($widgets as $widget)
    @php
        $widgetData = \Juzaweb\CMS\Facades\HookAction::getWidgets($widget['widget'] ?? 'null');
    @endphp

    @if($widgetData === null)
        @continue
    @endif

    {!! $sidebar->get('before_widget') !!}

    {!! $widgetData['widget']->show($widget) !!}

    {!! $sidebar->get('after_widget') !!}

@endforeach
