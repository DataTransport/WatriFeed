<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    /**
     * Show the application layout.
     *
     * @return \Illuminate\Http\Response
     */
    public function layout()
    {
        return view('test_select_ajax');
    }


    /**
     * Show the application dataAjax.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataAjax(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $search =(string)$search;
            if (strlen($search)>=3){
                $data = DB::table('stops')
                    ->select('id', 'stop_id')
                    ->where('stop_id','LIKE',"%$search%")
                    ->get();
            }

        }


        return response()->json($data);
    }

    public function leaflet(){

        return view('maps.index');
    }
}
