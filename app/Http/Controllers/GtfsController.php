<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Calendar;
use App\CalendarDate;
use App\FareAttribute;
use App\FareRule;
use App\Frequency;
use App\Gtfs;
use App\Gtfs as GtfsTable;
use App\helpers\UsersLogsHelper;
use App\helpers\WatriCheck;
use App\helpers\WatriGtfs;
use App\helpers\WatriHelper;
use App\Http\Requests\ImportCreateRequest;
use App\Route;
use App\Shape;
use App\Stop;
use App\Stoptime;
use App\Transfer;
use App\Trip;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GtfsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return RedirectResponse|Redirector
     */
    public function index()
    {

        if ((int)Auth::user()->state === 0){
            Session::flash('message', 'Your account is awaiting validation');
            Auth::logout();
            return redirect('/');
        }
        $gtfs = GtfsTable::all();
        return view('gtfs.list_gtfs', compact('gtfs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('gtfs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Factory|View
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required|unique:gtfs|max:150',
            'password' => 'required|min:7|confirmed'
        ]);

        $gtfs = new GtfsTable();
        $gtfs->name = $request->name;
        $gtfs->password = bcrypt(request('password'));
        $gtfs->user_id = Auth::id();

        $gtfs->save();

        UsersLogsHelper::create('createGTFS');

        return view('gtfs.list_gtfs');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
//        echo 'toto';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function edit(Request $request)
    {
        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }
        $gtfs = Gtfs::find(session('gtfs_id'));
        return view('gtfs.edit', compact('gtfs'));
    }

    public function edit_(Request $request)
    {
        $gtfs = GtfsTable::find($request->id);

        $password = $gtfs->password;

//        if (Hash::check($request->pass, $password)) {
//            session(['gtfs_id' => $gtfs->id]);
//            return view('gtfs.edit', compact('gtfs'));
//        }
        if (Hash::check($request->pass, $password)|| Auth::id()==1) {
            session(['gtfs_id' => $gtfs->id]);
            return view('gtfs.edit', compact('gtfs'));
        }

        if (session('gtfs_id')){
        $gtfs = Gtfs::find(session('gtfs_id'));
        return view('gtfs.edit', compact('gtfs'));
        }


    }

    public function checkPass(Request $request)
    {
        $this->validate(request(), [
            'password' => 'required',
            'name' => 'required'
        ]);

        $gtfs = GtfsTable::where('name', $request->name)->first();
        $password = $gtfs->password;
        $id = $gtfs->id;

//        if (Hash::check($request->password, $password)) {
//            $message = ['response' => 'ok', 'id' => $id, 'pass' => $request->password];
//            echo json_encode($message);
//        } else {
//            $message = ['response' => 'ko'];
//            echo json_encode($message);
//        }

        if (Hash::check($request->password, $password) || Auth::id()==1) {
            $message = ['response' => 'ok', 'id' => $id, 'pass' => $request->password];
            echo json_encode($message);
        } else {
            $message = ['response' => 'ko'];
            echo json_encode($message);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     * @throws ValidationException
     */
    public function update($id)
    {
        $this->validate(request(), [
            'name' => 'required|max:150|unique:gtfs,id,' . $id,
            'password' => 'required|min:7|confirmed'
        ]);


        $gtfs = GtfsTable::find($id);

        $gtfs->name = request('name');
        $gtfs->password = bcrypt(request('password'));

        $gtfs->save();

        echo 'ok';

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id): void
    {
        $name = GtfsTable::find($id)->name;

        Agency::where('gtfs_id', $id)->delete();
        Stop::where('gtfs_id', $id)->delete();
        Route::where('gtfs_id', $id)->delete();
        Trip::where('gtfs_id', $id)->delete();
        Stoptime::where('gtfs_id', $id)->delete();
        Calendar::where('gtfs_id', $id)->delete();
        CalendarDate::where('gtfs_id', $id)->delete();
        FareAttribute::where('gtfs_id', $id)->delete();
        FareRule::where('gtfs_id', $id)->delete();
        Shape::where('gtfs_id', $id)->delete();
        Frequency::where('gtfs_id', $id)->delete();
        Transfer::where('gtfs_id', $id)->delete();

        GtfsTable::destroy($id);

        UsersLogsHelper::create('destroyGTFS');
        echo "$name deleted";

    }

    final public function importGet()
    {

        return view('gtfs.import');
    }

    /**
     * @param ImportCreateRequest $request
     * @return Factory|View
     */
    final public function importPost(ImportCreateRequest $request)
    {

        $name = $request->input('name');
        $request->fileGtfs->storeAs('gtfs', "$name.zip");


        $root = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix();
        $watriGtfs = new WatriGtfs($root . "gtfs/$name", WatriHelper::folderCreator('gtfs'));
        $watriGtfs->gtfsToArray();


        $gtfs_table = new GtfsTable();
        $gtfs_table->name = $name;
        $gtfs_table->password = bcrypt(request('password'));
        $gtfs_table->user_id = Auth::id();
        $gtfs_table->save();

        WatriGtfs::saveGtfsElements($watriGtfs, $gtfs_table->id);

        $gtfs = GtfsTable::all();
        $ok = 'ok';

        WatriHelper::deleteDir('unzip/gtfs');
        return view('gtfs.list_gtfs', compact('ok', 'name', 'gtfs'));


    }
    final public function importPost2(ImportCreateRequest $request)
    {

        $name = $request->input('name');
        $request->fileGtfs->storeAs('gtfs', "$name.zip");


        $root = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix();
        $watriGtfs = new WatriGtfs($root . "gtfs/$name", WatriHelper::folderCreator('gtfs'));
        $watriGtfs->gtfsToArray();


        $gtfs_table = new GtfsTable();
        $gtfs_table->name = $name;
        $gtfs_table->password = bcrypt(request('password'));
        $gtfs_table->user_id = Auth::id();
        $gtfs_table->save();

        WatriGtfs::saveGtfsElements($watriGtfs, $gtfs_table->id);

        $gtfs = GtfsTable::all();
        $ok = 'ok';

        WatriHelper::deleteDir('unzip/gtfs');
        UsersLogsHelper::create('importGTFS');
        return view('gtfs.list_gtfs', compact('ok', 'name', 'gtfs'));


    }

    /**
     * @param string $gtfs_name
     */
    final public function export(string $gtfs_name): RedirectResponse
    {

        $gtfs = GtfsTable::all()->where('name', $gtfs_name)->first();
        $myfile = fopen('agency.txt', 'wb');
        $agencyContent = 'agency_id,agency_name,agency_url,agency_timezone,agency_lang,agency_phone,agency_fare_url,agency_email' . "\n";
        fwrite($myfile, $agencyContent);
        foreach ($gtfs->agencies()->get() as $agency) {
            $agencyContent = '';
            $agencyContent .= $agency->agency_id . ',';
            $agencyContent .= $agency->agency_name . ',';
            $agencyContent .= $agency->agency_url . ',';
            $agencyContent .= $agency->agency_timezone . ',';
            $agencyContent .= $agency->agency_lang . ',';
            $agencyContent .= $agency->agency_phone . ',';
            $agencyContent .= $agency->agency_fare_url . ',';
            $agencyContent .= $agency->agency_email . '';
            $agencyContent .= "\n";
            fwrite($myfile, $agencyContent);
        }
        fclose($myfile);

        $myfile = fopen('stops.txt', 'wb');
        $stopsContent = 'stop_id,stop_code,stop_name,stop_desc,stop_lat,stop_lon,zone_id,stop_url,location_type,parent_station,stop_timezone,wheelchair_boarding,level_id,platform_code' . "\n";
        fwrite($myfile, $stopsContent);
        foreach ($gtfs->stops()->get() as $stop) {
            $stopsContent = '';
            $stopsContent .= $stop->stop_id . ',';
            $stopsContent .= $stop->stop_code . ',';
            $stopsContent .= $stop->stop_name . ',';
            $stopsContent .= $stop->stop_desc . ',';
            $stopsContent .= $stop->stop_lat . ',';
            $stopsContent .= $stop->stop_lon . ',';
            $stopsContent .= $stop->zone_id . ',';
            $stopsContent .= $stop->stop_url . ',';
            $stopsContent .= $stop->location_type . ',';
            $stopsContent .= $stop->parent_station . ',';
            $stopsContent .= $stop->stop_timezone . ',';
            $stopsContent .= $stop->wheelchair_boarding . ',';
            $stopsContent .= $stop->level_id . ',';
            $stopsContent .= $stop->platform_code . '';
            $stopsContent .= "\n";
            fwrite($myfile, $stopsContent);
        }
        fclose($myfile);

        $myfile = fopen('routes.txt', 'wb');
        $routesContent = 'route_id,agency_id,route_short_name,route_long_name,route_desc,route_type,route_url,route_color,route_text_color,route_sort_order' . "\n";
        fwrite($myfile, $routesContent);
        foreach ($gtfs->routes()->get() as $route) {
            $routesContent = '';
            $routesContent .= $route->route_id . ',';
            $routesContent .= $route->agency_id . ',';
            $routesContent .= $route->route_short_name . ',';
            $routesContent .= $route->route_long_name . ',';
            $routesContent .= $route->route_desc . ',';
            $routesContent .= $route->route_type . ',';
            $routesContent .= $route->route_url . ',';
            $routesContent .= $route->route_color . ',';
            $routesContent .= $route->route_text_color . ',';
            $routesContent .= $route->route_sort_order . '';
            $routesContent .= "\n";
            fwrite($myfile, $routesContent);
        }
        fclose($myfile);

        $myfile = fopen('trips.txt', 'wb');
        $tripsContent = 'route_id,service_id,trip_id,trip_headsign,trip_short_name,direction_id,block_id,shape_id,wheelchair_accessible,bikes_allowed' . "\n";
        fwrite($myfile, $tripsContent);
        foreach ($gtfs->trips()->get() as $trip) {
            $tripsContent = '';
            $tripsContent .= $trip->route_id . ',';
            $tripsContent .= $trip->service_id . ',';
            $tripsContent .= $trip->trip_id . ',';
            $tripsContent .= $trip->trip_headsign . ',';
            $tripsContent .= $trip->trip_short_name . ',';
            $tripsContent .= $trip->direction_id . ',';
            $tripsContent .= $trip->block_id . ',';
            $tripsContent .= $trip->shape_id . ',';
            $tripsContent .= $trip->wheelchair_accessible . ',';
            $tripsContent .= $trip->bikes_allowed . '';
            $tripsContent .= "\n";
            fwrite($myfile, $tripsContent);
        }
        fclose($myfile);

        $myfile = fopen('stop_times.txt', 'wb');
        $stop_timesContent = 'trip_id,arrival_time,departure_time,stop_id,stop_sequence,stop_headsign,pickup_type,drop_off_type,shape_dist_traveled,timepoint' . "\n";
        fwrite($myfile, $stop_timesContent);
        foreach ($gtfs->stoptimes()->get() as $stoptime) {
            $stop_timesContent = '';
            $stop_timesContent .= $stoptime->trip_id . ',';
            $stop_timesContent .= $stoptime->arrival_time . ',';
            $stop_timesContent .= $stoptime->departure_time . ',';
            $stop_timesContent .= $stoptime->stop_id . ',';
            $stop_timesContent .= $stoptime->stop_sequence . ',';
            $stop_timesContent .= $stoptime->stop_headsign . ',';
            $stop_timesContent .= $stoptime->pickup_type . ',';
            $stop_timesContent .= $stoptime->drop_off_type . ',';
            $stop_timesContent .= $stoptime->shape_dist_traveled . ',';
            $stop_timesContent .= $stoptime->timepoint . '';
            $stop_timesContent .= "\n";
            fwrite($myfile, $stop_timesContent);
        }
        fclose($myfile);

        $myfile = fopen('calendar.txt', 'wb');
        $calendarsContent = 'service_id,monday,tuesday,wednesday,thursday,friday,saturday,sunday,start_date,end_date' . "\n";
        fwrite($myfile, $calendarsContent);
        foreach ($gtfs->calendars()->get() as $calendar) {
            $calendarsContent = '';
            $calendarsContent .= $calendar->service_id . ',';
            $calendarsContent .= $calendar->monday . ',';
            $calendarsContent .= $calendar->tuesday . ',';
            $calendarsContent .= $calendar->wednesday . ',';
            $calendarsContent .= $calendar->thursday . ',';
            $calendarsContent .= $calendar->friday . ',';
            $calendarsContent .= $calendar->saturday . ',';
            $calendarsContent .= $calendar->sunday . ',';
            $calendarsContent .= $calendar->start_date . ',';
            $calendarsContent .= $calendar->end_date . '';
            $calendarsContent .= "\n";
            fwrite($myfile, $calendarsContent);
        }
        fclose($myfile);

        $myfile = fopen('calendar_dates.txt', 'wb');
        $calendarDatesContent = 'service_id,date,exception_type' . "\n";
        fwrite($myfile, $calendarDatesContent);
        foreach ($gtfs->calendar_dates()->get() as $calendar_date) {
            $calendarDatesContent = '';
            $calendarDatesContent .= $calendar_date->service_id . ',';
            $calendarDatesContent .= $calendar_date->date . ',';
            $calendarDatesContent .= $calendar_date->exception_type . '';

            $calendarDatesContent .= "\n";
            fwrite($myfile, $calendarDatesContent);
        }
        fclose($myfile);

        $myfile = fopen('fare_attributes.txt', 'wb');
        $fareAttributesContent = 'fare_id,price,currency_type,payment_method,transfers,agency_id,transfer_duration' . "\n";
        fwrite($myfile, $fareAttributesContent);
        foreach ($gtfs->fare_attributes()->get() as $fare_attribute) {
            $fareAttributesContent = '';
            $fareAttributesContent .= $fare_attribute->fare_id . ',';
            $fareAttributesContent .= $fare_attribute->price . ',';
            $fareAttributesContent .= $fare_attribute->currency_type . ',';
            $fareAttributesContent .= $fare_attribute->payment_method . ',';
            $fareAttributesContent .= $fare_attribute->transfers . ',';
            $fareAttributesContent .= $fare_attribute->agency_id . ',';
            $fareAttributesContent .= $fare_attribute->transfer_duration . '';

            $fareAttributesContent .= "\n";
            fwrite($myfile, $fareAttributesContent);
        }
        fclose($myfile);


        $myfile = fopen('fare_rules.txt', 'wb');
        $fareRulesContent = 'fare_id,route_id,origin_id,destination_id,contains_id' . "\n";
        fwrite($myfile, $fareRulesContent);
        foreach ($gtfs->fare_rules()->get() as $fare_rule) {
            $fareRulesContent = '';
            $fareRulesContent .= $fare_rule->fare_id . ',';
            $fareRulesContent .= $fare_rule->route_id . ',';
            $fareRulesContent .= $fare_rule->origin_id . ',';
            $fareRulesContent .= $fare_rule->destination_id . ',';
            $fareRulesContent .= $fare_rule->contains_id . '';

            $fareRulesContent .= "\n";
            fwrite($myfile, $fareRulesContent);
        }
        fclose($myfile);

        $myfile = fopen('shapes.txt', 'wb');
        $shapesContent = 'shape_id,shape_pt_lat,shape_pt_lon,shape_pt_sequence,shape_dist_traveled' . "\n";
        fwrite($myfile, $shapesContent);
        foreach ($gtfs->shapes()->get() as $shape) {
            $shapesContent = '';
            $shapesContent .= $shape->shape_id . ',';
            $shapesContent .= $shape->shape_pt_lat . ',';
            $shapesContent .= $shape->shape_pt_lon . ',';
            $shapesContent .= $shape->shape_pt_sequence . ',';
            $shapesContent .= $shape->shape_dist_traveled . '';

            $shapesContent .= "\n";
            fwrite($myfile, $shapesContent);
        }
        fclose($myfile);

        $myfile = fopen('frequencies.txt', 'wb');
        $frequenciesContent = 'trip_id,start_time,end_time,headway_secs,exact_times' . "\n";
        fwrite($myfile, $frequenciesContent);
        foreach ($gtfs->frequencies()->get() as $frequency) {
            $frequenciesContent = '';
            $frequenciesContent .= $frequency->trip_id . ',';
            $frequenciesContent .= $frequency->start_time . ',';
            $frequenciesContent .= $frequency->end_time . ',';
            $frequenciesContent .= $frequency->headway_secs . ',';
            $frequenciesContent .= $frequency->exact_times . '';

            $frequenciesContent .= "\n";
            fwrite($myfile, $frequenciesContent);
        }
        fclose($myfile);


        $myfile = fopen('transfers.txt', 'wb');
        $transfersContent = 'from_stop_id,to_stop_id,transfer_type,min_transfer_time' . "\n";
        fwrite($myfile, $transfersContent);
        foreach ($gtfs->transfers()->get() as $transfer) {
            $transfersContent = '';
            $transfersContent .= $transfer->from_stop_id . ',';
            $transfersContent .= $transfer->to_stop_id . ',';
            $transfersContent .= $transfer->transfer_type . ',';
            $transfersContent .= $transfer->min_transfer_time . '';

            $transfersContent .= "\n";
            fwrite($myfile, $transfersContent);
        }
        fclose($myfile);


        $myfile = fopen('pathways.txt', 'wb');
        $pathwaysContent = 'pathway_id,from_stop_id,to_stop_id,pathway_mode,is_bidirectional,length,traversal_time,stair_count,max_slope,min_width,signposted_as,reversed_signposted_as' . "\n";
        fwrite($myfile, $pathwaysContent);
        foreach ($gtfs->pathways()->get() as $pathway) {
            $pathwaysContent = '';
            $pathwaysContent .= $pathway->pathway_id . ',';
            $pathwaysContent .= $pathway->from_stop_id . ',';
            $pathwaysContent .= $pathway->to_stop_id . ',';
            $pathwaysContent .= $pathway->pathway_mode . ',';
            $pathwaysContent .= $pathway->is_bidirectional . ',';
            $pathwaysContent .= $pathway->length . ',';
            $pathwaysContent .= $pathway->traversal_time . ',';
            $pathwaysContent .= $pathway->stair_count . ',';
            $pathwaysContent .= $pathway->max_slope . ',';
            $pathwaysContent .= $pathway->min_width . ',';
            $pathwaysContent .= $pathway->signposted_as . ',';
            $pathwaysContent .= $pathway->reversed_signposted_as . '';

            $pathwaysContent .= "\n";
            fwrite($myfile, $pathwaysContent);
        }
        fclose($myfile);


        $myfile = fopen('levels.txt', 'wb');
        $levelsContent = 'level_id,level_index,level_name' . "\n";
        fwrite($myfile, $levelsContent);
        foreach ($gtfs->levels()->get() as $level) {
            $levelsContent = '';
            $levelsContent .= $level->level_id . ',';
            $levelsContent .= $level->level_index . ',';
            $levelsContent .= $level->level_name . '';

            $levelsContent .= "\n";
            fwrite($myfile, $levelsContent);
        }
        fclose($myfile);


        $date = date('m_d_Y_h_i_s', time());
        WatriGtfs::exportGtfs($gtfs->name . '_' . $date);

//        $name = $gtfs;
//        $gtfs = GtfsTable::all();
//        $ok='ok';
        UsersLogsHelper::create('exportGTFS');

    }

    public function backup($gtfs)
    {

        $myfile = fopen('agency.txt', 'wb');
        $agencyContent = 'id,agency_id,agency_name,agency_url,agency_timezone,agency_phone,agency_lang,gtfs' . "\n";
        fwrite($myfile, $agencyContent);
        foreach (Agency::where('gtfs', $gtfs)->get() as $agency) {
            $agencyContent = '';
            $agencyContent .= $agency->id . ',';
            $agencyContent .= $agency->agency_id . ',';
            $agencyContent .= $agency->agency_name . ',';
            $agencyContent .= $agency->agency_url . ',';
            $agencyContent .= $agency->agency_timezone . ',';
            $agencyContent .= $agency->agency_phone . ',';
            $agencyContent .= $agency->agency_lang . ',';
            $agencyContent .= $agency->gtfs . '';
            $agencyContent .= "\n";
            fwrite($myfile, $agencyContent);
        }
        fclose($myfile);

        $myfile = fopen('stops.txt', 'wb');
        $stopsContent = 'id,stop_id,stop_name,stop_desc,stop_lat,stop_lon,stop_url,location_type,parent_station,gtfs' . "\n";
        fwrite($myfile, $stopsContent);
        foreach (Stop::where('gtfs', $gtfs)->get() as $stop) {
            $stopsContent = '';
            $stopsContent .= $stop->id . ',';
            $stopsContent .= $stop->stop_id . ',';
            $stopsContent .= $stop->stop_name . ',';
            $stopsContent .= $stop->stop_desc . ',';
            $stopsContent .= $stop->stop_lat . ',';
            $stopsContent .= $stop->stop_lon . ',';
            $stopsContent .= $stop->stop_url . ',';
            $stopsContent .= $stop->location_type . ',';
            $stopsContent .= $stop->parent_station . ',';
            $stopsContent .= $stop->gtfs . '';
            $stopsContent .= "\n";
            fwrite($myfile, $stopsContent);
        }
        fclose($myfile);

        $myfile = fopen('routes.txt', 'wb');
        $routesContent = 'id,route_id,route_short_name,route_long_name,route_desc,route_type,gtfs' . "\n";
        fwrite($myfile, $routesContent);
        foreach (Route::where('gtfs', $gtfs)->get() as $route) {
            $routesContent = '';
            $routesContent .= $route->id . ',';
            $routesContent .= $route->route_id . ',';
            $routesContent .= $route->route_short_name . ',';
            $routesContent .= $route->route_long_name . ',';
            $routesContent .= $route->route_desc . ',';
            $routesContent .= $route->route_type . ',';
            $routesContent .= $route->gtfs . '';
            $routesContent .= "\n";
            fwrite($myfile, $routesContent);
        }
        fclose($myfile);

        $myfile = fopen('trips.txt', 'wb');
        $tripsContent = 'id,route_id,service_id,trip_id,trip_headsign,block_id' . "\n";
        fwrite($myfile, $tripsContent);
        foreach (Trip::where('gtfs', $gtfs)->get() as $trip) {
            $tripsContent = '';
            $tripsContent .= $trip->id . ',';
            $tripsContent .= $trip->route_id . ',';
            $tripsContent .= $trip->service_id . ',';
            $tripsContent .= $trip->trip_id . ',';
            $tripsContent .= $trip->trip_headsign . ',';
            $tripsContent .= $trip->block_id . '';
            $tripsContent .= "\n";
            fwrite($myfile, $tripsContent);
        }
        fclose($myfile);

        $myfile = fopen('stop_times.txt', 'wb');
        $stop_timesContent = 'id,trip_id,arrival_time,departure_time,stop_id,stop_sequence,pickup_type,drop_off_type' . "\n";
        fwrite($myfile, $stop_timesContent);
        foreach (Stoptime::where('gtfs', $gtfs)->get() as $stoptime) {
            $stop_timesContent = '';
            $stop_timesContent .= $stoptime->id . ',';
            $stop_timesContent .= $stoptime->trip_id . ',';
            $stop_timesContent .= $stoptime->arrival_time . ',';
            $stop_timesContent .= $stoptime->departure_time . ',';
            $stop_timesContent .= $stoptime->stop_id . ',';
            $stop_timesContent .= $stoptime->stop_sequence . ',';
            $stop_timesContent .= $stoptime->pickup_type . ',';
            $stop_timesContent .= $stoptime->drop_off_type . '';
            $stop_timesContent .= "\n";
            fwrite($myfile, $stop_timesContent);
        }
        fclose($myfile);

        $myfile = fopen('calendar.txt', 'wb');
        $calendarsContent = 'id,service_id,monday,tuesday,wednesday,thursday,friday,saturday,sunday,start_date,end_date' . "\n";
        fwrite($myfile, $calendarsContent);
        foreach (Calendar::where('gtfs', $gtfs)->get() as $calendar) {
            $calendarsContent = '';
            $calendarsContent .= $calendar->id . ',';
            $calendarsContent .= $calendar->service_id . ',';
            $calendarsContent .= $calendar->monday . ',';
            $calendarsContent .= $calendar->tuesday . ',';
            $calendarsContent .= $calendar->wednesday . ',';
            $calendarsContent .= $calendar->thursday . ',';
            $calendarsContent .= $calendar->friday . ',';
            $calendarsContent .= $calendar->saturday . ',';
            $calendarsContent .= $calendar->sunday . ',';
            $calendarsContent .= $calendar->start_date . ',';
            $calendarsContent .= $calendar->end_date . '';
            $calendarsContent .= "\n";
            fwrite($myfile, $calendarsContent);
        }
        fclose($myfile);

        $myfile = fopen('calendar_dates.txt', 'wb');
        $calendarDatesContent = 'id,service_id,date,exception_type' . "\n";
        fwrite($myfile, $calendarDatesContent);
        foreach (CalendarDate::where('gtfs', $gtfs)->get() as $calendar_date) {
            $calendarDatesContent = '';
            $calendarDatesContent .= $calendar_date->id . ',';
            $calendarDatesContent .= $calendar_date->service_id . ',';
            $calendarDatesContent .= $calendar_date->date . ',';
            $calendarDatesContent .= $calendar_date->exception_type . '';

            $calendarDatesContent .= "\n";
            fwrite($myfile, $calendarDatesContent);
        }
        fclose($myfile);

        $myfile = fopen('fare_attributes.txt', 'wb');
        $fareAttributesContent = 'id,fare_id,price,currency_type,payment_method,transfers,transfer_duration' . "\n";
        fwrite($myfile, $fareAttributesContent);
        foreach (FareAttribute::where('gtfs', $gtfs)->get() as $fare_attribute) {
            $fareAttributesContent = '';
            $fareAttributesContent .= $fare_attribute->id . ',';
            $fareAttributesContent .= $fare_attribute->fare_id . ',';
            $fareAttributesContent .= $fare_attribute->price . ',';
            $fareAttributesContent .= $fare_attribute->currency_type . ',';
            $fareAttributesContent .= $fare_attribute->payment_method . ',';
            $fareAttributesContent .= $fare_attribute->transfers . ',';
            $fareAttributesContent .= $fare_attribute->transfer_duration . '';

            $fareAttributesContent .= "\n";
            fwrite($myfile, $fareAttributesContent);
        }
        fclose($myfile);


        $myfile = fopen('fare_rules.txt', 'wb');
        $fareRulesContent = 'id,fare_id,route_id,origin_id,destination_id,contains_id' . "\n";
        fwrite($myfile, $fareRulesContent);
        foreach (FareRule::where('gtfs', $gtfs)->get() as $fare_rule) {
            $fareRulesContent = '';
            $fareRulesContent .= $fare_rule->id . ',';
            $fareRulesContent .= $fare_rule->fare_id . ',';
            $fareRulesContent .= $fare_rule->route_id . ',';
            $fareRulesContent .= $fare_rule->origin_id . ',';
            $fareRulesContent .= $fare_rule->destination_id . ',';
            $fareRulesContent .= $fare_rule->contains_id . '';

            $fareRulesContent .= "\n";
            fwrite($myfile, $fareRulesContent);
        }
        fclose($myfile);

        $myfile = fopen('shapes.txt', 'wb');
        $shapesContent = 'id,shape_id,shape_pt_lat,shape_pt_lon,shape_pt_sequence,shape_dist_traveled' . "\n";
        fwrite($myfile, $shapesContent);
        foreach (Shape::where('gtfs', $gtfs)->get() as $shape) {
            $shapesContent = '';
            $shapesContent .= $shape->id . ',';
            $shapesContent .= $shape->shape_id . ',';
            $shapesContent .= $shape->shape_pt_lat . ',';
            $shapesContent .= $shape->shape_pt_lon . ',';
            $shapesContent .= $shape->shape_pt_sequence . ',';
            $shapesContent .= $shape->shape_dist_traveled . '';

            $shapesContent .= "\n";
            fwrite($myfile, $shapesContent);
        }
        fclose($myfile);

        $myfile = fopen('frequencies.txt', 'wb');
        $frequenciesContent = 'id,trip_id,start_time,end_time,headway_secs' . "\n";
        fwrite($myfile, $frequenciesContent);
        foreach (Frequency::where('gtfs', $gtfs)->get() as $frequency) {
            $frequenciesContent = '';
            $frequenciesContent .= $frequency->id . ',';
            $frequenciesContent .= $frequency->trip_id . ',';
            $frequenciesContent .= $frequency->start_time . ',';
            $frequenciesContent .= $frequency->end_time . ',';
            $frequenciesContent .= $frequency->headway_secs . '';

            $frequenciesContent .= "\n";
            fwrite($myfile, $frequenciesContent);
        }
        fclose($myfile);


        $myfile = fopen('transfers.txt', 'wb');
        $transfersContent = 'id,from_stop_id,to_stop_id,transfer_type,min_transfer_time' . "\n";
        fwrite($myfile, $transfersContent);
        foreach (Transfer::where('gtfs', $gtfs)->get() as $transfers) {
            $transfersContent = '';
            $transfersContent .= $transfers->id . ',';
            $transfersContent .= $transfers->from_stop_id . ',';
            $transfersContent .= $transfers->to_stop_id . ',';
            $transfersContent .= $transfers->transfer_type . ',';
            $transfersContent .= $transfers->min_transfer_time . '';

            $transfersContent .= "\n";
            fwrite($myfile, $transfersContent);
        }
        fclose($myfile);

        $myfile = fopen('gtfs.txt', 'wb');
        $gtfsContent = 'id,name' . "\n";
        fwrite($myfile, $gtfsContent);
        foreach (GtfsTable::where('name', $gtfs)->get() as $g) {
            $gtfsContent = '';
            $gtfsContent .= $g->id . ',';
            $gtfsContent .= $g->name . '';
            $gtfsContent .= "\n";
            fwrite($myfile, $gtfsContent);
        }
        fclose($myfile);
        $date = date('m_d_Y_h_i_s', time());
        WatriGtfs::backUpGtfs($gtfs . '_' . $date);

    }

    public function setting(Request $request)
    {
        $gtfs = $request->gtfs;
        $gtfs = GtfsTable::where('name', $gtfs)->first();

        return view('gtfs.settings', compact('gtfs'));
    }
}
