Juzaweb Themes are files that work together to create the design and functionality of a Juzaweb site. Each Theme may be different, offering many choices for site owners to instantly change their website look.

Here is the Theme structure for Juzaweb website. Of course, your Theme can contain any other stylesheets, images, or files.

[auto_menu]

### Create a Theme
Create a theme with command

```shell
php artisan theme:make {theme-name}
```

### Theme Structure
```
|- theme-name
    |- data (optional)
        |- blocks
        |- widgets
    |- views
        |- auth (optional)
            - login.twig
            - register.twig
            - forgot_password.twig
        |- profile (optional)
            - index.twig
        |- components
            |- blocks (optional)
            |- widgets (optional)
            - pagination.twig
        |- template-parts
            - content.twig
            - single.twig
            - taxonomy.twig
        |- templates (optional)
        - 404.twig (optional)
        - footer.twig
        - header.twig
        - index.twig
        - search.twig
    - changelog.yml
    - register.json
    - theme.json
```

### Template Files List
Here is the Theme files for Juzaweb website. Of course, your Theme can contain any other stylesheets, images, or files.
- **index.twig**
  The main template. If your Theme provides its own templates, index.php must be present.
- **single.twig**
  The single post template. Used when a single post is queried. For this and all other query templates, index.php is used if the query template is not present.
- **single-{post-type}.twig**
  The single post template used when a single post from a custom post type is queried. For example, single-book.php would be used for displaying single posts from the custom post type named "book". index.php is used if the query template for the custom post type is not present.
- **taxonomy.twig**
  The term template. Used when a term in a custom taxonomy is queried.
- **search.twig**
  The search results template. Used when a search is performed.
- **404.twig**
  The 404 Not Found template. Used when WordPress cannot find a post or page that matches the query.

