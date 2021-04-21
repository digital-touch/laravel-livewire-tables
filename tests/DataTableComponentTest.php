<?php

namespace Rappasoft\LaravelLivewireTables\Tests;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Tests\Http\Livewire\PetsTable;

class DataTableComponentTest extends TestCase
{
    protected DataTableComponent $table;

    public function setUp(): void
    {
        parent::setUp();

        $this->table = new PetsTable();
    }

    /** @test */
    public function bootstrap_test_datatable(): void
    {
        $this->assertInstanceOf(DataTableComponent::class, $this->table);
    }

    /** @test */
    public function test_columns(): void
    {
        $columns = $this->table->columns();

        $this->assertCount(5, $columns);
    }

    /** @test */
    public function test_rows(): void
    {
        $rows = $this->table->rows;

        $this->assertInstanceOf(LengthAwarePaginator::class, $rows);
        $this->assertEquals(5, $this->table->getRowsProperty()->total());
    }

    /** @test */
    public function test_search_filter(): void
    {
        $this->table->filters['search'] = 'Cartman';
        $this->assertEquals(1, $this->table->getRowsProperty()->total());
    }

    /** @test */
    public function test_search_filter_reset(): void
    {
        $this->table->filters['search'] = 'Cartman';
        $this->table->resetFilters();
        $this->assertEquals(1, $this->table->rows->total());
    }

    /** @test */
    public function test_search_filter_remove(): void
    {
        $this->table->filters['search'] = 'Cartman';
        $this->table->removeFilter('search');
        $this->assertEquals(5, $this->table->rows->total());
    }
}