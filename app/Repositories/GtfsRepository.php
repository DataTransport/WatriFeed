<?php
/**
 * Created by PhpStorm.
 * User: applehouse
 * Date: 29/01/2019
 * Time: 11:53
 */

namespace App\Repositories;

use App\Gtfs;

class GtfsRepository extends ResourceRepository{


    /**
     * GtfsRepository constructor.
     */
    public function __construct(Gtfs $gtfs){
        $this->model=$gtfs;
    }

    public function store(Array $inputs)
    {
        return $this->model->create($inputs);
    }
}