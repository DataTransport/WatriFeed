<?php

namespace App\Http\Controllers;

use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use App\Route;
use App\Shape;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
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

        if ($request->t === csrf_token() && $gtfs->password === $request->_ && $request->a === 'true') {

            return response()->json($gtfs->trips()->get());
        }


        $trips = $gtfs->trips()->get();
        $routes = $gtfs->routes()->get();
        $shapes = $gtfs->shapes()->get();

        return view('gtfs.partials.trips', compact('trips', 'routes', 'shapes', 'gtfs'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view("trips");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'trip_id' => 'required|unique:trips',
            'service_id' => 'required',
            'route_id' => 'required'
        ]);

        $gtfs_id = session('gtfs_id');

        $trip = new Trip();

        $trip->trip_id = $request->trip_id;
        $trip->service_id = $request->service_id;
        $trip->route_id = $request->route_id;
        $trip->trip_headsign = $request->trip_headsign;
        $trip->trip_short_name = $request->trip_short_name;
        $trip->direction_id = $request->direction_id;
        $trip->block_id = $request->block_id;
        $trip->shape_id = $request->shape_id;
        $trip->wheelchair_accessible = $request->wheelchair_accessible;
        $trip->bikes_allowed = $request->bikes_allowed;

        $trip->gtfs_id = $gtfs_id;

        $trip->save();

        WatriHelper::update_gtfs($gtfs_id);
        echo 'Trip Inserted';
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
        if ($validate_column_name === 'service_id') {
            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'route_id') {

            $request->validate([
                'value' => 'required'
            ]);
        }


        Trip::where('id', $id)
            ->update([$column_name => $value]);

        $gtfs = Trip::find($id)->gtfs_id;
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
            'service_id' => 'required',
            'route_id' => 'required'
        ]);

        $gtfs_id = session('gtfs_id');


        $trip = Trip::find($id);

        $trip->trip_id = $request->trip_id;
        $trip->service_id = $request->service_id;
        $trip->route_id = $request->route_id;
        $trip->trip_headsign = $request->trip_headsign;
        $trip->trip_short_name = $request->trip_short_name;
        $trip->direction_id = $request->direction_id;
        $trip->block_id = $request->block_id;
        $trip->shape_id = $request->shape_id;
        $trip->wheelchair_accessible = $request->wheelchair_accessible;
        $trip->bikes_allowed = $request->bikes_allowed;

        $trip->gtfs_id = $gtfs_id;

        $trip->save();

        WatriHelper::update_gtfs($gtfs_id);
        echo 'Trip update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Trip::destroy($id);
        echo 'Trip Deleted';
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
                $data = DB::table('trips')
                    ->select('id', 'trip_id')
                    ->where('gtfs_id', $gtfs_id)
                    ->where('trip_id', 'LIKE', "%$search%")
                    ->get();
            }

        }


        return response()->json($data);
    }

    public function visualisation(Request $request)
    {
        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }

        $route = Route::find($request->route);
        $trip = WatriHelper::addStoptimesAndStopToTrip(Trip::find($request->trip));

        $trip->routeColor = '#' . Route::find($request->route)->route_color;

        session(['trip' => $trip]);

        return view('gtfs.visualisations.trip', compact('route', 'trip'));

    }

    public function getMapData(Request $request)
    {

        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }

        $trip = session('trip');

        $shapes = Shape::where('shape_id', $trip->shape_id)
            ->where('gtfs_id',session('gtfs_id'))
            ->get()
            ->sortBy('shape_pt_sequence')
            ->toArray();
        $result = $shapes;
        $shapes=[];
        foreach ($result as $v){
            $shapes[]=$v;
        }

//        dd($shapes);
        return response()->json(['stops' => $trip->stops,
            'shapes' => $shapes,
            'routeColor' => $trip->routeColor,
            'stopSequence' => $trip->stopSequence]);
    }

    final public function importTripsCSV(Request $request)
    {
        $titles_allow = ['route_id', 'service_id', 'trip_id', 'trip_headsign', 'trip_short_name', 'direction_id', 'block_id', 'shape_id',
            'wheelchair_accessible', 'bikes_allowed'];

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
                            return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "$value is not a trip column");
                        }
                        $title_data[$value] = $key;
                    }
                    $title_row = false;
                    $line++;
                    continue;
                }
                if (!isset($title_data['route_id'])) {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', 'route_id is required');
                }
                if (!isset($title_data['service_id'])) {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', 'service_id is required');
                }
                if (!isset($title_data['trip_id'])) {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', 'trip_id is required');
                }
                if ($column[$title_data['route_id']] === '') {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "missing route_id on line $line");
                }

                if ($column[$title_data['service_id']] === '') {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "missing service_id on line $line");
                }

                if ($column[$title_data['trip_id']] === '') {
                    return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "missing trip_id on line $line");
                }


                $gtfs_id = session('gtfs_id');
                $trip_exist = Trip::where('trip_id', $column[$title_data['trip_id']])->where('gtfs_id', $gtfs_id)->first();
                if (!$trip_exist) {
                    $trip = new Trip();
                    $nbr_add++;
                } else {
                    $trip = $trip_exist;
                    $nbr_upd++;
                }

                $trip->route_id = $column[$title_data['route_id']];
                $trip->service_id = $column[$title_data['service_id']];
                $trip->trip_id = $column[$title_data['trip_id']];
                $trip->trip_headsign = isset($title_data['trip_headsign']) ? $column[$title_data['trip_headsign']] : '';
                $trip->trip_short_name = isset($title_data['trip_short_name']) ? $column[$title_data['trip_short_name']] : '';
                $trip->direction_id = isset($title_data['direction_id']) ? $column[$title_data['direction_id']] : '';
                $trip->block_id = isset($title_data['block_id']) ? $column[$title_data['block_id']] : '';
                $trip->shape_id = isset($title_data['shape_id']) ? $column[$title_data['shape_id']] : '';
                $trip->wheelchair_accessible = isset($title_data['wheelchair_accessible']) ? $column[$title_data['wheelchair_accessible']] : '';
                $trip->bikes_allowed = isset($title_data['bikes_allowed']) ? $column[$title_data['bikes_allowed']] : '';
                $trip->gtfs_id = $gtfs_id;
                $trip->save();
                $line++;
            }
            return back()->with('success', "$nbr_add Trips add | $nbr_upd Trips update");
        }
    }

    final public function exportTripsCSV(){

        $trips = Trip::select('route_id','service_id', 'trip_id', 'trip_headsign', 'trip_short_name', 'direction_id', 'block_id', 'shape_id', 'wheelchair_accessible', 'bikes_allowed')
            ->where('gtfs_id',session('gtfs_id'))
            ->get()
            ->toArray();
        WatriHelper::download_send_headers('trips_export_' . date('Y-m-d') . '.csv');
        echo WatriHelper::array2csv($trips);
        die();
    }

}

