<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Support\HookActions\Entities;

use Illuminate\Support\Str;
use Juzaweb\CMS\Abstracts\HookActions\Entity;

class Menu extends Entity
{
    protected string $key;

    protected string $title;

    protected string $slug;

    protected string $icon = 'fa fa-list-ul';

    protected string $url;

    protected ?string $parent = null;

    protected int $position = 20;

    protected array $permissions = ['admin'];

    public static function make(string $title): static
    {
        return app()->make(static::class)->title($title);
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function key(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function parent(string $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function position(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key ?? ($this->key = $this->generateKeyByTitle());
    }

    public function getSlug(): string
    {
        return $this->slug ?? ($this->slug = $this->generateSlug());
    }

    public function getUrl(): string
    {
        return $this->url ?? ($this->url = Str::replace('.', '/', $this->getSlug()));
    }

    public function generate(): void
    {
        $this->hookAction->addAdminMenu(
            $this->title,
            $this->getKey(),
            $this->toArray()
        );
    }

    protected function generateKeyByTitle(): string
    {
        return Str::slug($this->title);
    }

    protected function generateSlug(): string
    {
        return Str::replace('.', '-', $this->getKey());
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'key' => $this->key,
            'permissions' => $this->permissions,
            'slug' => $this->getSlug(),
            'icon' => $this->icon,
            'url' => $this->getUrl(),
            'parent' => $this->parent,
            'position' => $this->position,
        ];
    }
}
