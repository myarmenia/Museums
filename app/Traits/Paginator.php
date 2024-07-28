<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
trait Paginator
{
  public function arrayPaginator($array, $request, $perPage)
  {

    $page = request()->input('page', 1);
    $total = $array->count();

    $offset = ($page * $perPage) - $perPage;

    // Get the items for the current page
    $items = $array->slice($offset, $perPage);

    // Create the paginator instance
    $paginator = new LengthAwarePaginator(
      $items,
      $total,
      $perPage,
      $page,
      ['path' => $request->url(), 'query' => $request->query()]
    );

    return $paginator;

  }
}
