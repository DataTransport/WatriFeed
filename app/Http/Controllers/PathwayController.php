<?php

namespace App\Http\Controllers;

use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use App\Pathway;
use Illuminate\Http\Request;

class PathwayController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }
        $gtfs = Gtfs::find(session('gtfs_id'));

        $pathways = $gtfs->pathways()->get();

        return view('gtfs.partials.pathways', compact('pathways', 'gtfs'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'pathway_id' => 'required',
            'from_stop_id' => 'required',
            'to_stop_id' => 'required',
            'pathway_mode' => 'required',
            'is_bidirectional' => 'required|numeric|max:1',
        ]);
        $gtfs_id = session('gtfs_id');
        $pathway = new Pathway();

        $pathway->pathway_id = $request->pathway_id;
        $pathway->from_stop_id = $request->from_stop_id;
        $pathway->to_stop_id = $request->to_stop_id;
        $pathway->pathway_mode = $request->pathway_mode;
        $pathway->is_bidirectional = $request->is_bidirectional;
        $pathway->length = $request->length;
        $pathway->traversal_time = $request->traversal_time;
        $pathway->stair_count = $request->stair_count;
        $pathway->max_slope = $request->max_slope;
        $pathway->min_width = $request->min_width;
        $pathway->signposted_as = $request->signposted_as;
        $pathway->reversed_signposted_as = $request->reversed_signposted_as;


        $pathway->gtfs_id = $gtfs_id;

        $pathway->save();
        WatriHelper::update_gtfs($gtfs_id);
        echo 'Pathway Inserted';
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pathway_id' => 'required',
            'from_stop_id' => 'required',
            'to_stop_id' => 'required',
            'pathway_mode' => 'required',
            'is_bidirectional' => 'required|numeric|max:1',
        ]);
        $gtfs_id = session('gtfs_id');
        $pathway = Pathway::find($id);

        $pathway->pathway_id = $request->pathway_id;
        $pathway->from_stop_id = $request->from_stop_id;
        $pathway->to_stop_id = $request->to_stop_id;
        $pathway->pathway_mode = $request->pathway_mode;
        $pathway->is_bidirectional = $request->is_bidirectional;
        $pathway->length = $request->length;
        $pathway->traversal_time = $request->traversal_time;
        $pathway->stair_count = $request->stair_count;
        $pathway->max_slope = $request->max_slope;
        $pathway->min_width = $request->min_width;
        $pathway->signposted_as = $request->signposted_as;
        $pathway->reversed_signposted_as = $request->reversed_signposted_as;


        $pathway->gtfs_id = $gtfs_id;

        $pathway->save();
        WatriHelper::update_gtfs($gtfs_id);
        echo 'Pathway Inserted';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
