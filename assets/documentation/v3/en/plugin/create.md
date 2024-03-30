Plugin is a Laravel package which was created to manage your large Laravel app using modules. A plugin is like a Laravel package, it has some views, controllers or models. This package is supported and tested in Laravel 9.

[auto_menu]

### Create plugin
Creating a plugin is simple and straightforward. Run the following command to create a plugin.
```shell
php artisan plugin:make author/plugin-name
```

### Folder structure
```
plugins/
|-- plugin-name/
    |-- src/
        |-- database/
          |-- factories/
          |-- migrations/
          |-- seeders/
        |-- Http/
          |-- Controllers/
          |-- Middleware/
          |-- Requests/
          |-- Datatables/
        |-- Models/
        |-- Providers/
          |-- PluginNameServiceProvider.php
        |-- resources/
          |-- assets/
              |-- js/
                |-- app.js
              |-- sass/
                |-- app.scss
          |-- lang/
          |-- views/
        |-- routes/
          |-- api.php
          |-- admin.php
    |-- tests/
    |-- composer.json
    |-- package.json
    |-- webpack.mix.js
```

### Information & Autoload
Open the **composer.json** file, you will see a file of the form
```json
{
    "name": "author/name",
    "description": "Description plugin.",
    "extra": {
        "juzaweb": {
            "providers": [
                "Author\\Name\\Providers\\PluginNameServiceProvider"
            ],
            "name": "Plugin Display Name",
            "domain": "domain",
            "cms_min": "3.0",
            "version": "1.0"
        }
    },
    "autoload": {
        "psr-4": {
            "Author\\Name\\": "src/"
        }
    }
}
```

Extra **juzaweb** config
  - **PluginServiceProvider** load on plugin activated
  - **name**: Display name plugin in cms
  - **domain**: Namespace for views and lang in plugins
  - **cms_min**: Min CMS version can run plugin
  - **version**: Current version of plugin

### Custom namespace
When you create a new module it also registers new custom namespace for **Lang, View and Config**. For example, if you create a new module named blog, it will also register new namespace/hint blog for that module. Then, you can use that namespace for calling **Lang, View or Config**. Following are some examples of its usage:
Calling Lang:
```php
Lang::get('domain::group.name');
```
On blade template
```
@trans('domain::group.name');
```
Calling View:
```php
view('domain::index')

view('domain::folder.index')
```
