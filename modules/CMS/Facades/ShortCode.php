<?php

namespace Juzaweb\CMS\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\CMS\Contracts\ShortCode as ShortCodeContract;

/**
 * @method static static register(string $name, callable|string $callback)
 * @method static string compile(string $value)
 * @see \Juzaweb\CMS\Support\ShortCode\ShortCode
 */
class ShortCode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ShortCodeContract::class;
    }
}
