<?php


namespace App\helpers;


use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;

class WatriCheck{
    /**
     * @param Request $request
     * @param string $key
     * @return bool
     */
    final public static function session(Request $request, string $key){

        return $request->session()->exists($key);
    }

    final public  static function checkTitleData (array $title_data,array $titles_required) {
        foreach ($titles_required as $title) {
            if (!isset($title_data[$title])) {
                return redirect()->route('gtfs.edit', ['gtfs' => session('gtfs_id')])->with('error', "$title is required");
            }
        }
    }
}
