<?php

namespace Juzaweb\Backend\Http\Controllers\Backend;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Juzaweb\CMS\Facades\HookAction;
use Juzaweb\CMS\Http\Controllers\BackendController;
use Symfony\Component\HttpFoundation\Response;

class PageController extends BackendController
{
    protected Collection $page;

    public function callAction($method, $parameters): Response|View
    {
        $this->page = $this->findPageOrFail();

        return parent::callAction($method, $parameters);
    }

    protected function findPageOrFail(): Collection
    {
        $page = HookAction::getAdminPages($this->getPageSlug());

        abort_if($page === null, 404);

        if (is_array($page)) {
            $page = $this->recursiveGetPage($page);
        }

        return $page;
    }

    protected function recursiveGetPage($page)
    {
        $page = $page[$this->getPageSlug(2)];

        abort_unless($page, 404);

        if (is_array($page)) {
            $page = $this->recursiveGetPage($page);
        }

        return $page;
    }

    protected function getPageSlug(int $index = 1): string
    {
        $slugs = explode('/', Route::getCurrentRoute()->uri);
        $adminSlugs = explode('/', config('juzaweb.admin_prefix'));

        $slugs = array_values(array_diff($slugs, $adminSlugs));

        return $slugs[$index - 1];
    }
}
