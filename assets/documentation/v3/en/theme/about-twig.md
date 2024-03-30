In Juzaweb CMS, themes are built using [symfony twig template](https://twig.symfony.com/). This is template The flexible, fast, and secure template engine for PHP.

[auto_menu]

### Twig is a modern template engine
- **Fast**: Twig compiles templates down to plain optimized PHP code. The overhead compared to regular PHP code was reduced to the very minimum.
- **Secure**: Twig has a sandbox mode to evaluate untrusted template code. This allows Twig to be used as a template language for applications where users may modify the template design.
- **Flexible**: Twig is powered by a flexible lexer and parser. This allows the developer to define its own custom tags and filters, and create its own DSL.

See all documention [https://twig.symfony.com/doc/3.x/](https://twig.symfony.com/doc/3.x/)

### Layout and Block
- Default theme layouts: `cms::layouts.frontend`
- Default block content name: `content`
- Default block header name: `header`
- Default block footer name: `footer`