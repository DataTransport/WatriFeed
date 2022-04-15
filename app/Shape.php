<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shape extends Model 
{

    protected $table = 'shapes';
    public $timestamps = true;
    protected $fillable = array(
        'shape_id',
        'shape_pt_lat',
        'shape_pt_lon',
        'shape_pt_sequence',
        'shape_dist_traveled',
        'gtfs_id'
    );

    public function trips()
    {
        return $this->belongsToMany('Trip');
    }

}
