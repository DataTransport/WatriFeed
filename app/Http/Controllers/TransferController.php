<?php

namespace App\Http\Controllers;

use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use App\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
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


        $transfers = $gtfs->transfers()->get();
        $stops = $gtfs->stops()->get();

        return view('gtfs.partials.transfers', compact('transfers', 'gtfs', 'stops'));


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
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_stop_id' => 'required',
            'to_stop_id' => 'required',
            'transfer_type' => 'required'
        ]);

        $gtfs_id = session('gtfs_id');
        $transfer = new Transfer();

        $transfer->from_stop_id = $request->from_stop_id;
        $transfer->to_stop_id = $request->to_stop_id;
        $transfer->transfer_type = $request->transfer_type;
        $transfer->min_transfer_time = $request->min_transfer_time;

        $transfer->gtfs_id = $gtfs_id;

        $transfer->save();
        WatriHelper::update_gtfs($gtfs_id);
        echo 'Transfer Inserted';
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

        if ($column_name === 'from_stop_id') {

            $request->validate([
                'value' => 'required'
            ]);
        }

        if ($column_name === 'to_stop_id') {

            $request->validate([
                'value' => 'required'
            ]);
        }

        if ($column_name === 'transfer_type') {

            $request->validate([
                'value' => 'required'
            ]);
        }


        Transfer::where('id', $id)
            ->update([$column_name => $value]);


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
            'from_stop_id' => 'required',
            'to_stop_id' => 'required',
            'transfer_type' => 'required'
        ]);

        $gtfs_id = session('gtfs_id');
        $transfer = Transfer::find($id);

        $transfer->from_stop_id = $request->from_stop_id;
        $transfer->to_stop_id = $request->to_stop_id;
        $transfer->transfer_type = $request->transfer_type;
        $transfer->min_transfer_time = $request->min_transfer_time;

        $transfer->gtfs_id = $gtfs_id;

        $transfer->save();
        WatriHelper::update_gtfs($gtfs_id);
        echo 'Transfer Inserted';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Transfer::destroy($id);
        echo 'Data Deleted';
    }

}

?>
