<?php

namespace App\Http\Controllers;

use App\helpers\WatriHelper;
use App\Route;
use App\Shape;
use App\Stop;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtraController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    final public function importByGeoJson(Request $request)
    {
        $gtfs_id = session('gtfs_id');
        $fileName = request('file')->getRealPath();
        $time_depart = $request->input('time_depart');
        $time_interval = $request->input('time_interval');
        $interval_time_on = $request->input('interval_time_on') ? true : false;
        $speed = $request->input('speed') ?? 20;
        $string = file_get_contents($fileName);
        $json_a = json_decode($string, true);
        $direction_id = $request->trip_direction ?? 0;

        $d = [];
        $ref_latitude = $request->input('ref_latitude') ?? 12.6503426;
        $ref_longitude = $request->input('ref_longitude') ?? -7.9925432;
        $shape = [];
        $sortOrder = true;
        if ($ref_latitude !== 12.6503426 && $ref_longitude !== -7.9925432) {
            $sortOrder = false;
        }
        $i = 0;

        $stopsUnSort = [];
        $stopsSort = [];
        foreach ($json_a['features'] as $feature) {
            if (isset($feature['properties']['highway']) && $feature['properties']['highway'] === 'bus_stop' && explode('/', trim($feature['properties']['@id']), 2)[0] === 'node') {
                $d['stops'][$i]['properties'] = $feature['properties'];
                $d['stops'][$i]['coordinates'] = $feature['geometry']['coordinates'];
                $stopsUnSort[] = ['id' => explode('/', $feature['properties']['@id'])[1], 'latitude' => $feature['geometry']['coordinates'][1], 'longitude' => $feature['geometry']['coordinates'][0], 'stopname' => $feature['properties']['name']];
                $i++;
            }
            if (explode('/', trim($feature['properties']['@id']), 2)[0] === 'relation') {
                $shape = $feature['geometry']['coordinates'];
            }
        }

        if ($request->input('by_latlon') === 'latitudes') {
            $stopsSort = WatriHelper::sortByNearestLatLong($stopsUnSort, (float)$ref_latitude, (float)$ref_longitude, false)['latitudes'];
        }

        if ($request->input('by_latlon') === 'longitudes') {
            $stopsSort = WatriHelper::sortByNearestLatLong($stopsUnSort, (float)$ref_latitude, (float)$ref_longitude, false)['longitudes'];
        }

        if ($sortOrder && $request->trip_direction) {
            $stopsSort = array_reverse($stopsSort);
        }

        $d['shape'] = $shape;
        $route_info = $json_a['features'][0]['properties'];

        /**
         * ===============================================================================================
         * ===============================================================================================
         *                                          Import Route
         * ===============================================================================================
         * ===============================================================================================
         */

        $nbr_add_r = 0;
        $nbr_upd_r = 0;
        if ($request->prefix_route) {
            $prefix = $request->prefix_route;
            $last_id = $route_info['ref'];
        } else {
            $prefix = '';
            $last_id = 'RI' . time();
        }
        $route_id = "$prefix.L$last_id";
        $route_exist = Route::where('route_id', $route_id)->where('gtfs_id', $gtfs_id)->first();
        if (!$route_exist) {
            $route = new Route();
            $nbr_add_r++;
            $route->route_id = $route_id;
            $route->route_long_name = $route_info['name'];

        } else {
            $route = $route_exist;
            $nbr_upd_r++;
        }
        if ($route_info['route'] === 'bus') {
            $route_type = 3;
        }
        $route->route_short_name = $route_info['ref'];
        $route->route_type = $route_type ?? 0;
        $route->gtfs_id = $gtfs_id;
        $route->save();


        /**
         * ===============================================================================================
         * ===============================================================================================
         *                        End Import Route
         * ===============================================================================================
         * ===============================================================================================
         */


        /**
         * ===============================================================================================
         * ===============================================================================================
         *                         Import Shapes
         * ===============================================================================================
         * ===============================================================================================
         */

        //Delete Shapes
        $trip_exist = Trip::where('direction_id', $direction_id)->where('route_id', $route_id)->where('gtfs_id', $gtfs_id)->first();
        if ($trip_exist){
            $shapes = Shape::where('gtfs_id', $gtfs_id)->where('shape_id', $trip_exist->shape_id)->delete();
        }
        $nbr_add_sh = 0;
        $nbr_upd_sh = 0;
        $shape_id = 'SHI' . time();
        $shape_pt_sequence = 0;
        if ($request->trip_direction) {
            $shapes__ = $d['shape'];
        } else {
            $shapes__ = array_reverse($d['shape']);
        }
        foreach ($shapes__ as $sh) {
            if (is_array($sh[0]) || is_array($sh[1])) {
                return redirect("/gtfs/$gtfs_id/edit")->with('error', 'Incorrect geojson file | error Multiline');
            }
            $shape_pt_sequence++;
            $shape = new Shape();
            $nbr_add_sh++;
            $shape->shape_id = $shape_id;
            $shape->shape_pt_lat = $sh[1];
            $shape->shape_pt_lon = $sh[0];
            $shape->shape_pt_sequence = $shape_pt_sequence;
            $shape->gtfs_id = $gtfs_id;
            $shape->save();
        }



        /**
         * ===============================================================================================
         * ===============================================================================================
         *                        End Import Shapes
         * ===============================================================================================
         * ===============================================================================================
         */

        /**
         * ===============================================================================================
         * ===============================================================================================
         *                        Import Trips
         * ===============================================================================================
         * ===============================================================================================
         */

        $trip_id = time() - 1000;
        $trip_id++;
        $nbr_add_t = 0;
        $nbr_upd_t = 0;

        if (!$trip_exist) {
            $trip = new Trip();
            $nbr_add_t++;
            $trip->trip_id = sprintf('TI%d', $trip_id);
        } else {
            $trip = $trip_exist;
            $nbr_upd_t++;
        }
        $trip->route_id = $route_id;
        $trip->trip_headsign = $route_info['to'];
        $trip->service_id = 0;
        $trip->direction_id = $direction_id;
        $trip->shape_id = $shape->shape_id;
        $trip->gtfs_id = $gtfs_id;
        $trip->save();

        /**
         * ===============================================================================================
         * ===============================================================================================
         *                        End Import Trips
         * ===============================================================================================
         * ===============================================================================================
         */

        /**
         * ===============================================================================================
         * ===============================================================================================
         *                        Import Stops
         * ===============================================================================================
         * ===============================================================================================
         */

        $nbr_add_s = 0;
        $nbr_upd_s = 0;
        $stops_id = [];
        foreach (array_reverse($d['stops']) as $s) {
            $stop_id = explode('/', $s['properties']['@id'])[1];
            $stop_exist = Stop::where('stop_id', $stop_id)->where('gtfs_id', $gtfs_id)->first();
            if (!$stop_exist) {
                $stop = new Stop();
                $nbr_add_s++;
                $stop->stop_id = $stop_id;
            } else {
                $stop = $stop_exist;
                $nbr_upd_s++;
            }
            $stops_id[] = (int)$stop->stop_id;
            $stop->stop_name = $s['properties']['name'];
            $stop->stop_desc = $s['properties']['name'];
            $stop->stop_lat = $s['coordinates'][1];
            $stop->stop_lon = $s['coordinates'][0];
            $stop->gtfs_id = $gtfs_id;
            $stop->save();
        }
        sort($stops_id);
//        dd($stops_id);
        /**
         * ===============================================================================================
         * ===============================================================================================
         *                        End Import Stops
         * ===============================================================================================
         * ===============================================================================================
         */


        /**
         * ===============================================================================================
         * ===============================================================================================
         *                        Import StopTimes
         * ===============================================================================================
         * ===============================================================================================
         */
        $nbr_add_st = 0;
        if ($time_depart && $time_interval) {
            $depart = $time_depart;
            $result = WatriHelper::generateStopTimes($stopsSort, $trip->trip_id, $depart, $time_interval, $interval_time_on, (int)$speed);
            $nbr_add_st += $result['nbr_add'];
        } else {
            $result = WatriHelper::generateStopTimes($stopsSort, $trip->trip_id);
            $nbr_add_st += $result['nbr_add'];
        }

        /**
         * ===============================================================================================
         * ===============================================================================================
         *                        End Import StopTimes
         * ===============================================================================================
         * ===============================================================================================
         */

        return redirect("/gtfs/$gtfs_id/edit")->with('success', "
        $nbr_add_s Stops add | $nbr_upd_s Stops update |
        $nbr_add_r Route add | $nbr_upd_r Route update |
        $nbr_add_t Trip add | $nbr_upd_t Trip update |
        $nbr_add_sh Shapes add | $nbr_upd_sh Shapes update|
        $nbr_add_st Stoptimes add |
        ");


    }
}
