<?php namespace Juzaweb\CMS\Support\ShortCode\Compilers;

use Illuminate\Contracts\Support\Arrayable;

class ShortCode implements Arrayable
{
    /**
     * Shortcode name
     *
     * @var string
     */
    protected $name;

    /**
     * Shortcode Attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Shortcode content
     *
     * @var string
     */
    public $content;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $content
     * @param array  $attributes
     */
    public function __construct($name, $content, $attributes = [])
    {
        $this->name = $name;
        $this->content = $content;
        $this->attributes = $attributes;
    }

    /**
     * Get html attribute
     *
     * @param  string $attribute
     *
     * @return string|null
     */
    public function get($attribute, $fallback = null)
    {
        $value = $this->{$attribute};
        if (!is_null($value)) {
            return $attribute . '="' . $value . '"';
        } elseif (!is_null($fallback)) {
            return $attribute . '="' . $fallback . '"';
        }
    }

    /**
     * Get shortcode name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get shortcode attributes
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    public function getAttribute(string $key): ?string
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Return array of attributes;
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Dynamically get attributes
     *
     * @param  string $param
     *
     * @return string|null
     */
    public function __get($param)
    {
        return isset($this->attributes[$param]) ? $this->attributes[$param] : null;
    }
}
