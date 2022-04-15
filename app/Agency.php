<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{

    public $timestamps = true;
    protected $table = 'agencies';
    protected $fillable = array(
        'agency_id',
        'agency_name',
        'agency_url',
        'agency_timezone',
        'agency_lang',
        'agency_phone',
        'agency_fare_url',
        'agency_email',
        'gtfs_id');

    public function routes()
    {
        return $this->hasMany('Route');
    }

}
