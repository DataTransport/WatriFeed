<?php

namespace App\Http\Controllers;

use App\CalendarDate;
use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use Illuminate\Http\Request;

class CalendarDateController extends Controller
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
        if (!WatriCheck::session($request, 'gtfs_id')) {
            return redirect()->to('/gtfs');
        }
        $gtfs = Gtfs::find(session('gtfs_id'));

        $calendarDs = $gtfs->calendar_dates()->get();
        return view('gtfs.partials.calendar_dates', compact('calendarDs', 'gtfs'));

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
            'service_id' => 'required',
            'date' => 'required|numeric|digits:8',
            'exception_type' => 'required',
            'gtfs' => 'required'
        ]);

        $calendarDate = new CalendarDate();

        $calendarDate->service_id = $request->service_id;
        $calendarDate->date = $request->date;
        $calendarDate->exception_type = $request->exception_type;

        $calendarDate->gtfs_id = $request->gtfs;

        $calendarDate->save();

        WatriHelper::update_gtfs($request->gtfs);

        echo 'Calendar Date Inserted';
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

        $validate_column_name = $column_name;

        if ($validate_column_name === 'date') {
            $request->validate([
                'value' => 'required|numeric|digits:8'
            ]);
        }
        if ($validate_column_name === 'exception_type') {
            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'service_id') {

            $request->validate([
                'value' => 'required'
            ]);
        }


        CalendarDate::where('id', $id)
            ->update([$column_name => $value]);

        $gtfs = CalendarDate::find($id)->gtfs_id;
        WatriHelper::update_gtfs($gtfs);

        echo "$column_name update";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        CalendarDate::destroy($id);
        echo 'Data Deleted';
    }

}

?>
