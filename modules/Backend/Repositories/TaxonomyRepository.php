<?php

namespace Juzaweb\Backend\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Juzaweb\Backend\Models\Taxonomy;
use Juzaweb\CMS\Interfaces\Repositories\WithAppendFilter;
use Juzaweb\CMS\Repositories\BaseRepository;
use Juzaweb\CMS\Repositories\Exceptions\RepositoryException;
use Juzaweb\CMS\Repositories\Interfaces\FilterableInterface;
use Juzaweb\CMS\Repositories\Interfaces\SearchableInterface;
use Juzaweb\CMS\Repositories\Interfaces\SortableInterface;

interface TaxonomyRepository extends
    BaseRepository,
    FilterableInterface,
    SearchableInterface,
    SortableInterface,
    WithAppendFilter
{
    public function findBySlug(string $slug): null|Taxonomy;
    
    /**
     * @param  int  $limit
     * @return LengthAwarePaginator
     * @throws RepositoryException
     */
    public function frontendListPaginate(int $limit): LengthAwarePaginator;
    
    public function frontendDetail(string $slug): ?Taxonomy;
}
