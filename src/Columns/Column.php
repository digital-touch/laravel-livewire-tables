<?php

namespace Rappasoft\LaravelLivewireTables\Columns;

use Illuminate\Support\Str;

/**
 * Class Column.
 */
class Column
{
    /**
     * @var string|null
     */
    public ?string $column = null;

    /**
     * @var string|null
     */
    public ?string $text = null;

    /**
     * @var bool
     */
    public bool $sortable = false;

    /**
     * @var
     */
    public $sortCallback;

    /**
     * @var bool
     */
    public bool $searchable = false;

    /**
     * @var callable
     */
    public $searchCallback;

    /**
     * @var string|null
     */
    public ?string $classes = null;

    /**
     * @var string|null
     */
    public ?string $width = null;

    /**
     * @var bool
     */
    public bool $blank = false;

    /**
     * @var
     */
    public $formatCallback;

    /**
     * @var bool
     */
    public bool $asHtml = false;

    /**
     * @var bool
     */
    public bool $hidden = false;

    /**
     * Column constructor.
     *
     * @param string|null $column
     * @param string|null $text
     */
    public function __construct(string $text = null, string $column = null)
    {
        $this->text = $text;

        if (!$column && $text) {
            $this->column = Str::snake($text);
        } else {
            $this->column = $column;
        }

        if (!$this->column && !$this->text) {
            $this->blank = true;
        }
    }

    /**
     * @param string|null $column
     * @param string|null $text
     *
     * @return Column
     */
    public static function make(string $text = null, string $column = null): Column
    {
        return new static($text, $column);
    }

    /**
     * @return Column
     */
    public static function blank(): Column
    {
        return new static(null, null);
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable === true;
    }

    public function setSearchable(bool $value = true): self
    {
        $this->searchable = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSearchable(): bool
    {
        return $this->searchable === true;
    }

    /**
     * @return bool
     */
    public function isBlank(): bool
    {
        return $this->blank === true;
    }

    /**
     * @return $this
     */
    public function sortable($callback = null): self
    {
        $this->sortable = true;

        $this->sortCallback = $callback;

        return $this;
    }

    /**
     * @param callable|null $callback
     * @return $this
     */
    public function searchable(callable $callback = null): self
    {
        $this->searchable = true;

        $this->searchCallback = $callback;

        return $this;
    }

    /**
     * @param string $classes
     *
     * @return $this
     */
    public function setClasses(string $classes): self
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * @return string|null
     */
    public function classes(): ?string
    {
        return $this->classes;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setWidth(string $class): self
    {
        $this->width = $class;

        return $this;
    }

    /**
     * @return string|null
     */
    public function width(): ?string
    {
        return $this->width;
    }

    /**
     * @return Column
     */
    public function asHtml(): Column
    {
        $this->asHtml = true;

        return $this;
    }

    /**
     * @return string|null
     */
    public function column(): ?string
    {
        return $this->column;
    }

    /**
     * @return string|null
     */
    public function text(): ?string
    {
        return $this->text;
    }

    /**
     * @param callable $callable
     *
     * @return $this
     */
    public function format(callable $callable): Column
    {
        $this->formatCallback = $callable;

        return $this;
    }

    /**
     * @param $row
     * @param null $column
     *
     * @return array|mixed|null
     */
    public function formatted($row, $column = null)
    {
        if ($column instanceof self) {
            $columnName = $column->column();
        } elseif (is_string($column)) {
            $columnName = $column;
        } else {
            $columnName = $this->column();
        }

        $value = data_get($row, $columnName);

        if ($this->formatCallback) {
            return app()->call($this->formatCallback, ['value' => $value, 'column' => $column, 'row' => $row]);
        }

        return $value;
    }

    /**
     * @return bool
     */
    public function hasSortCallback(): bool
    {
        return $this->sortCallback !== null;
    }

    /**
     * @return callable|null
     */
    public function getSortCallback(): ?callable
    {
        return $this->sortCallback;
    }

    /**
     * @return bool
     */
    public function hasSearchCallback(): bool
    {
        return $this->searchCallback !== null;
    }

    /**
     * @return callable|null
     */
    public function getSearchCallback(): ?callable
    {
        return $this->searchCallback;
    }

    /**
     * @param $condition
     *
     * @return $this
     */
    public function hideIf($condition): self
    {
        $this->hidden = $condition;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->hidden !== true;
    }
}
