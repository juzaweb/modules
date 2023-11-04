<?php

namespace Juzaweb\CMS\Contracts;

interface ThemeLoaderContract
{
    /**
     * Load providers for a given theme.
     *
     * @param string $theme The theme to load providers for.
     * @throws \Exception If an error occurs while loading providers.
     * @return void
     */
    public function loadProviders(string $theme): void;
}
