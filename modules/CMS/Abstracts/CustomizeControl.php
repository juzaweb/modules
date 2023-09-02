<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Abstracts;

use Illuminate\Support\Collection;
use Juzaweb\CMS\Support\Theme\Customize;

abstract class CustomizeControl
{
    /**
     * @var Customize
     */
    protected Customize $customize;

    protected string $key;

    /**
     * @var Collection
     */
    protected Collection $args;

    public function __construct(Customize $customize, string $key, array $args = [])
    {
        $this->customize = $customize;
        $this->key = $key;
        $this->args = new Collection($args);
    }

    abstract public function contentTemplate();

    public function getKey(): string
    {
        return $this->key;
    }

    public function getArgs(): Collection
    {
        return $this->args;
    }
}
