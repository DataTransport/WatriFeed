<?php

namespace App\Http\Controllers;

use App\FareRule;
use App\Gtfs;
use App\helpers\WatriCheck;
use App\helpers\WatriHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FareRuleController extends Controller
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
        $fareRules = $gtfs->fare_rules()->get();
        $stops = $gtfs->stops()->get();
        $routes = $gtfs->routes()->get();
        return view('gtfs.partials.fare_rules', compact('fareAttributes', 'fareRules', 'gtfs', 'stops', 'routes'));

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
            'fare_id' => 'required'
        ]);

        $gtfs_id = session('gtfs_id');
        $fareRule = new FareRule();

        $fareRule->fare_id = $request->fare_id;
        $fareRule->route_id = $request->route_id;
        $fareRule->origin_id = $request->origin_id;
        $fareRule->destination_id = $request->destination_id;
        $fareRule->contains_id = $request->contains_id;

        $fareRule->gtfs_id = $gtfs_id;

        $fareRule->save();
        WatriHelper::update_gtfs($gtfs_id);
        echo 'Fare Rule Inserted';
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

        if ($column_name === 'fare_id') {

            $request->validate([
                'value' => 'required'
            ]);
        }


        FareRule::where('id', $id)
            ->update([$column_name => $value]);

        $gtfs = FareRule::find($id)->gtfs_id;
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
            'fare_id' => 'required'
        ]);

        $gtfs_id = session('gtfs_id');
        $fareRule = FareRule::find($id);

        $fareRule->fare_id = $request->fare_id;
        $fareRule->route_id = $request->route_id;
        $fareRule->origin_id = $request->origin_id;
        $fareRule->destination_id = $request->destination_id;
        $fareRule->contains_id = $request->contains_id;

        $fareRule->gtfs_id = $gtfs_id;

        $fareRule->save();
        WatriHelper::update_gtfs($gtfs_id);
        echo 'Fare Rule Update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        FareRule::destroy($id);
        echo 'Data Deleted';
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAddField(Request $request)
    {
        $gtfs = Gtfs::find($request->g);

        if ($request->t === csrf_token() && $gtfs->password === $request->_ && $request->a === 'true') {

            $data = [$gtfs->fare_attributes()->get(), $gtfs->routes()->get(), $gtfs->stops()->get()];
            return response()->json($data);
        }
//        $fare_attribute = FareAttribute::all();
//        $routes = Route::all();
//        $stops = Stop::all();


//

    }

}

