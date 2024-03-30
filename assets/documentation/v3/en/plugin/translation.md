Multiple language support for your Plugin... If you ever struggled to support multiple languages for your website. Juzaweb CMS supports you with a translation tool for faster and easier translate.

[auto_menu]

### Import translation
Find and create translation in your Plugin to `jw_translations` table

```shell
php artisan plugin:import-translation {plugin-name}
```

### Google Translate Plugin
Translate your plugin by Google Translate

```shell
php artisan plugin:google-translate {plugin-name} {source} {target}
```

This command will translate and add new translations to the `jw_translations` table via Google Translate.

E.x:
```shell
php artisan plugin:google-translate default en hi
```

### Export translation to file json

Export all translation to file json

```shell
php artisan plugin:export-translation {plugin-name}
```

If you want to export a specific language, add the 2nd param

```shell
php artisan plugin:export-translation {plugin-name} {language}
```