<?php

namespace App\Http\Controllers;

use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use App\Stop;
use App\Stoptime;
use App\Trip;
use Illuminate\Http\Request;

class StoptimeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {

        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }

        $gtfs = Gtfs::find(session('gtfs_id'));


        $t = csrf_token();
        $gtfs_id = session('gtfs_id');
        if (isset($request->search)) {

            $stopTs = $gtfs->stoptimes()->where('stop_id', $request->search)->paginate(100);
            $stopTs->withPath("/stoptime?t=$t&g=$gtfs_id&_=$request->_&search=$request->search");

            $stops = $gtfs->stops()->get();
            $trips = $gtfs->trips()->get();
        }elseif(isset($request->search_trip)){

            $stopTs = $gtfs->stoptimes()->where('trip_id', $request->search_trip)->paginate(100);
            $stopTs->withPath("/stoptime?t=$t&g=$gtfs_id&_=$request->_&search=$request->search_trip");

            $stops = $gtfs->stops()->get();
            $trips = $gtfs->trips()->get();
        }else {

            $stopTs = $gtfs->stoptimes()->paginate(100);
            $stopTs->withPath("/stoptime?t=$t&g=$gtfs_id&_=$request->_");
            $trips = $gtfs->trips()->get();
            $stops = $gtfs->stops()->get();

        }

        return view('gtfs.partials.stop_times', compact('stopTs', 'gtfs', 'trips', 'stops'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'trip_id' => 'required',
            // 'arrival_time' => 'date_format:H:i',
            // 'departure_time' => 'date_format:H:i|after:arrival_time',
            'stop_id' => 'required',
            'stop_sequence' => 'digits_between:1,3'
        ]);

        $gtfs_id = session('gtfs_id');
        $stop_times = new Stoptime();

        $stop_times->trip_id = $request->trip_id;
        $stop_times->arrival_time = $request->arrival_time;
        $stop_times->departure_time = $request->departure_time;
        $stop_times->stop_id = $request->stop_id;
        $stop_times->stop_sequence = $request->stop_sequence;
        $stop_times->stop_headsign = $request->stop_headsign;
        $stop_times->pickup_type = $request->pickup_type;
        $stop_times->drop_off_type = $request->drop_off_type;
        $stop_times->shape_dist_traveled = $request->shape_dist_traveled;
        $stop_times->timepoint = $request->timepoint;

        $stop_times->gtfs_id = $gtfs_id;

        $stop_times->save();
        WatriHelper::update_gtfs($gtfs_id);
        echo 'Stop times Inserted';

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id, Request $request)
    {

        $column_name = $request->input('column_name');
        $value = $request->input('value');

        $validate_column_name = $column_name;

        if ($validate_column_name === 'trip_id') {
            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'arrival_time') {
            $request->validate([
                'value' => 'date_format:H:i:s'
            ]);
        }
        if ($validate_column_name === 'departure_time') {

            $request->validate([
                'value' => 'date_format:H:i:s'
            ]);
        }
        if ($validate_column_name === 'stop_id') {

            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'stop_sequence') {

            $request->validate([
                'value' => 'digits_between:1,3'
            ]);
        }

        Stoptime::where('id', $id)
            ->update([$column_name => $value]);

        $gtfs = Stoptime::find($id)->gtfs_id;
        WatriHelper::update_gtfs($gtfs);

        echo "$column_name update";

    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'trip_id' => 'required',
            'arrival_time' => 'date_format:H:i',
            'departure_time' => 'date_format:H:i',
            'stop_id' => 'required',
            'stop_sequence' => 'digits_between:1,3'
        ]);

        $gtfs_id = session('gtfs_id');
        $stop_times = Stoptime::find($id);

        $stop_times->trip_id = $request->trip_id;
        $stop_times->arrival_time = $request->arrival_time;
        $stop_times->departure_time = $request->departure_time;
        $stop_times->stop_id = $request->stop_id;
        $stop_times->stop_sequence = $request->stop_sequence;
        $stop_times->stop_headsign = $request->stop_headsign;
        $stop_times->pickup_type = $request->pickup_type;
        $stop_times->drop_off_type = $request->drop_off_type;
        $stop_times->shape_dist_traveled = $request->shape_dist_traveled;
        $stop_times->timepoint = $request->timepoint;

        $stop_times->gtfs_id = $gtfs_id;

        $stop_times->save();
        WatriHelper::update_gtfs($gtfs_id);
        echo 'Stop times Inserted';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Stoptime::destroy($id);
        echo 'Data Deleted';
    }

    final public function importStoptimesCSV(Request $request)
    {
        $titles_allow = ['trip_id', 'arrival_time', 'departure_time', 'stop_id', 'stop_sequence', 'stop_headsign',
            'pickup_type', 'drop_off_type', 'shape_dist_traveled', 'timepoint'];

        $fileName = request('file')->getRealPath();
        $fileSize = $request->file('file')->getSize();

        if ($fileSize > 0) {
            $file = fopen($fileName, 'rb');
            $title_row = true;
            $title_data = [];
            $nbr_add = 0;
            $nbr_upd = 0;
            $line = 1;
            $gtfs_id = session('gtfs_id');
//            $fp = file($fileName);
//            $row_total = count($fp);
            while (($column = fgetcsv($file, 10000, ',')) !== FALSE) {
                if ($title_row) {
                    foreach ($column as $key => $value) {
                        if (!in_array($value, $titles_allow, true)) {
                            return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "$value is not a trip column");
                        }
                        $title_data[$value] = $key;
                    }
                    $title_row = false;
                    $line++;
                    continue;
                }
                if (!isset($title_data['trip_id'])) {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', 'trip_id is required');
                }
                if (!isset($title_data['stop_id'])) {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', 'stop_id is required');
                }
                if (!isset($title_data['stop_sequence'])) {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', 'stop_sequence is required');
                }

                if ($column[$title_data['trip_id']] === '') {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "missing trip_id on line $line");
                }

                if ($column[$title_data['stop_id']] === '') {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "missing stop_id on line $line");
                }

                if ($column[$title_data['stop_sequence']] === '') {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "missing stop_sequence on line $line");
                }

                $stoptime_exist = Stoptime::where('stop_id', $column[$title_data['stop_id']])->where('trip_id', $column[$title_data['trip_id']])->where('gtfs_id', $gtfs_id)->first();
                if (!$stoptime_exist) {
                    $stoptime = new Stoptime();
                    $nbr_add++;
                } else {
                    $stoptime = $stoptime_exist;
                    $nbr_upd++;
                }

                $stoptime->trip_id = $column[$title_data['trip_id']];
                $stoptime->stop_id = $column[$title_data['stop_id']];
                $stoptime->stop_sequence = $column[$title_data['stop_sequence']];
                $stoptime->arrival_time = isset($title_data['arrival_time']) ? $column[$title_data['arrival_time']] : '';
                $stoptime->departure_time = isset($title_data['departure_time']) ? $column[$title_data['departure_time']] : '';
                $stoptime->stop_headsign = isset($title_data['stop_headsign']) ? $column[$title_data['stop_headsign']] : '';
                $stoptime->pickup_type = isset($title_data['pickup_type']) ? $column[$title_data['pickup_type']] : '';
                $stoptime->drop_off_type = isset($title_data['drop_off_type']) ? $column[$title_data['drop_off_type']] : '';
                $stoptime->shape_dist_traveled = isset($title_data['shape_dist_traveled']) ? $column[$title_data['shape_dist_traveled']] : '';
                $stoptime->timepoint = isset($title_data['timepoint']) ? $column[$title_data['timepoint']] : '';
                $stoptime->gtfs_id = $gtfs_id;
                $stoptime->save();
                $line++;
            }

            return back()->with('success', "$nbr_add Stoptimes add | $nbr_upd Stoptimes update");
        }
    }

    final public function exportStoptimesCSV(){
        $stoptimes = Stoptime::select('trip_id', 'arrival_time', 'departure_time', 'stop_id', 'stop_sequence', 'stop_headsign',
            'pickup_type', 'drop_off_type', 'shape_dist_traveled', 'timepoint')
            ->where('gtfs_id',session('gtfs_id'))
            ->get()
            ->toArray();
        WatriHelper::download_send_headers('stoptimes_export_' . date('Y-m-d') . '.csv');
        echo WatriHelper::array2csv($stoptimes);
        die();
    }

    final public function generate(Request $request){

        $gtfs_id = session('gtfs_id');
        $nbr_add=0;
        $nbr_upd=0;

        for ($i=1; $i<4; $i++){

            $time_depart = $request->input("time_depart$i");
            $time_interval = $request->input("time_interval$i");
            $trip_id = $request->input("trip_id$i");
            $prefix_stop = $request->input("prefix_stop$i");

            if ($time_depart && $time_interval && $trip_id && $prefix_stop){
                $depart = "$time_depart:00";
                $time_interval = "$time_interval:00";
                $result = WatriHelper::generateStopTimes($trip_id,$depart,$prefix_stop,$time_interval);
                $nbr_add+=$result['nbr_add'];
                $nbr_upd+=$result['nbr_upd'];
            }
        }


        return redirect("/gtfs/$gtfs_id/edit")
            ->with('success', "$nbr_add StopTimes add | $nbr_upd StopTimes update");
    }

    public function deleteStoptimes(Request $request)
    {

        $validatedData = $request->validate([
            'id' => 'required'
        ]);
        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }
        $trip_id = $request->input('id');

        if (Stoptime::where('trip_id', $trip_id)->where('gtfs_id', session('gtfs_id'))->first()) {
            $res = Stoptime::where('trip_id', $trip_id)->where('gtfs_id', session('gtfs_id'))->delete();
            return back()->with('success', "Stoptimes deleted");
        } else {
            return back()->with('error', 'these Stoptimes not exist');
        }

    }

    public function refreshStoptimes(Request $request){
        $interval_time_on = $request->input('interval_time_on')?true:false;
        $trip_id = session('trip')->trip_id;
        $stops = session('trip')->stops;
        $nbr_add_st = 0;
        $stopsUnSort=[];
        foreach ($stops as $stop){
            $stopsUnSort[] = ['id' => $stop->stop_id, 'latitude' => $stop->stop_lat, 'longitude' => $stop->stop_lon, 'stopname' => $stop->stop_name];
        }

        if (Stoptime::where('trip_id', $trip_id)->where('gtfs_id', session('gtfs_id'))->first()) {
            $res = Stoptime::where('trip_id', $trip_id)->where('gtfs_id', session('gtfs_id'))->delete();
            $ref_latitude = $request->input('ref_latitude');
            $ref_longitude = $request->input('ref_longitude');
            $time_depart = $request->input('time_depart');
            $time_interval = $request->input('time_interval');
            $speed = $request->input('speed') ?? 20;

            if($request->input('by_latlon')=== 'latitudes'){
                $stopsSort = WatriHelper::sortByNearestLatLong($stopsUnSort, (float)$ref_latitude, (float)$ref_longitude, false)['latitudes'];
            }

            if($request->input('by_latlon')=== 'longitudes'){
                $stopsSort = WatriHelper::sortByNearestLatLong($stopsUnSort, (float)$ref_latitude, (float)$ref_longitude, false)['longitudes'];
            }

            if ($time_depart && $time_interval) {
                $result = WatriHelper::generateStopTimes($stopsSort, $trip_id, $time_depart, $time_interval,$interval_time_on,(int)$speed);
                $nbr_add_st += $result['nbr_add'];
            } else {
                $result = WatriHelper::generateStopTimes($stopsSort, $trip_id);
                $nbr_add_st += $result['nbr_add'];
            }
            $gtfs_id = session('gtfs_id');
            return back()->with('success', "$nbr_add_st Stoptimes add");
        }
    }

    public function inverseSequence(Request $request){
        $validatedData = $request->validate([
            'first_stop' => 'required',
            'second_stop' => 'required'
        ]);
        $retour = "OK";
        $trip_id = session('trip')->trip_id;
        $first_stoptime = Stoptime::where('trip_id',$trip_id)->where('stop_id',$request->first_stop)->first();
        $second_stoptime = Stoptime::where('trip_id',$trip_id)->where('stop_id',$request->second_stop)->first();
        $first_sequence = $first_stoptime->stop_sequence;
        $second_sequence = $second_stoptime->stop_sequence;

        $first_stoptime->stop_id = $request->second_stop;
        $second_stoptime->stop_id = $request->first_stop;
        $first_stoptime->save();
        $second_stoptime->save();

        $tird_stop_id = $request->tird_stop;
        $fourth_stop_id = $request->fourth_stop;
        $fifth_stop_id = $request->fifth_stop;
        $sixth_stop_id = $request->sixth_stop;

        if(isset($tird_stop_id,$fourth_stop_id)){
            $tird_stoptime = Stoptime::where('trip_id',$trip_id)->where('stop_id',$tird_stop_id)->first();
            $fourth_stoptime = Stoptime::where('trip_id',$trip_id)->where('stop_id',$fourth_stop_id)->first();

            $tird_sequence = $tird_stoptime->stop_sequence;
            $fourth_sequence = $fourth_stoptime->stop_sequence;

            $tird_stoptime->stop_id = $fourth_stop_id;
            $fourth_stoptime->stop_id = $tird_stop_id;
            $tird_stoptime->save();
            $fourth_stoptime->save();

            // $retour += " | $tird_sequence <---> $fourth_sequence";
        }

        if(isset($fifth_stop_id,$sixth_stop_id)){
            $fifth_stoptime = Stoptime::where('trip_id',$trip_id)->where('stop_id',$fifth_stop_id)->first();
            $sixth_stoptime = Stoptime::where('trip_id',$trip_id)->where('stop_id',$sixth_stop_id)->first();

            $fifth_sequence = $fifth_stoptime->stop_sequence;
            $sixth_sequence = $sixth_stoptime->stop_sequence;

            $fifth_stoptime->stop_id = $sixth_stop_id;
            $sixth_stoptime->stop_id = $fifth_stop_id;
            $fifth_stoptime->save();
            $sixth_stoptime->save();

            // $retour += "$fifth_sequence <---> $fourth_sequence";
        }

        // $retour += " | $first_sequence <---> $second_sequence";
        return back()->with('success', $retour);
    }

    public function recalcTime(Request $request){
        $validatedData = $request->validate([
            'speed' => 'required',
        ]);
        $retour = "OK";
        $trip_id = session('trip')->trip_id;
        WatriHelper::re_calc_time($trip_id,$request->speed);
        return back()->with('success', $retour);
    }


}

