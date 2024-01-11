<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Abstracts\HookActions;

use Illuminate\Contracts\Support\Arrayable;
use Juzaweb\CMS\Contracts\HookActionContract;

abstract class Entity implements Arrayable
{
    public function __construct(protected HookActionContract $hookAction)
    {
    }
}
