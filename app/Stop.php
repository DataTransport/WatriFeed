<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model 
{

    protected $table = 'stops';
    public $timestamps = true;
    public function Stoptime()
    {
        return $this->hasOne('Stoptime');
    }

    protected $fillable = array(
        'stop_id',
        'stop_code',
        'stop_name',
        'stop_desc',
        'stop_lat',
        'stop_lon',
        'zone_id',
        'stop_url',
        'location_type',
        'parent_station',
        'stop_timezone',
        'wheelchair_boarding',
        'level_id',
        'platform_code',
        'gtfs_id',);

}
