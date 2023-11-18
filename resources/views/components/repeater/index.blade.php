<div class="row form-repeater">
    <div class="col-md-12 repeater-items">
        @if(empty($values))
            @component('cms::components.repeater.item', [
                'options' => $options,
                'marker' => Str::uuid()
            ])
            @endcomponent
        @endif

        @foreach($values as $key => $value)
            @component('cms::components.repeater.item', [
                'options' => $options,
                'marker' => $key,
                'values' => collect($value)->mapWithKeys(
                    fn ($item, $ikey) => ["{$options['name']}[{$key}][{$ikey}]" => $item]
                )
                    ->toArray(),
            ])
            @endcomponent
        @endforeach
    </div>

    <div class="col-md-12">
        <button type="button" class="btn btn-primary btn-sm add-repeater-item">
            {{ trans('cms::app.add_repeater_item', ['label' => $options['label']]) }}
        </button>
    </div>

    <script type="text/html" class="repeater-item-template">
        @component('cms::components.repeater.item', [
                'options' => $options,
                'values' => [],
                'marker' => '{marker}'
            ])
        @endcomponent
    </script>
</div>
