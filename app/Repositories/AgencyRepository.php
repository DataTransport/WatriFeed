<?php
/**
 * Created by PhpStorm.
 * User: applehouse
 * Date: 29/01/2019
 * Time: 15:17
 */

namespace App\Repositories;

use App\Agency;

class AgencyRepository extends ResourceRepository{


    /**
     * AgencyRepository constructor.
     */
    public function __construct(Agency $agency){
        $this->model=$agency;

    }

}