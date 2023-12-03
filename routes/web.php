<?php

use App\Models\Categrory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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

Route::get('/', function (Request $request) {
    $users = User::query();

    if (isset($request->keyword) && $request->keyword) {
        $users->where(function ($q) use ($request) {
            $q->where('name', 'LIKE', "%$request->keyword%");
            $q->orWhere('email', 'LIKE', "%$request->keyword%");
        });
    }

    // dd($users->toSql());
    $data = $users->get();
    if (isset($request->include_id) && $request->include_id) {
        includeAdapter($data, 'id', User::class, $request->include_id);
    }
    $response = paginateCustom($data);
    return $response;
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
