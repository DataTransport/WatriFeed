<?php

namespace App\Http\Controllers;

use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use App\Route;
use App\Shape;
use App\Stop;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StopController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
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

        if ($request->t === csrf_token() && $gtfs->password === $request->_ && $request->a === 'true') {

            return response()->json($gtfs->stops()->get());
        }

        if (isset($request->search)) {
            $stops = $gtfs->stops()->where('stop_id', $request->search)->paginate(100);
            $stops->withPath("/stop?t=$t&g=$gtfs_id&_=$request->_&search=$request->search");

        } elseif (isset($request->search_name)) {
            //'name', 'like', '%' . Input::get('name') . '%'
            $stops = $gtfs->stops()->where('stop_name', 'like',$request->search_name . '%')->paginate(100);
            $stops->withPath("/stop?t=$t&g=$gtfs_id&_=$request->_&search=$request->search_name");
        } else {
            $stops = $gtfs->stops()->paginate(100);
            $stops->withPath("/stop?t=$t&g=$gtfs_id&_=$request->_");

        }


        return view('gtfs.partials.stops', compact('stops', 'gtfs'));

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
            'stop_id' => 'required|unique:stops',
            'stop_name' => 'required',
            'stop_lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'stop_lon' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'zone_id' => 'required',
//            'parent_station' => 'required'
        ]);
        $gtfs_id = session('gtfs_id');
        $stop = new Stop();
        $stop->stop_id = $request->stop_id;
        $stop->stop_name = $request->stop_name;
        $stop->zone_id = $request->zone_id;
        $stop->stop_code = $request->stop_code;
        $stop->stop_desc = $request->stop_desc;
        $stop->stop_lat = $request->stop_lat;
        $stop->stop_lon = $request->stop_lon;
        $stop->stop_url = $request->stop_url;
        $stop->location_type = $request->location_type;
        $stop->parent_station = $request->parent_station;
        $stop->stop_timezone = $request->stop_timezone;
        $stop->wheelchair_boarding = $request->wheelchair_boarding;
        $stop->level_id = $request->level_id;
        $stop->platform_code = $request->platform_code;
        $stop->gtfs_id = $gtfs_id;

        $stop->save();

        WatriHelper::update_gtfs($gtfs_id);
        echo 'Stop Inserted';

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

        if ($validate_column_name === 'stop_name') {
            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'stop_lat') {
            $request->validate([
                'value' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/']
            ]);
        }
        if ($validate_column_name === 'stop_lon') {

            $request->validate([
                'value' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']
            ]);
        }

        Stop::where('id', $id)
            ->update([$column_name => $value]);


        $gtfs = Stop::find($id)->gtfs_id;
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
            'stop_id' => 'required',
            'stop_name' => 'required',
            'stop_lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'stop_lon' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
//            'parent_station' => 'required'
        ]);

        $stop = Stop::find($id);
        $gtfs_id = session('gtfs_id');
        $stop->stop_id = $request->stop_id;
        $stop->stop_name = $request->stop_name;
        $stop->zone_id = $request->zone_id;
        $stop->stop_code = $request->stop_code;
        $stop->stop_desc = $request->stop_desc;
        $stop->stop_lat = $request->stop_lat;
        $stop->stop_lon = $request->stop_lon;
        $stop->stop_url = $request->stop_url;
        $stop->location_type = $request->location_type;
        $stop->parent_station = $request->parent_station;
        $stop->stop_timezone = $request->stop_timezone;
        $stop->wheelchair_boarding = $request->wheelchair_boarding;
        $stop->level_id = $request->level_id;
        $stop->platform_code = $request->platform_code;
        $stop->gtfs_id = $gtfs_id;

        $stop->save();
        WatriHelper::update_gtfs($gtfs_id);
        echo $request->stop_name . ' modifiée';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Stop::destroy($id);
        echo 'Data Deleted';
    }

    /**
     * Show the application dataAjax.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataAjax(Request $request)
    {
        $gtfs = session('gtfs_id');
        $gtfs_id = $gtfs ?? 0;
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $search = (string)$search;
            if (strlen($search) >= 2) {
                $data = DB::table('stops')
                    ->select('id', 'stop_id')
                    ->where('gtfs_id', $gtfs_id)
                    ->where('stop_id', 'LIKE', "%$search%")
                    ->get();
            }

        }


        return response()->json($data);
    }

    /**
     * Show the application dataAjax.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataAjaxZoneId(Request $request)
    {
        $gtfs = session('gtfs_id');
        $gtfs_id = $gtfs ?? 0;
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $search = (string)$search;
            if (strlen($search) >= 2) {
                $data = DB::table('stops')
                    ->select('id', 'zone_id')
                    ->where('gtfs_id', $gtfs_id)
                    ->where('zone_id', 'LIKE', "%$search%")
                    ->get();
            }

        }


        return response()->json($data);
    }

    public function getDataMap(Request $request)
    {
        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }

        $gtfs = Gtfs::find(session('gtfs_id'));

        return response()->json($gtfs->stops()->get());
    }

    final public function importStopsCSV(Request $request)
    {

        $titles_allow = ['stop_id', 'stop_code', 'stop_name', 'stop_desc', 'stop_lat', 'stop_lon', 'zone_id', 'stop_url',
            'location_type', 'parent_station', 'stop_timezone', 'wheelchair_boarding', 'level_id', 'platform_code'];

        $fileName = request('file')->getRealPath();
        $fileSize = $request->file('file')->getSize();

        if ($fileSize > 0) {

            $file = fopen($fileName, 'rb');

            $title_row = true;
            $title_data = [];
            $nbr_add = 0;
            $nbr_upd = 0;
            $line = 1;
            while (($column = fgetcsv($file, 10000, ',')) !== FALSE) {
                if ($title_row) {
                    foreach ($column as $key => $value) {
                        if (!in_array($value, $titles_allow, true)) {
                            return redirect()->route('gtfs.edit',['gtfs'=>session('gtfs_id')])->with('error', "$value is not a stop column");
                        }
                        $title_data[$value] = $key;
                    }
                    $title_row = false;
                    $line++;
                    continue;
                }
                if (!isset($title_data['stop_id'])){
                    return redirect()->route('gtfs.edit',['gtfs'=>session('gtfs_id')])->with('error', "stop_id is required");
                }
                if ($column[$title_data['stop_id']]===''){
                    return redirect()->route('gtfs.edit',['gtfs'=>session('gtfs_id')])->with('error', "missing stop_id on line $line");
                }

                $gtfs_id = session('gtfs_id');
                $stop_exist = Stop::where('stop_id', $column[$title_data['stop_id']])->where('gtfs_id', $gtfs_id)->first();
                if (!$stop_exist) {
                    $stop = new Stop();
                    $nbr_add++;
                } else {
                    $stop = $stop_exist;
                    $nbr_upd++;
                }
                $stop->stop_id = $column[$title_data['stop_id']];
                $stop->stop_name = isset($title_data['stop_name']) ? $column[$title_data['stop_name']] : '';
                $stop->zone_id = isset($title_data['zone_id']) ? $column[$title_data['zone_id']] : '';
                $stop->stop_code = isset($title_data['stop_code']) ? $column[$title_data['stop_code']] : '';
                $stop->stop_desc = isset($title_data['stop_desc']) ? $column[$title_data['stop_desc']] : '';
                $stop->stop_lat = isset($title_data['stop_lat']) ? $column[$title_data['stop_lat']] : '';
                $stop->stop_lon = isset($title_data['stop_lon']) ? $column[$title_data['stop_lon']] : '';
                $stop->stop_url = isset($title_data['stop_url']) ? $column[$title_data['stop_url']] : '';
                $stop->location_type = isset($title_data['location_type']) ? $column[$title_data['location_type']] : '';
                $stop->parent_station = isset($title_data['parent_station']) ? $column[$title_data['parent_station']] : '';
                $stop->stop_timezone = isset($title_data['stop_timezone']) ? $column[$title_data['stop_timezone']] : '';
                $stop->wheelchair_boarding = isset($title_data['wheelchair_boarding']) ? $column[$title_data['wheelchair_boarding']] : '';
                $stop->level_id = isset($title_data['level_id']) ? $column[$title_data['level_id']] : '';
                $stop->platform_code = isset($title_data['platform_code']) ? $column[$title_data['platform_code']] : '';
                $stop->gtfs_id = $gtfs_id;
                $stop->save();
                $line++;
            }
            return back()->with('success', "$nbr_add Stops add | $nbr_upd Stops update");
        }

    }

    final public function exportStopsCSV(){

        $stops = Stop::select('stop_id','stop_name', 'stop_desc', 'stop_lat', 'stop_lon', 'zone_id', 'stop_url', 'location_type', 'parent_station', 'stop_timezone','wheelchair_boarding', 'level_id', 'platform_code')
            ->where('gtfs_id',session('gtfs_id'))
            ->get()
            ->toArray();
        WatriHelper::download_send_headers('stops_export_' . date('Y-m-d') . '.csv');
        echo WatriHelper::array2csv($stops);
//        dd(WatriHelper::array2csv($stops));
        die();
    }


}
