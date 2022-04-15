<?php

namespace App\Http\Controllers\Api;

use App\Agency;
use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use App\Http\Controllers\Controller;
use App\Route;
use App\Shape;
use App\Stop;
use App\Stoptime;
use App\Trip;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class ApiController extends Controller
{

    public function all_routes(int $gtfs_id, Request $request)
    {

        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {
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

        return response()->json(['errors' => 'api_key incorrect']);


    }

    public function get_route(int $gtfs_id, string $route_id, Request $request)
    {
        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {
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
        return response()->json(['errors' => 'api_key incorrect']);

    }

    public function all_trips(int $gtfs_id, Request $request)
    {
        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {

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

                $trip->route_long_name = Route::where('route_id', $trip->route_id)->where('gtfs_id', $trip->gtfs_id)->first()->route_long_name;

                return $trip;
            });
            return response()->json($trips);
        }
        return response()->json(['errors' => 'api_key incorrect']);


    }

    public function get_trip(int $gtfs_id, string $trip_id, Request $request)
    {
        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {
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
        return response()->json(['errors' => 'api_key incorrect']);

    }

    public function all_destinations(int $gtfs_id, Request $request)
    {
        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {
            $destinations = Trip::where('gtfs_id', $gtfs_id)->get([
                'route_id',
                'service_id',
                'trip_id',
                'trip_headsign',
            ]);
            return response()->json($destinations);
        }
        return response()->json(['errors' => 'api_key incorrect']);


    }

    public function get_shapes(int $gtfs_id, Request $request)
    {
        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {
            $shapes = Shape::where('gtfs_id', $gtfs_id)->get([
                'shape_id',
                'shape_pt_lat',
                'shape_pt_lon',
                'shape_pt_sequence',
                'shape_dist_traveled',
            ]);
            return response()->json($shapes);
        }
        return response()->json(['errors' => 'api_key incorrect']);

    }

    public function get_shape(int $gtfs_id, string $shape_id, Request $request)
    {
        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {
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
        return response()->json(['errors' => 'api_key incorrect']);

    }

    public function get_shape_lat_lon(int $gtfs_id, string $shape_id, Request $request)
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

    public function all_routes_line_stops(int $gtfs_id, Request $request)
    {
        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {
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
        return response()->json(['errors' => 'api_key incorrect']);

    }

    public function get_stops(int $gtfs_id, Request $request)
    {
        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {
            $stops = Stop::where('gtfs_id', $gtfs_id)->get();


            return response()->json($stops);
        }
        return response()->json(['errors' => 'api_key incorrect']);

    }

    public function get_stop(int $gtfs_id, string $stop_id, Request $request)
    {
        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {
            $stops = Stop::where('gtfs_id', $gtfs_id)->where('stop_id', $stop_id)->get();


            return response()->json($stops);
        }
        return response()->json(['errors' => 'api_key incorrect']);

    }

    public function shapesByTrip(int $gtfs_id, string $shape_id, Request $request)
    {
        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {
            if ($shape_id === 'all') {
                $shapes = DB::table('shapes')
                    ->where('gtfs_id', $gtfs_id)
                    ->select('shape_id')
                    ->groupBy('shape_id')
                    ->get();
            } else {
                $shapes = DB::table('shapes')
                    ->where('gtfs_id', $gtfs_id)
                    ->where('shape_id', $shape_id)
                    ->select('shape_id')
                    ->groupBy('shape_id')
                    ->get();
            }
            $shs = [];
            $shsT = [];
            $popups = [];
            $withId = [];

            foreach ($shapes as $shape) {

                $trip = Trip::where('shape_id', $shape->shape_id)->where('gtfs_id', $gtfs_id)->first();
                if (!$trip) {
                    continue;
                }
                $route = Route::where('route_id', $trip->route_id)->where('gtfs_id', $gtfs_id)->first()->route_long_name;
                $direction = ((int)$trip->direction_id === 0) ? ' <span style="color: green">(To go)</span> ' : ' <span style="color: darkred">(To return) </span>';

                $distance_shape = 0;
                foreach (Shape::where('shape_id', $shape->shape_id)->where('gtfs_id', $gtfs_id)->get() as $s) {
                    $shs[] = [(float)$s->shape_pt_lon, (float)$s->shape_pt_lat];
                }
                $shsT [] = $shs;
                $popups[] = "<strong>$route $direction</strong>";
                $shs = [];
                $withId[] = [$shape->shape_id, "<strong>$route $direction</strong>"];
            }
            return response()->json([
                'shapes' => $shsT,
                'popups' => $popups,
                'withId' => $withId
            ]);
        }
        return response()->json(['errors' => 'api_key incorrect']);

    }

//      public function get_user_trip(int $gtfs_id, string $trip_id, Request $request)
//     {
//         $trip = Trip::where('gtfs_id', $gtfs_id)->where('trip_id', $trip_id)->get([
//             'route_id',
//             'service_id',
//             'trip_id',
//             'trip_headsign',
//             'trip_short_name',
//             'direction_id',
//             'block_id',
//             'shape_id',
//             'wheelchair_accessible',
//             'bikes_allowed',
//         ])->first();

//         if (isset($request->stops) and $request->stops === 'true') {
//             $stoptimes = Stoptime::where('trip_id', $trip->trip_id)->where('gtfs_id', $gtfs_id)->get();
//             $stops = [];
//             $stops_sequences = [];
//             foreach ($stoptimes as $stoptime) {
//                 $stops[] = Stop::where('stop_id', $stoptime->stop_id)->where('gtfs_id', $gtfs_id)->first();
//                 $stops_sequences[$stoptime->stop_id] = $stoptime->stop_sequence;
//             }
//             $trip->stops = $stops;
//             $trip->stopsSequence = $stops_sequences;
//         }
//         if (isset($request->user_long, $request->user_lat, $trip->stops)) {
//             $nearestStop = null;
//             $min = 10000;
//             foreach ($trip->stops as $stop) {
//                 $distance = WatriHelper::distance($request->user_lat, $request->user_long, $stop->stop_lat, $stop->stop_lon, 'K');
//                 if ($distance < $min) {
//                     $min = $distance;
//                     $nearestStop = $stop;
//                 }
//             }
// //            $response = $this->APIOpenRouteService($request, $nearestStop);
//             $response = $this->APIOSRMOsm($request, $nearestStop);

//             $user_steps = $response->routes[0]->legs[0]->steps;
// //            dd($user_steps);

// //            $user_shapes = $response->features[0]->geometry->coordinates;
// //            $trip->user_shapes = $user_shapes;
//             $trip->user_shapes = $response;
//             $trip->user_steps = $user_steps;
//             $trip->nearest_stop = $nearestStop;
//         }
//         if (isset($request->shapes) and $request->shapes === 'true') {
//             $shapes = Shape::where('shape_id', $trip->shape_id)
//                 ->where('gtfs_id', $gtfs_id)
//                 ->get()
//                 ->sortBy('shape_pt_sequence')
//                 ->toArray();
//             $result = $shapes;
//             $shapes = [];
//             foreach ($result as $v) {
//                 $shapes[] = $v;
//             }
//             $trip->shapes = $shapes;
//         }

//         if (isset($request->stoptimes) and $request->stoptimes === 'true') {
//             $trip->stoptimes = $stoptimes;
//         }

//         return response()->json($trip);
//     }

    public function get_user_trip(int $gtfs_id, Request $request)
    {

        if (isset($request->api_key) &&
            User::where('key_api', $request->api_key)->where('state', 1)->count() > 0) {
            $icResponse = null;
            if (isset($request->u_lo, $request->u_la, $request->d_lo, $request->d_la, $request->d_i)) {

                $icResponse = WatriHelper::itineraryCalculator($gtfs_id, ['long' => $request->u_lo, 'lat' => $request->u_la], ['long' => $request->d_lo, 'lat' => $request->d_la, 'stop_id' => $request->d_i]);

                // if(!$icResponse['second_turn_stop_line']){
                //     // dd($request->d_i,Stoptime::where('stop_id',$request->d_i)->get(),Stoptime::where('stop_id',$icResponse['nearest_stop']->stop_id)->get());
                //     // $trip = Trip::where('trip_id',Stoptime::where('stop_id',$request->d_i)->where('stop_id',$icResponse['nearest_stop']->stop_id)->first()->trip_id)->first();
                //     // $shapes = Shape::where('shape_id',$trip->shape_id)->get();
                //     // $icResponse['trip_destination'] = $trip;
                //     // $icResponse['shapes_destination'] = $shapes;

                // }

                // if ($icResponse['second_turn_stop'] === 'destination') {
                //     $client = new Client();
                //     $params = [
                //         'query' => [
                //             'overview' => 'false',
                //             'geometries' => 'polyline',
                //             'steps' => 'true',
                //             'hints' => '_NBwigXRcIpsBQAAAAAAAAAAAAAAAAAAY9QKQwAAAAAAAAAAAAAAAFYEAAAAAAAAAAAAAAAAAAAUAAAAdDuG_0kQwQB0O4b_SRDBAAAADxBwpHVf%3BfO9wihHwcIpjAQAAKAAAAAAAAAAAAAAAkSrjQUXgRkAAAAAAAAAAAOMAAAAaAAAAAAAAAAAAAAAUAAAAdouG_xMwwQCQi4b_KTDBAAAAvwhwpHVf',


                //         ]
                //     ];
                //     $first_turn_stop_lon = $icResponse['first_turn_stop']->stop_lon;
                //     $first_turn_stop_lat = $icResponse['first_turn_stop']->stop_lat;
                //     $url = "https://routing.openstreetmap.de/routed-foot/route/v1/driving/$first_turn_stop_lon,$first_turn_stop_lat;$request->d_lo,$request->d_la";
                //     $body = $client->get((string)$url, $params)->getBody();
                //     $response_2=json_decode($body, false);
                //     $icResponse['first_turn_stop_to_destination'] = $response_2;
                // }

                if (isset($icResponse['second_turn_stop'])) {
                    $client = new Client();
                    $params = [
                        'query' => [
                            'overview' => 'false',
                            'geometries' => 'polyline',
                            'steps' => 'true',
                            'hints' => '_NBwigXRcIpsBQAAAAAAAAAAAAAAAAAAY9QKQwAAAAAAAAAAAAAAAFYEAAAAAAAAAAAAAAAAAAAUAAAAdDuG_0kQwQB0O4b_SRDBAAAADxBwpHVf%3BfO9wihHwcIpjAQAAKAAAAAAAAAAAAAAAkSrjQUXgRkAAAAAAAAAAAOMAAAAaAAAAAAAAAAAAAAAUAAAAdouG_xMwwQCQi4b_KTDBAAAAvwhwpHVf',


                        ]
                    ];
                    $first_turn_stop_lon = $icResponse['first_turn_stop']->stop_lon;
                    $first_turn_stop_lat = $icResponse['first_turn_stop']->stop_lat;

                    $second_turn_stop_lon = $icResponse['second_turn_stop']->stop_lon;
                    $second_turn_stop_lat = $icResponse['second_turn_stop']->stop_lat;

                    $url = "https://routing.openstreetmap.de/routed-foot/route/v1/driving/$first_turn_stop_lon,$first_turn_stop_lat;$second_turn_stop_lon,$second_turn_stop_lat";
                    $body = $client->get((string)$url, $params)->getBody();
                    $response_2 = json_decode($body, false);
                    $icResponse['first_turn_stop_to_second_turn_stop'] = $response_2;
                    $icResponse['user_steps2'] = $response_2->routes[0]->legs[0]->steps;

                }


                $response = $this->APIOSRMOsm($request, $icResponse['nearest_stop']);

                $icResponse['user_shapes'] = $response;
                $icResponse['user_steps'] = $response->routes[0]->legs[0]->steps;

            }


            return response()->json($icResponse);
        }
        return response()->json(['errors' => 'api_key incorrect']);
    }

    final public function APIOpenRouteService(Request $request, Stop $nearestStop)
    {

        $client = new Client();
        $params = [
            'query' => [
                'api_key' => '5b3ce3597851110001cf6248f6e9acb519f64d3c83456f951e4b7555',
                'start' => $request->user_long . ',' . $request->user_lat,
                'end' => $nearestStop->stop_lon . ',' . $nearestStop->stop_lat
            ]
        ];
        $url = 'https://api.openrouteservice.org/v2/directions/driving-car';

        $body = $client->get((string)$url, $params)->getBody();
        return json_decode($body, false);
    }

//     public function APIOSRMOsm(Request $request, Stop $nearestStop){
//         $client = new Client();
//         $params = [
//             'query' => [
//                 'overview' => 'false',
//                 'geometries' => 'polyline',
//                 'steps' => 'true',
//                 'hints' => '_NBwigXRcIpsBQAAAAAAAAAAAAAAAAAAY9QKQwAAAAAAAAAAAAAAAFYEAAAAAAAAAAAAAAAAAAAUAAAAdDuG_0kQwQB0O4b_SRDBAAAADxBwpHVf%3BfO9wihHwcIpjAQAAKAAAAAAAAAAAAAAAkSrjQUXgRkAAAAAAAAAAAOMAAAAaAAAAAAAAAAAAAAAUAAAAdouG_xMwwQCQi4b_KTDBAAAAvwhwpHVf',


//             ]
//         ];
//         $url = "https://routing.openstreetmap.de/routed-foot/route/v1/driving/$request->user_long,$request->user_lat;$nearestStop->stop_lon,$nearestStop->stop_lat";
//         $body = $client->get((string)$url, $params)->getBody();
// //        dd(json_decode($body,false));
//         return json_decode($body,false);

//     }

    public function APIOSRMOsm(Request $request, Stop $nearestStop)
    {
        $client = new Client();
        $params = [
            'query' => [
                'overview' => 'false',
                'geometries' => 'polyline',
                'steps' => 'true',
                'hints' => '_NBwigXRcIpsBQAAAAAAAAAAAAAAAAAAY9QKQwAAAAAAAAAAAAAAAFYEAAAAAAAAAAAAAAAAAAAUAAAAdDuG_0kQwQB0O4b_SRDBAAAADxBwpHVf%3BfO9wihHwcIpjAQAAKAAAAAAAAAAAAAAAkSrjQUXgRkAAAAAAAAAAAOMAAAAaAAAAAAAAAAAAAAAUAAAAdouG_xMwwQCQi4b_KTDBAAAAvwhwpHVf',


            ]
        ];
        $url = "https://routing.openstreetmap.de/routed-foot/route/v1/driving/$request->u_lo,$request->u_la;$nearestStop->stop_lon,$nearestStop->stop_lat";
        $body = $client->get((string)$url, $params)->getBody();

        return json_decode($body, false);

    }

}
