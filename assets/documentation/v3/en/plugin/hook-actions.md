Actions and filters in Laravel. WordPress-style. Actions are pieces of code you want to execute at certain points in your code. Actions never return anything but merely serve as the option to hook in to your existing code without having to mess things up. Filters are made to modify entities. They always return some kind of value. By default, they return their first parameter, and you should too.


[auto_menu]


### Actions
#### Helper functions
**Do action hook helper function**
```php
function do_action($tag, ...$args)
```

- **@param string $tag**: Name/key of action
- **@param mixed ...$args**: Additional parameters to pass to the callback functions.
- **@return void**

**Add action to hook**
```php
function add_action($tag, $callback, $priority = 20, $arguments = 1)
```
- **@param string $tag** The name of the filter to hook the **$function_to_add** callback to.
- **@param callable $callback** The callback to be run when the filter is applied.
- **@param int $priority** (Optional)
  - Used to specify the order in which the functions associated with a particular action are executed.
  - Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they were added to the action.
  - Default 20.
- **@param int $arguments** (Optional)
  - The number of arguments the function accepts.
  - Default 1.
- **@return** void

#### Example
- Anywhere in your code you can create a new action like so:
```php
do_action('my.hook', $user);
```

- The first parameter is the name of the hook; you will use this at a later point when you'll be listening to your hook. All subsequent parameters are sent to the action as parameters. These can be anything you'd like. For example, you might want to tell the listeners that this is attached to a certain model. Then you would pass this as one of the arguments.

- To listen to your hooks, you attach listeners. These are best added to file in actions folder in your plugin

For example if you wanted to hook in to the above hook, you could do:
```php
add_action('my.hook', function($user) {
    if ($user->is_awesome) {
         $this->doSomethingAwesome($user);
    }
}, 20, 1);
```

#### Facade Method
```php
HookAction::addAction($tag, $callback, $priority = 20, $arguments = 1): void;
```

### Filters
#### Helper functions
```php
function apply_filters($tag, $value, ...$args) {}
```

- Apply filters to value
- **@param string $tag** The name of the filter hook.
- **@param mixed $value** The value to filter.
- **@param mixed  ...$args** Additional parameters to pass to the callback functions.
- **@return mixed** The filtered value after all hooked functions are applied to it.

```php
function add_filters($tag, $callback, $priority = 20, $arguments = 1) {}
```
- **@param string $tag** The name of the filter to hook the $function_to_add callback to.
- **@param callable $callback** The callback to be run when the filter is applied.
- **@param int $priority** (Optional)
  - Used to specify the order in which the functions associated with a particular action are executed.
  - Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they were added to the action.
  - Default 20.
- **@param int $arguments** (Optional). The number of arguments the function accepts. Default 1.
- **@return bool**
#### Example
- Filters work in much the same way as actions and have the exact same build-up as actions. The most significant difference is that filters always return their value.

```php
$value = apply_filters('my.hook', 'awesome');
```

- If no listeners are attached to this hook, the filter would simply return `'awesome'`.
- This is how you add a listener to this filter (still in the **actions** folder files)
```php
add_filters('my.hook', function($what) {
    $what = 'not '. $what;
    return $what;
}, 20, 1);
```

- The filter would now return `'not awesome'`. Neat!
- You could use this in conjunction with the previous hook:
```php
add_action('my.hook', function($what) {
    $what = add_filters('my.hook', 'awesome');
    echo 'You are '. $what;
});
```

### Using in Blade
Adding the same action as the one in the action example above:
```php
@do_action('my.hook', $user)
```
Adding the same filter as the one in the filter example above:

```
You are @apply_filters('my.hook', 'awesome')
```

#### Facade Method
```php
HookAction::addFilter($tag, $callback, $priority = 20, $arguments = 1): void;

HookAction::applyFilters(string $tag, mixed $value, ...$args): mixed;
```

### Use Action class
To make the management easy, we divide the action handlers into separate classes. Instead of calling **add_action** or **apply_filter** everywhere your project, Let's create an Action class, handle your add_action or apply_filter, making them easy to manage.

**Make your custom actions**
- Run command in termial
```shell
php artisan plugin:make-action ActionName vender/plugin-name
```

A new file is created, with the content as below
```php
namespace Vendor\Plugin\Actions;

use Juzaweb\CMS\Abstracts\Action;

class CustomAction extends Action
{
    public function handle()
    {
        // Add action or acc filter function
    }

    // Handle add action and filter
}
```

- Example:
```php
namespace Vendor\Plugin;

use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\AdsManager\Models\Ads;
use Juzaweb\CMS\Facades\HookAction;

class CustomAction extends Action
{
    public function handle()
    {
        $this->addAction(Action::BACKEND_INIT, [$this, 'addAdminMenus']);
        $this->addAction(Action::POSTS_FORM_LEFT_ACTION, [$this, 'addActionWithParameter']);
    }

    public function addAdminMenus()
    {
        HookAction::registerAdminPage(
            'banner-ads',
            [
                'title' => trans('plugin_domain::content.banner_ads'),
                'menu' => [
                    'icon' => 'fa fa-file',
                    'position' => 30,
                ]
            ]
        );
        
        // Or an easier way
        $this->hookAction->registerAdminPage(
            'banner-ads',
            [
                'title' => trans('plugin_domain::content.banner_ads'),
                'menu' => [
                    'icon' => 'fa fa-file',
                    'position' => 30,
                ]
            ]
        );
    }
    
    public function addActionWithParameter($model)
    {
        echo 'Model Title: ' . $model->title;
    }
}
```

- Register Your action 

For your actions to work, you need to register them In `boot` function in your **ServiceProvider**, Register your action.
```php
namespace Juzaweb\Example\Providers;

use Juzaweb\CMS\Facades\ActionRegister;
use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\Example\Actions\ExampleAction;

class ExampleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register Plugin Action
        ActionRegister::register([ExampleAction::class]);
    }
    
    // Your code
} 
```

### Basic HookAction methods
- Register Admin Page

```php
HookAction::registerAdminPage(string $key, array $args);
```
- **@param string $key**: Key as page
- **@param array $args**
  - title (string): Title/Label menu
  - menu (array):
    - icon: default(fa fa-list)
    - position: default(30)

- Register Permalink
```php
HookAction::registerPermalink($key, $args);
```
- **@param string $key**: Post Type key (Ex: pages, posts)
- **@param array $args**
