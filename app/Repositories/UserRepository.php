<?php
/**
 * Created by PhpStorm.
 * User: applehouse
 * Date: 07/12/2018
 * Time: 00:52
 */

namespace App\Repositories;


use App\User;

class UserRepository extends ResourceRepository {

    public function __construct(User $user)
    {
        $this->model = $user;
    }


}