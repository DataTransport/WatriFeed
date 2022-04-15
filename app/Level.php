<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{

    protected $table = 'levels';
    public $timestamps = true;
    protected $fillable = array(
        'level_id',
        'level_index',
        'level_name',
        'gtfs_id'
    );



}
