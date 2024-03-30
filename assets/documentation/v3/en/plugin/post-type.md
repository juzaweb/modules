There are many types of content in Juzaweb CMS. These content types are normally described as Post Types, which may be a little confusing since it refers to all different types of content in Juzaweb CMS. For example, a post is a specific Post Type, and so is a page.

[auto_menu]

### Default Post Types
- Post (Post Type: ``posts``)
- Page (Post Type: ``pages``)

### Register Custom Post Type
To Register custom post type to your plugin, you can use `registerPostType` in Facade `HookAction`. To make sure it works, add them to the action `juzaweb.init`.
- **_Syntax_**:
```php
HookAction::registerPostType($postType, $args);
```
- **_Parameters_**
  - **$postType** string Required
    Post type key (plural). Must not exceed 20 characters and may only contain lowercase alphanumeric characters, dashes, and underscores.
  - **$args** array
    - **label** string
      - Name of the post type shown in the menu. Usually plural.
    - **description** string
      - A short descriptive summary of what the post type is.
    - **show_in_menu** bool
      - Show in admin menu. Default true
    - **rewrite** bool
      - Custom base prefix to post type. Default true
    - **menu_box** bool
      - Show post type menu box in menu builder. Default true
    - **menu_position** int
      - (integer) (optional) The position in the menu order the post type should appear. Default: null
    - **menu_icon** string
      - The icon to be used for this menu
    - **supports** array
      - Core feature(s) the post type supports. Core features include `'thumbnail'`, `'comment'`
    - **metas** array <key: string, value: array>
      - Meta (input fileds) custom to post type


#### **See the EXAMPLE below**
```php
namespace Juzaweb\Example\Actions;

use Juzaweb\CMS\Abstracts\Action;

class ExampleAction extends Action
{
    /**
     * Execute the actions.
     *
     * @return void
     */
    public function handle(): void
    {
        // Add your action
        $this->addAction(Action::INIT_ACTION, [$this, 'registerPostTypes']);
    }

    public function registerPostTypes()
    {
        $this->hookAction->registerPostType(
            'examples',
            [
                'label' => trans('example Post'),
                'description' => trans('This is Example Post'),
                'menu_icon' => 'fa fa-list',
                'supports' => ['category', 'tag'],
                'metas' => [
                    'example' => [
                        'type' => 'text',
                        'label' => trans('Example Field')
                    ],
                    'select' => [
                        'type' => 'select',
                        'label' => trans('Example Select'),
                        'data' => [
                            'options' => [
                                0 => trans('Disabled'),
                                1 => trans('Enabled')
                            ]
                        ]
                    ]
                ],
            ]
        );
    }
}
```

### Taxonomies
A simple function for creating or modifying a taxonomy object based on the parameters given.
- **_Syntax_**:
```php
HookAction::registerTaxonomy(string $taxonomy, array|string $postType, array|string $args = array());
```

- **_Parameters_**
  - **$taxonomy** string Required Taxonomy key, must not exceed 32 characters and may only contain lowercase alphanumeric characters, dashes, and underscores
  - **$postType** Object type or array of object types with which the taxonomy should be associated.
  - **$args** array
    - Array or query string of arguments for registering a taxonomy.

Example:
```php
$this->hookAction->registerTaxonomy(
    'countries',
    'examples',
    [
        'label' => __('Countries'),
        'supports' => []
    ]
);
```
Register Taxonomy with multiple Post Type
```php
$this->hookAction->registerTaxonomy(
    'countries',
    ['examples', 'movies'],
    [
        'label' => __('Countries'),
        'supports' => []
    ]
);
```

#### Register a Custom Post Type with custom Taxonomy
```php
namespace Juzaweb\Example\Actions;

use Juzaweb\CMS\Abstracts\Action;

class ExampleAction extends Action
{
    /**
     * Execute the actions.
     *
     * @return void
     */
    public function handle(): void
    {
        // Add your action
        $this->addAction(Action::INIT_ACTION, [$this, 'registerPostTypes']);
    }

    public function registerPostTypes()
    {
        $this->hookAction->registerPostType(
            'examples',
            [
                'label' => __('Example Post'),
                'menu_icon' => 'fa fa-list',
                'supports' => ['category', 'tag'],
                'metas' => [
                    'example' => [
                        'type' => 'text',
                        'label' => __('Example Field')
                    ],
                    'select' => [
                        'type' => 'select',
                        'label' => __('Example Select'),
                        'data' => [
                            'options' => [
                                0 => __('Disabled'),
                                1 => __('Enabled')
                            ]
                        ]
                    ]
                ],
            ]
        );

        $this->hookAction->registerTaxonomy(
            'countries',
            'examples',
            [
                'label' => __('Countries'),
                'supports' => []
            ]
        );
        
        // Or Register Taxonomy with multiple Post Type
        $this->hookAction->registerTaxonomy(
            'countries',
            ['examples', 'movies'],
            [
                'label' => __('Countries'),
                'supports' => []
            ]
        );
    }
}
```