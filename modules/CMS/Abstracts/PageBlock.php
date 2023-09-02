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
use Illuminate\View\View;
use Juzaweb\CMS\Facades\ThemeLoader;
use TwigBridge\Facade\Twig;

abstract class PageBlock
{
    public function __construct(protected Collection|array $data, protected string $theme)
    {
        //
    }

    /**
     * Creating widget front-end
     *
     * @param  array  $data
     * @return View
     */
    abstract public function show(array $data): View|string;

    /**
     * Retrieves the data from a JSON file.
     *
     * @return array|Collection The data retrieved from the JSON file.
     * @throws \JsonException
     */
    public function getData(): array|Collection
    {
        $dataFile = ThemeLoader::getThemePath(
            $this->theme,
            "data/blocks/{$this->data['key']}.json"
        );

        if (!file_exists($dataFile)) {
            return [];
        }

        return collect(json_decode(file_get_contents($dataFile), true, 512, JSON_THROW_ON_ERROR));
    }

    /**
     * Renders a Twig view with the given parameters.
     *
     * @param  string  $view  The name of the Twig view to render.
     * @param  array  $params  An associative array of parameters to pass to the view
     * @return string The rendered Twig view.
     */
    protected function view(string $view, array $params = [])
    {
        return Twig::render($view, $params);
    }
}
