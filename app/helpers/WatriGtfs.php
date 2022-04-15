<?php
/**
 * Created by PhpStorm.
 * User: applehouse
 * Date: 28/01/2019
 * Time: 23:56
 */

namespace App\helpers;

use App\Agency;
use App\Calendar;
use App\CalendarDate;
use App\FareAttribute;
use App\FareRule;
use App\Frequency;
use App\Level;
use App\Pathway;
use App\Route as RouteGtfs;
use App\Shape;
use App\Stop;
use App\Stoptime;
use App\Transfer;
use App\Trip;

class WatriGtfs
{

    private $pathGtfs;
    private $pathGtfsUnzip;
    private $agency;
    private $calendar;
    private $calendar_date;
    private $fare_attributes;
    private $fare_rules;
    private $frequencies;
    private $levels;
    private $pathways;
    private $routes;
    private $shapes;
    private $stop_times;
    private $stops;
    private $transfers;
    private $trips;


    /**
     * WatriGtfs constructor.
     * @param $pathGtfs
     * @param $pathGtfsUnzip
     */
    public function __construct($pathGtfs, $pathGtfsUnzip)
    {
        $this->pathGtfs = $pathGtfs;

        $this->pathGtfsUnzip = $pathGtfsUnzip;
    }

    final public static function exportGtfs(string $name): void
    {

        $zipname = 'gtfs_' . $name . '.zip';
        rename('gtfs.zip', $zipname);
        $zip = new \ZipArchive;
        if ($zip->open($zipname, \ZipArchive::OVERWRITE) === TRUE) {
            // Add files to the zip file
            $zip->addFile('agency.txt');
            $zip->addFile('stops.txt');
            $zip->addFile('routes.txt');
            $zip->addFile('trips.txt');
            $zip->addFile('stop_times.txt');
            $zip->addFile('calendar.txt');
            $zip->addFile('calendar_dates.txt');
            $zip->addFile('fare_attributes.txt');
            $zip->addFile('fare_rules.txt');
            $zip->addFile('shapes.txt');
            $zip->addFile('frequencies.txt');
            $zip->addFile('transfers.txt');
            $zip->addFile('pathways.txt');
            $zip->addFile('levels.txt');

            // All files are added, so close the zip file.
            $zip->close();
        }

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
        rename($zipname, 'gtfs.zip');


    }

    final public static function backUpGtfs($name)
    {

        $zipname = 'gtfs_backup_' . $name . '.zip';
        rename('gtfs_backup.zip', $zipname);
        $zip = new \ZipArchive;
        if ($zip->open($zipname, \ZipArchive::OVERWRITE) === TRUE) {
            // Add files to the zip file
            $zip->addFile('agency.txt');
            $zip->addFile('stops.txt');
            $zip->addFile('routes.txt');
            $zip->addFile('trips.txt');
            $zip->addFile('stop_times.txt');
            $zip->addFile('calendar.txt');
            $zip->addFile('calendar_dates.txt');
            $zip->addFile('fare_attributes.txt');
            $zip->addFile('fare_rules.txt');
            $zip->addFile('shapes.txt');
            $zip->addFile('frequencies.txt');
            $zip->addFile('transfers.txt');
            $zip->addFile('gtfs.txt');

            // All files are added, so close the zip file.
            $zip->close();
        }

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
        rename($zipname, 'gtfs_backup.zip');


    }

    final static function saveGtfsElements($watri_gtfs, $id)
    {

        if ($watri_gtfs->getAgency()) {
            foreach ($watri_gtfs->getAgency() as $agency) {
                $agency['gtfs_id'] = $id;
                Agency::create(WatriGtfs::formatGtfsAgency($agency));
            }
        }

        if ($watri_gtfs->getCalendar()) {
            foreach ($watri_gtfs->getCalendar() as $calendar) {
                $calendar['gtfs_id'] = $id;
                Calendar::create(WatriGtfs::formatGtfsCalendar($calendar));
            }
        }

        if ($watri_gtfs->getCalendarDate()) {
            foreach ($watri_gtfs->getCalendarDate() as $calendarDate) {
                $calendarDate['gtfs_id'] = $id;
                CalendarDate::create(WatriGtfs::formatGtfsCalendarDate($calendarDate));
            }
        }

        if ($watri_gtfs->getFareAttributes()) {
            foreach ($watri_gtfs->getFareAttributes() as $fareAttribute) {
                $fareAttribute['gtfs_id'] = $id;
                FareAttribute::create(WatriGtfs::formatGtfsFareAttribute($fareAttribute));
            }
        }

        if ($watri_gtfs->getFareRules()) {
            foreach ($watri_gtfs->getFareRules() as $fare_rule) {
                $fare_rule['gtfs_id'] = $id;
                FareRule::create(WatriGtfs::formatGtfsFareRules($fare_rule));
            }
        }

        if ($watri_gtfs->getFrequencies()) {
            foreach ($watri_gtfs->getFrequencies() as $frequency) {
                $frequency['gtfs_id'] = $id;
                Frequency::create(WatriGtfs::formatGtfsFrequencies($frequency));
            }
        }

        if ($watri_gtfs->getRoutes()) {
            foreach ($watri_gtfs->getRoutes() as $route) {
                $route['gtfs_id'] = $id;
                RouteGtfs::create(WatriGtfs::formatGtfsRoute($route));
            }
        }

        if ($watri_gtfs->getShapes()) {
            foreach ($watri_gtfs->getShapes() as $shape) {
                $shape['gtfs_id'] = $id;
                Shape::create(WatriGtfs::formatGtfsShape($shape));

            }
        }

        if ($watri_gtfs->getStops()) {
            foreach ($watri_gtfs->getStops() as $stop) {
                $stop['gtfs_id'] = $id;
                Stop::create(WatriGtfs::formatGtfsStop($stop));
            }
        }

        if ($watri_gtfs->getStopTimes()) {
            foreach ($watri_gtfs->getStopTimes() as $stopTime) {
                $stopTime['gtfs_id'] = $id;
                Stoptime::create(WatriGtfs::formatGtfsStopTime($stopTime));
            }
        }

        if ($watri_gtfs->getTransfers()) {
            foreach ($watri_gtfs->getTransfers() as $transfer) {
                $transfer['gtfs_id'] = $id;
                Transfer::create(WatriGtfs::formatGtfsTransfer($transfer));
            }
        }
        if ($watri_gtfs->getTrips()) {
            foreach ($watri_gtfs->getTrips() as $trip) {
                $trip['gtfs_id'] = $id;
                Trip::create(WatriGtfs::formatGtfsTrip($trip));
            }
        }
        if ($watri_gtfs->getPathways()) {
            foreach ($watri_gtfs->getPathways() as $pathway) {
                $pathway['gtfs_id'] = $id;
                Pathway::create(WatriGtfs::formatGtfsPathways($pathway));
            }
        }
        if ($watri_gtfs->getLevels()) {
            foreach ($watri_gtfs->getLevels() as $level) {
                $level['gtfs_id'] = $id;
                Level::create(WatriGtfs::formatGtfsLevels($level));
            }
        }

    }

    final public static function formatGtfsAgency(array $agencyData, $update = 0): array
    {
        if ($update === 1) {
            $agencyData['id'] = $agencyData['id'] ?? '';
        }
        $agencyData['agency_id'] = $agencyData['agency_id'] ?? '';
        $agencyData['agency_name'] = $agencyData['agency_name'] ?? '';
        $agencyData['agency_url'] = $agencyData['agency_url'] ?? '';
        $agencyData['agency_timezone'] = $agencyData['agency_timezone'] ?? '';
        $agencyData['agency_lang'] = $agencyData['agency_lang'] ?? '';
        $agencyData['agency_phone'] = $agencyData['agency_phone'] ?? '';
        $agencyData['agency_fare_url'] = $agencyData['agency_fare_url'] ?? '';
        $agencyData['agency_email'] = $agencyData['agency_email'] ?? '';
        $agencyData['gtfs_id'] = $agencyData['gtfs_id'] ?? '';
        return $agencyData;
    }

    final public static function formatGtfsCalendar(array $calendarData, $update = 0): array
    {
        if ($update === 1) {
            $calendarData['id'] = $calendarData['id'] ?? '';
        }
        $calendarData['service_id'] = $calendarData['service_id'] ?? '';
        $calendarData['monday'] = $calendarData['monday'] ?? '';
        $calendarData['tuesday'] = $calendarData['tuesday'] ?? '';
        $calendarData['wednesday'] = $calendarData['wednesday'] ?? '';
        $calendarData['thursday'] = $calendarData['thursday'] ?? '';
        $calendarData['friday'] = $calendarData['friday'] ?? '';
        $calendarData['saturday'] = $calendarData['saturday'] ?? '';
        $calendarData['sunday'] = $calendarData['sunday'] ?? '';
        $calendarData['start_date'] = $calendarData['start_date'] ?? '';
        $calendarData['end_date'] = $calendarData['end_date'] ?? '';
        $calendarData['gtfs_id'] = $calendarData['gtfs_id'] ?? '';
        return $calendarData;
    }

    /**
     * @param array $gtfs
     * @param int $update
     * @return mixed
     */
    final public static function formatGtfsCalendarDate(array $calendarDateData, $update = 0): array
    {
        if ($update === 1) {
            $calendarDateData['id'] = $calendarDateData['id'] ?? '';
        }
        $calendarDateData['service_id'] = $calendarDateData['exception_type'] ?? '';
        $calendarDateData['date'] = $calendarDateData['date'] ?? '';
        $calendarDateData['exception_type'] = $calendarDateData['exception_type'] ?? '';
        $calendarDateData['gtfs_id'] = $calendarDateData['gtfs_id'] ?? '';
        return $calendarDateData;
    }

    final public static function formatGtfsFareAttribute(array $fareAttributeData, $update = 0): array
    {
        if ($update === 1) {
            $fareAttributeData['id'] = $fareAttributeData['id'] ?? '';
        }
        $fareAttributeData['fare_id'] = $fareAttributeData['fare_id'] ?? '';
        $fareAttributeData['price'] = $fareAttributeData['price'] ?? '';
        $fareAttributeData['currency_type'] = $fareAttributeData['currency_type'] ?? '';
        $fareAttributeData['payment_method'] = $fareAttributeData['payment_method'] ?? '';
        $fareAttributeData['transfers'] = $fareAttributeData['transfers'] ?? '';
        $fareAttributeData['agency_id'] = $fareAttributeData['agency_id'] ?? '';
        $fareAttributeData['transfers_duration'] = $fareAttributeData['transfers_duration'] ?? '';
        $fareAttributeData['gtfs_id'] = $fareAttributeData['gtfs_id'] ?? '';
        return $fareAttributeData;
    }

    final public static function formatGtfsFareRules(array $fareRuleData, $update = 0): array
    {
        if ($update === 1) {
            $fareRuleData['id'] = $fareRuleData['id'] ?? '';
        }
        $fareRuleData['fare_id'] = $fareRuleData['fare_id'] ?? '';
        $fareRuleData['route_id'] = $fareRuleData['route_id'] ?? '';
        $fareRuleData['origin_id'] = $fareRuleData['origin_id'] ?? '';
        $fareRuleData['destination_id'] = $fareRuleData['destination_id'] ?? '';
        $fareRuleData['contains_id'] = $fareRuleData['contains_id'] ?? '';
        $fareRuleData['gtfs_id'] = $fareRuleData['gtfs_id'] ?? '';
        return $fareRuleData;
    }

    final public static function formatGtfsFrequencies(array $frequenciesData, $update = 0): array
    {
        if ($update === 1) {
            $frequenciesData['id'] = $frequenciesData['id'] ?? '';
        }
        $frequenciesData['trip_id'] = $frequenciesData['trip_id'] ?? '';
        $frequenciesData['start_time'] = $frequenciesData['start_time'] ?? '';
        $frequenciesData['end_time'] = $frequenciesData['end_time'] ?? '';
        $frequenciesData['headway_secs'] = $frequenciesData['headway_secs'] ?? '';
        $frequenciesData['exact_times'] = $frequenciesData['exact_times'] ?? '';
        $frequenciesData['gtfs_id'] = $frequenciesData['gtfs_id'] ?? '';
        return $frequenciesData;
    }

    final public static function formatGtfsRoute(array $routeData, $update = 0): array
    {
        if ($update === 1) {
            $routeData['id'] = $routeData['id'] ?? '';
        }
        $routeData['route_id'] = $routeData['route_id'] ?? '';
        $routeData['agency_id'] = $routeData['agency_id'] ?? '';
        $routeData['route_short_name'] = $routeData['route_short_name'] ?? '';
        $routeData['route_long_name'] = $routeData['route_long_name'] ?? '';
        $routeData['route_desc'] = $routeData['route_desc'] ?? '';
        $routeData['route_type'] = $routeData['route_type'] ?? '';
        $routeData['route_url'] = $routeData['route_url'] ?? '';
        $routeData['route_color'] = $routeData['route_color'] ?? '';
        $routeData['route_text_color'] = $routeData['route_text_color'] ?? '';
        $routeData['route_sort_order'] = $routeData['route_sort_order'] ?? '';
        $routeData['gtfs_id'] = $routeData['gtfs_id'] ?? '';
        return $routeData;
    }

    /**
     * @param array $gtfs
     * @param int $update
     * @return array
     */
    final public static function formatGtfsShape(array $shapeData, $update = 0): array
    {
        if ($update === 1) {
            $shapeData['id'] = $shapeData['id'] ?? '';
        }
        $shapeData['shape_id'] = $shapeData['shape_id'] ?? '';
        $shapeData['shape_pt_lat'] = $shapeData['shape_pt_lat'] ?? '';
        $shapeData['shape_pt_lon'] = $shapeData['shape_pt_lon'] ?? '';
        $shapeData['shape_pt_sequence'] = $shapeData['shape_pt_sequence'] ?? '';
        $shapeData['shape_dist_traveled'] = $shapeData['shape_dist_traveled'] ?? '';
        $shapeData['gtfs_id'] = $shapeData['gtfs_id'] ?? '';
        return $shapeData;
    }

    final public static function formatGtfsStop(array $stopData, $update = 0): array
    {
        if ($update === 1) {
            $stopData['id'] = $stopData['id'] ?? '';
        }
        $stopData['stop_id'] = $stopData['stop_id'] ?? '';
        $stopData['stop_code'] = $stopData['stop_code'] ?? '';
        $stopData['stop_name'] = $stopData['stop_name'] ?? '';
        $stopData['stop_desc'] = $stopData['stop_desc'] ?? '';
        $stopData['stop_lat'] = $stopData['stop_lat'] ?? '';
        $stopData['stop_lon'] = $stopData['stop_lon'] ?? '';
        $stopData['zone_id'] = $stopData['zone_id'] ?? 0;
        $stopData['stop_url'] = $stopData['stop_url'] ?? '';
        $stopData['location_type'] = $stopData['location_type'] ?? '';
        $stopData['parent_station'] = $stopData['parent_station'] ?? '';
        $stopData['stop_timezone'] = $stopData['stop_timezone'] ?? '';
        $stopData['wheelchair_boarding'] = $stopData['wheelchair_boarding'] ?? '';
        $stopData['level_id'] = $stopData['level_id'] ?? '';
        $stopData['platform_code'] = $stopData['platform_code'] ?? '';
        $stopData['gtfs_id'] = $stopData['gtfs_id'] ?? '';
        return $stopData;
    }

    final public static function formatGtfsStopTime(array $stopTimeData, $update = 0): array
    {
        if ($update === 1) {
            $stopTimeData['id'] = $stopTimeData['id'] ?? '';
        }
        $stopTimeData['trip_id'] = $stopTimeData['trip_id'] ?? '';
        $stopTimeData['arrival_time'] = $stopTimeData['arrival_time'] ?? '';
        $stopTimeData['departure_time'] = $stopTimeData['departure_time'] ?? '';
        $stopTimeData['stop_id'] = $stopTimeData['stop_id'] ?? '';
        $stopTimeData['stop_sequence'] = $stopTimeData['stop_sequence'] ?? '';
        $stopTimeData['stop_headsign'] = $stopTimeData['stop_headsign'] ?? '';
        $stopTimeData['pickup_type'] = $stopTimeData['pickup_type'] ?? '';
        $stopTimeData['drop_off_type'] = $stopTimeData['drop_off_type'] ?? '';
        $stopTimeData['shape_dist_traveled'] = $stopTimeData['shape_dist_traveled'] ?? '';
        $stopTimeData['timepoint'] = $stopTimeData['timepoint'] ?? '';
        $stopTimeData['gtfs_id'] = $stopTimeData['gtfs_id'] ?? '';
        return $stopTimeData;
    }

    final public static function formatGtfsTransfer(array $transferData, $update = 0): array
    {
        if ($update === 1) {
            $transferData['id'] = $transferData['id'] ?? '';
        }
        $transferData['id'] = $transferData['id'] ?? '';
        $transferData['from_stop_id'] = $transferData['from_stop_id'] ?? '';
        $transferData['to_stop_id'] = $transferData['to_stop_id'] ?? '';
        $transferData['transfer_type'] = $transferData['transfer_type'] ?? '';
        $transferData['min_transfer_time'] = $transferData['min_transfer_time'] ?? '';
        $transferData['gtfs_id'] = $transferData['gtfs_id'] ?? '';
        return $transferData;
    }

    /**
     * @param array $gtfs
     * @param int $update
     * @return array
     */
    final public static function formatGtfsTrip(array $tripData, $update = 0): array
    {
        if ($update === 1) {
            $tripData['id'] = $tripData['id'] ?? '';
        }
        $tripData['route_id'] = $tripData['route_id'] ?? '';
        $tripData['service_id'] = $tripData['service_id'] ?? '';
        $tripData['trip_id'] = $tripData['trip_id'] ?? '';
        $tripData['trip_headsign'] = $tripData['trip_headsign'] ?? '';
        $tripData['trip_short_name'] = $tripData['trip_short_name'] ?? '';
        $tripData['direction_id'] = $tripData['direction_id'] ?? '';
        $tripData['block_id'] = $tripData['block_id'] ?? '';
        $tripData['shape_id'] = $tripData['shape_id'] ?? '';
        $tripData['wheelchair_accessible'] = $tripData['wheelchair_accessible'] ?? '';
        $tripData['bikes_allowed'] = $tripData['bikes_allowed'] ?? '';
        $tripData['gtfs_id'] = $tripData['gtfs_id'] ?? '';
        return $tripData;
    }

    /**
     * @param array $pathwayData
     * @param int $update
     * @return mixed
     */
    final public static function formatGtfsPathways(array $pathwayData, $update = 0): array
    {
        if ($update === 1) {
            $pathwayData['id'] = $pathwayData['id'] ?? '';
        }
        $pathwayData['pathway_id'] = $pathwayData['pathway_id'] ?? '';
        $pathwayData['from_stop_id'] = $pathwayData['from_stop_id'] ?? '';
        $pathwayData['to_stop_id'] = $pathwayData['to_stop_id'] ?? '';
        $pathwayData['pathway_mode'] = $pathwayData['pathway_mode'] ?? '';
        $pathwayData['is_bidirectional'] = $pathwayData['is_bidirectional'] ?? '';
        $pathwayData['length'] = $pathwayData['length'] ?? '';
        $pathwayData['traversal_time'] = $pathwayData['traversal_time'] ?? '';
        $pathwayData['stair_count'] = $pathwayData['stair_count'] ?? '';
        $pathwayData['max_slope'] = $pathwayData['max_slope'] ?? '';
        $pathwayData['min_width'] = $pathwayData['min_width'] ?? '';
        $pathwayData['signposted_as'] = $pathwayData['signposted_as'] ?? '';
        $pathwayData['reversed_signposted_as'] = $pathwayData['reversed_signposted_as'] ?? '';
        $pathwayData['gtfs_id'] = $pathwayData['gtfs_id'] ?? '';
        return $pathwayData;
    }

    /**
     * @param array $gtfs
     * @param int $update
     * @return mixed
     */
    final public static function formatGtfsLevels(array $levelsData, $update = 0): array
    {
        if ($update === 1) {
            $levelsData['id'] = $levelsData['id'] ?? '';
        }
        $levelsData['level_id'] = $levelsData['level_id'] ?? '';
        $levelsData['level_index'] = $levelsData['level_index'] ?? '';
        $levelsData['level_name'] = $levelsData['level_name'] ?? '';
        $levelsData['gtfs_id'] = $levelsData['gtfs_id'] ?? '';
        return $levelsData;
    }

    /**
     * @return mixed
     */
    public function getPathGtfs()
    {
        return $this->pathGtfs;
    }

    /**
     * @param mixed $pathGtfs
     * @return WatriGtfs
     */
    public function setPathGtfs($pathGtfs)
    {
        $this->pathGtfs = $pathGtfs;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPathGtfsUnzip()
    {
        return $this->pathGtfsUnzip;
    }

    /**
     * @param mixed $pathGtfsUnzip
     * @return WatriGtfs
     */
    public function setPathGtfsUnzip($pathGtfsUnzip)
    {
        $this->pathGtfsUnzip = $pathGtfsUnzip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAgency()
    {
        return $this->agency;
    }

    /**
     * @param mixed $agency
     * @return WatriGtfs
     */
    public function setAgency($agency)
    {
        $this->agency = $agency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param mixed $calendar
     * @return WatriGtfs
     */
    public function setCalendar($calendar)
    {
        $this->calendar = $calendar;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCalendarDate()
    {
        return $this->calendar_date;
    }

    /**
     * @param mixed $calendar_date
     * @return WatriGtfs
     */
    public function setCalendarDate($calendar_date)
    {
        $this->calendar_date = $calendar_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFareAttributes()
    {
        return $this->fare_attributes;
    }

    /**
     * @param mixed $fare_attributes
     * @return WatriGtfs
     */
    public function setFareAttributes($fare_attributes)
    {
        $this->fare_attributes = $fare_attributes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFareRules()
    {
        return $this->fare_rules;
    }

    /**
     * @param mixed $fare_rules
     * @return WatriGtfs
     */
    public function setFareRules($fare_rules)
    {
        $this->fare_rules = $fare_rules;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrequencies()
    {
        return $this->frequencies;
    }

    /**
     * @param mixed $frequencies
     * @return WatriGtfs
     */
    public function setFrequencies($frequencies)
    {
        $this->frequencies = $frequencies;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param mixed $routes
     * @return WatriGtfs
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShapes()
    {
        return $this->shapes;
    }

    /**
     * @param mixed $shapes
     * @return WatriGtfs
     */
    public function setShapes($shapes)
    {
        $this->shapes = $shapes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStopTimes()
    {
        return $this->stop_times;
    }

    /**
     * @param mixed $stop_times
     * @return WatriGtfs
     */
    public function setStopTimes($stop_times)
    {
        $this->stop_times = $stop_times;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStops()
    {
        return $this->stops;
    }

    /**
     * @param mixed $stops
     * @return WatriGtfs
     */
    public function setStops($stops)
    {
        $this->stops = $stops;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransfers()
    {
        return $this->transfers;
    }

    /**
     * @param mixed $transfers
     * @return WatriGtfs
     */
    public function setTransfers($transfers)
    {
        $this->transfers = $transfers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrips()
    {
        return $this->trips;
    }

    /**
     * @param mixed $trips
     * @return WatriGtfs
     */
    public function setTrips($trips)
    {
        $this->trips = $trips;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLevels()
    {
        return $this->levels;
    }

    /**
     * @param mixed $levels
     * @return WatriGtfs
     */
    public function setLevels($levels)
    {
        $this->levels = $levels;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPathways()
    {
        return $this->pathways;
    }

    /**
     * @param mixed $pathways
     * @return WatriGtfs
     */
    public function setPathways($pathways)
    {
        $this->pathways = $pathways;
        return $this;
    }

    public function gtfsToArray()
    {
        $this->unzipGtfs();
        $path = $this->pathGtfsUnzip;
        $gtfs = [
            'agencies' => $this->fileToArray("$path/agency"),
            'calendar_dates' => $this->fileToArray("$path/calendar_dates"),
            'calendars' => $this->fileToArray("$path/calendar"),
            'fare_attributes' => $this->fileToArray("$path/fare_attributes"),
            'fare_rules' => $this->fileToArray("$path/fare_rules"),
            'frequencies' => $this->fileToArray("$path/frequencies"),
            'levels' => $this->fileToArray("$path/levels"),
            'pathways' => $this->fileToArray("$path/pathways"),
            'routes' => $this->fileToArray("$path/routes"),
            'shapes' => $this->fileToArray("$path/shapes"),
            'stops' => $this->fileToArray("$path/stops"),
            'stop_times' => $this->fileToArray("$path/stop_times"),
            'transfers' => $this->fileToArray("$path/transfers"),
            'trips' => $this->fileToArray("$path/trips"),
        ];

        $this->setAgency($this->fileToArray("$path/agency"))
            ->setCalendar($this->fileToArray("$path/calendar"))
            ->setCalendarDate($this->fileToArray("$path/calendar_dates"))
            ->setFareAttributes($this->fileToArray("$path/fare_attributes"))
            ->setFareRules($this->fileToArray("$path/fare_rules"))
            ->setFrequencies($this->fileToArray("$path/frequencies"))
            ->setLevels($this->fileToArray("$path/levels"))
            ->setPathways($this->fileToArray("$path/pathways"))
            ->setRoutes($this->fileToArray("$path/routes"))
            ->setShapes($this->fileToArray("$path/shapes"))
            ->setStopTimes($this->fileToArray("$path/stop_times"))
            ->setStops($this->fileToArray("$path/stops"))
            ->setTransfers($this->fileToArray("$path/transfers"))
            ->setTrips($this->fileToArray("$path/trips"));

        WatriHelper::deleteDir($path);
        return $gtfs;
    }

    private function unzipGtfs()
    {
        $zip = new \ZipArchive;
        $res = $zip->open("$this->pathGtfs.zip");
        if ($res === TRUE) {
            $zip->extractTo($this->pathGtfsUnzip);
            $zip->close();
        } else {
            echo 'doh!';
        }
    }

    final static function fileToArray($pathFile, $extension = "txt")
    {
        $array = [];
        $array1 = [];
        $i = 0;
//        dd($pathFile,file_exists ( "$pathFile.$extension" ));
        if (file_exists("$pathFile.$extension")) {
            $file = fopen("$pathFile.$extension", 'r');
            $keys = [];
            while (($line = fgetcsv($file)) !== FALSE) {
                if ($i === 0) {
                    $keys = $line;

                } else {
                    foreach ($line as $k => $value) {
                        $key = $keys[$k];
                        $array[$key] = $value;
                    }
                }
                $array1[$i] = $array;
                $i++;
            }
            fclose($file);
            unset($array1[0]);
            return $array1;
        }

    }


}
