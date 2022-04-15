<?php

namespace App\Http\Controllers;

use App\Gtfs;
use App\Gtfs as GtfsTable;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use App\Route;
use App\Shape;
use App\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShapeController extends Controller
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
//        dd($this->distance(12.606099,-8.010214,12.6531188,-7.981954,"K"));
        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }
        $gtfs = Gtfs::find(session('gtfs_id'));

        $t = csrf_token();
        $gtfs_id = session('gtfs_id');
        if (isset($request->search)) {
            $shapes = $gtfs->shapes()->where('shape_id', $request->search)->paginate(100);
            $shapes->withPath("/shape?t=$t&g=$gtfs_id&_=$request->_&search=$request->search");

        } else {

            $shapes = $gtfs->shapes()->paginate(100);
            $shapes->withPath("/shape?t=$t&g=$gtfs_id&_=$request->_");

        }
        $data = DB::table('shapes')
            ->where('gtfs_id', $gtfs_id)
            ->select('shape_id')
            ->groupBy('shape_id')
            ->paginate(5);

        $data->setPageName('other_page');
        $numbers = [];
        for ($i=1;$i<=5 *$data->currentPage();$i++){
            $numbers[]=$i;
        }
        $numbers = array_reverse($numbers);
        $last_5_item=[];
        for ($i=0;$i<5;$i++){
            $last_5_item[]=$numbers[$i];
        }
        $numbers=array_reverse($last_5_item);
        if ($request->ajax()) {

            return view('presult', compact('data','numbers'));
        }

        return view('gtfs.partials.shapes', compact('shapes', 'gtfs','data','numbers'));
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
            'shape_id' => 'required',
            'shape_pt_lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'shape_pt_lon' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'shape_pt_sequence' => 'required',
            'gtfs' => 'required'
        ]);

        $shape = new Shape();

        $shape->shape_id = $request->shape_id;
        $shape->shape_pt_lat = $request->shape_pt_lat;
        $shape->shape_pt_lon = $request->shape_pt_lon;
        $shape->shape_pt_sequence = $request->shape_pt_sequence;
        $shape->shape_dist_traveled = $request->shape_dist_traveled;

        $shape->gtfs_id = $request->gtfs;

        $shape->save();

        WatriHelper::update_gtfs($request->gtfs);
        echo 'Shape Inserted';
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

        if ($column_name === 'shape_id') {
            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($column_name === 'shape_pt_lat') {
            $request->validate([
                'value' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/']
            ]);
        }

        if ($column_name === 'shape_pt_lon') {
            $request->validate([
                'value' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']
            ]);
        }

        if ($column_name === 'shape_pt_sequence') {
            $request->validate([
                'value' => 'required'
            ]);
        }

        Shape::where('id', $id)
            ->update([$column_name => $value]);


        echo "$column_name update";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Shape::destroy($id);
        echo 'Data Deleted';
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function get_shapes(Request $request): ?JsonResponse
    {
        $gtfs = GtfsTable::find($request->g);
        if ($request->t === csrf_token() && $gtfs->password === $request->_) {
            return response()->json($gtfs->shapes()->get());
        }

    }

    /**
     * Show the application dataAjax.
     *
     * @param Request $request
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
                $data = DB::table('shapes')
                    ->select('id', 'shape_id')
                    ->where('gtfs_id', $gtfs_id)
                    ->where('shape_id', 'LIKE', "%$search%")
                    ->get();
            }

        }


        return response()->json($data);
    }

    final public function importShapesCSV(Request $request)
    {
        $titles_allow = ['shape_id', 'shape_pt_lat', 'shape_pt_lon', 'shape_pt_sequence', 'shape_dist_traveled'];
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
            while (($column = fgetcsv($file, 10000, ',')) !== FALSE) {
                if ($title_row) {
                    foreach ($column as $key => $value) {
                        if (!in_array($value, $titles_allow, true)) {
                            return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "$value is not a trip column");
                        }
                        $title_data[$value] = $key;
                    }
                    foreach (['shape_id', 'shape_pt_lat', 'shape_pt_lon', 'shape_pt_sequence'] as $title) {
                        if (!isset($title_data[$title])) {
                            return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "$title is required");
                        }
                    }
                    $title_row = false;
                    $line++;
                    continue;
                }
                foreach (['shape_id', 'shape_pt_lat', 'shape_pt_lon', 'shape_pt_sequence'] as $title) {
                    if ($column[$title_data[$title]] === '') {
                        return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "missing $title on line $line");
                    }
                }
                $shape_exist = Shape::where('shape_id', $column[$title_data['shape_id']])->where('shape_pt_sequence', $column[$title_data['shape_pt_sequence']])->where('gtfs_id', $gtfs_id)->first();

                if (!$shape_exist) {
                    $shape = new Shape();
                    $nbr_add++;
                } else {
                    $shape = $shape_exist;
                    $nbr_upd++;
                }
                $shape->shape_id = $column[$title_data['shape_id']];
                $shape->shape_pt_lat = $column[$title_data['shape_pt_lat']];
                $shape->shape_pt_lon = $column[$title_data['shape_pt_lon']];
                $shape->shape_pt_sequence = $column[$title_data['shape_pt_sequence']];
                $shape->shape_dist_traveled = isset($title_data['shape_dist_traveled']) ? $column[$title_data['shape_dist_traveled']] : '';
                $shape->gtfs_id = $gtfs_id;
                $shape->save();
                $line++;
            }

            return back()->with('success', "$nbr_add Shapes add | $nbr_upd Shapes update");
        }
    }

    public function getMapData(Request $request)
    {

        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }



        if ($request->id==='all'){
            $shapes = DB::table('shapes')
                ->where('gtfs_id', session('gtfs_id'))
                ->select('shape_id')
                ->groupBy('shape_id')
                ->get();
        }else{
            $shapes = DB::table('shapes')
                ->where('gtfs_id', session('gtfs_id'))
                ->where('shape_id',$request->id)
                ->select('shape_id')
                ->groupBy('shape_id')
                ->get();
        }
        $shs = [];
        $shsT = [];
        $popups = [];


        foreach ($shapes as $shape) {

            $trip = Trip::where('shape_id', $shape->shape_id)->first();
             if(!$trip){
                 continue;
             }
            $route = Route::where('route_id', $trip->route_id)->first()->route_long_name;
            $direction = ((int)$trip->direction_id === 0) ? ' <span style="color: green">(To go)</span> ' : ' <span style="color: darkred">(To return) </span>';

            $distance_shape=0;
            foreach (Shape::where('shape_id', $shape->shape_id)->get() as $s) {
                $shs[] = [(float)$s->shape_pt_lon, (float)$s->shape_pt_lat];
            }
            $shsT [] = $shs;
            $popups[] = "<strong>$route $direction</strong>";
            $shs = [];
        }
        return response()->json([
            'shapes' => $shsT,
            'popups' => $popups
        ]);
    }

    public function deleteShape(Request $request)
    {

        $validatedData = $request->validate([
            'id' => 'required'
        ]);
        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }
        $shape_id = $request->input('id');

        if (Shape::where('shape_id', $shape_id)->where('gtfs_id', session('gtfs_id'))->first()) {
            $res = Shape::where('shape_id', $shape_id)->where('gtfs_id', session('gtfs_id'))->delete();
            return back()->with('success', "$shape_id deleted");
        } else {
            return back()->with('error', 'this shape not exist');
        }

    }


    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::                                                                         :*/
    /*::  This routine calculates the distance between two points (given the     :*/
    /*::  latitude/longitude of those points). It is being used to calculate     :*/
    /*::  the distance between two locations using GeoDataSource(TM) Products    :*/
    /*::                                                                         :*/
    /*::  Definitions:                                                           :*/
    /*::    South latitudes are negative, east longitudes are positive           :*/
    /*::                                                                         :*/
    /*::  Passed to function:                                                    :*/
    /*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
    /*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
    /*::    unit = the unit you desire for results                               :*/
    /*::           where: 'M' is statute miles (default)                         :*/
    /*::                  'K' is kilometers                                      :*/
    /*::                  'N' is nautical miles                                  :*/
    /*::  Worldwide cities and other features databases with latitude longitude  :*/
    /*::  are available at https://www.geodatasource.com                          :*/
    /*::                                                                         :*/
    /*::  For enquiries, please contact sales@geodatasource.com                  :*/
    /*::                                                                         :*/
    /*::  Official Web site: https://www.geodatasource.com                        :*/
    /*::                                                                         :*/
    /*::         GeoDataSource.com (C) All Rights Reserved 2018                  :*/
    /*::                                                                         :*/
    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

    public function inverse(Request $request){
        WatriHelper::inverShapeOrder($request->shape_id);
        return back()->with('success', 'Shape inverted');
    }

    final public function exportShapesCSV(): void
    {
        $shapes = Shape::select('shape_id', 'shape_pt_lat', 'shape_pt_lon', 'shape_pt_sequence', 'shape_dist_traveled')
            ->where('gtfs_id',session('gtfs_id'))
            ->get()
            ->toArray();
        WatriHelper::download_send_headers('shapes_export_' . date('Y_m_d_s') . '.csv');
        echo WatriHelper::array2csv($shapes);
        die();
    }

}

