<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

use Illuminate\Support\Str;

if (! function_exists('plugin_path')) {
    function plugin_path($name, $path = ''): string
    {
        $module = app('plugins')->find($name);

        return $module->getPath() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('namespace_snakename')) {
    function namespace_snakename(string $namespace): string
    {
        return Str::snake(preg_replace('/[^0-9a-z]/', ' ', strtolower($namespace)));
    }
}

if (! function_exists('installed_plugins')) {
    function installed_plugins(): array
    {
        $plugins = app('plugins')->all();

        return array_keys($plugins);
    }
}

if (! function_exists('plugin_enabled')) {
    function plugin_enabled(string $name): bool
    {
        $plugin = app('plugins')->find($name);

        return $plugin && $plugin->isEnabled();
    }
}
