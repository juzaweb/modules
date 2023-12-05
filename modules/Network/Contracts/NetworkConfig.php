<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Network\Contracts;

use Illuminate\Support\Collection;
use Juzaweb\Network\Models\NetworkConfig as ConfigModel;

/**
 * @see \Juzaweb\Network\Support\NetworkConfig
 */
interface NetworkConfig
{
    /**
     * Retrieves the value of a configuration key.
     *
     * @param  string  $key  The key of the configuration value.
     * @param  string|array|null  $default  The default value to return if the key is not found. Default is null.
     * @return null|string|array The value of the configuration key.
     */
    public function getConfig(string $key, string|array $default = null): null|string|array;

    /**
     * Sets the configuration value for a given key.
     *
     * @param string $key The key of the configuration value.
     * @param string|array|null $value The value to set. It can be a string, an array, or null.
     * @return ConfigModel The updated ConfigModel instance.
     */
    public function setConfig(string $key, string|array $value = null): ConfigModel;

    /**
     * Retrieves the configuration values for the given keys.
     *
     * @param array $keys The keys for the configuration values.
     * @param string|array|null $default The default value to return if the configuration value is not found.
     * @return array The configuration values for the given keys.
     */
    public function getConfigs(array $keys, string|array $default = null): array;

    /**
     * Retrieves all the configs.
     *
     * @return Collection The collection configs.
     */
    public function all(): Collection;
}
