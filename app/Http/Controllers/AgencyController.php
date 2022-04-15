<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgencyController extends Controller
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

        if ($request->t === csrf_token() && $gtfs->password === $request->_ && $request->a === 'true') {

            return response()->json($gtfs->agencies()->get());
        }

        $agencies = $gtfs->agencies()->get();

        return view('gtfs.partials.agencies', compact('agencies', 'gtfs'));
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

        $validatedData = $request->validate([
            'agency_name' => 'required',
            'agency_url' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'agency_timezone' => 'required',
            'gtfs' => 'required'
        ]);

        $agency = new Agency();


        $agency->agency_id = $request->agency_id;

        if (!$request->agency_id) {
            $agency->agency_id = WatriHelper::initial_id($request->agency_name);
        }
        $agency->agency_name = $request->agency_name;
        $agency->agency_url = $request->agency_url;
        $agency->agency_timezone = $request->agency_timezone;
        $agency->agency_lang = $request->agency_lang;
        $agency->agency_phone = $request->agency_phone;
        $agency->agency_fare_url = $request->agency_fare_url;
        $agency->agency_email = $request->agency_email;
        $agency->gtfs_id = $request->gtfs;

        $agency->save();

        WatriHelper::update_gtfs($request->gtfs);

        echo 'Agency Inserted';
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
     * @param Request $request
     * @return void
     */
    public function edit($id, Request $request)
    {

        $column_name = $request->input('column_name');
        $value = $request->input('value');

        $validate_column_name = $column_name;

        if ($validate_column_name === 'agency_name') {
            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'agency_url') {
            $request->validate([
                'value' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'
            ]);
        }
        if ($validate_column_name === 'agency_timezone') {

            $request->validate([
                'value' => 'required'
            ]);
        }


        Agency::where('id', $id)
            ->update([$column_name => $value]);

        $gtfs = Agency::find($id)->gtfs_id;
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
        Agency::destroy($id);
        echo 'Agency Deleted';
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function get_agencies(Request $request): ?JsonResponse
    {
        $gtfs = Gtfs::find($request->g);
        if ($request->t === csrf_token() && $gtfs->password === $request->_) {
            return response()->json($gtfs->agencies()->get());
        }

    }

    /**
     * Show the application dataAjax.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function dataAjax(Request $request)
    {
        $gtfs = session('gtfs_id');
        $gtfs_id = $gtfs ?? 0;
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $search = (string)$search;
            if (strlen($search) >= 2) {
                $data = DB::table('agencies')
                    ->select('id', 'agency_id')
                    ->where('gtfs_id', $gtfs_id)
                    ->where('agency_id', 'LIKE', "%$search%")
                    ->get();
            }

        }


        return response()->json($data);
    }

    final public function importAgenciesCSV(Request $request)
    {

        $titles_allow = ['agency_id', 'agency_name', 'agency_url', 'agency_timezone', 'agency_lang', 'agency_phone',
            'agency_fare_url', 'agency_email'];

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
                    foreach (['agency_name', 'agency_url', 'agency_timezone'] as $title) {
                        if (!isset($title_data[$title])) {
                            return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "$title is required");
                        }
                    }
                    $title_row = false;
                    $line++;
                    continue;
                }
                foreach (['agency_name', 'agency_url', 'agency_timezone'] as $title) {
                    if ($column[$title_data[$title]] === '') {
                        return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "missing $title on line $line");
                    }
                }

                $agency_exist = Agency::where('agency_name', $column[$title_data['agency_name']])->where('gtfs_id', $gtfs_id)->first();
                if (!$agency_exist) {
                    $agency = new Agency();
                    $nbr_add++;
                } else {
                    $agency = $agency_exist;
                    $nbr_upd++;
                }

                $agency->agency_id = $column[$title_data['agency_id']];
                $agency->agency_name = $column[$title_data['agency_name']];
                $agency->agency_url = $column[$title_data['agency_url']];
                $agency->agency_timezone = isset($title_data['agency_timezone']) ? $column[$title_data['agency_timezone']] : '';
                $agency->agency_lang = isset($title_data['agency_lang']) ? $column[$title_data['agency_lang']] : '';
                $agency->agency_phone = isset($title_data['agency_phone']) ? $column[$title_data['agency_phone']] : '';
                $agency->agency_fare_url = isset($title_data['agency_fare_url']) ? $column[$title_data['agency_fare_url']] : '';
                $agency->agency_email = isset($title_data['agency_email']) ? $column[$title_data['agency_email']] : '';
                $agency->gtfs_id = $gtfs_id;
                $agency->save();
                $line++;
            }

            return back()->with('success', "$nbr_add Agencies add | $nbr_upd Agencies update");
        }
    }

    final public  function checkTitleData (array $title_data,array $titles_required) {
        foreach ($titles_required as $title) {
            if (!isset($title_data[$title])) {
                return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "$title is required");
            }
        }
    }

    final public function exportAgenciesCSV(){
        $agencies = Agency::select('agency_id','agency_name', 'agency_url', 'agency_timezone', 'agency_lang', 'agency_phone', 'agency_fare_url', 'agency_email')
            ->where('gtfs_id',session('gtfs_id'))
            ->get()
            ->toArray();
        WatriHelper::download_send_headers('agency_export_' . date('Y-m-d') . '.csv');
        echo WatriHelper::array2csv($agencies);
        die();
    }


}
