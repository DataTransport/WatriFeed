<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model 
{

    protected $table = 'transfers';
    public $timestamps = true;
    protected $fillable = array(
        'from_stop_id',
        'to_stop_id',
        'transfer_type',
        'min_transfer_time',
        'gtfs_id'
    );

}
