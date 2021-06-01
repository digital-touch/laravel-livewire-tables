@if ($paginationEnabled && $rows->hasMorePages())

    <div class="overflow-hidden h-0.5 text-xs flex rounded bg-opacity-20 bg-indigo-200">
        <div style="width:{{($rows->currentPage() / $rows->lastPage()) * 100}}%"
             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-opacity-50 bg-indigo-500"></div>
    </div>

    <div class="p-4">
        {{ $rows->links() }}
    </div>

@endif
