<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model 
{

    protected $table = 'routes';
    public $timestamps = true;
    protected $fillable = array(
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
        'gtfs_id'
    );

    public function fareRule()
    {
        return $this->hasOne('FareRule');
    }

    public function agency()
    {
        return $this->belongsTo('Agency');
    }

    public function trips()
    {
        return $this->hasMany('App\Trip');
    }

}
