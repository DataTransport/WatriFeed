<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Calendar;
use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use Illuminate\Http\Request;

class CalendarController extends Controller
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

        $calendars = $gtfs->calendars()->get();
        return view('gtfs.partials.calendar', compact('calendars', 'gtfs'));

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
    public function store(Request $request): void
    {

        $validatedData = $request->validate([
            'service_id' => 'required',
            'monday' => 'required|numeric|min:0|max:1',
            'tuesday' => 'required|numeric|min:0|max:1',
            'wednesday' => 'required|numeric|min:0|max:1',
            'thursday' => 'required|numeric|min:0|max:1',
            'friday' => 'required|numeric|min:0|max:1',
            'saturday' => 'required|numeric|min:0|max:1',
            'sunday' => 'required|numeric|min:0|max:1',
            'start_date' => 'required|numeric|digits:8',
            'end_date' => 'required|numeric|digits:8',
            'gtfs' => 'required'
        ]);

        $calendar = new Calendar();

        $calendar->service_id = $request->service_id;
        $calendar->monday = $request->monday;
        $calendar->tuesday = $request->tuesday;
        $calendar->wednesday = $request->wednesday;
        $calendar->thursday = $request->thursday;
        $calendar->friday = $request->friday;
        $calendar->saturday = $request->saturday;
        $calendar->sunday = $request->sunday;
        $calendar->start_date = $request->start_date;
        $calendar->end_date = $request->end_date;

        $calendar->gtfs_id = $request->gtfs;

        if ($calendar->save()) {
            echo 'Calendar Inserted';
            WatriHelper::update_gtfs($request->gtfs);
        } else {
            echo 'Verifiez vos informations';
        }
//      $calendar->save();

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

        if ($validate_column_name === 'service_id') {
            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'monday') {
            $request->validate([
                'value' => 'required|numeric|min:0|max:1'
            ]);
        }
        if ($validate_column_name === 'tuesday') {
            $request->validate([
                'value' => 'required|numeric|min:0|max:1'
            ]);
        }
        if ($validate_column_name === 'wednesday') {
            $request->validate([
                'value' => 'required|numeric|min:0|max:1'
            ]);
        }
        if ($validate_column_name === 'thursday') {
            $request->validate([
                'value' => 'required|numeric|min:0|max:1'
            ]);
        }
        if ($validate_column_name === 'friday') {
            $request->validate([
                'value' => 'required|numeric|min:0|max:1'
            ]);
        }
        if ($validate_column_name === 'saturday') {
            $request->validate([
                'value' => 'required|numeric|min:0|max:1'
            ]);
        }
        if ($validate_column_name === 'sunday') {
            $request->validate([
                'value' => 'required|numeric|min:0|max:1'
            ]);
        }
        if ($validate_column_name === 'start_date') {
            $request->validate([
                'value' => 'required|numeric|digits:8'
            ]);
        }
        if ($validate_column_name === 'end_date') {

            $request->validate([
                'value' => 'required|numeric|digits:8'
            ]);
        }
        if ($validate_column_name === 'route_type') {

            $request->validate([
                'value' => 'required'
            ]);
        }

        Calendar::where('id', $id)
            ->update([$column_name => $value]);

        $gtfs = Calendar::find($id)->gtfs_id;
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
        Calendar::destroy($id);
        echo 'Data Deleted';
    }

    final public function importCalendarsCSV(Request $request)
    {

        $titles_allow = ['service_id', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday',
            'sunday', 'saturday', 'start_date', 'end_date'];

        $fileName = request('file')->getRealPath();
        $fileSize = $request->file('file')->getSize();

        if ($fileSize > 0) {
            $file = fopen($fileName, 'rb');
            $title_row = true;
            $title_data = [];
            $nbr_add = 0;
            $nbr_upd = 0;
            $line = 1;
            $gtfs_id = session('gtfs_id');
            while (($column = fgetcsv($file, 10000, ',')) !== FALSE) {
                if ($title_row) {
                    foreach ($column as $key => $value) {
                        if (!in_array($value, $titles_allow, true)) {
                            return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "$value is not a trip column");
                        }
                        $title_data[$value] = $key;
                    }
                    foreach (['service_id', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'sunday', 'saturday', 'start_date', 'end_date'] as $title) {
                        if (!isset($title_data[$title])) {
                            return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "$title is required");
                        }
                    }
                    $title_row = false;
                    $line++;
                    continue;
                }
                foreach (['service_id', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'sunday', 'saturday', 'start_date', 'end_date'] as $title) {
                    if ($column[$title_data[$title]] === '') {
                        return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "missing $title on line $line");
                    }
                }
                $calendar_exist = Calendar::where('service_id', $column[$title_data['service_id']])->where('gtfs_id', $gtfs_id)->first();

                if (!$calendar_exist) {
                    $calendar = new Calendar();
                    $nbr_add++;
                } else {
                    $calendar = $calendar_exist;
                    $nbr_upd++;
                }

                $calendar->service_id = $column[$title_data['service_id']];
                $calendar->monday = $column[$title_data['monday']];
                $calendar->tuesday = $column[$title_data['tuesday']];
                $calendar->wednesday = $column[$title_data['wednesday']];
                $calendar->thursday = $column[$title_data['thursday']];
                $calendar->friday = $column[$title_data['friday']];
                $calendar->sunday = $column[$title_data['sunday']];
                $calendar->saturday = $column[$title_data['saturday']];
                $calendar->start_date = $column[$title_data['start_date']];
                $calendar->end_date = $column[$title_data['end_date']];
                $calendar->gtfs_id = $gtfs_id;
                $calendar->save();
                $line++;
            }

            return back()->with('success', "$nbr_add Calendars add | $nbr_upd Calendars update");
        }
    }

}

?>
