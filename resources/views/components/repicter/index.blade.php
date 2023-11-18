<div class="row form-repicter">
    <div class="col-md-12 repicter-items">
        @if(empty($values))
            @component('cms::components.repicter.item', [
                'options' => $options,
                'marker' => Str::uuid()
            ])
            @endcomponent
        @endif

        @foreach($values as $key => $value)
            @component('cms::components.repicter.item', [
                'options' => $options,
                'marker' => $key,
                'values' => collect($value)->mapWithKeys(
                    fn ($item, $ikey) => ["features[{$key}][{$ikey}]" => $item]
                )
                    ->toArray(),
            ])
            @endcomponent
        @endforeach
    </div>

    <div class="col-md-12">
        <button type="button" class="btn btn-primary btn-sm add-repicter-item">
            {{ trans('cms::app.add_repicter_item', ['label' => $options['label']]) }}
        </button>
    </div>

    <script type="text/html" class="repicter-item-template">
        @component('cms::components.repicter.item', [
                'options' => $options,
                'values' => [],
                'marker' => '{marker}'
            ])
        @endcomponent
    </script>
</div>
