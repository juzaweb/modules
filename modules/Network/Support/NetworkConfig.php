<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Network\Support;

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Juzaweb\Network\Models\NetworkConfig as ConfigModel;
use Juzaweb\Network\Contracts\NetworkConfig as NetworkConfigAlias;

class NetworkConfig implements NetworkConfigAlias
{
    protected array $configs;

    /**
     * @var CacheManager
     */
    protected CacheManager $cache;

    public function __construct(Container $app, CacheManager $cache)
    {
        $this->cache = $cache;
    }

    public function getConfig(string $key, string|array $default = null): null|string|array
    {
        $configKeys = explode('.', $key);
        $value = $this->configs()[$configKeys[0]] ?? $default;
        if (is_json($value)) {
            $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            if (count($configKeys) > 1) {
                unset($configKeys[0]);
                $value = Arr::get($value, implode('.', $configKeys), $default);
            }
        }

        return $value;
    }

    public function setConfig(string $key, string|array $value = null): ConfigModel
    {
        if (is_array($value)) {
            $value = json_encode($value, JSON_THROW_ON_ERROR);
        }

        $config = ConfigModel::updateOrCreate(
            [
                'code' => $key,
            ],
            [
                'value' => $value,
            ]
        );

        $configs = $this->configs();

        $configs[$key] = $value;
        $this->cache->store('file')->forever(
            $this->getCacheKey(),
            $configs
        );

        $this->configs = $configs;

        return $config;
    }

    public function getConfigs(array $keys, string|array $default = null): array
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->getConfig($key, $default);
        }

        return $data;
    }

    public function all(): Collection
    {
        return collect($this->configs())->map(
            function ($value) {
                if (is_json($value)) {
                    return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
                }

                return $value;
            }
        );
    }

    protected function configs(): array
    {
        return $this->configs = $this->cache
            ->store('file')
            ->rememberForever(
                $this->getCacheKey(),
                function () {
                    return ConfigModel::get(
                        [
                            'code',
                            'value',
                        ]
                    )->keyBy('code')
                        ->map(
                            function ($item) {
                                return $item->value;
                            }
                        )
                        ->toArray();
                }
            );
    }

    protected function getCacheKey(): string
    {
        return cache_prefix('jw_network_configs');
    }
}
