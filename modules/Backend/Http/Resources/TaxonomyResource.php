<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Juzaweb\Backend\Models\Taxonomy;

/**
 * @property-read Taxonomy $resource
 */
class TaxonomyResource extends JsonResource
{
    protected bool $withParents = false;

    public function withParents(bool $withParents): static
    {
        $this->withParents = $withParents;

        return $this;
    }

    public function toArray($request): array
    {
        $results = [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'taxonomy' => $this->resource->taxonomy,
            'singular' => Str::singular($this->resource->taxonomy),
            'slug' => $this->resource->slug,
            'level' => $this->resource->level,
            'total_post' => $this->resource->total_post,
            'thumbnail' => $this->resource->getThumbnail(),
            'url' => $this->resource->getLink(),
        ];

        if ($this->withParents) {
            $parents = [];
            $this->mapRecursiveParents($this->resource, $parents);
            $results['parents'] = array_reverse($parents);
        }

        return $results;
    }

    protected function mapRecursiveParents(Taxonomy $taxonomy, &$results): void
    {
        if ($taxonomy->recursiveParents) {
            $results[] = self::make($taxonomy->recursiveParents);

            if ($taxonomy->recursiveParents->recursiveParents) {
                $this->mapRecursiveParents($taxonomy->recursiveParents->recursiveParents, $results);
            }
        }
    }
}
