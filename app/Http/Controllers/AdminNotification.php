<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminNotification extends Controller
{
  public function index(){





$admin= Admin::find((auth()->id()));
return response()->json([
'notifications' => $admin->notifications


]);
}

function unread(){
$admin = Admin::find((auth()->id()));
return response()->json([
    'notifications'=>$admin->unreadNotifications
]);
}




function Asread(){

    $admin= Admin::find((auth()->id()));

    foreach($admin->unreadNotifications as $unread){
        $unread->markAsRead();
        $unread->save();

    }

    return response()->json([
        'message'=> 'successfully unread'
    ]);
}



function deleteall(){
$admin= Admin::find((auth()->id()));
$admin->notifications()->delete();
return response()->json([
    "message"=> "Successfully deleted all"
]);
}






public function deleteOneNotification ($id){


    $admin = Admin::find((auth()->id()));
    DB::table("notifications")->where( 'id' , $id)->delete();
    return response()->json([
        "message"=> "deleted one notification",
    ]);
}


public function ReadOneNotification($id){



     $notification = auth()->user()->notifications()->where('id', $id)->get();


    if($notification) {
        $notification->markAsRead();

    }
return response()->json([
      "message"=> "read one notification",
    ]);
}


}
