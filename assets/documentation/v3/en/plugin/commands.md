[auto_menu]

_Note all the following commands use "author/plugin-name" as example plugin name_

- **Make model** Generate the given model for the specified plugin.
```shell
php artisan plugin:make-model ModelName author/plugin-name
```
- **Make controller** Generate a controller for the specified plugin.
```shell
php artisan plugin:make-controller ModelName author/plugin-name
```
- **Make resource** Generate Juza CMS resource for the specified plugin.
```shell
php artisan plugin:make-jw-resource table_name author/plugin-name
```
- **Migrate**: Migrate the given plugin, or without a plugin an argument, migrate all plugins.
```shell
php artisan plugin:migrate author/plugin-name
```
- **Migrate rollback**: Rollback the given plugin, or without an argument, rollback all plugins.
```shell
php artisan plugin:migrate-rollback author/plugin-name
```
