<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Traits\HookActions;

use Illuminate\Support\Collection;

trait PageCustomData
{
    public function getPageCustomDatas(string|null $key = null): ?Collection
    {
        $configs = collect($this->globalData->get('page_custom_datas'));

        if ($key) {
            return $configs->get($key);
        }

        return $configs;
    }

    public function registerPageCustomData(string $key, callable $value): void
    {
        $args = [
            'callback' => $value,
        ];

        $this->globalData->set("page_custom_datas.{$key}", new Collection($args));
    }
}
