[auto_menu]

### Query Data From View

#### Get post type list data
```twig
get_posts(type, options = {})
```
- Parameters
  - **@param string $type** : Post type
  - **@param array $options**
      -  **paginate** : Row per page if you would like paginate
      -  **taxonomies** : Filter by taxonomies
      -  **taxonomy** : Filter by taxonomy
      -  **limit** : Limit posts
      -  **metas** : Filter by meta
      -  **order_by** : Order by. Default id DESC
  - **@return array**

#### Get taxonomy of post
```twig
get_post_taxonomy(post, taxonomy = null, params = [])
```
- Parameters
    - **@param array post**
    - **@param string taxonomy**
    - **@param array params**
    - **@return array**

#### Get taxonomy list of post
```twig
get_post_taxonomies(post, taxonomy = null, params = [])
```
- Parameters
    - **@param array post**
    - **@param string taxonomy**
    - **@param array params**
        -  parent_id
        -  tree
        -  limit
    - **@return array**

#### Get related posts
```twig
get_related_posts(post, limit = 5, taxonomy = null)
```

- Parameters
    - **@param array post**
    - **@param int limit**
        - Default: 5
    - **@param string taxonomy** : Get by taxonomy
        - Example: categories,tags
        - Default: null
    - **@return array**

#### Get post resource detail
```twig
get_post_resource(resource, id)
```

- Parameters
    - **@param string resource**
    - **@param int id**
    - **@return array**

#### Get post resource list
```twig
get_post_resources(resource, options = [])
```

- Parameters
  - **@param string resource**
  - **@param array options**
      - id
      - post_id
      - parent_id
      - limit
          - Default 10
  - **@return array**

#### Get previous post
```twig
get_previous_post(post)
```

- Parameters
    - **@param array post**
    - **@return array**

#### Get popular posts
```twig
get_popular_posts(type = null, post = null, limit = 5)
```

- Parameters
    - **@param string type** : Post type
    - **@param array post** : Post exclude
    - **@param int limit** : Rows limit

### Helper Functions

#### Dump data
```twig
dump(...var)
```
- Parameters
    - **@param mixed ...var**: Text or key translation
    - **@return string**
#### Dump data and exit
```twig
dd(...var)
```
- Parameters
    - **@param mixed ...var**: Text or key translation
    - **@return never**
#### Get data translation of text
```twig
__(string text)
```

- Parameters
    - **@param string text**: Text or key translation
    - **@return string**

#### Get full url from path     
```twig
url(string path = '')
```
- Parameters
    - **@param string path** (Optional) Return url home page if empty
    - **@return string**

#### Get csrf token       
```twig
csrf_token()
```
    
- Parameters
  - **@return string**

#### Get csrf token field
```twig
csrf_field()
```
    
- Parameters
    - **@return string**

#### Get media full url from path

```twig
upload_url(string path = '')
```

- Parameters
    - **@param string path**
    - **@return string**

#### Paginate render

```twig
paginate_links(data, view = null)
```

- Parameters
    - **@param data**: Paginate data
    - **@param string view**: View paginate item
  
#### Finds whether a variable is an string
```twig
is_string(var)
```
- Parameters
    - **@param mixed var**: The variable being evaluated.
    - **@return bool** true if var is an string

#### Check var is array

```twig
is_array(var)
```

- Parameters
    - **@param mixed var**: The variable being evaluated.
    - **@return bool** true if var is an array

#### Get json from array

```twig
json_encode(array $array)
```

- Parameters
    - **@param mixed $value**: The *value* being encoded. Can be any type except a resource.
    - `@return string|false` a JSON encoded string on success or *FALSE* on failure.

#### Calculate the md5 hash of a string
```twig
md5(str)
```
- Parameters
    - **@param string str**
    - **@return string** the hash as a 32-character hexadecimal number.

#### Check current page is home page
```twig
is_home()
```
- Parameters
    - **@return bool** true if current page is home page

#### Display dynamic sidebar.
By default, this displays the default sidebar or ‘sidebar-1’. If your theme specifies the ‘id’ or ‘name’ parameter for its registered sidebars you can pass an ID or name as the $index parameter. Otherwise, you can pass in a numerical index to display the sidebar at that index.
```twig
dynamic_sidebar(key)
```
- Parameters
    - `@param (int|string) key` Index, name or ID of dynamic sidebar.
    - `@return \Illuminate\Contracts\View\View`

#### Display dynamic page block.
```twig
dynamic_block(post, key)
```
- Parameters
    - **@param $post** Data post
    - `@param (int|string) key` Index, name or ID of dynamic sidebar.
    - `@return \Illuminate\Contracts\View\View|string`

#### Get share url social media
```twig
share_url(social, url, text = null)
```
- Parameters
    - **@param string social**
    - **@param string url**
    - **@param string text**
    - **@return string**
