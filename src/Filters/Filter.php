<?php

namespace Rappasoft\LaravelLivewireTables\Filters;

/**
 * Class Filter.
 */
abstract class Filter
{
    /**
     * @var string
     */
    public const STRING_VALUE_TYPE = 'string';

    /**
     * @var string
     */
    public const ARRAY_VALUE_TYPE = 'array';

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $component;

    /**
     * @var string
     */
    public string $type;

    /**
     * Filter constructor.
     *
     * @param string $name
     */
    public function __construct(string $name, string $component, string $type)
    {
        $this->name = $name;
        $this->component = $component;
        $this->type = $type;
    }

    public function setName(string $name): Filter
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    public function setType(string $type): Filter
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function isArrayType(): bool
    {
        return $this->type === self::ARRAY_VALUE_TYPE;
    }

    /**
     * @return string
     */
    public function isStringType(): bool
    {
        return $this->type === self::STRING_VALUE_TYPE;
    }

    /**
     * @return string
     */
    public function component(): string
    {
        return $this->component;
    }

    public function setComponent(string $component): Filter
    {
        $this->component = $component;
        return $this;
    }

    /**
     * @param $raw
     * @return string|array|null
     */
    public abstract function processValue($raw);

    /**
     * @param $raw
     * @return string|array|null
     */
    public abstract function allowedValue($raw):bool;
}
