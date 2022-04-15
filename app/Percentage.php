<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Percentage extends Model
{
    public $timestamps = true;
    protected $fillable = array('row_total','row_current','percentage');
}
