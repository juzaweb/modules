<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\CMS\Contracts;

interface ShortCode
{
    public function register(string $name, callable|string $callback): static;

    public function enable(): static;

    public function disable(): static;

    public function compile(string $value): string;

    public function strip(string $value): string;
}
