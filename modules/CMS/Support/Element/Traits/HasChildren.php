<?php

namespace Juzaweb\CMS\Support\Element\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Juzaweb\CMS\Support\Element\Elements;

trait HasChildren
{
    use HasInputElement;

    protected Collection $children;

    public function row(array $configs = []): Elements\Row
    {
        $row = new Elements\Row($configs);

        $this->pushChild($row);

        return $row;
    }

    public function col(array $configs = []): Elements\Col
    {
        $col = new Elements\Col($configs);

        $this->pushChild($col);

        return $col;
    }

    public function card(): Elements\Card
    {
        $card = new Elements\Card();

        $this->pushChild($card);

        return $card;
    }

    public function statsCard(): Elements\StatsCard
    {
        $card = new Elements\StatsCard();

        $this->pushChild($card);

        return $card;
    }

    public function form(array $configs = []): Elements\Form
    {
        $form = new Elements\Form($configs);
        $this->pushChild($form);
        return $form;
    }

    public function lineChart(array $configs = []): Elements\Charts\Chart
    {
        $chart = new Elements\Charts\Line($configs);
        $this->pushChild($chart);
        return $chart;
    }

    public function getChildren(): Collection
    {
        return $this->children ?? new Collection();
    }

    public function setChildren(Collection $children): static
    {
        $this->children = $children;

        return $this;
    }

    public function pushChild(array|Arrayable $child): static
    {
        if (!isset($this->children)) {
            $this->children = new Collection();
        }

        $this->children->push($child);

        return $this;
    }
}
