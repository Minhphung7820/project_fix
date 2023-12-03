<?php

use Illuminate\Pagination\LengthAwarePaginator;

if (!function_exists('includeAdapter')) {
  function includeAdapter(&$data, $includeField, $model, $includeId, $relationships = [])
  {
    $recordWithIdOne =   $data->firstWhere($includeField, $includeId);
    if (!$recordWithIdOne) {
      $inlcudeData = $model::with($relationships)->where($includeField, $includeId)->first();
      $data->push($inlcudeData);
    }
    return $data;
  }
}

if (!function_exists('paginateCustom')) {
  function paginateCustom($data)
  {
    $page = request()->input('page', 1);
    $perPage = request()->input('limit', 10);
    $dataPaginated = new LengthAwarePaginator(
      $data->forPage($page, $perPage)->values(),
      $data->count(),
      $perPage,
      $page
    );


    $dataPaginated->setPath(request()->url());

    return $dataPaginated;
  }
}
