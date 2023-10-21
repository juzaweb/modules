<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Frontend\Criterias;

use Illuminate\Database\Eloquent\Builder;
use Juzaweb\Backend\Models\Post;
use Juzaweb\Backend\Repositories\PostRepository;
use Juzaweb\CMS\Repositories\Contracts\CriteriaInterface;
use Juzaweb\CMS\Repositories\Contracts\RepositoryInterface;

class FrontendPostCriteria implements CriteriaInterface
{
    public function __construct(protected ?string $type = null)
    {
    }

    /**
     * Apply criteria in query repository
     *
     * @param  Builder|Post  $model
     * @param  RepositoryInterface  $repository
     *
     * @return Builder
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository): Builder
    {
        if (!$repository instanceof PostRepository) {
            throw new \RuntimeException('Repository must be instance of PostRepository');
        }

        $builder = $model->with($repository->withFrontendDefaults())
            ->cacheFor(3600)
            ->select(
                [
                    'id',
                    'uuid',
                    'title',
                    'description',
                    'thumbnail',
                    'slug',
                    'views',
                    'total_rating',
                    'total_comment',
                    'type',
                    'status',
                    'created_by',
                    'created_at',
                    'json_metas',
                    'json_taxonomies',
                ]
            )
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->wherePublish();

        return apply_filters('post.selectFrontendBuilder', $builder);
    }
}
