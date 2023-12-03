<?php

use Carbon\Carbon;
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

if (!function_exists('caculateTimes')) {
  function caculateTimes(
    $startDate,
    $endDate,
    $configs,
    $break = 0,
    $flagSubEveryConfig = true
  ) {
    $datesInRange = [];
    $startDate = Carbon::parse($startDate);
    $endDate = Carbon::parse($endDate);


    while ($startDate->lte($endDate)) {
      $datesInRange[] = $startDate->toDateString();
      $startDate->addDay();
    }
    $totalMinutes = 0;
    foreach ($datesInRange as $key => $date) {
      foreach ($configs as $key2 => $config) {
        $configTimeStart = Carbon::parse($config['start_time']);
        $configTimeEnd = Carbon::parse($config['end_time']);
        if ($key == 0) {
          if ($configTimeStart->format('H:i:s') >= $startDate->format('H:i:s')) {
            $totalMinutes += $configTimeEnd->diffInMinutes($configTimeStart);
          }
        } elseif ($key == count($datesInRange)) {
          if ($configTimeEnd->format('H:i:s') <= $endDate->format('H:i:s')) {
            $totalMinutes += $configTimeEnd->diffInMinutes($configTimeStart);
          }
        } else {
          $totalMinutes += $configTimeEnd->diffInMinutes($configTimeStart);
        }

        if ($flagSubEveryConfig) {
          $totalMinutes -= $break * 60;
        }
      }
    }
    $totalHours = ($totalMinutes / 60);
    if (!$flagSubEveryConfig) {
      $totalHours = $totalHours - $break;
    }
    return $totalHours;
  }
}
