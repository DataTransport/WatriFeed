<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pathway extends Model
{

    public $timestamps = true;
    protected $table = 'pathways';
    protected $fillable = array(
        'pathway_id',
        'from_stop_id',
        'to_stop_id',
        'pathway_mode',
        'is_bidirectional',
        'length',
        'traversal_time',
        'stair_count',
        'max_slope',
        'min_width',
        'signposted_as',
        'reversed_signposted_as',
        'gtfs_id');

}
