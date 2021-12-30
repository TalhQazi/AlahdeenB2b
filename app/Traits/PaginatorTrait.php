<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait PaginatorTrait {

    public static function getPaginator($request, $items) {
        $total = $items->meta->total; // total count of the set, this is necessary so the paginator will know the total pages to display
        $page = $request->page ?? 1; // get current page from the request, first page is null
        $perPage = $items->meta->per_page; // how many items you want to display per page?
        return new LengthAwarePaginator($items->data, $total, $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query()
        ], true);
    }

}
