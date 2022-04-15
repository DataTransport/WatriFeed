<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FareAttribute extends Model 
{

    protected $table = 'fare_attributes';
    public $timestamps = true;
    protected $fillable = array(
        'fare_id',
        'price',
        'currency_type',
        'payment_method',
        'transfers',
        'agency_id',
        'transfer_duration',
        'gtfs_id'
    );

    public function fareRules()
    {
        return $this->hasMany('FareRule');
    }

}
