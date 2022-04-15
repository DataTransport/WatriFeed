<?php

namespace App\Http\Controllers;

use App\Gtfs as GtfsTable;
use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use App\Level;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (!WatriCheck::session($request,'gtfs_id')){
            return redirect()->to('/gtfs');
        }
        $gtfs = Gtfs::find(session('gtfs_id'));

        $levels = $gtfs->levels()->get();

        return view('gtfs.partials.levels',compact('levels','gtfs'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'level_id'=>'required',
            'level_index'=>['required']
        ]);

        $gtfs_id = session('gtfs_id');
        $level = new Level();

        $level->level_id = $request->level_id;
        $level->level_index = $request->level_index;
        $level->level_name = $request->level_name;

        $level->gtfs_id = $gtfs_id;

        $level->save();

        WatriHelper::update_gtfs($gtfs_id);
        echo 'Level Inserted';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id,Request $request)
    {

        $column_name = $request->input('column_name');
        $value =  $request->input('value');

        if ($column_name==='level_id'){
            $request->validate([
                'value'=>'required'
            ]);
        }
        if ($column_name==='level_index'){
            $request->validate([
                'value'=>['required']
            ]);
        }



        Level::where('id',$id)
            ->update([$column_name=>$value]);


        echo "$column_name update";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Level::destroy($id);
        echo 'Data Deleted';
    }

}
