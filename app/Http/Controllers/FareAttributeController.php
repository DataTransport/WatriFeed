<?php

namespace App\Http\Controllers;

use App\FareAttribute;
use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FareAttributeController extends Controller
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


        $fareAttributes = $gtfs->fare_attributes()->get();
        $agencies = $gtfs->agencies()->get();
        return view('gtfs.partials.fare_attributes', compact('fareAttributes', 'gtfs', 'agencies'));

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
            'fare_id' => 'required',
            'price' => 'required',
            'currency_type' => 'required',
            'payment_method' => 'required',
            'transfers' => 'required',
            'gtfs' => 'required'
        ]);

        $fareAttributes = new FareAttribute();

        $fareAttributes->fare_id = $request->fare_id;
        $fareAttributes->price = $request->price;
        $fareAttributes->currency_type = $request->currency_type;
        $fareAttributes->payment_method = $request->payment_method;
        $fareAttributes->transfers = $request->transfers;
        $fareAttributes->transfer_duration = $request->transfer_duration;
        $fareAttributes->agency_id = $request->agency_id;

        $fareAttributes->gtfs_id = $request->gtfs;

        $fareAttributes->save();

        WatriHelper::update_gtfs($request->gtfs);

        echo 'Fare Attribute Inserted';
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

        if ($validate_column_name === 'fare_id') {
            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'price') {
            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'payment_method') {

            $request->validate([
                'value' => 'required'
            ]);
        }
        if ($validate_column_name === 'transfers') {

            $request->validate([
                'value' => 'required'
            ]);
        }

        FareAttribute::where('id', $id)
            ->update([$column_name => $value]);

        $gtfs = FareAttribute::find($id)->gtfs_id;
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
        FareAttribute::destroy($id);
        echo 'Data Deleted';
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
            if ($search !== '') {
                $data = DB::table('fare_attributes')
                    ->select('id', 'fare_id')
                    ->where('gtfs_id', $gtfs_id)
                    ->where('fare_id', 'LIKE', "%$search%")
                    ->get();
            }

        }


        return response()->json($data);
    }

}

?>
