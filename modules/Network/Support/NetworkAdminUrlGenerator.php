<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Network\Support;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Arr;

class NetworkAdminUrlGenerator extends UrlGenerator
{
    public function toRoute($route, $parameters, $absolute): string
    {
        if ($siteId = $this->request->query->get('site_id')) {
            $parameters = Arr::wrap($parameters);

            Arr::set($parameters, 'site_id', $siteId);
        }

        return parent::toRoute($route, $parameters, $absolute);
    }
}
