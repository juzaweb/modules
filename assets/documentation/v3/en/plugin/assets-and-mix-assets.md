

[auto_menu]

### Assets folder

The assets folder structure of a Plugin includes:
```
├── assets
    ├── public
        ├── css
            └── styles.min.css
        ├── fonts
            ├── font.otf
            ├── font.eot
        ├── images
            ├── loading.png
            ├── screenshot.png
        └── js
            └── script.min.js
    └── styles
        ├── css
            └── styles.css
        └── js
            └── script.js
    ├── mix.js
```

All folders and files in the `public` directory will be published when you run command:
```shell
php artisan plugin:publish {plugin-name}
```

By default, file `public/images/screenshot.png` will be displayed as a Plugin thumbnail in the admin interface `admin-cp/plugins`.

### Using
You can use the `plugin_asset()` function to get the url to your asset file.

```php
function plugin_asset(string $path, string $plugin = null): ?string
```

- Parameters
  - **@param string $path**: Path to file assets
  - **@param string $plugin**: Plugin name (Default is current plugin)
  - **@return string**

E.x:
```html
<link rel="stylesheet" href="{{ plugin_asset('css/styles.min.css') }}">

<img src="{{ plugin_asset('images/loading.png') }}" alt="Example image">
```

If you want to get the assets of a specific Plugin, add the Plugin name to the 2nd param:
```html
<link rel="stylesheet" href="{{ plugin_asset('css/styles.min.css', 'default') }}">
```

### Mix assets
Laravel Mix provides a clean, fluent API for defining basic webpack build steps for your applications. Mix supports several common CSS and JavaScript pre-processors. In Juzaweb CMS you can easily use them in each of your plugins.

The file `assets/mix.js` will be the webpack file for your plugin, instead of `webpack.mix.js` in the root folder. See the example below:

```javascript
const mix = require('laravel-mix');

mix.styles(
    [
        `${__dirname}/styles/css/styles.css`,
    ],
    `${__dirname}/public/css/styles.min.css`
);

mix.combine(
    [
        `${__dirname}/styles/js/script.js`,
    ],
    `${__dirname}/public/js/script.min.js`
);
```
The script above will mix and minify two files `styles.css` and `script.js` to form `css/styles.min.css` and `js/script.min.js` files in public folder. To execute them, run command:

```shell
yarn run prod --plugin={plugin-name}
```
_* Don't forget run `yarn install` to download the packages before mix them_

See more documentation Laravel Mix [here](https://github.com/laravel-mix/laravel-mix/tree/master/docs).
