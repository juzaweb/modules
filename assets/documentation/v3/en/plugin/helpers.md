Helper functions, helper class supported in Juzaweb CMS

[auto_menu]

### Fields helpers
Field Facade helper.

**Usage:**
```php
// Use Facade Field
use Juzaweb\CMS\Facades\Field;
```

#### Input text field
```php
Field::text(string|Model $label, ?string $name, ?array $options = [])
```

**$options** array
  - id (string): Input id, default `random`
  - value (string|integer) Input value, default `null`
  - class (string) Input class, default `form-control`
  - required (bool) Input required, default `false`
  - disabled (bool) Input disabed, default `false`
  - readonly (bool) Input readonly, default `false`
  - placeholder (string) Input placeholder, default `empty`
  - data (array) Input data-{key}={value} list (for javascript handle)

#### Input hidden field
```php
Field::hidden(string|Model $label, ?string $name, ?array $options = [])
```

**$options** array
  - id (string): Input id, default `random`
  - value (string|integer) Input value, default `null`
  - class (string) Input class, default `form-control`
  - placeholder (string) Input placeholder, default `empty`
  - data (array) Input data-{key}={value} list (for javascript handle)

#### Textarea field
```php
Field::textarea(string|Model $label, ?string $name, ?array $options = [])
```

**$options** array
  - id (string): Input id, default `random`
  - value (string|integer) Input value, default `null`
  - class (string) Input class, default `form-control`
  - required (bool) Input required, default `false`
  - disabled (bool) Input disabed, default `false`
  - readonly (bool) Input readonly, default `false`
  - placeholder (string) Input placeholder, default `empty`
  - data (array) Input data-{key}={value} list (for javascript handle)

#### Select box (Dropdown List) field
```php
Field::select(string|Model $label, ?string $name, ?array $options = [])
```

**$options** array
  - id (string): Input id, default `random`
  - value (string|integer) Input value, default `null`
  - class (string) Input class, default `form-control`
  - required (bool) Input required, default `false`
  - disabled (bool) Input disabed, default `false`
  - readonly (bool) Input readonly, default `false`
  - placeholder (string) Input placeholder, default `empty`
  - data (array) Input data-{key}={value} list (for javascript handle)

#### Checkbox field 
```php
Field::checkbox(string|Model $label, ?string $name, ?array $options = [])
```
**$options** array
  - id (string): Input id, default `random`
  - value (string|integer) Input value, default `null`
  - class (string) Input class, default `form-control`
  - required (bool) Input required, default `false`
  - disabled (bool) Input disabed, default `false`
  - readonly (bool) Input readonly, default `false`
  - placeholder (string) Input placeholder, default `empty`
  - data (array) Input data-{key}={value} list (for javascript handle)

#### Slug field (is custom text field)
```php
Field::slug(string|Model $label, ?string $name, ?array $options = [])
```
**$options** array
  - id (string): Input id, default `random`
  - class (string) Input class, default `form-control`
  - placeholder (string) Input placeholder, default `empty`
  - data (array) Input data-{key}={value} list (for javascript handle)

#### Editor field (Text editor field)
```php
Field::editor(string|Model $label, ?string $name, ?array $options = [])
```

**$options** array
  - id (string): Input id, default `random`
  - value (string|integer) Input value, default `null`
  - class (string) Input class, default `form-control`
  - required (bool) Input required, default `false`
  - disabled (bool) Input disabed, default `false`
  - readonly (bool) Input readonly, default `false`
  - placeholder (string) Input placeholder, default `empty`
  - data (array) Input data-{key}={value} list (for javascript handle)

#### Image filed (Select image from file manager)
```php
Field::image(string|Model $label, ?string $name, ?array $options = [])
```

**$options** array
  - id (string): Input id, default `random`
  - value (string|integer) Input value, default `null`
  - class (string) Input class, default `form-control`
  - required (bool) Input required, default `false`
  - disabled (bool) Input disabed, default `false`
  - readonly (bool) Input readonly, default `false`
  - placeholder (string) Input placeholder, default `empty`
  - data (array) Input data-{key}={value} list (for javascript handle)

#### Images filed (Select images from file manager)
```php
Field::images(string|Model $label, ?string $name, ?array $options = [])
```
**$options** array
  - id (string): Input id, default `random`
  - value (string|integer) Input value, default `null`
  - class (string) Input class, default `form-control`
  - required (bool) Input required, default `false`
  - disabled (bool) Input disabed, default `false`
  - readonly (bool) Input readonly, default `false`
  - placeholder (string) Input placeholder, default `empty`
  - data (array) Input data-{key}={value} list (for javascript handle)

#### Upload Url field (Select file from file manager or enter url file)
```php
Field::uploadUrl(string|Model $label, ?string $name, ?array $options = [])
```

**$options** array
  - id (string): Input id, default `random`
  - value (string|integer) Input value, default `null`
  - class (string) Input class, default `form-control`
  - required (bool) Input required, default `false`
  - disabled (bool) Input disabed, default `false`
  - readonly (bool) Input readonly, default `false`
  - placeholder (string) Input placeholder, default `empty`
  - data (array) Input data-{key}={value} list (for javascript handle)

#### Security Field (Value will be partially hidden)
```php
Field::security(string|Model $label, ?string $name, ?array $options = [])
```

**$options** array
  - id (string): Input id, default `random`
  - value (string|integer) Input value, default `null`
  - class (string) Input class, default `form-control`
  - required (bool) Input required, default `false`
  - disabled (bool) Input disabed, default `false`
  - readonly (bool) Input readonly, default `false`
  - placeholder (string) Input placeholder, default `empty`
  - data (array) Input data-{key}={value} list (for javascript handle)

#### Example with Form
```html
<form action="" method="post" class="form-ajax">
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6 text-right">
            <div class="btn-group">
                <button type="submit" class="btn btn-success px-5">
                    <i class="fa fa-save"></i> {{ trans('cms::app.save') }}
                </button>

                <button type="button" class="btn btn-warning cancel-button px-3">
                    <i class="fa fa-refresh"></i> {{ trans('cms::app.reset') }}
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            {{ Field::text($model, 'title', ['class' => 'example-class']) }}

            {{ Field::editor($model, 'content') }}
            
            {{ Field::text(trans('example::content.custom'), 'custom', ['value' => 'custom-value']) }}
        </div>

        <div class="col-md-4">
            {{ Field::image($model, 'thumbnail') }}
        </div>
    </div>
</form>
```

### Helper functions
#### Get database config
```php
$config = get_config($key, $default = null)
```
- Parameters
  - **$key (string)**: Config key in database (E.x: **title**)
  - **$default (mixed)**: Default value if not isset config

#### Set database config
```php
set_config($key, $value)
```
- Parameters
  - **$key (string)**: Config key in database (E.x: title)
  - **$value (string|array|null)**: Config value

#### Get client ip (Support cloudflare dns)
```php
$ip = get_client_ip()
```

#### Check string is url
```php
function is_url(string $string): bool
```
- Parameters
  - **$string (string)**: Url
  - **@return bool**: true if string is a url

#### Esc html (XssCleaner)
```php
function e_html($str): string
```
- Parameters
  - **$str (string)**: Html/text/string
  - **@return string**

#### Upload url
Get file upload url in public storage
```php
function upload_url(?string $path, ?string $default = null, ?string $size = null): string
```

#### Check str is json
Rerutn true if string is a json
```php
function is_json(mixed $string): bool
```

