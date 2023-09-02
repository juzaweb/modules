<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Interfaces\ElementBuilders;

use Illuminate\Contracts\Support\Arrayable;
use Juzaweb\CMS\Support\Element\Contracts\ElementBuilder as ElementBuilderContract;

interface ElementBuilder extends Arrayable
{
    public function builder(): ElementBuilderContract;

    public function toArray(): array;
}
