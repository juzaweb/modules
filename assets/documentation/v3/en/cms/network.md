### Network Config
- Enable network (multisite):

Add config to file `.env`
```
JW_ALLOW_MULTISITE=true
JW_NETWORK_ROOT_DOMAIN=yourdomain.com
```

`JW_NETWORK_ROOT_DOMAIN` is root domain your website

- Make subsite:

Run command
```
php artisan network:make-site {sitename}
```

New subsite domain `sitename.yourdomain.com`
