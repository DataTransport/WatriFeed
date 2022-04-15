<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FareRule extends Model 
{

    protected $table = 'fare_rules';
    public $timestamps = true;
    protected $fillable = array(
        'fare_id',
        'route_id',
        'origin_id',
        'destination_id',
        'contains_id',
        'gtfs_id'
    );

    public function fareAttribute()
    {
        return $this->belongsTo('FareAttribute');
    }

    public function route()
    {
        return $this->belongsTo('Route');
    }

}
