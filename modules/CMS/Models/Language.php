<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Juzaweb\CMS\Traits\QueryCache\QueryCacheable;
use Juzaweb\Network\Traits\Networkable;

/**
 * Juzaweb\CMS\Models\Language
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property bool $default
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Language newModelQuery()
 * @method static Builder|Language newQuery()
 * @method static Builder|Language query()
 * @method static Builder|Language whereCode($value)
 * @method static Builder|Language whereCreatedAt($value)
 * @method static Builder|Language whereDefault($value)
 * @method static Builder|Language whereId($value)
 * @method static Builder|Language whereName($value)
 * @method static Builder|Language whereSiteId($value)
 * @method static Builder|Language whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int|null $site_id
 */
class Language extends Model
{
    use QueryCacheable, Networkable;

    public string $cachePrefix = 'languages_';

    protected $table = 'languages';
    protected $fillable = [
        'code',
        'name',
        'default'
    ];

    protected $casts = [
        'default' => 'bool'
    ];

    public static function existsCode($code): bool
    {
        return self::whereCode($code)->exists();
    }

    public static function setDefault($code): void
    {
        $language = self::whereCode($code)->firstOrFail();
        $language->update(
            [
                'default' => true
            ]
        );

        self::where('code', '!=', $code)
            ->where('default', '=', true)
            ->update(
                [
                    'default' => false
                ]
            );

        set_config('language', $language->code);
    }

    public static function languages(): Collection
    {
        return self::cacheFor(config('juzaweb.performance.query_cache.lifetime'))
            ->all()
            ->keyBy('code');
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    protected function getCacheBaseTags(): array
    {
        return [
            'languages',
        ];
    }
}
