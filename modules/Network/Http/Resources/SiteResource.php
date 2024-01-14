<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Network\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Juzaweb\Network\Contracts\SiteManagerContract;
use Juzaweb\Network\Models\Site;

/**
 * @property-read Site $resource
 */
class SiteResource extends JsonResource
{
    public function toArray($request): array
    {
        global $jw_user;

        $site = app()->make(SiteManagerContract::class)->find($this->resource);

        return [
            'uuid' => $this->resource->uuid,
            'subdomain' => $this->resource->subdomain,
            'domain' => $this->resource->domain,
            'full_domain' => $this->resource->getFullDomain(),
            'status' => $this->resource->status,
            'login_url' => $site?->getLoginUrl($jw_user),
        ];
    }
}
