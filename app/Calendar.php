<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model 
{

    protected $table = 'calendars';
    public $timestamps = true;
    protected $fillable = array(
        'service_id',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'start_date',
        'end_date',
        'gtfs_id');

    public function trip()
    {
        return $this->belongsTo('Trip');
    }

    public function calendarDates()
    {
        return $this->hasMany('CalendarDate');
    }

}
