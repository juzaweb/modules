<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Juzaweb\CMS\Facades\HookAction;
use Juzaweb\CMS\Http\Controllers\BackendController;

class AjaxController extends BackendController
{
    public function handle(Request $request, string $slug)
    {
        $key = str_replace('/', '.', $slug);

        $ajax = HookAction::getAdminAjaxs($key);

        if (!$ajax instanceof Collection) {
            return response('Ajax function not found.', 404);
        }

        if ($request->method() != strtoupper($ajax->get('method'))) {
            return response('Method is not supported.', 403);
        }

        $callback = $ajax->get('callback');

        if (is_string($callback[0])) {
            return App::call([app($callback[0]), $callback[1]]);
        }

        return App::call($callback, [$request]);
    }
}
