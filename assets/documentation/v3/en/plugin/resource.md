In Juzaweb CMS, we define resources as management modules (CRUD). By default, resources are stored in the resources table. A resource can come with an old Post Type.

E.g: A book can have many chapters, here, `books` will be defined as a Post Type and Resource is the `chapters` in a book.

[auto_menu]

### Register Resource
#### Register a Post Type Resource
- Add your Action
```php
$this->addAction(self::INIT_ACTION, [$this, 'registerPostTypes']);
$this->addAction(self::INIT_ACTION, [$this, 'registerResources']);
```

- Register Post Type
```php
public function registerPostTypes()
{
    $this->hookAction->registerPostType(
        'stories',
        [
            'label' => trans('example::content.stories'),
            'menu_icon' => 'fa fa-list',
            'supports' => ['tag', 'comment'],
            'metas' => [
                'chapters' => [
                    'type' => 'text',
                    'label' => trans('example::content.total_chapter')
                ],
                'source' => [
                    'type' => 'text',
                    'label' => trans('example::content.source')
                ]
            ]
        ]
    );

    // Register Taxonomies for Post Type Stories
    $this->hookAction->registerTaxonomy(
        'genres',
        'stories',
        [
            'label' => 'Genres',
        ]
    );

    $this->hookAction->registerTaxonomy(
        'authors',
        'stories',
        [
            'label' => 'Authors',
        ]
    );
}
```

- Register Resource for Post Type
```php
public function registerResources()
{
    $this->hookAction->registerResource(
        'chapters',
        'stories',
        [
            'label' => 'Chapters',
            'metas' => [
                'content' => [
                    'type' => 'editor',
                    'label' => 'Content'
                ]
            ]
        ]
    );
}
```

Full Action class example
```php
namespace Juzaweb\Example\Actions;

use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\CMS\Facades\HookAction;

class ExampleAction extends Action
{
    public function handle()
    {
        $this->addAction(self::INIT_ACTION, [$this, 'registerPostTypes']);
        $this->addAction(self::INIT_ACTION, [$this, 'registerResources']);
    }

    public function registerPostTypes()
    {
        $this->hookAction->registerPostType(
            'stories',
            [
                'label' => trans('example::content.stories'),
                'menu_icon' => 'fa fa-list',
                'supports' => ['tag', 'comment'],
                'metas' => [
                    'chapters' => [
                        'type' => 'text',
                        'label' => trans('example::content.total_chapter')
                    ],
                    'source' => [
                        'type' => 'text',
                        'label' => trans('example::content.source')
                    ]
                ]
            ]
        );
    
        // Register Taxonomies for Post Type Stories
        $this->hookAction->registerTaxonomy(
            'genres',
            'stories',
            [
                'label' => 'Genres',
            ]
        );
    
        $this->hookAction->registerTaxonomy(
            'authors',
            'stories',
            [
                'label' => 'Authors',
            ]
        );
    }

    public function registerResources()
    {
        $this->hookAction->registerResource(
            'chapters',
            'stories',
            [
                'label' => 'Chapters',
                'metas' => [
                    'content' => [
                        'type' => 'editor',
                        'label' => 'Content'
                    ]
                ]
            ]
        );
    }
}
```

#### Register a Resource _without Post Type_
If you want to register a Resource with no Post Type, set null for the second param.

- Add Your Action
```php
$this->addAction(self::INIT_ACTION, [$this, 'registerResource']);
```

- Register Resource in Action
```php
public function registerResources()
{
    $this->hookAction->registerResource(
        'examples',
        null,
        [
            'label' => trans('example::content.examples'),
            // Don't forget to register your Resource menu
            'menu' => [
                'parent' => 'setting',
                'position' => 20,
            ],
            'metas' => [
                'content' => [
                    'type' => 'editor',
                    'label' => trans('example::content.content')
                ]
            ]
        ]
    );
}
```

### Custom Resource
- Resource in Juza CMS is created to easily create, update, or delete actions
- It includes components
    - **Controller**: Include actions
        - index + datatable
        - create
        - edit
        - store
        - update
        - bulk action
    - **Model**
    - **Route**
    
#### Make Resource Model
- Make migration
```
php artisan plugin:make-migration create_examples_table author/plugin-name
```
- Make your model by command
```
php artisan plugin:make-model Example author/plugin-name
```
- Use trait **ResourceModel** for your model
```php
namespace Plugins\Example\Models;

use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Traits\ResourceModel;

class Example extends Model
{
    use ResourceModel;

    protected $fieldName = 'name';// Define column to show in breadcrumb
    
    protected $fillable = [
        'name',
    ];
    // Your code
}
```

#### Resource controller
- Make controller
```
php artisan plugin:make-controller ExampleController author/plugin-name
```
- Use trait **ResourceController** for your model
```php
namespace Plugins\Example\Http\Controllers;

use Juzaweb\CMS\Http\Controllers\BackendController;
use Plugins\Example\Models\Example;

class ExampleController extends BackendController
{
    use ResourceController;

    protected $viewPrefix = 'example::slider'; // View prefix for resource

    // Make validator for store and update
    protected function validator(array $attributes, ...$params)
    {
        $validator = Validator::make(
            $attributes,
             [
                'name' => 'required|string|max:250',
            ]
        );

        return $validator;
    }

    protected function getModel(...$params)
    {
        return Example::class;
    }

    protected function getTitle(...$params)
    {
        return trans('example::app.sliders');
    }
}
```

#### Add Resource route
- Add route for resource
```php
Route::jwResource('example', 'SliderController');
```