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

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\CMS\Abstracts\DataTable as DataTableAbstract;
use Juzaweb\CMS\Support\Element\Interfaces\Element;
use Juzaweb\CMS\Support\Element\Traits\HasClass;
use Juzaweb\CMS\Support\Element\Traits\HasId;

class DataTable implements Element
{
    use HasClass, HasId;

    protected string $element = 'data-table';

    protected string $class = 'table data-table';

    /**
     * Number of items per page.
     *
     * @var int
     */
    protected int $perPage = 10;

    /**
     * Name of the attribute used for sorting.
     *
     * @var string
     */
    protected string $sortName = 'id';

    /**
     * Sorting order (asc or desc).
     *
     * @var string
     */
    protected string $sortOder = 'desc';

    /**
     * Additional parameters.
     *
     * @var array
     */
    protected array $params = [];

    /**
     * URL for fetching data.
     *
     * @var string|null
     */
    protected ?string $dataUrl = null;

    /**
     * URL for performing actions.
     *
     * @var string|null
     */
    protected ?string $actionUrl = null;

    /**
     * Array of characters to escape.
     *
     * @var array
     */
    protected array $escapes = [];

    /**
     * Flag indicating whether searching is enabled.
     *
     * @var bool
     */
    protected bool $searchable = false;

    /**
     * Current URL.
     *
     * @var string|null
     */
    protected ?string $currentUrl = null;

    protected array $columns = [];

    protected array $actions = [];

    protected array $bulkActions = [];

    protected array $searchFields = [];

    protected array $rowActions = [];

    public function __construct(array|DataTableAbstract $configs)
    {
        if ($configs instanceof DataTableAbstract) {
            $configs = $configs->toArray();
        }

        foreach ($configs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function perPage(int $perPage): static
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function sortName(string $sortName): static
    {
        $this->sortName = $sortName;

        return $this;
    }

    public function sortOder(string $sortOder): static
    {
        $this->sortOder = $sortOder;

        return $this;
    }

    public function params(array $params): static
    {
        $this->params = $params;

        return $this;
    }

    public function dataUrl(string $dataUrl): static
    {
        $this->dataUrl = $dataUrl;

        return $this;
    }

    public function actionUrl(string $actionUrl): static
    {
        $this->actionUrl = $actionUrl;

        return $this;
    }

    public function escapes(array $escapes): static
    {
        $this->escapes = $escapes;

        return $this;
    }

    public function searchable(bool $searchable): static
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function currentUrl(string $currentUrl): static
    {
        $this->currentUrl = $currentUrl;

        return $this;
    }

    public function columns(array $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    public function actions(array $actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    public function bulkActions(array $bulkActions): static
    {
        $this->bulkActions = $bulkActions;

        return $this;
    }

    public function searchFields(array $searchFields): static
    {
        $this->searchFields = $searchFields;

        return $this;
    }

    public function rowActions(array $rowActions): static
    {
        $this->rowActions = $rowActions;

        return $this;
    }

    public function getId() : ?string
    {
        return $this->id ?? 'juzaweb_'. Str::random(10);
    }

    public function toArray(): array
    {
        $data = Arr::only(
            get_object_vars($this),
            [
                'class',
                'element',
                'id',
                'perPage',
                'sortName',
                'sortOder',
                'dataUrl',
                'actionUrl',
                'escapes',
                'searchable',
                'currentUrl',
                'columns',
                'actions',
                'bulkActions',
                'searchFields',
                'params',
            ]
        );

        return array_merge($data, [
            'searchFieldTypes' => $this->getSearchFieldTypes(),
            'table' => Crypt::encryptString(static::class),
            'uniqueId' => $this->getId(),
        ]);
    }

    public function render(): string
    {
        return '';
    }

    protected function getSearchFieldTypes(): array
    {
        return apply_filters(Action::DATATABLE_SEARCH_FIELD_TYPES_FILTER, []);
    }
}
