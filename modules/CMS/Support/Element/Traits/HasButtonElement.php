<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support\Element\Traits;

use Juzaweb\CMS\Support\Element\Elements\Buttons\ButtonGroup;

trait HasButtonElement
{
    public function buttonGroup(): ButtonGroup
    {
        $btn = new ButtonGroup();

        $this->pushChild($btn);

        return $btn;
    }
}
