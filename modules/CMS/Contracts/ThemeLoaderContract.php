<?php

namespace Juzaweb\CMS\Contracts;

interface ThemeLoaderContract
{
    /**
     * Retrieves the value of a register.
     *
     * @param  string  $theme  The theme name.
     * @param  mixed|null  $key  The register key (optional).
     * @param  mixed|null  $default  The default value if the register does not exist (optional).
     * @return string|array|null The value of the register.
     */
    public function getRegister(string $theme, ?string $key = null, mixed $default = null): string|array|null;

    /**
     * Retrieves the composer for a given theme.
     *
     * @param  string  $theme  The name of the theme.
     * @param  mixed  $key  Optional. The key to retrieve from the composer. Default null.
     * @param  mixed|null  $default
     * @return string|array|null The value of the composer for the given theme.
     * If a key is provided, only the value of that key will be returned.
     * If the key is not found, the default value will be returned.
     * If no key is provided, the entire composer array will be returned.
     * If the theme does not have a composer, null will be returned.
     */
    public function getComposer(string $theme, ?string $key = null, mixed $default = null): string|array|null;
}
