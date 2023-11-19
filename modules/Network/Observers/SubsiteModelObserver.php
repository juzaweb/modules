<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Network\Observers;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Network\Facades\Network;

class SubsiteModelObserver
{
    public function creating(Model $model): void
    {
        $model->setAttribute('site_id', Network::getCurrentSiteId());
    }
}
