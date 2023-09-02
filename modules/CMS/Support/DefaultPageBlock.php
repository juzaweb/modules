<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support;

use Illuminate\View\View;
use Juzaweb\CMS\Abstracts\PageBlock;

class DefaultPageBlock extends PageBlock
{
    /**
     * Creating widget front-end
     *
     * @param  array  $data
     * @return View|string
     */
    public function show(array $data): View|string
    {
        return $this->view(
            $this->data['view'],
            compact(
                'data'
            )
        );
    }
}
