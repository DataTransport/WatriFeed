<?php
/**
 * Created by PhpStorm.
 * User: applehouse
 * Date: 29/01/2019
 * Time: 00:52
 */

namespace App\helpers;


use App\Calendar;
use App\Gtfs as GtfsTable;
use App\Route;
use App\Shape;
use App\Stop;
use App\Stoptime;
use App\Trip;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;


class WatriHelper
{
    public static $v = 5;

    /**
     * @param string $name
     * @param string $path
     * @return string
     */
    public static function folderCreator(string $name, string $path = 'unzip'): string
    {
        $path .= '/' . $name . '/';
        $a = 1;
        $folder = '';
        while ($a) {
            if (!file_exists("$path$a")) {
                if (!mkdir("$path$a", 0777, true) && !is_dir("$path$a")) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', "$path$a"));
                }
                $folder = "$path$a";
                $a = 0;
                continue;
            }
            $a++;
        }
        return $folder;
    }


    public static function deleteDir(string $dirPath): void
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if ($dirPath[strlen($dirPath) - 1] !== '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
//        rmdir($dirPath);
    }

    public static function overpass($options)
    {
        // overpass query
// $overpass = 'http://overpass-api.de/api/interpreter?data=[out:json];area(3600046663)->.searchArea;(node["amenity"="drinking_water"](area.searchArea););out;';

        $overpass = 'http://overpass-api.de/api/interpreter?data=[out:json];area(3600192781)-%3E.searchArea;(node[%22public_transport%22=%22platform%22][%22bus%22=%22yes%22](area.searchArea);node[%22highway%22=%22bus_stop%22][%22public_transport%22!~%22stop_position%22](area.searchArea););out;%3E;out%20skel%20qt;';


// collecting results in JSON format
        $html = file_get_contents($overpass);
        $result = json_decode($html, true); // "true" to get PHP array instead of an object

// elements key contains the array of all required elements
        $data = $result['elements'];

        foreach ($data as $key => $row) {

            // latitude
            $lat = $row['lat'];

            // longitude
            $lng = $row['lon'];
        }

        return $data[2800];

    }

    /**
     * @param $string
     * @return string
     */
    public static function initial_id(string $string): string
    {
        $words = explode(' ', $string);
        $acronym = '';

        foreach ($words as $w) {
            $acronym .= $w[0];
        }
        return strtoupper($acronym);
    }

    public static function update_gtfs(string $id): void
    {
        $name = GtfsTable::find($id)->name;
        GtfsTable::where('name', $name)
            ->update(['name' => $name]);
    }

    public static function serviceIdToString(string $id): string
    {
        $calendar = Calendar::where('service_id', $id)
            ->where('gtfs_id', session('gtfs_id'))
            ->first();

        if ($calendar) {
            $string = '';
            $string .= $calendar->monday ? 'Monday' : '';
            $string .= $calendar->tuesday ? '-Tuesday' : '';
            $string .= $calendar->wednesday ? '-Wednesday' : '';
            $string .= $calendar->thursday ? '-Thursday' : '';
            $string .= $calendar->friday ? '-Friday' : '';
            $string .= $calendar->saturday ? '-Saturday' : '';
            $string .= $calendar->sunday ? '-Sunday' : '';
            $string = $string === 'Monday-Tuesday-Wednesday-Thursday-Friday-Saturday-Sunday' ? 'Everyday' : $string;

            return $string;
        }
        return '';

    }

    public static function addStoptimesAndStopToTrip(Trip $trip): Trip
    {


//        $stoptimes = Stoptime::all()->where('trip_id', $trip->trip_id)
//            ->where('gtfs_id', session('gtfs_id'))
//            ->sortBy('stop_sequence');

        $stoptimes = Stoptime::where('trip_id', $trip->trip_id)->get()
            ->where('gtfs_id', session('gtfs_id'))
            ->sortBy('stop_sequence');

        $stoptimes->map(static function ($stoptime) {
            $stop = Stop::where('stop_id', $stoptime->stop_id)
                ->where('gtfs_id', session('gtfs_id'))->first();
            if ($stop) {
                $stoptime['stop_name'] = $stop->stop_name;
            }

            return $stoptime;
        });


        $trip->stoptimes = $stoptimes;
        $stops = [];
        $stops_sequences = [];

        foreach ($trip->stoptimes as $stoptime) {
            $stop = Stop::where('stop_id', $stoptime->stop_id)
                ->where('gtfs_id', session('gtfs_id'))
                ->first();
            if ($stop) {
                $stops[] = $stop;
                $stops_sequences[$stoptime->stop_id] = $stoptime->stop_sequence;
            }

        }

        $trip->stops = $stops;
        $trip->stopSequence = $stops_sequences;

        return $trip;

    }

    public static function array2csv(array $array)
    {
        if (count($array) === 0) {
            return null;
        }
        ob_start();
        $df = fopen('php://output', 'wb');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }

    public static function download_send_headers($filename)
    {
        // disable caching
        $now = gmdate('D, d M Y H:i:s');
        header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
        header('Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate');
        header("Last-Modified: {$now} GMT");

        // force download
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/download');

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header('Content-Transfer-Encoding: binary');

        header('Content-Type: text/csv');
    }

    public static function getShapeRoute($shape_id)
    {
        $trip = Trip::where('shape_id', $shape_id)->first();
        if ($trip) {
            return Route::where('route_id', $trip->route_id)->first();
        }
    }

    /**
     * @param $stops_id
     * @param $trip_id
     * @param int $time_depart
     * @param int $time_interval
     * @return array|RedirectResponse|Redirector
     */
    public static function generateStopTimes(array $stops, string $trip_id, string $time_depart = '', string $time_interval = '', bool $interval_time_on = false, int $speed = 50): array
    {
        $gtfs_id = session('gtfs_id');
        $results = [];
        $nbr_add = 0;
        $nbr_upd = 0;
        $trip_exist = Trip::where('trip_id', $trip_id)->where('gtfs_id', $gtfs_id)->first();
        if (!$trip_exist && $stops->count() === 0) {
            return redirect("/gtfs/$gtfs_id/edit")
                ->with('error', 'Check your informations');
        }

        $previousStop = null;
        $sequence = 0;
        foreach ($stops as $stop) {
            if (!$interval_time_on) {
                if ($previousStop) {
                    $secs = strtotime(self::timeBeetween($previousStop['latitude'], $previousStop['longitude'], $stop['latitude'], $stop['longitude'], $speed)) - strtotime('00:00:00');
                    $time_depart = date('H:i:s', strtotime($time_depart) + $secs);
                }
                $previousStop = $stop;
            }
            $sequence++;
            $stoptime_exist = Stoptime::where('trip_id', $trip_id)
                ->where('stop_id', $stop['id'])
                ->where('gtfs_id', $gtfs_id)
                ->first();
            if (!$stoptime_exist) {
                $stoptime = new Stoptime();
                $stoptime->stop_sequence = $sequence;
                $stoptime->trip_id = $trip_id;
                if ($time_interval !== '' && $time_depart !== '') {
                    $stoptime->arrival_time = $time_depart;
                    $stoptime->departure_time = $time_depart;
                }
                $stoptime->stop_id = $stop['id'];
                $stoptime->gtfs_id = $gtfs_id;
                $stoptime->save();
                $nbr_add++;

                if ($time_interval !== '' && $time_depart !== '' && $interval_time_on) {
                    $secs = strtotime($time_interval) - strtotime('00:00:00');
                    $time_depart = date('H:i:s', strtotime($time_depart) + $secs);

                }
            }
        }
        return ['nbr_add' => $nbr_add];
    }

    public static function sortByNearestLatLong($geoData, $lat, $long, $returnNearestOnly = true)
    {
        // CREATE AN ARRAY FOR USE INSIDE THE FUNCTION
        $arrCloseMatchLat = array();
        $arrCloseMatchLong = array();
        $matchedGeoSet = array();

        // LOOP THROUGH ALL THE $geoData ARRAY AND SUBTRACT THE GIVEN LAT & LONG VALUES
        // FROM THOSE CONTAINED IN THE ORIGINAL ARRAY: $geoData
        // WE KNOW THAT THE SMALLER THE RESULT OF THE SUBTRACTION; THE CLOSER WE ARE
        // WE DO THIS FOR BOTH THE LONGITUDE & LATITUDE... HENCE OUR ARRAY:
        // $arrCloseMatchLat AND $arrCloseMatchLong RESPECTIVELY
        foreach ($geoData as $iKey => $arrGeoStrip) {
            $arrCloseMatchLat[$iKey] = abs((float)(($arrGeoStrip['latitude']) - $lat));
            $arrCloseMatchLong[$iKey] = abs((float)(($arrGeoStrip['longitude']) - $long));
        }


        // WE SORT BOTH ARRAYS NUMERICALLY KEEPING THE KEYS WHICH WE NEED FOR OUR FINAL RESULT
        asort($arrCloseMatchLat, SORT_NUMERIC);
        asort($arrCloseMatchLong, SORT_NUMERIC);

        // WE CAN RETURN ONLY THE RESULT OF THE FIRST, CLOSEST MATCH
        if ($returnNearestOnly) {
            foreach ($arrCloseMatchLat as $index => $difference) {
                $matchedGeoSet['latitudes'][] = $geoData[$index];
                break;
            }
            foreach ($arrCloseMatchLong as $index => $difference) {
                $matchedGeoSet['longitudes'][] = $geoData[$index];
                break;
            }
            // OR WE CAN RETURN THE ENTIRE $geoData ARRAY ONLY SORTED IN A "CLOSEST FIRST" FASHION...
            // WE DO THIS FOR BOTH THE LONGITUDE & LATITUDE RESPECTIVELY SO WE END UP HAVING 2
            // ARRAYS: ONE THAT SORTS THE CLOSEST IN TERMS OF LONG VALUES
            // AN ONE THAT SORTS THE CLOSEST IN TERMS OF LAT VALUES...
        } else {
            foreach ($arrCloseMatchLat as $index => $difference) {
                $matchedGeoSet['latitudes'][] = $geoData[$index];
            }
            foreach ($arrCloseMatchLong as $index => $difference) {
                $matchedGeoSet['longitudes'][] = $geoData[$index];
            }
        }
        return $matchedGeoSet;
    }

    public static function inverShapeOrder(string $shape_id)
    {
        $shape = Shape::where('shape_id', $shape_id)->where('gtfs_id', session('gtfs_id'))->get();
        $shape_sequences = [];
        $shape_ids = [];
        foreach ($shape->toArray() as $s) {
            $shape_sequences[] = $s['shape_pt_sequence'];
            $shape_ids[] = $s['id'];
        }
        $i = 0;
        foreach ($shape_ids as $id) {
            Shape::where('id', $id)->update(array('shape_pt_sequence' => array_reverse($shape_sequences)[$i++]));
        }
    }

    public static function distance(float $lat1, float $lon1, float $lat2, float $lon2, string $unit)
    {
        if (($lat1 === $lat2) && ($lon1 === $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit === 'K') {
                return ($miles * 1.609344);
            } else if ($unit === 'N') {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

    public static function timeBeetween(float $lat1, float $lon1, float $lat2, float $lon2, int $speed = 50): string
    {
        $distance = self::distance((float)$lat1, (float)$lon1, (float)$lat2, (float)$lon2, 'K') * 1000;
        $time = $distance / (($speed * 1000) / 3600);
        $time = date('H:i:s', $time);
        return $time;
    }

    public static function re_calc_time($trip_id, $speed = 15)
    {

        $stoptimes = Stoptime::all()->where('trip_id', $trip_id)
            ->where('gtfs_id', session('gtfs_id'))
            ->sortBy('stop_sequence');


        $previousStop = null;
        $time_depart = $stoptimes->first()->arrival_time;

        foreach ($stoptimes as $stoptime) {


            $stop = Stop::where('stop_id', $stoptime->stop_id)->where('gtfs_id', session('gtfs_id'))->first();

            if ($previousStop) {
                $secs = strtotime(self::timeBeetween((float)$previousStop->stop_lat, (float)$previousStop->stop_lon, (float)$stop->stop_lat, (float)$stop->stop_lon, $speed)) - strtotime('00:00:00');
                $time_depart = date('H:i:s', strtotime($time_depart) + $secs);

                $stoptime->arrival_time = $time_depart;
                $stoptime->departure_time = $time_depart;
                $stoptime->save();


            }
            $previousStop = $stop;
        }


    }


    public static function itineraryCalculator(int $gtfs_id,array $user_point, array $destination_stop): array
    {
        $stoptimes = Stoptime::all(['trip_id', 'stop_id']);
        $nearest_stop = self::nearest_stop(Stop::where('gtfs_id', $gtfs_id)->get(), $user_point)['nearest_stop'];

        $responses = ['nearest_stop' => $nearest_stop,];

        $nearest_stop_lines = [];
        $s1 = [];
        $s2 = [];
        foreach ($stoptimes->where('stop_id', $nearest_stop->stop_id) as $stoptime) {
            foreach ($stoptimes->where('trip_id', $stoptime->trip_id) as $stime) {
                $nearest_stop_lines[$stime->stop_id] = $stoptime->trip_id;
                $s1 [] = $stime->stop_id;
            }
        }
        $nearest_stop_lines_stops = Stop::where('gtfs_id', $gtfs_id)->get()->whereIn('stop_id', $s1);
        $destination_stop_lines = [];
        foreach ($stoptimes->where('stop_id', $destination_stop['stop_id']) as $stoptime) {
            foreach ($stoptimes->where('trip_id', $stoptime->trip_id) as $stime) {
                $destination_stop_lines[$stime->stop_id] = $stoptime->trip_id;
                $s2 [] = $stime->stop_id;
            }
        }
        $destination_stop_lines_stops = Stop::where('gtfs_id', $gtfs_id)->get()->whereIn('stop_id', $s2);
        $response = null;
        $min = 2000;
        foreach ($destination_stop_lines_stops as $destination_stop_lines_stop) {
            $r = self::nearest_stop($nearest_stop_lines_stops, ['long' => $destination_stop_lines_stop->stop_lon, 'lat' => $destination_stop_lines_stop->stop_lat, 'stop_id' => $destination_stop_lines_stop->stop_id]);
            if ($r['distance'] < 2000 && $min > $r['distance']) {
                $response = $r;
                $min = $r['distance'];
            }
        }

        if ($response['nearest_stop']) {
            $responses['first_turn_stop'] = $response['nearest_stop'];
            $responses['first_turn_stop_line'] = Trip::where('trip_id', $nearest_stop_lines[$response['nearest_stop']->stop_id])->first();
            $responses['first_turn_stop_line_shapes'] = Shape::where('shape_id', $responses['first_turn_stop_line']->shape_id)->get();

            $stops = [];
            $stopsSequences1 = [];
            foreach (Stoptime::where('trip_id', $responses['first_turn_stop_line']->trip_id)->orderBy('stop_sequence', 'asc')->get() as $stoptime) {
                $stops[] = Stop::where('stop_id', $stoptime->stop_id)->first();
                $stopsSequences1[$stoptime->stop_id] = $stoptime->stop_sequence;
            }
            $responses['nearest_stops'] = $stops;
            $responses['stopsSequences1'] = $stopsSequences1;

        } else {
            $first_turn_stop_line = null;
        }

        if ($response['stop']['stop_id']) {
            $responses['second_turn_stop'] = Stop::where('stop_id', $response['stop']['stop_id'])->first();
            $responses['second_turn_stop_line'] = Trip::where('trip_id', $destination_stop_lines[$response['stop']['stop_id']])->first();
            if ($responses['first_turn_stop_line']->id !== $responses['second_turn_stop_line']->id) {
                $responses['second_turn_stop_line_shapes'] = Shape::where('shape_id', $responses['second_turn_stop_line']->shape_id)->get();
                $stops = [];
                $stopsSequences2 = [];
                foreach (Stoptime::where('trip_id', $responses['second_turn_stop_line']->trip_id)->orderBy('stop_sequence', 'asc')->get() as $stoptime) {
                    $stops[] = Stop::where('stop_id', $stoptime->stop_id)->first();
                    $stopsSequences2[$stoptime->stop_id] = $stoptime->stop_sequence;
                }
                $responses['destination_stops'] = $stops;
                $responses['stopsSequences2'] = $stopsSequences2;
            }else{
                $responses['second_turn_stop_line_shapes'] = null;
                $responses['destination_stops'] = null;
                $responses['stopsSequences2'] = null;
                $responses['second_turn_stop']= null;
                $responses['second_turn_stop_line'] = null;

            }

        } else {
            $second_turn_stop_line = null;
        }

        return $responses;


    }

    public static function nearest_stop(Collection $group_stops, array $user_point): array
    {
        $nearest_stop = null;
        $start_stop = null;
        $min = 2000;
        foreach ($group_stops as $stop) {
            $distance = self::distance($user_point['lat'], $user_point['long'], $stop->stop_lat, $stop->stop_lon, 'K') * 1000;
            $distance += $distance * 30 / 100;
            if (($distance < 2000) && $min > $distance) {
                $min = $distance;
                $nearest_stop = $stop;
                $start_stop = $user_point;
            }
        }
        return ['stop' => $start_stop, 'nearest_stop' => $nearest_stop, 'distance' => $min];
    }
}
