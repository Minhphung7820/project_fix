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
