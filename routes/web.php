<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
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

use App\Shape;
use App\Stop;
use App\Trip;
use App\Route as RouteTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return view('frontend');
});

Route::get('watrifeed-api', static function (){
    return view('api');
});

Route::get('/test', function () {


    return view('test');
});

Route::get('/dashboard','DashboardController@index');

Route::post('/agency/store','AgencyController@store');
Route::post('agency/{id}/edit', 'AgencyController@edit')->where(['id'=>'[0-9]+']);
Route::post('agency/{id}/delete', 'AgencyController@destroy')->where(['id'=>'[0-9]+']);
Route::get('agencies/', 'AgencyController@get_agencies');

Route::post('/stop/store','StopController@store');
Route::post('stop/{id}/edit', 'StopController@edit')->where(['id'=>'[0-9]+']);
Route::post('stop/{id}/delete', 'StopController@destroy')->where(['id'=>'[0-9]+']);

Route::post('/route/store','RouteController@store');
Route::post('route/{id}/edit', 'RouteController@edit')->where(['id'=>'[0-9]+']);
Route::post('route/{id}/delete', 'RouteController@destroy')->where(['id'=>'[0-9]+']);
Route::get('routes/', 'RouteController@get_routes');

Route::post('/trip/store','TripController@store');
Route::post('trip/{id}/edit', 'TripController@edit')->where(['id'=>'[0-9]+']);
Route::post('trip/{id}/delete', 'TripController@destroy')->where(['id'=>'[0-9]+']);

Route::post('/stoptime/store','StoptimeController@store');
Route::post('stoptime/{id}/edit', 'StoptimeController@edit')->where(['id'=>'[0-9]+']);
Route::post('stoptime/{id}/delete', 'StoptimeController@destroy')->where(['id'=>'[0-9]+']);

Route::post('/calendar/store','CalendarController@store');
Route::post('calendar/{id}/edit', 'CalendarController@edit')->where(['id'=>'[0-9]+']);
Route::post('calendar/{id}/delete', 'CalendarController@destroy')->where(['id'=>'[0-9]+']);

Route::post('/calendardate/store','CalendarDateController@store');
Route::post('calendardate/{id}/edit', 'CalendarDateController@edit')->where(['id'=>'[0-9]+']);
Route::post('calendardate/{id}/delete', 'CalendarDateController@destroy')->where(['id'=>'[0-9]+']);

Route::post('/fareattribute/store','FareAttributeController@store');
Route::post('fareattribute/{id}/edit', 'FareAttributeController@edit')->where(['id'=>'[0-9]+']);
Route::post('fareattribute/{id}/delete', 'FareAttributeController@destroy')->where(['id'=>'[0-9]+']);

Route::post('/farerule/store','FareRuleController@store');
Route::post('farerule/{id}/edit', 'FareRuleController@edit')->where(['id'=>'[0-9]+']);
Route::post('farerule/{id}/delete', 'FareRuleController@destroy')->where(['id'=>'[0-9]+']);

Route::post('/shape/store','ShapeController@store');
Route::post('shape/{id}/edit', 'ShapeController@edit')->where(['id'=>'[0-9]+']);
Route::post('level/{id}/edit', 'LevelController@edit')->where(['id'=>'[0-9]+']);
Route::post('shape/{id}/delete', 'ShapeController@destroy')->where(['id'=>'[0-9]+']);
Route::get('shapes/', 'ShapeController@get_shapes');

Route::post('/frequency/store','FrequencyController@store');
Route::post('frequency/{id}/edit', 'FrequencyController@edit')->where(['id'=>'[0-9]+']);
Route::post('frequency/{id}/delete', 'FrequencyController@destroy')->where(['id'=>'[0-9]+']);

Route::post('/transfer/store','TransferController@store');
Route::post('transfer/{id}/edit', 'TransferController@edit')->where(['id'=>'[0-9]+']);
Route::post('transfer/{id}/delete', 'TransferController@destroy')->where(['id'=>'[0-9]+']);

//Route::post('agency/save','AgencyController@store');


Route::resource('stop', 'StopController');
Route::resource('stoptime', 'StoptimeController');
Route::resource('trip', 'TripController');
Route::resource('fareattribute', 'FareAttributeController');
Route::resource('farerule', 'FareRuleController');
Route::resource('route', 'RouteController');
Route::resource('agency', 'AgencyController');
Route::resource('shape', 'ShapeController');
Route::resource('frequency', 'FrequencyController');
Route::resource('calendar', 'CalendarController');
Route::resource('calendardate', 'CalendarDateController');
Route::resource('transfer', 'TransferController');
Route::resource('pathway', 'PathwayController');
Route::resource('level', 'LevelController');
Route::resource('user', 'UserController');
Route::get('active_user/{id}','UserController@active_user')
    ->where(['id'=>'[0-9]+'])
    ->middleware('auth')
    ->name('active_user');
Route::get('reset_user/{id}','UserController@reset_user')
    ->where(['id'=>'[0-9]+'])
    ->middleware('auth')
    ->name('reset_user');

Route::resource('gtfs', 'GtfsController');
Route::get('import','GtfsController@importGet');

Route::post('import','GtfsController@importPost2')->name('gtfs.import');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Route::get('users/{user}',  ['as' => 'users.edit', 'uses' => 'UserController@edit']);
Route::get('users/',  ['as' => 'users.edit', 'uses' => 'UserController@edit']);
Route::post('users/{user}/update',  ['as' => 'users.update', 'uses' => 'UserController@update']);

Route::get('export/{gtfs}',  ['as' => 'gtfs.export', 'uses' => 'GtfsController@export']);
Route::get('backup/{gtfs}',  ['as' => 'gtfs.backup', 'uses' => 'GtfsController@backup']);

Route::get('/fareruleadd','FareRuleController@getAddField');
Route::post('check_pass','GtfsController@checkPass')->name('gtfs.check_pass.post');
Route::post('gtfs/edit_','GtfsController@edit_')->name('gtfs.edit_');
Route::post('gtfs/setting','GtfsController@setting')->name('gtfs.setting');

Route::get('select2-autocomplete', 'TestController@layout');
Route::get('select2-autocomplete-ajax', 'TestController@dataAjax');

//Select Ajax routes

Route::get('select2-stops-ajax', 'StopController@dataAjax')->middleware('auth');
Route::get('select2-zones-ajax', 'StopController@dataAjaxZoneId')->middleware('auth');
Route::get('select2-levels-ajax', 'LevelController@dataAjax')->middleware('auth');
Route::get('select2-routes-ajax', 'RouteController@dataAjax')->middleware('auth');
Route::get('select2-shapes-ajax', 'ShapeController@dataAjax')->middleware('auth');
Route::get('select2-agencies-ajax', 'AgencyController@dataAjax')->middleware('auth');
Route::get('select2-trips-ajax', 'TripController@dataAjax')->middleware('auth');
Route::get('select2-fares-ajax', 'FareAttributeController@dataAjax')->middleware('auth');

//Tests routes

Route::get('test-leaflet', 'TestController@leaflet')->middleware('auth');

Route::get('test-helper', function () {

    \App\helpers\WatriGtfs::fileToArray('unzip/gtfs/15/toto');
});

Route::get('stop-map/', 'StopController@getDataMap')->middleware('auth');
Route::get('trip-map/', 'TripController@getMapData')->middleware('auth');
Route::get('shapes-map/', 'ShapeController@getMapData')->middleware('auth');
Route::get('/stop-visualisation',function (){
    return view('gtfs.visualisations.stops');
})->middleware('auth');

Route::get('/percentage-st',function (Request $request){

    $percentage = \App\Percentage::where('file_name','stoptimes')->where('gtfs_id',session('gtfs_id'));
    if ($percentage){
        echo json_encode ($percentage);
    }else{
        echo json_encode(0);
    }


})->middleware('auth');



Route::get('/route-visualisation','RouteController@visualisation')->middleware('auth');
Route::get('/trip-visualisation','TripController@visualisation')->middleware('auth');

// Import

Route::post('agencies-import/', 'AgencyController@importAgenciesCSV')->name('agencies-import')->middleware('auth');
Route::post('agencies-import-geojson/', 'AgencyController@importAgenciesGeoJson')->name('agencies-import')->middleware('auth');
Route::post('extra-import-geojson/', 'ExtraController@importByGeoJson')->name('extra-import-geojson')->middleware('auth');
Route::post('stops-import/', 'StopController@importStopsCSV')->name('stops-import')->middleware('auth');
Route::post('routes-import/', 'RouteController@importRoutesCSV')->name('routes-import')->middleware('auth');
Route::post('trips-import/', 'TripController@importTripsCSV')->name('routes-import')->middleware('auth');
Route::post('stoptimes-import/', 'StoptimeController@importStoptimesCSV')->name('stoptimes-import')->middleware('auth');
Route::post('stoptimes-generate/', 'StoptimeController@generate')->name('stoptimes-generate')->middleware('auth');
Route::post('calendars-import/', 'CalendarController@importCalendarsCSV')->name('calendars-import')->middleware('auth');
Route::post('shapes-import/', 'ShapeController@importShapesCSV')->name('shapes-import')->middleware('auth');

// Export

Route::get('agencies-export/', 'AgencyController@exportAgenciesCSV')->name('agencies-export')->middleware('auth');
Route::get('stops-export/', 'StopController@exportStopsCSV')->name('stops-export')->middleware('auth');
Route::get('routes-export/', 'RouteController@exportRoutesCSV')->name('routes-export')->middleware('auth');
Route::get('trips-export/', 'TripController@exportTripsCSV')->name('routes-export')->middleware('auth');
Route::get('stoptimes-export/', 'StoptimeController@exportStoptimesCSV')->name('stoptimes-export')->middleware('auth');
Route::get('calendars-export/', 'CalendarController@exportCalendarsCSV')->name('calendars-export')->middleware('auth');
Route::get('shapes-export/', 'ShapeController@exportShapesCSV')->name('shapes-export')->middleware('auth');
Route::post('delete-shape/', 'ShapeController@deleteShape')->name('delete-shape')->middleware('auth');
Route::post('delete-stoptimes/', 'StoptimeController@deleteStoptimes')->name('delete-stoptimes')->middleware('auth');
Route::get('inverse-shape/','ShapeController@inverse')->name('inverse-shape')->middleware('auth');


Route::post('refresh-stoptimes/','StoptimeController@refreshStoptimes')->name('refresh-stoptimes')->middleware('auth');
Route::post('inverse-sequence/','StoptimeController@inverseSequence')->name('inverse-sequence')->middleware('auth');
Route::post('recalc-time/','StoptimeController@recalcTime')->name('recalc-time')->middleware('auth');


Route::get('ajax-pagination','TestController@ajaxPagination')->name('ajax.pagination');

Route::post('/generate-frequencies','FrequencyController@generateFrequencies')->name('generate-frequencies')->middleware('auth');

Route::get('/reset-service',function(){

    $trips = Trip::where('gtfs_id',session('gtfs_id'))->get();
    foreach ($trips as  $trip){
        $trip->service_id = 'MON-SUN';
        $trip->save();
    }
})->middleware('auth');

// =========================================================================
                            //ERRORS
// =========================================================================

Route::get('/stops-duplicated',function(){

    $duplicates = DB::table('stops')
        ->select('stop_name','stop_id', DB::raw('COUNT(*) as `count`'))
        ->groupBy('stop_name', 'stop_id')
        ->havingRaw('COUNT(*) > 1')
        ->where('gtfs_id',session('gtfs_id'))
        ->get();

    return view('gtfs.errors.stops-duplicated',compact('duplicates'));
})->middleware('auth');

Route::get('/deleted-stops-duplicates',function(){

    $duplicates = DB::table('stops')
        ->select('stop_name','stop_id', DB::raw('COUNT(*) as `count`'))
        ->groupBy('stop_name', 'stop_id')
        ->havingRaw('COUNT(*) > 1')
        ->where('gtfs_id',session('gtfs_id'))
        ->deleted();

    return view('gtfs.errors.stops-duplicated',compact('duplicates'));
})->middleware('auth');

Route::get('/modif-stop_desc',function(){

   $stops = Stop::where('gtfs_id',session('gtfs_id'))->get();
    foreach ($stops as  $stop){
        $stop->stop_desc = $stop->stop_name." BKO";
        $stop->save();
    }
})->middleware('auth');

Route::get('/modif-route-short-name',function(){

   $routes = RouteTable::where('gtfs_id',session('gtfs_id'))->get();
    foreach ($routes as  $route){
        $route->agency_id = "sotrama_bko";
        $route->save();
    }
})->middleware('auth');

Route::get('/delete-shape-no-use',function(){
    $shapes= DB::table('shapes')
        ->select('shape_id')
        ->groupBy( 'shape_id')
        ->where('gtfs_id',session('gtfs_id'))
        ->get();

    foreach($shapes as $shape){
        $trip = Trip::where('shape_id',$shape->shape_id)->where('gtfs_id',session('gtfs_id'))->first();
        if(!$trip){
            Shape::where('shape_id',$shape->shape_id)->where('gtfs_id',session('gtfs_id'))->delete();
        }
    }


})->middleware('auth');
