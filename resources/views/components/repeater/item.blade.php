<div class="repeater-item card mb-1">
    <div class="card-body">
        <div class="repeater-item-remove">
            <a href="javascript:void(0)" class="btn btn-danger btn-sm remove-repeater-item">
                <i class="fa fa-trash"></i>
            </a>
        </div>

        <div class="repeater-item-content">
            {{ Field::render(
                map_name_repeater($options->get('fields', []), $options, $marker),
                $values ?? [],
            ) }}
        </div>
    </div>
</div>

