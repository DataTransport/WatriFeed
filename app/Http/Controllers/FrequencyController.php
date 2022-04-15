<?php

namespace App\Http\Controllers;

use App\Frequency;
use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use App\Trip;
use Illuminate\Http\Request;

class FrequencyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }
        $gtfs = Gtfs::find(session('gtfs_id'));


        $frequencies = $gtfs->frequencies()->get();
        $trips = $gtfs->trips()->get();

        return view('gtfs.partials.frequencies', compact('frequencies', 'gtfs', 'trips'));


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

        $request->validate([
            'trip_id' => 'required',
            'start_time' => 'date_format:H:i',
            'end_time' => 'date_format:H:i|after:start_time',
            'headway_secs' => 'numeric'
        ]);
        $gtfs_id = session('gtfs_id');
        $frequency = new Frequency();

        $frequency->trip_id = $request->trip_id;
        $frequency->start_time = $request->start_time;
        $frequency->end_time = $request->end_time;
        $frequency->headway_secs = $request->headway_secs;
        $frequency->exact_times = $request->exact_times;

        $frequency->gtfs_id = $gtfs_id;

        $frequency->save();
        WatriHelper::update_gtfs($gtfs_id);
        echo 'Frequency Inserted';
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id, Request $request)
    {

        $column_name = $request->input('column_name');
        $value = $request->input('value');

        if ($column_name === 'trip_id') {

            $request->validate([
                'value' => 'required'
            ]);
        }

        if ($column_name === 'start_time') {

            $request->validate([
                'value' => 'date_format:H:i:s'
            ]);
        }

        if ($column_name === 'end_time') {

            $request->validate([
                'value' => 'date_format:H:i:s'
            ]);
        }

        if ($column_name === 'headway_secs') {

            $request->validate([
                'value' => 'numeric'
            ]);
        }

        if ($column_name === 'trip_id') {

            $request->validate([
                'value' => 'required'
            ]);
        }


        Frequency::where('id', $id)
            ->update([$column_name => $value]);

        $gtfs = Frequency::find($id)->gtfs_id;
        WatriHelper::update_gtfs($gtfs);

        echo "$column_name update";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'trip_id' => 'required',
            'start_time' => 'date_format:H:i',
            'end_time' => 'date_format:H:i|after:start_time',
            'headway_secs' => 'numeric'
        ]);
        $gtfs_id = session('gtfs_id');
        $frequency = Frequency::find($id);

        $frequency->trip_id = $request->trip_id;
        $frequency->start_time = $request->start_time;
        $frequency->end_time = $request->end_time;
        $frequency->headway_secs = $request->headway_secs;
        $frequency->exact_times = $request->exact_times;

        $frequency->gtfs_id = $gtfs_id;

        $frequency->save();
        WatriHelper::update_gtfs($gtfs_id);
        echo 'Frequency Updated';

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Frequency::destroy($id);
        echo 'Data Deleted';
    }
    
    public function generateFrequencies(Request $request){
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'headway_secs' => 'numeric'
        ]);
        $gtfs_id = session('gtfs_id');
        $trips = Trip::where('gtfs_id',$gtfs_id)->get();
        $deleted = 0;
        $generated = 0;
        if ($trips){
            $res = Frequency::where('gtfs_id', $gtfs_id);
            $deleted = $res->get()->count();
            $res->delete();
        }
        
        foreach ($trips as  $trip){
            $frequency = new Frequency();
            $frequency->trip_id = $trip->trip_id;
            $frequency->start_time = $request->start_time;
            $frequency->end_time = $request->end_time;
            $frequency->headway_secs = $request->headway_secs;
            $frequency->gtfs_id = $gtfs_id;
            $frequency->save();
            $generated++;
        }
        return redirect("/gtfs/$gtfs_id/edit")->with('success', "$deleted deleted | $generated generated");
    }

}

?>
