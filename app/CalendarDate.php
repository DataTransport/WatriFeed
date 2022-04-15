<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalendarDate extends Model 
{

    protected $table = 'calendar_dates';
    public $timestamps = true;
    protected $fillable = array(
        'service_id',
        'date',
        'exception_type',
        'gtfs_id'
    );

    public function calendar()
    {
        return $this->belongsTo('Calendar');
    }

}
