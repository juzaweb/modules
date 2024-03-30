[auto_menu]

### Publish the config file

To publish the config, run the vendor publish command
```
php artisan vendor:publish --tag=cms_config
```
This will create new config files named `config/juzaweb.php` and `config/network.php`. But we don't recommend doing this, it can lead to having to manually change the config file every time you update. Instead, we recommend adding/editing/removing settings in the `.env` file.

Add/edit/remove settings in your file `.env` to config according to your needs.
### CMS Config
- Show/Hidden admin-bar in frontend (default `true`)
```dotenv
ADMINBAR_ENDABLE=true
```
- Deny iframe to website
  
To deny the access from a document in an iframe, we'll need to modify the X-Frame-Options of the header. Websites like YouTube disallow its access from iframes, if you try to embed their website from in an iframe i.e:
```html
<iframe width="1000px" height="700px" src="https://www.youtube.com" ></iframe>
```
You'll find the error message in the console:

_Error : Refused to display 'https://www.youtube.com/' in a frame because it set 'X-Frame-Options' to 'SAMEORIGIN'._

To deny iframe to your website: (default `true`)
```dotenv
JW_DENY_IFRAME=true
```

- Enable API

The CMS will generate the necessary APIs for use by SPA websites or other applications. If you need to use it, enable it by adding this config to your `.env` file (default `false`)
```dotenv
JW_ALLOW_API=true
```
### Network configs
You have the ability to create a network of sites by using the multisite feature.

- Config file `config/network.php`
- Enable your site network (Multisite): 
  - Add config to your fite `.env`
```dotenv
JW_ALLOW_MULTISITE=true
JW_NETWORK_ROOT_DOMAIN=yourdomain.com
```
  - Subsite domain will look like `subdomain.yourdomain.com`


### Publish assets
Run the vendor publish command
```
php artisan vendor:publish --tag=cms_assets
```
