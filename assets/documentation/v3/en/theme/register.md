At the root of each theme, there is always a `register.json` file. In this file you can add what is needed for your theme.

[auto_menu]

### Register js and css your theme
You can add js or css to your theme very simple. Add or edit your `register.json` file, Accept `path/to/yourtheme/assets/path` or url remote file. E.x
```json
{
    "styles": {
        "js": [
            "assets/js/main.js"
        ],
        "css": [
            "https://fonts.googleapis.com/css2?family=Montserrat:ital",
            "assets/css/main.css"
        ]
    }
}
```

### Register nav menu
Add nav menu location in your theme. Add or edit your `register.json` file
```json
{
    "nav_menus": {
        "primary": {
            "label": "Primary menu"
        }
    }
}
```
And `Admin -> Menu`, You can choose `location` for your menu

### Register sidebar
Sidebar refers to a widget ready area used by Themes to display information that is not part of the main content. It is not always a vertical column on the side. It can be a horizontal rectangle below or above the content area, footer, header or anywhere in the Theme.

The use of Sidebars varies and depends on the choice of the Theme designer. Themes that support multiple Sidebars are also known as widget ready areas.

#### Add sidebar to Theme
- Add or edit your `register.json` file
```json
{
    "sidebars": {
        "sidebar": {
            "label": "Sidebar"
        }
    }
}
```
- Now, you can go to `Admin -> Widgets`, there is a block `Sidebar` on the right column of the screen.
#### Show sidebar on Theme
- You can use the `dynamic_sidebar` function to call the Sidebar where you want the Sidebar to be displayed
- Ex:
```twig
<div class="sticky-top">
    {{ dynamic_sidebar('sidebar') }}
</div>
```

### Register `widgets`
#### Add `widgets`
- Add or edit your `register.json` file
```json
{
    "widgets": {
        "banner": {
            "label": "Banner",
            "description": "Banner sidebar",
            "view": "theme::widgets.banner.show"
        },
        "popular": {
            "label": "Popular",
            "description": "Popular posts",
            "view": "theme::widgets.popular.show"
        }
    }
}
```
#### Add `widgets` to `sidebars`
- To add `widgets` to `sidebars` go to `Admin -> Appearance -> Widgets` select and click `Add Widget` from the left column, `widgets` will be added to the respective `sidebars` in the right column

### Register page templates
- Add or edit your `register.json` file
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

- The above code adds a template with key `home`, you can see it in `Admin -> Page -> Edit/Create Form -> Template`. `theme::templates.home` is the view that displays the page content. To display template content, see [Custom Page](custom-page).

### Register page block
`Page Block` is similar to `widgets` but it can be customized on specific pages if the page uses `Page Block` content.

#### Add Page Block
- Add or edit your `register.json` file.
```json
{
    "blocks": {
        "popular_news": {
            "label": "Popular news",
            "description": "Popular news",
            "view": "theme::widgets.popular_news.show"
        }
    }
}
```
- The above code adds a `Page Block` whose key is `popular_news`, `theme::widgets.popular_news.show` is the display view of this block on Theme.
#### Using Page Block in the Template
- To enable Page Block in your template, you need to register blocks in your template registration.
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
        }
    }
}
```
- `content` is the block users can add sub-blocks when creating/editing pages with `home` template.

See more document for page templates: 

### Full file demo theme register

```json
{
    "styles": {
        "js": [
            "assets/js/main.js"
        ],
        "css": [
            "https://fonts.googleapis.com/css2?family=Montserrat:ital",
            "assets/css/main.css"
        ]
    },
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
    },
    "nav_menus": {
        "primary": {
            "label": "Primary menu"
        }
    },
    "sidebars": {
        "sidebar": {
            "label": "Sidebar"
        },
        "footer_column": {
            "label": "Footer"
        }
    },
    "widgets": {
        "banner": {
            "label": "Banner",
            "description": "Banner sidebar",
            "view": "theme::widgets.banner.show"
        },
        "popular": {
            "label": "Popular",
            "description": "Popular posts",
            "view": "theme::widgets.popular.show"
        }
    },
    "blocks": {
        "news_slider": {
            "label": "Post slider",
            "description": "Posts slider carousel",
            "view": "theme::widgets.news_slider.show"
        },
        "popular_news": {
            "label": "Popular news",
            "description": "Popular news",
            "view": "theme::widgets.popular_news.show"
        }
    }
}
```