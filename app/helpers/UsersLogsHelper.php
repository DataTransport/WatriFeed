<?php


namespace App\helpers;


use App\UserLog;
use Illuminate\Support\Facades\Auth;

class UsersLogsHelper
{

    public static function create(string $description): void
    {
        $user_log = new UserLog();
        $user_log->id_user = Auth::id();
        $user_log->description = $description;
        $user_log->save();

//        $descriptions : login, createGTFS, destroyGTFS, importGTFS, exportGTFS
    }
}
