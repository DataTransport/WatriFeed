<?php

namespace App\Http\Controllers;

use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use App\Route;
use App\Stop;
use App\Stoptime;
use App\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RouteController extends Controller
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

        $routes = $gtfs->routes()->get();
        $agencies = $gtfs->agencies()->get();
        return view('gtfs.partials.routes', compact('routes', 'gtfs', 'agencies'));

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
            'route_id' => 'required',
            'route_short_name' => 'required',
            'route_long_name' => 'required',
            'route_type' => 'required'
        ]);

        $route = new Route();

        $route->route_id = $request->route_id;
        $route->agency_id = $request->agency_id;
        $route->route_short_name = $request->route_short_name;
        $route->route_long_name = $request->route_long_name;
        $route->route_desc = $request->route_desc;
        $route->route_type = $request->route_type;
        $route->route_url = $request->route_url;
        $route->route_color = $request->route_color;
        $route->route_text_color = $request->route_text_color;
        $route->route_sort_order = $request->route_sort_order;

        $route->gtfs_id = $request->gtfs;

        $route->save();

        WatriHelper::update_gtfs($request->gtfs);
        echo 'Route Inserted';

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

        if ($validate_column_name === 'route_id') {
            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'route_short_name') {
            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'route_long_name') {

            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'route_type') {

            $request->validate([
                'value' => 'required'
            ]);
        }

        Route::where('id', $id)
            ->update([$column_name => $value]);

        $gtfs = Route::find($id)->gtfs_id;
        WatriHelper::update_gtfs($gtfs);

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
        Route::destroy($id);
        echo 'Route Deleted';

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function get_routes(Request $request): ?JsonResponse
    {
        $gtfs = Gtfs::find($request->g);
        if ($request->t === csrf_token() && $gtfs->password === $request->_) {
            return response()->json($gtfs->routes()->get());
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
                $data = DB::table('routes')
                    ->select('id', 'route_id')
                    ->where('gtfs_id', $gtfs_id)
                    ->where('route_id', 'LIKE', "%$search%")
                    ->get();
            }

        }


        return response()->json($data);
    }

    public function visualisation(Request $request){
        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }
        $route = Route::find($request->id);
        $trips = Trip::where('route_id', $route->route_id)
            ->where('gtfs_id', session('gtfs_id'))
            ->get();
        $trips->map(static function ($trip) {
            $trip = WatriHelper::addStoptimesAndStopToTrip($trip);
            return $trip;
        });


        return view('gtfs.visualisations.route', compact('route', 'trips'));
    }

    final public function importRoutesCSV(Request $request){
        $titles_allow = ['route_id', 'agency_id', 'route_short_name', 'route_long_name', 'route_desc', 'route_type', 'route_url', 'route_color',
            'route_text_color', 'route_sort_order'];

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
                            return redirect()->route('gtfs.edit',['gtfs'=>session('gtfs_id')])->with('error', "$value is not a route column");
                        }
                        $title_data[$value] = $key;
                    }
                    $title_row = false;
                    $line++;
                    continue;
                }
                if (!isset($title_data['route_id'])){
                    return redirect()->route('gtfs.edit',['gtfs'=>session('gtfs_id')])->with('error', 'route_id is required');
                }
                if (!isset($title_data['route_type'])){
                    return redirect()->route('gtfs.edit',['gtfs'=>session('gtfs_id')])->with('error', 'route_type is required');
                }
                if ($column[$title_data['route_id']]===''){
                    return redirect()->route('gtfs.edit',['gtfs'=>session('gtfs_id')])->with('error', "missing route_id on line $line");
                }

                if ($column[$title_data['route_type']]===''){
                    return redirect()->route('gtfs.edit',['gtfs'=>session('gtfs_id')])->with('error', "missing route_type on line $line");
                }

                $gtfs_id = session('gtfs_id');
                $route_exist = Route::where('route_id', $column[$title_data['route_id']])->where('gtfs_id', $gtfs_id)->first();
                if (!$route_exist) {
                    $route = new Route();
                    $nbr_add++;
                } else {
                    $route = $route_exist;
                    $nbr_upd++;
                }
                $route->route_id = $column[$title_data['route_id']];
                $route->agency_id = isset($title_data['agency_id']) ? $column[$title_data['agency_id']] : '';
                $route->route_short_name = isset($title_data['route_short_name']) ? $column[$title_data['route_short_name']] : '';
                $route->route_long_name = isset($title_data['route_long_name']) ? $column[$title_data['route_long_name']] : '';
                $route->route_desc = isset($title_data['route_desc']) ? $column[$title_data['route_desc']] : '';
                $route->route_type = isset($title_data['route_type']) ? $column[$title_data['route_type']] : '';
                $route->route_url = isset($title_data['route_url']) ? $column[$title_data['route_url']] : '';
                $route->route_color = isset($title_data['route_color']) ? $column[$title_data['route_color']] : '';
                $route->route_text_color = isset($title_data['route_text_color']) ? $column[$title_data['route_text_color']] : '';
                $route->route_sort_order = isset($title_data['route_sort_order']) ? $column[$title_data['route_sort_order']] : '';
                $route->gtfs_id = $gtfs_id;
                $route->save();
                $line++;
            }
            return back()->with('success', "$nbr_add Route add | $nbr_upd Route update");
        }
    }

    final public function exportRoutesCSV(){

        $routes = Route::all(['route_id','agency_id', 'route_short_name', 'route_long_name', 'route_desc', 'route_type', 'route_url', 'route_color', 'route_text_color', 'route_sort_order'])->toArray();
        WatriHelper::download_send_headers('routes_export_' . date('Y-m-d') . '.csv');
        echo WatriHelper::array2csv($routes);
        die();
    }


}

