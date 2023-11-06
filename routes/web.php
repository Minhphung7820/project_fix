<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // $startTime = new Carbon("2023-11-01 17:00:00");
    // $endTime = new Carbon("2023-11-04 21:00:00");
    // $caOvertimeStart = new Carbon("17:00:00");
    // $caOvertimeEnd = new Carbon("21:00:00");
    $configs = [
        [
            "start_time" => "17:00:00",
            "end_time" => "21:00:00",
        ]
    ];
    dd(caculateTimes(
        '2023-10-25 17:00:00',
        '2023-10-26 21:00:00',
        $configs,
        1,
        false
    ));
});


function caculateTimes(
    $startDate,
    $endDate,
    $configs,
    $break = 0,
    $flagSubEveryConfig = true
) {
    try {
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
    } catch (\Exception $e) {
        throw new Exception($e->getMessage());
    }
}
