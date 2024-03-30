Multiple language support for your Theme... If you ever struggled to support multiple languages for your website. Juzaweb CMS supports you with a translation tool for faster and easier translate.

[auto_menu]

### Import translation
Find and create translation in your Theme to `jw_translations` table

```shell
php artisan theme:import-translation {theme-name}
```

### Google Translate Theme
Translate your theme by Google Translate

```shell
php artisan theme:google-translate {theme-name} {source} {target}
```

This command will translate and add new translations to the `jw_translations` table via Google Translate.

E.x:
```shell
php artisan theme:google-translate default en hi
```

### Export translation to file json

Export all translation to file json

```shell
php artisan theme:export-translation {theme-name}
```

If you want to export a specific language, add the 2nd param

```shell
php artisan theme:export-translation {theme-name} {language}
```