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

class SiteResource extends JsonResource
{
    public function toArray($request): array
    {
        global $jw_user;

        $site = app()->make(SiteManagerContract::class)->find($this->resource);

        return [
            'id' => $this->resource->id,
            'subdomain' => $this->resource->subdomain,
            'domain' => $this->resource->domain,
            'status' => $this->resource->status,
            'login_url' => $site->getLoginUrl($jw_user),
        ];
    }
}
