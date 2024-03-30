A custom page lets you use a different layout from the rest of your website. Many websites use custom page layouts for their sales pages, landing pages, webinar pages, and more.

[auto_menu]

### Register page templates
Add or edit your `register.json` file
```json
{
    "templates": {
        "home": {
            "label": "Home",
            "view": "theme::templates.home",
            "blocks": {
                "content": {
                    "label": "Content"
                }
            }
        },
        "support": {
            "label": "Support",
            "view": "theme::templates.support"
        }
    }
}
```
- **theme::templates.home**, **theme::templates.support** is View dispaly template
- **blocks** Define blocks for template

The files defining each page template are found in your Themes directory. To create a new custom page template for a page you must create a file in `templates` directory. Let's call our first page template for our page home.twig. At the top of the `home.twig` file, put the following:

```twig
{% extends 'cms::layouts.frontend' %}

{% block content %}
    {# Your template content #}
{% endblock %}
```

* **cms::layouts.frontend** is the default layout with added styles. You can replace it with an existing template in your theme.

* block **content** is block default shows content in layout.

