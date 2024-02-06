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

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Juzaweb\CMS\Models\User;

/**
 * @property-read User $resource
 */
class UserResource extends JsonResource
{
    protected bool $withAdminField = true;

    public function withAdminField(bool $withAdminField): static
    {
        $this->withAdminField = $withAdminField;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'created_at' => jw_date_format($this->resource->created_at),
            'avatar' => $this->resource->getAvatar(),
            'metas' => (array) $this->resource->getMetas(),
            'role' => $this->resource->is_admin ? 'admin' : 'user',
        ];

        if ($this->withAdminField) {
            $data['is_admin'] = (bool) $this->resource->is_admin;
        }

        return apply_filters('user.resouce_data', $data, $this->resource);
    }
}
