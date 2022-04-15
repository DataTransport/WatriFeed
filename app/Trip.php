<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model 
{

    protected $table = 'trips';
    public $timestamps = true;
    protected $fillable = array(
        'trip_id',
        'shape_id',
        'service_id',
        'route_id',
        'trip_headsign',
        'trip_short_name',
        'direction_id',
        'block_id',
        'wheelchair_accessible',
        'bikes_allowed',
        'gtfs_id'
    );

    public function route()
    {
        return $this->belongsTo('Route');
    }

    public function stoptimes()
    {
        return $this->hasMany('Stoptime');
    }

    public function sharps()
    {
        return $this->belongsToMany('Shape');
    }

    public function frequency()
    {
        return $this->hasOne('Frequency');
    }

    public function calendar()
    {
        return $this->hasOne('Calendar');
    }

}
