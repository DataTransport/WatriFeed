<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Frequency extends Model 
{

    protected $table = 'frequencies';
    public $timestamps = true;
    protected $fillable = array(
        'trip_id',
        'start_time',
        'end_time',
        'headway_secs',
        'exact_times',
        'gtfs_id'
    );

    public function trip()
    {
        return $this->belongsTo('Trip');
    }

}
