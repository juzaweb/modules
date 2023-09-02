<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Abstracts\ElementBuilders;

use Juzaweb\CMS\Support\Element\Contracts\ElementBuilder as ElementBuilderContract;

abstract class ElementBuilder
{
    public function builder(): ElementBuilderContract
    {
        return app()->make(ElementBuilderContract::class);
    }

    abstract public function toArray(): array;
}
