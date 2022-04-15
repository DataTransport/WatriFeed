<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Shape;
use App\Trip;
use Illuminate\Http\Request;
use App\Gtfs as GTFSTABLE;
use App\Route as Route_Model;
use App\Stop;
use App\Stoptime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    final public function index(){

        if ((int)Auth::user()->state === 0){
            Session::flash('message', 'Your account is awaiting validation');
            Auth::logout();
            return redirect('/');
        }

        $gtfs_length = GTFSTABLE::count();
        $routes_length = Route_Model::count();
        $stops_length = Stop::count();
        $stopTimes_length = Stoptime::count();
        $agencies_length = Agency::count();
        $trips_length = Trip::count();
        $shapes_length = Shape::count();
        $calendars_length = Shape::count();
        return view('dashboard',compact('gtfs_length','stops_length','routes_length','stopTimes_length','agencies_length','shapes_length','trips_length','calendars_length'));
    }
}
