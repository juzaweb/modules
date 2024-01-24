<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait UseMeta
{
    public function getMeta(string $key, mixed $default = null): mixed
    {
        return $this->json_metas[$key] ?? $default;
    }

    public function getMetas(): ?array
    {
        return $this->json_metas;
    }

    /**
     * @param  Builder  $builder
     * @param  string  $key
     * @param  string|array|int|null  $value
     *
     * @return Builder
     */
    public function scopeWhereMeta(Builder $builder, string $key, string|array|int|null $value): Builder
    {
        return $builder->whereHas(
            'metas',
            function (Builder $q) use (
                $key,
                $value
            ) {
                $q->where('meta_key', '=', $key);
                if (is_array($value)) {
                    $q->whereIn('meta_value', $value);
                } else {
                    $q->where('meta_value', '=', $value);
                }
            }
        );
    }

    /**
     * A scope to filter the builder based on meta key and values.
     *
     * @param Builder $builder The query builder instance
     * @param string $key The meta key to filter by
     * @param array $values The array of meta values to filter by
     * @return Builder The modified query builder instance
     */
    public function scopeWhereMetaIn(Builder $builder, string $key, array $values): Builder
    {
        return $builder->whereHas(
            'metas',
            function (Builder $q) use (
                $key,
                $values
            ) {
                $q->where('meta_key', '=', $key);
                $q->whereIn('meta_value', $values);
            }
        );
    }

    public function setMeta(string $key, string|array|int|null $value): void
    {
        $metas = $this->getMetas();

        $this->metas()->updateOrCreate(
            [
                'meta_key' => $key
            ],
            [
                'meta_value' => is_array($value) ? json_encode($value, JSON_THROW_ON_ERROR) : $value
            ]
        );

        $metas[$key] = $value;

        $this->update(
            [
                'json_metas' => $metas,
            ]
        );
    }

    public function deleteMeta(string $key): bool
    {
        $this->metas()->where('meta_key', $key)->delete();

        $metas = $this->getMetas();

        unset($metas[$key]);

        $this->update(
            [
                'json_metas' => $metas
            ]
        );

        return true;
    }

    public function deleteMetas(array $keys): bool
    {
        $this->metas()->whereIn('meta_key', $keys)->delete();

        $metas = $this->getMetas();

        foreach ($keys as $key) {
            unset($metas[$key]);
        }

        $this->update(
            [
                'json_metas' => $metas
            ]
        );

        return true;
    }

    public function syncMetas(array $data = []): void
    {
        $this->syncMetasWithoutDetaching($data);

        $this->metas()->whereNotIn('meta_key', array_keys($data))->delete();
    }

    public function syncMetasWithoutDetaching(array $data = []): void
    {
        $metas = $this->json_metas;

        foreach ($data as $key => $val) {
            $this->metas()->updateOrCreate(
                [
                    'meta_key' => $key
                ],
                [
                    'meta_value' => is_array($val) ? json_encode($val, JSON_THROW_ON_ERROR) : $val
                ]
            );

            $metas[$key] = $val;
        }

        $this->update(
            [
                'json_metas' => $metas,
            ]
        );
    }
}
