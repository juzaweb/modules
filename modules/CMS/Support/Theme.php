<?php

namespace Juzaweb\CMS\Support;

use Composer\Autoload\ClassLoader;
use Exception;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Juzaweb\CMS\Contracts\BackendMessageContract;
use Juzaweb\CMS\Contracts\ConfigContract;
use Juzaweb\CMS\Contracts\LocalPluginRepositoryContract;
use Juzaweb\CMS\Interfaces\Theme\ThemeInterface;
use Noodlehaus\Config as ReadConfig;

class Theme implements ThemeInterface
{
    /**
     * The laravel|lumen application instance.
     *
     * @var ApplicationContract
     */
    protected ApplicationContract $app;

    /**
     * The plugin name.
     *
     * @var string $name
     */
    protected string $name;

    /**
     * The plugin path.
     *
     * @var string
     */
    protected string $path;

    /**
     * @var Filesystem
     */
    protected Filesystem $files;

    protected UrlGenerator $url;

    protected array $register;

    protected array $themeInfo;

    protected ConfigContract $dynamicConfig;

    protected CacheManager $cache;

    protected Kernel $artisan;

    protected BackendMessageContract $message;

    protected LocalPluginRepositoryContract $plugins;

    protected \Illuminate\Config\Repository $config;

    public function __construct($app, $path)
    {
        $this->app = $app;
        $this->path = $path;
        $this->files = $app['files'];
        $this->url = $app['url'];
        $this->name = $this->getName();
        $this->dynamicConfig = $app[ConfigContract::class];
        $this->cache = $app['cache'];
        $this->config = $app['config'];
        $this->artisan = $app[Kernel::class];
        $this->message = $app[BackendMessageContract::class];
        $this->plugins = $app[LocalPluginRepositoryContract::class];
    }

    /**
     * Get name.
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->get('name');
    }

    public function getLowerName(): ?string
    {
        return strtolower($this->getName());
    }

    /**
     * Get path.
     *
     * @param string $path
     * @return string
     */
    public function getPath(string $path = ''): string
    {
        if (empty($path)) {
            return realpath($this->path);
        }

        return realpath($this->path) . "/{$path}";
    }

    public function fileExists(string $path): bool
    {
        return file_exists($this->getPath($path));
    }

    public function getTemplate(): string
    {
        return $this->getInfo()->get('template', 'twig');
    }

    public function getTemplates(string $template = null): array|null
    {
        if ($template) {
            return Arr::get($this->getRegister(null, []), "templates.{$template}");
        }

        return $this->getRegister('templates', []);
    }

    /**
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function getContents(string $path): ?string
    {
        if (!$this->fileExists($path)) {
            throw new Exception('File does not exists.');
        }

        return File::get($this->getPath($path));
    }

    public function getViewPublicPath(string $path = null): string
    {
        return resource_path('views/themes/' . $this->name) .'/'. ltrim($path, '/');
    }

    public function getLangPublicPath(string $path = null): string
    {
        return resource_path('lang/themes/' . $this->name) .'/'. ltrim($path, '/');
    }

    /**
     * Get particular theme all information.
     *
     * @param bool $assoc
     * @return array|Collection
     */
    public function getInfo(bool $assoc = false): array|Collection
    {
        if (isset($this->themeInfo)) {
            return $assoc ? $this->themeInfo : new Collection($this->themeInfo);
        }

        $configPath = $this->path . '/theme.json';

        // $changelogPath = $this->path . '/changelog.yml';

        $config = [];

        if (file_exists($configPath)) {
            $config = ReadConfig::load($configPath)->all();
        }

        // $config['changelog'] = ReadConfig::load($changelogPath)->all();

        $config['screenshot'] = $this->getScreenshot();

        $config['path'] = $this->path;

        $config['networkable'] = $this->isNetworkSupport();

        $this->themeInfo = $config;

        return $assoc ? $this->themeInfo : new Collection($this->themeInfo);
    }

    public function getConfigFields(): array
    {
        return $this->getRegister('configs', []);
    }

    public function getRegister($key = null, $default = null): string|array|null
    {
        $path = $this->getPath('register.json');

        if (!isset($this->register) && File::exists($path)) {
            $this->register = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
        }

        if ($key) {
            return Arr::get($this->register, $key, $default);
        }

        return $this->register;
    }

    /**
     * Get version theme
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->get('version', '0');
    }

    public function asset(string $path, string $default = null): string
    {
        if (str_starts_with($path, 'jw-styles/')) {
            return $this->url->asset($path);
        }

        $path = str_replace('assets/', '', $path);

        $fullPath = $this->getPath("assets/public/{$path}");

        if (!file_exists($fullPath)) {
            if (is_url($default)) {
                return $default;
            }

            return $this->url->asset($default);
        }

        return $this->url->asset("jw-styles/themes/{$this->name}/assets/{$path}");
    }

    public function getScreenshot(): string
    {
        return $this->asset(
            'images/screenshot.png',
            'jw-styles/juzaweb/images/screenshot.svg'
        );
    }

    /**
     * Get a specific data from json file by given the key.
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed
     * @throws Exception
     */
    public function get(string $key, $default = null): mixed
    {
        return $this->json()->get($key, $default);
    }

    /**
     * Get json contents from the cache, setting as needed.
     *
     * @param string|null $file
     *
     * @return Json
     * @throws Exception
     */
    public function json(string $file = null): Json
    {
        if ($file === null) {
            $file = 'theme.json';
        }

        return new Json(
            $this->getPath($file),
            $this->files
        );
    }

    public function isActive(): bool
    {
        return jw_current_theme() == $this->name;
    }

    public function activate(): void
    {
        $this->cache->pull(cache_prefix('jw_theme_configs'));

        $status = [
            'name' => $this->name,
        ];

        $this->dynamicConfig->setConfig('theme_statuses', $status);

        if (!$this->config->get('network.enable')) {
            $this->artisan->call(
                'theme:publish',
                [
                    'theme' => $this->name,
                    'type' => 'assets',
                ]
            );
        }

        $this->addRequireThemeActive();
    }

    public function delete(): bool
    {
        if ($this->isActive()) {
            throw new Exception('Can\'t delete activated theme');
        }

        return $this->json()->getFilesystem()->deleteDirectory($this->getPath());
    }

    public function isNetworkSupport(): bool
    {
        return $this->get('networkable', true);
    }

    public function getPluginRequires(): array
    {
        return $this->json()->get('require', []);
    }

    public function register(): void
    {
        $this->autoloadPSR4();

        $this->registerProviders();
    }

    public function registerProviders(): void
    {
        $providers = Arr::get($this->json()->get('extra', []), 'juzaweb.providers', []);

        if (empty($providers)) {
            return;
        }

        (new ProviderRepository(
            $this->app,
            new Filesystem(),
            $this->getCachedServicesPath()
        ))
            ->load($providers);
    }

    /**
     * Get the path to the cached *_module.php file.
     */
    public function getCachedServicesPath(): string
    {
        return Str::replaceLast(
            'services.php',
            $this->getName().'_theme.php',
            $this->app->getCachedServicesPath()
        );
    }

    public function addRequireThemeActive(): void
    {
        $this->message->deleteGroup('require_plugins');

        if (!$require = $this->getPluginRequires()) {
            return;
        }

        $plugins = $this->plugins->all();
        $inactive = [];
        foreach ($require as $plugin => $ver) {
            if ($this->plugins->has($plugin) && $this->plugins->isEnabled($plugin)) {
                continue;
            }

            if (!array_key_exists($plugin, $plugins)) {
                $plugins[$plugin] = $plugin;
            }

            $inactive[] = "<strong>{$plugin}</strong>";
        }

        if ($inactive) {
            $this->message->add(
                'require_plugins',
                trans(
                    'cms::app.theme_require_plugins',
                    [
                        'plugins' => implode(', ', $inactive),
                        'link' => route('admin.themes.require-plugins')
                    ]
                ),
                'warning'
            );
        }
    }

    protected function autoloadPSR4(): void
    {
        $loadmaps = Arr::get($this->json()->get('autoload', []), 'psr-4', []);

        if (empty($loadmaps)) {
            return;
        }

        $loader = new ClassLoader();
        foreach ($loadmaps as $namespace => $loadmap) {
            $loader->setPsr4($namespace, [$this->getPath($loadmap)]);
        }

        $loader->register(true);
    }

    public function toArray(): array
    {
        return $this->getInfo()->toArray();
    }
}
