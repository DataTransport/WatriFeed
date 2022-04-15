<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use App\Route;
use App\Shape;
use App\Stop;
use App\Stoptime;
use App\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function all_routes(int $gtfs_id)
    {
        $routes = Route::where('gtfs_id', $gtfs_id)->get([
            'route_id',
            'agency_id',
            'route_short_name',
            'route_long_name',
            'route_desc',
            'route_type',
            'route_url',
            'route_color',
            'route_text_color',
            'route_sort_order',
        ]);
        return response()->json($routes);
    }

    public function get_route(int $gtfs_id, string $route_id, Request $request)
    {
        $route = Route::where('gtfs_id', $gtfs_id)->where('route_id', $route_id)->first([
            'route_id',
            'agency_id',
            'route_short_name',
            'route_long_name',
            'route_desc',
            'route_type',
            'route_url',
            'route_color',
            'route_text_color',
            'route_sort_order',
        ]);

        if (isset($request->trips) and $request->trips == "true") {
            $ts = Trip::where('gtfs_id', $gtfs_id)->where('route_id', $route_id)->get()->sortBy('direction_id');
            $trips = [];
            foreach ($ts as $trip) {
                $trips[] = $trip;
            }
            $trips = collect([$trips[0], $trips[1]]);

            $route->trips = $trips;
        }
        if (isset($request->stops) and $request->stops === 'true') {
            $trips->map(function ($trip) {
                $stoptimes = Stoptime::where('trip_id', $trip->trip_id)->where('gtfs_id', $trip->gtfs_id)->get();
                $stops = [];
                foreach ($stoptimes as $stoptime) {
                    $stops[] = Stop::where('stop_id', $stoptime->stop_id)->where('gtfs_id', $trip->gtfs_id)->first();
                }
                $trip->stops = $stops;
                return $trip;
            });
        }
        return response()->json($route);
    }

    public function all_trips(int $gtfs_id)
    {
        $trips = Trip::where('gtfs_id', $gtfs_id)->get([
            'route_id',
            'service_id',
            'trip_id',
            'trip_headsign',
            'trip_short_name',
            'direction_id',
            'block_id',
            'shape_id',
            'wheelchair_accessible',
            'bikes_allowed',
            'gtfs_id'
        ]);
        $trips->map(function ($trip) {
                
                $trip->route_long_name = Route::where('route_id',$trip->route_id)->where('gtfs_id',$trip->gtfs_id)->first()->route_long_name;
            
                return $trip;
            });
        
        return response()->json($trips);
    }

    public function get_trip(int $gtfs_id, string $trip_id, Request $request)
    {
        $trip = Trip::where('gtfs_id', $gtfs_id)->where('trip_id', $trip_id)->get([
            'route_id',
            'service_id',
            'trip_id',
            'trip_headsign',
            'trip_short_name',
            'direction_id',
            'block_id',
            'shape_id',
            'wheelchair_accessible',
            'bikes_allowed',
        ])->first();

        if (isset($request->stops) and $request->stops === 'true') {
            $stoptimes = Stoptime::where('trip_id', $trip->trip_id)->where('gtfs_id', $gtfs_id)->get();
            $stops = [];
            $stops_sequences = [];
            foreach ($stoptimes as $stoptime) {
                $stops[] = Stop::where('stop_id', $stoptime->stop_id)->where('gtfs_id', $gtfs_id)->first();
                $stops_sequences[$stoptime->stop_id] = $stoptime->stop_sequence;
            }
            $trip->stops = $stops;
            $trip->stopsSequence = $stops_sequences;
        }
        if (isset($request->shapes) and $request->shapes === 'true') {
            $shapes = Shape::where('shape_id', $trip->shape_id)
                ->where('gtfs_id', $gtfs_id)
                ->get()
                ->sortBy('shape_pt_sequence')
                ->toArray();
            $result = $shapes;
            $shapes = [];
            foreach ($result as $v) {
                $shapes[] = $v;
            }
            $trip->shapes = $shapes;
        }

        if (isset($request->stoptimes) and $request->stoptimes === 'true') {
            $trip->stoptimes = $stoptimes;
        }

        return response()->json($trip);
    }

    public function all_destinations(int $gtfs_id)
    {
        $destinations = Trip::where('gtfs_id', $gtfs_id)->get([
            'route_id',
            'service_id',
            'trip_id',
            'trip_headsign',
        ]);
        return response()->json($destinations);
    }

    public function get_shapes(int $gtfs_id)
    {
        $shapes = Shape::where('gtfs_id', $gtfs_id)->get([
            'shape_id',
            'shape_pt_lat',
            'shape_pt_lon',
            'shape_pt_sequence',
            'shape_dist_traveled',
        ]);
        return response()->json($shapes);
    }

    public function get_shape(int $gtfs_id, string $shape_id)
    {
        $shapes = Shape::where('gtfs_id', $gtfs_id)->where('shape_id', $shape_id)->get([
            'shape_id',
            'shape_pt_lat',
            'shape_pt_lon',
            'shape_pt_sequence',
            'shape_dist_traveled',
        ]);
        $sh = [];
        foreach ($shapes as $shape) {
            $sh[] = $shape;
        }

        return response()->json($sh);
    }

    public function get_shape_lat_lon(int $gtfs_id, string $shape_id)
    {
        $shape = Shape::where('gtfs_id', $gtfs_id)->where('shape_id', $shape_id)->get([
            'shape_pt_lat',
            'shape_pt_lon',
        ]);
        $sh = [];
        foreach ($shape as $s) {
            $sh[] = [(float)$s->shape_pt_lat, (float)$s->shape_pt_lon];
        }
        return $sh;
    }

    public function all_routes_line_stops(int $gtfs_id)
    {
        $routes = Route::where('gtfs_id', $gtfs_id)->orderBy('route_long_name')->get([
            'route_id',
            'agency_id',
            'route_short_name',
            'route_long_name',
            'route_desc',
            'route_type',
            'route_url',
            'route_color',
            'route_text_color',
            'route_sort_order',
        ]);
        $response = [];
        $i = 1;
        foreach ($routes as $route) {
            // $response[]="Line $i".explode(':',$route->route_long_name)[1];
            $response[] = $route->route_long_name;
            $i++;
        }
        return $response;
    }
    
    public function get_stops(int $gtfs_id)
    {
        $stops = Stop::where('gtfs_id', $gtfs_id)->get();
        
        
        return response()->json($stops);
    }
    
    public function get_stop(int $gtfs_id, string $stop_id)
    {
        $stops = Stop::where('gtfs_id', $gtfs_id)->where('stop_id', $stop_id)->get();
        
        
        return response()->json($stops);
    }

}
