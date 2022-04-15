<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stoptime extends Model 
{

    protected $table = 'stoptimes';
    public $timestamps = true;
    protected $fillable = array(
        'stop_id',
        'trip_id',
        'arrival_time',
        'departure_time',
        'stop_sequence',
        'stop_headsign',
        'pickup_type',
        'drop_off_type',
        'shape_dist_traveled',
        'timepoint',
        'gtfs_id');

    public function stop()
    {
        return $this->belongsTo('Stop');
    }

    public function trip()
    {
        return $this->belongsTo('Trip');
    }

}
