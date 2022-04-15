<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * ===============================================================================================
 * ===============================================================================================
 *                         API URL
 * ===============================================================================================
 * ===============================================================================================
 */

Route::namespace('Api')->group(function () {

    /*--------------> Routes */

    Route::get('{gtfs_id}/routes', '\App\Http\Controllers\Api\ApiController@all_routes')->name('all-routes');
    Route::get('{gtfs_id}/{route_id}/route', '\App\Http\Controllers\Api\ApiController@get_route')->name('get-route');

    /*--------------> Trips */

    Route::get('{gtfs_id}/trips', '\App\Http\Controllers\Api\ApiController@all_trips')->name('all-trips');
    Route::get('{gtfs_id}/{trip_id}/trip', '\App\Http\Controllers\Api\ApiController@get_trip')->name('get-trip');

    /*--------------> Directions */

    Route::get('{gtfs_id}/destinations', '\App\Http\Controllers\Api\ApiController@all_destinations')->name('all-destinations');
//Route::get('api/{gtfs_id}/{trip_id}/trip','ApiController@get_trip')->name('get-destination');

    /*--------------> Shape */

    Route::get('{gtfs_id}/shapes', '\App\Http\Controllers\Api\ApiController@get_shapes')->name('get-shapes');
    Route::get('{gtfs_id}/{shape_id}/shape', '\App\Http\Controllers\Api\ApiController@get_shape')->name('get-shape');

    Route::get('{gtfs_id}/{shape_id}/shapes', '\App\Http\Controllers\Api\ApiController@shapesByTrip')->name('shapesByTrip');

    Route::get('{gtfs_id}/{shape_id}/shape-lat-lon','\App\Http\Controllers\Api\ApiController@get_shape_lat_lon')->name('get-shape');


    Route::get('{gtfs_id}/routes-line-stops','\App\Http\Controllers\Api\ApiController@all_routes_line_stops')->name('all-routes-line-stops');


    /*--------------> Get Trip from user position */

    Route::get('{gtfs_id}/user-trip', '\App\Http\Controllers\Api\ApiController@get_user_trip')->name('get-user-trip');


    /*--------------> Stops */

Route::get('{gtfs_id}/stops','\App\Http\Controllers\Api\ApiController@get_stops')->name('get-stops');
Route::get('{gtfs_id}/{stop_id}/stop','\App\Http\Controllers\Api\ApiController@get_stop')->name('get-stop');



});



/**
 * ===============================================================================================
 * ===============================================================================================
 *                        END API URL
 * ===============================================================================================
 * ===============================================================================================
 */
