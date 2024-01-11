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

class AdminPage extends Entity
{
    protected string $key;

    protected string $title;

    protected AdminMenu|array $menu = [
        'icon' => 'fa fa-list',
        'position' => 30,
    ];

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

    public function menu(AdminMenu|array $menu): static
    {
        $this->menu = $menu;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key ?? ($this->key = $this->generateKeyByTitle());
    }

    public function generate(): void
    {
        $this->hookAction->registerAdminPage(
            $this->getKey(),
            $this->toArray()
        );
    }

    protected function generateKeyByTitle(): string
    {
        return Str::slug($this->title);
    }

    public function toArray(): array
    {
        return [
            'key' => $this->getKey(),
            'title' => $this->title,
            'menu' => (array) $this->menu,
        ];
    }
}
