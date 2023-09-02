<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support\Element\Elements;

use Juzaweb\CMS\Support\Element\Abstracts\ElementAbstract;
use Juzaweb\CMS\Support\Element\Traits\HasChildren;
use Juzaweb\CMS\Support\Element\Traits\HasClass;
use Juzaweb\CMS\Support\Element\Traits\HasId;

class Link extends ElementAbstract
{
    use HasClass, HasId, HasChildren;

    protected string $element = 'link';

    protected string $href;

    protected string $target;

    protected string $text;

    public function __construct(array $configs = [])
    {
        foreach ($configs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function text(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function render(): string
    {
        return '';
    }
}
