<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Models\post;



class OrderController extends Controller
{





 public function addorder(Request $request)
{
$data = $request->all();
$order= order::create($data);
return response()->json([
'your_order is ' => $order
],202);

}



public function showOrder(){
    $order= order::where('status', 'pending')->with('post', 'client')->whereHas('post',function ($query){
        $query->where('worker_id',auth()->guard('worker')->id());
    })->get();
    return response()->json([
        '$order is  ' => $order


    ]);
}







public function updateStatus($id , Request $request){


$status = order::findorFail($id);
$status->setAttribute('status',$request->status)->save();
return response()->json([
    'status '=>$status]);

}


}
