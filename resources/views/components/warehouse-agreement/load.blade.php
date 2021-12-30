<div class="overflow-x-auto mt-6" id="invoice">
    <table class="table-auto border-collapse w-full">
        <thead>
            @include($table_header)
        </thead>
        <tbody class="text-sm font-normal text-gray-700 search_results">
            @include($table_body)
        </tbody>
    </table>
    <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
        {{$paginator}}
    </div>
</div>
