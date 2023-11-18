<div class="row form-repicter">
    <div class="col-md-12 repicter-items">
        @component('cms::components.repicter.item', [
            'options' => $options,
            'values' => $values,
            'marker' => Str::uuid()
        ])
        @endcomponent
    </div>

    <div class="col-md-12">
        <button type="button" class="btn btn-primary btn-sm add-repicter-item">
            {{ trans('cms::app.add_repicter_item', ['name' => $options['label']]) }}
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


