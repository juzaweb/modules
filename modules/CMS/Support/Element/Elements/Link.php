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

use Juzaweb\CMS\Support\Element\Interfaces\Element;
use Juzaweb\CMS\Support\Element\Traits\HasChildren;
use Juzaweb\CMS\Support\Element\Traits\HasClass;
use Juzaweb\CMS\Support\Element\Traits\HasId;

class Link implements Element
{
    use HasClass, HasId, HasChildren;

    protected string $element = 'link';

    protected string $href;

    protected string $target;

    protected string $text;

    public function __construct(array $configs = [])
    {
        //
    }

    public function text(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function render(): string
    {
        return '';
    }
}
