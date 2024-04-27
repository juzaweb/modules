<?php

require __DIR__ . '/permissions.php';
require __DIR__ . '/html_dom.php';
require __DIR__ . '/data-get.php';
require __DIR__ . '/plugin.php';
require __DIR__ . '/url.php';

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Juzaweb\Backend\Models\Post;
use Juzaweb\CMS\Contracts\BackendMessageContract;
use Juzaweb\CMS\Contracts\ConfigContract;
use Juzaweb\CMS\Facades\Config;
use Juzaweb\CMS\Facades\Hook;
use Juzaweb\CMS\Facades\HookAction;
use Juzaweb\CMS\Facades\XssCleaner;
use Juzaweb\CMS\Models\User;
use Juzaweb\CMS\Support\Breadcrumb;
use Juzaweb\Network\Contracts\NetworkConfig;
use Juzaweb\Network\Models\NetworkConfig as NetworkConfigModel;

if (!function_exists('e_html')) {
    function e_html($str): string
    {
        return XssCleaner::clean($str);
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * Get client ip
     *
     * @return string
     * */
    function get_client_ip(): string
    {
        // Check Cloudflare support
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            return $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        // Get ip from server
        return request()->ip();
    }
}

if (!function_exists('get_config')) {
    /**
     * Get DB config
     *
     * @param string $key
     * @param mixed $default
     * @return array|string|null
     */
    function get_config(string $key, mixed $default = null): array|string|null
    {
        try {
            return app(ConfigContract::class)->getConfig($key, $default);
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('get_configs')) {
    /**
     * Get multi DB configs
     *
     * @param array $keys
     * @param mixed $default
     * @return array
     */
    function get_configs(array $keys, mixed $default = null): array
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = get_config($key, $default);
        }

        return $data;
    }
}

if (!function_exists('set_config')) {
    /**
     * Set DB config
     *
     * @param string $key
     * @param mixed $value
     * @return \Juzaweb\CMS\Models\Config
     */
    function set_config(string $key, mixed $value): \Juzaweb\CMS\Models\Config
    {
        return Config::setConfig($key, $value);
    }
}

if (!function_exists('generate_token')) {
    /**
     * Generate static by token
     *
     * @param string $string
     * @return string
     */
    function generate_token(string $string): string
    {
        $month = date('Y-m');
        $ip = get_client_ip();
        $key = 'ADA&$sdss$#&%^23vx' . config('app.key');
        return sha1($key . $month . $key . $ip . $string);
    }
}

if (!function_exists('check_token')) {
    /**
     * Check static token
     *
     * @param string $token
     * @param string $string
     * @return bool
     */
    function check_token(string $token, string $string): bool
    {
        if (generate_token($string) == $token) {
            return true;
        }

        return false;
    }
}

if (!function_exists('sub_words')) {
    function sub_words($string, int $words = 20): string
    {
        return Str::words($string, $words);
    }
}

if (!function_exists('count_unread_notifications')) {
    /**
     * Count number unread notifications
     *
     * @return int
     */
    function count_unread_notifications(): int
    {
        global $jw_user;

        if (!isset($jw_user)) {
            return 0;
        }

        if (method_exists($jw_user, 'unreadNotifications')) {
            return $jw_user->unreadNotifications()->cacheFor(3600)->count(['id']);
        }

        return 0;
    }
}

if (!function_exists('user_avatar')) {
    function user_avatar($user = null): string
    {
        if ($user) {
            if (!$user instanceof User) {
                $user = User::find($user);
            }

            return $user->getAvatar();
        }

        if (Auth::check()) {
            /**
             * @var User $user
             */
            $user = Auth::user();

            return $user->getAvatar();
        }

        return asset('jw-styles/juzaweb/images/thumb-default.png');
    }
}

if (!function_exists('jw_breadcrumb')) {
    function jw_breadcrumb(string $name, array $addItems = []): View
    {
        $items = apply_filters($name . '_breadcrumb', []);

        if ($addItems) {
            foreach ($addItems as $addItem) {
                $items[] = $addItem;
            }
        }

        return Breadcrumb::render($name, $items);
    }
}

if (!function_exists('combine_pivot')) {
    function combine_pivot($entities, $pivots = []): array
    {
        // Set array
        $pivotArray = [];
        // Loop through all pivot attributes
        foreach ($pivots as $pivot => $value) {
            // Combine them to pivot array
            $pivotArray += [$pivot => $value];
        }
        // Get the total of arrays we need to fill
        $total = count($entities);
        // Make filler array
        $filler = array_fill(0, $total, $pivotArray);

        // Combine and return filler pivot array with data
        return array_combine($entities, $filler);
    }
}

if (!function_exists('upload_url')) {
    /**
     * Get file upload url in public storage
     *
     * @param string|null $path
     * @param string|null $default Default path if file not exists
     * @param string|null $size
     * @return string
     */
    function upload_url(?string $path, ?string $default = null, ?string $size = null): string
    {
        if (is_url($path)) {
            return apply_filters('get_upload_url', $path, $path, $default, $size);
        }

        if (str_starts_with($path, '/')) {
            return url($path);
        }

        $storage = Storage::disk('public');
        if (empty($path) || !$storage->exists(jw_basepath($path))) {
            if ($default) {
                return $default;
            }

            return asset('jw-styles/juzaweb/images/thumb-default.png');
        }

        if ($size) {
            $filename = File::name($path);
            $pathSize = str_replace($filename, "{$filename}_{$size}", $path);

            if ($storage->exists(jw_basepath($pathSize))) {
                return url($storage->url($pathSize));
            }

            if (config('juzaweb.filemanager.image_resizer')) {
                return asset("jw-styles/images/resize/{$size}/{$path}");
            }
        }

        return apply_filters('get_upload_url', url($storage->url($path)), $path, $default, $size);
    }
}

if (!function_exists('random_string')) {
    function random_string(int $length = 16): string
    {
        return Str::random($length);
    }
}

if (!function_exists('is_json')) {
    /**
     * Rerutn true if string is a json
     *
     * @param string $string
     * @return bool
     */
    function is_json(mixed $string): bool
    {
        try {
            json_decode($string);

            return json_last_error() === JSON_ERROR_NONE;
        } catch (\Throwable $e) {
            return false;
        }
    }
}

if (!function_exists('do_action')) {
    /**
     * JUZAWEB CMS: Do action hook
     *
     * @param string $tag
     * @param mixed ...$args Additional parameters to pass to the callback functions.
     * @return void
     * */
    function do_action(string $tag, ...$args): void
    {
        Hook::action($tag, ...$args);
    }
}

if (!function_exists('add_action')) {
    /**
     * JUZAWEB CMS: Add action to hook
     *
     * @param  string  $tag The name of the filter to hook the $function_to_add callback to.
     * @param  callable  $callback The callback to be run when the filter is applied.
     * @param  int  $priority Optional. Used to specify the order in which the functions
     *                                  associated with a particular action are executed.
     *                                  Lower numbers correspond with earlier execution,
     *                                  and functions with the same priority are executed
     *                                  in the order in which they were added to the action. Default 20.
     * @param  int  $arguments Optional. The number of arguments the function accepts. Default 1.
     * @return void
     */
    function add_action(string $tag, callable $callback, int $priority = 20, int $arguments = 1): void
    {
        Hook::addAction($tag, $callback, $priority, $arguments);
    }
}

if (!function_exists('apply_filters')) {
    /**
     * JUZAWEB CMS: Apply filters to value
     *
     * @param  string  $tag The name of the filter hook.
     * @param mixed $value The value to filter.
     * @param mixed ...$args Additional parameters to pass to the callback functions.
     * @return mixed The filtered value after all hooked functions are applied to it.
     */
    function apply_filters(string $tag, mixed $value, ...$args): mixed
    {
        return Hook::filter($tag, $value, ...$args);
    }
}

if (!function_exists('add_filters')) {
    /**
     * @param  string  $tag The name of the filter to hook the $function_to_add callback to.
     * @param  callable  $callback The callback to be run when the filter is applied.
     * @param  int  $priority Optional. Used to specify the order in which the functions
     *                                  associated with a particular action are executed.
     *                                  Lower numbers correspond with earlier execution,
     *                                  and functions with the same priority are executed
     *                                  in the order in which they were added to the action. Default 20.
     * @param  int  $arguments Optional. The number of arguments the function accepts. Default 1.
     * @return void
     */
    function add_filters(string $tag, callable $callback, int $priority = 20, int $arguments = 1): void
    {
        Hook::addFilter($tag, $callback, $priority, $arguments);
    }
}

if (!function_exists('is_active_route')) {
    /**
     * Set the active class to the current opened menu.
     *
     * @param array|string $route
     * @param string $className
     * @return bool|string
     */
    function is_active_route(array|string $route, string $className = 'active'): bool|string
    {
        if (is_array($route)) {
            return in_array(Route::currentRouteName(), $route) ? $className : '';
        }

        if (Route::currentRouteName() == $route) {
            return $className;
        }

        if (strpos(URL::current(), $route)) {
            return $className;
        }

        return false;
    }
}

if (!function_exists('jw_date_format')) {
    /**
     * Format date to global format cms
     *
     * @param string $date
     * @param int $format // JW_DATE || JW_DATE_TIME
     * @return string
     */
    function jw_date_format(mixed $date, int $format = JW_DATE_TIME): string
    {
        if ($date instanceof Carbon) {
            $date = $date->format('Y-m-d H:i:s');
        }

        $dateFormat = get_config('date_format', 'F j, Y');
        if ($dateFormat == 'custom') {
            $dateFormat = get_config('date_format_custom', 'F j, Y');
        }

        if ($format == JW_DATE) {
            return date($dateFormat, $date);
        }

        $timeFormat = get_config('time_format', 'g:i a');
        if ($timeFormat == 'custom') {
            $timeFormat = get_config('time_format_custom', 'g:i a');
        }

        return date($dateFormat . ' ' . $timeFormat, strtotime($date));
    }
}

if (!function_exists('jw_current_user')) {
    /**
     * Get current login user
     *
     * @return Authenticatable|User|null
     */
    function jw_current_user(): Authenticatable|User|null
    {
        return Auth::user();
    }
}

if (!function_exists('jw_get_page')) {
    /**
     * @param $id
     * @return Post|null
     */
    function jw_get_page($id): Post|null
    {
        return Post::find($id);
    }
}

if (!function_exists('array_only')) {
    /**
     * Get a subset of the items from the given array.
     *
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    function array_only(array $array, array|string $keys): array
    {
        return Arr::only($array, $keys);
    }
}

if (!function_exists('array_except')) {
    /**
     * Get all the given array except for a specified array of keys.
     *
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    function array_except(array $array, array|string $keys): array
    {
        return Arr::except($array, $keys);
    }
}

if (!function_exists('get_enqueue_scripts')) {
    function get_enqueue_scripts($inFooter = false): Collection
    {
        return HookAction::getEnqueueScripts($inFooter);
    }
}

if (!function_exists('get_enqueue_styles')) {
    function get_enqueue_styles($inFooter = false): Collection
    {
        return HookAction::getEnqueueStyles($inFooter);
    }
}

if (!function_exists('jw_get_select_options')) {
    function jw_get_select_options($data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $value['label'];
            } else {
                $result[$key] = $value->get('label');
            }
        }

        return $result;
    }
}

if (!function_exists('str_words_length')) {
    function str_words_length($string, $words, $max_length): string
    {
        while (strlen($string) > $max_length) {
            $string = Str::words($string, $words);
            $words--;
        }

        return $string;
    }
}

if (!function_exists('recursive_level_model')) {
    function recursive_level_model(&$level, $model, $limit = 5): void
    {
        if ($level > $limit) {
            $level = 0;

            return;
        }

        $model->load(['parent']);
        if ($model->parent) {
            $level++;
            recursive_level_model($level, $model->parent);
        }
    }
}

if (!function_exists('get_version_by_tag')) {
    function get_version_by_tag($tag): string
    {
        return str_replace('v', '', $tag);
    }
}

if (!function_exists('get_backend_message')) {
    function get_backend_message(): array
    {
        return app(BackendMessageContract::class)->all();
    }
}

if (!function_exists('add_backend_message')) {
    function add_backend_message($group, $messages = [], $status = 'success'): void
    {
        app(BackendMessageContract::class)->add(
            $group,
            $messages,
            $status
        );
    }
}

if (!function_exists('remove_backend_message')) {
    function remove_backend_message($key): bool
    {
        return app(BackendMessageContract::class)->delete($key);
    }
}

if (!function_exists('is_admin')) {
    /**
     * @param User|null $user
     * @return bool
     */
    function is_admin(?User $user = null): bool
    {
        if (empty($user)) {
            /**
             * @var User $jw_user
             */
            global $jw_user;

            $user = $jw_user;
        }

        if (empty($user)) {
            return false;
        }

        return $user->isAdmin();
    }
}

if (!function_exists('has_permission')) {
    /**
     * @param User|null $user
     * @return bool
     */
    function has_permission(User|null $user = null): bool
    {
        if (empty($user)) {
            /**
             * @var User $jw_user
             */
            global $jw_user;

            $user = $jw_user;
        }

        if (empty($user)) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasPermission();
    }
}

if (!function_exists('collect_metas')) {
    function collect_metas(array $metas): Collection
    {
        return \Juzaweb\CMS\Facades\Field::collect($metas);
    }
}

if (!function_exists('get_youtube_id')) {
    function get_youtube_id($url): ?string
    {
        preg_match(
            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            $url,
            $match
        );

        if (@$match[1]) {
            return $match[1];
        }

        return null;
    }
}

if (!function_exists('get_vimeo_id')) {
    function get_vimeo_id(string $url)
    {
        $regs = [];
        $id = '';
        if (preg_match(
            '%^https?:\/\/(?:www\.|player\.)?vimeo.com\/'
            . '(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)'
            . '(\d+)(?:$|\/|\?)(?:[?]?.*)$%im',
            $url,
            $regs
        )
        ) {
            $id = $regs[3];
        }

        return $id;
    }
}

if (function_exists('get_google_drive_id')) {
    function get_google_drive_id(string $url): string
    {
        return explode('/', $url)[5];
    }
}

if (!function_exists('remove_query_url')) {
    function remove_query_url(string $url): string
    {
        return explode('?', $url)[0];
    }
}

function format_size_units($bytes, $decimals = 2): string
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, $decimals) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, $decimals) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, $decimals) . ' KB';
    } elseif ($bytes > 1) {
        $bytes .= ' bytes';
    } elseif ($bytes == 1) {
        $bytes .= ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function convert_linux_path(string $path): string
{
    return str_replace(
        '\\',
        '/',
        $path
    );
}

function seo_string($string, $chars = 70)
{
    $string = strip_tags($string);
    $string = str_replace(["\n", "\t"], ' ', $string);
    $string = html_entity_decode($string, ENT_HTML5);
    return sub_char($string, $chars);
}

if (!function_exists('basename_without_query_string')) {
    function basename_without_query_string(string $name): string
    {
        $base = basename($name);
        $base = explode('?', $base)[0];
        if (empty($base)) {
            $base = Str::random(10);
        }
        return $base;
    }
}

function jw_basename(string $name): string
{
    return basename_without_query_string($name);
}

function parse_price_format($price): float
{
    $price = str_replace(',', '', $price);
    return (float)$price;
}

function sub_char($str, $n, $end = '...')
{
    if (strlen($str) < $n) {
        return $str;
    }

    $html = mb_substr($str, 0, $n);
    $html = mb_substr($html, 0, mb_strrpos($html, ' '));
    return $html . $end;
}

function cache_prefix($name): string
{
    return config('juzaweb.cache_prefix') . $name;
}

if (!function_exists('admin_url')) {
    function admin_url($path = '', $parameters = [], $secure = null): string
    {
        $adminUrl = apply_filters(
            'admin_url',
            url(config('juzaweb.admin_prefix'), $parameters, $secure)
        );

        if ($path) {
            $url = url(
                $adminUrl.'/'.ltrim($path, '/'),
                $parameters,
                $secure
            );
        } else {
            $url = url($adminUrl, $parameters, $secure);
        }

        return apply_filters('admin_url.full', $url, $path, $parameters, $secure);
    }
}

if (!function_exists('jw_basepath')) {
    function jw_basepath(string $path): string
    {
        return explode('?', $path)[0];
    }
}

if (!function_exists('remove_bbcode')) {
    function remove_bbcode(string $text): ?string
    {
        $text = preg_replace('~\[img\](.*?)\[/img\]~s', '', $text);
        $pattern = '|[[\/\!]*?[^\[\]]*?]|si';
        $text = preg_replace($pattern, ' ', $text);
        $text = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $text);
        $text = str_replace(["\n", "\t"], '', $text);
        return trim($text);
    }
}

if (!function_exists('get_domain_by_url')) {
    function get_domain_by_url(string $url, bool $noneWWW = false): string|bool
    {
        if (str_starts_with($url, 'https://')
            || str_starts_with($url, 'http://')
            || str_starts_with($url, '//')
        ) {
            $domain = explode('/', $url)[2];
            if ($noneWWW) {
                if (str_starts_with($domain, 'www.')) {
                    $domain = str_replace('www.', '', $domain);
                }
            }

            return explode('?', $domain)[0];
        }

        return false;
    }
}

if (!function_exists('number_human_format')) {
    function number_human_format(int $number): string
    {
        if ($number < 1000000) {
            return number_format($number);
        }

        if ($number < 1000000000) {
            return number_format($number / 1000000, 2) . ' M';
        }

        if ($number < 1000000000000) {
            return number_format($number / 1000000000, 2) . ' B';
        }

        return number_format($number / 1000000000000, 2) . ' T';
    }
}

if (!function_exists('action_replace')) {
    function action_replace(string $action, $replaces = []): string
    {
        if (preg_match_all("/\{([0-9a-z]+)\}/", $action, $matches)) {
            foreach ($matches[1] as $i => $varname) {
                $action = str_replace($matches[0][$i], sprintf('%s', $replaces[$varname]), $action);
            }
        }

        return $action;
    }
}

if (!function_exists('is_dev_tool_enable')) {
    function is_dev_tool_enable(): bool
    {
        if (config('app.debug') && app()->environment() !== 'production') {
            return true;
        }

        return config('dev-tool.enable', false);
    }
}

if (!function_exists('map_name_repeater')) {
    function map_name_repeater(array $fields, Collection $options, string $marker): array
    {
        return collect($fields)->map(
            function ($field, $name) use ($options, $marker) {
                $fieldName = $options['name'] . '['. $marker .'][' . ($field['name'] ?? $name) . ']';

                if (isset($field['fields'])) {
                    $field['fields'] = map_name_repeater($field['fields'], $options, $marker);
                }

                return array_merge($field, ['name' => $fieldName]);
            }
        )->toArray();
    }
}

if (!function_exists('get_network_config')) {
    /**
     * Get DB config
     *
     * @param string $key
     * @param mixed $default
     * @return array|string|null
     */
    function get_network_config(string $key, mixed $default = null): array|string|null
    {
        try {
            return app(NetworkConfig::class)->getConfig($key, $default);
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('get_network_configs')) {
    /**
     * Get multi DB configs
     *
     * @param array $keys
     * @param mixed $default
     * @return array
     */
    function get_network_configs(array $keys, mixed $default = null): array
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = get_network_config($key, $default);
        }

        return $data;
    }
}

if (!function_exists('set_network_config')) {
    /**
     * Set DB config
     *
     * @param string $key
     * @param mixed $value
     * @return \Juzaweb\CMS\Models\Config
     */
    function set_network_config(string $key, mixed $value): NetworkConfigModel
    {
        return app(NetworkConfig::class)->setConfig($key, $value);
    }
}

if (!function_exists('is_admin_page')) {
    function is_admin_page(?string $url = null): bool
    {
        $adminPrefix = explode('/', config('juzaweb.admin_prefix'))[0];

        if ($url) {
            $path = ltrim(path_url($url), '/');

            return explode('/', $path)[0] == $adminPrefix;
        }

        return request()?->segment(1) == $adminPrefix;
    }
}

if (!function_exists('basename_without_extension')) {
    function basename_without_extension(string $path): ?string
    {
        return pathinfo(basename($path), PATHINFO_FILENAME);
    }
}

if (!function_exists('is_bot_request')) {
    function is_bot_request(): bool
    {
        return Str::contains(request()?->userAgent(), 'bot');
    }
}

if (!function_exists('cms_languages')) {
    function cms_languages(): Collection
    {
        $folders = File::directories(base_path('vendor/juzaweb/modules/resources/lang'));

        return collect(config('locales'))
            ->whereIn('code', collect($folders)->map(fn ($item) => basename($item))->values()->toArray());
    }
}
