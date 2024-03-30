[auto_menu]

### Update in browser
- Go to **Admin -> Dashboard -> Updates** click button Update now to Update CMS

- Done!

#### Common problems
- Server timeout: Edit timeout in your server, Cloudflare DNS (if you use) and try again or try [Update with command](#content-update-with-command).
- Service provider not found: Run command `composer dump-autoload` and try again.

### Update with command

- Run command to update CMS `php artisan juzacms:update`

- Run command `composer dump-autoload` to autoload vendor

- Done!

### Manual update
- Download CMS The Latest version here [https://juzaweb.com/download](https://juzaweb.com/download)
- Upload overwrite the following files and folders to your server:
```
modules
vendor
bootstrap/cache/packages.php
composer.json
composer.lock
```
- Done!
