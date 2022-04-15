<?php
use \Illuminate\Database\Seeder;
use App\User;


class UsersTableSeeder extends Seeder {
   public function run(){

       User::create(array(
           'name'=>'Mohamed Konaté',
           'email'=>'ing.mohamedkonate@gmail.com',
           'password'=>bcrypt('1234567')
       ));
   }
}
