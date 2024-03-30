In your plugin, there are times when you will need to user configs something or simply toggle a feature in the plugin. Juzaweb CMS gives you a powerful tool to create simple and quick setup forms and fields.

[auto_menu]

### Register Config
To register config fields you can use `registerConfig` method in `HookAction`, see below example.
```php
// In your Action class, add to action INIT
$this->addAction(Action::INIT_ACTION, [$this, 'addConfigs']);
```

Register config field
```php
public function addConfigs()
{
    $this->hookAction->registerConfig(
        [
            "enable_feature",
        ]
    );
}
```
Ok, now you can use function `set_config()` or `get_config()` to set or get config `enable_feature`

```php
set_config('enable_feature', 1);

// Get config
$config = get_config('enable_feature');
```

### Register Config Form
Config form makes it easy to create config forms for users in **Admin -> Setting -> General Setting**

#### Register a Config Form
```php
public function addConfigs()
{
    $this->hookAction->addSettingForm(
        'examble',
        [
            'name' => trans('examble::content.setting'),
            'priority' => 20
        ]
    );
}
```

#### Set form for Config
```php
public function addConfigs()
{
    $this->hookAction->registerConfig(
        [
            "enable_feature" => [
                'label' => trans('examble::content.enable_feature'),
                'form' => 'examble',
            ],
        ]
    );
}
```
Field `enable_feature` default type is `text`, You can modify them by adding type to array.

```php
public function addConfigs()
{
    $this->hookAction->registerConfig(
        [
            "enable_feature" => [
                'form' => 'examble',
                'type' => 'select',
                'label' => trans('examble::content.enable_feature'),
                'data' => [
                    'options' => [
                        0 => trans('cms::app.disabled'),
                        1 => trans('cms::app.enabled'),
                    ]
                ]
            ],
        ]
    );
}
```
Type Support: text, textarea, select, checkbox, radio, image, images, security, hidden, editor, upload_url.

If you want to create fields manually, add an option view to your Config Form and configure the view to display them.

```php
public function addConfigs()
{
    $this->hookAction->addSettingForm(
        'examble',
        [
            'name' => trans('examble::content.setting'),
            'view' => view('examble::setting.form'),
            'priority' => 20
        ]
    );
}
```

#### Full Config form action
```php
namespace Juzaweb\Example\Actions;

use Juzaweb\CMS\Abstracts\Action;

class ExampleAction extends Action
{
    public function handle()
    {
        $this->addAction(Action::INIT_ACTION, [$this, 'addConfigs']);
    }

    public function addConfigs(): void
    {
        $this->hookAction->addSettingForm(
            'examble',
            [
                'name' => trans('examble::content.setting'),
                'priority' => 20
            ]
        );
        
        $this->hookAction->registerConfig(
            [
                "enable_feature" => [
                    'form' => 'examble',
                    'type' => 'select',
                    'label' => trans('examble::content.enable_feature'),
                    'data' => [
                        'options' => [
                            0 => trans('cms::app.disabled'),
                            1 => trans('cms::app.enabled'),
                        ]
                    ]
                ],
            ]
        );
        
        // Or use custom view
        $this->hookAction->addSettingForm(
            'examble',
            [
                'name' => trans('examble::content.setting'),
                'view' => view('examble::setting.form'),
                'priority' => 20
            ]
        );
    }
}
```
