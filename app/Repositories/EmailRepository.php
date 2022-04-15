<?php
/**
 * Created by PhpStorm.
 * User: applehouse
 * Date: 06/12/2018
 * Time: 13:17
 */

namespace App\Repositories;


use App\Email;

class EmailRepository implements EmailRepositoryInterface {


    protected $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }


    public function save($email){
        $this->email->email = $email;
        $this->email->save();
    }
}