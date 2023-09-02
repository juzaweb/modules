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

class StatsCard extends Card
{
    protected string $data;

    public function data(string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['element'] = 'stats-card';
        $data['title'] = $this->getTitle();
        $data['data'] = $this->getData();
        return $data;
    }
}
