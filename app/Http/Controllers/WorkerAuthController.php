<?php
namespace App\Http\Controllers;
use App\Mail\OrderShipped;
use App\Models\Worker;
use App\Notifications\NewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;


use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class WorkerAuthController extends Controller
{

 public function __construct() {
        $this->middleware('auth:worker', ['except' => ['login', 'register']]);


    }












    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->guard('worker')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // تحقق من الحالة
        $worker = auth()->guard('worker')->user();
        if ($worker->status != 1) {

            auth()->guard('worker')->logout();
            return response()->json(['error' => 'status===0'], 401);
        }

        if ($worker->verfication_token !== null) {

            return response()->json(['error' => 'Token is not null, login not allowed'], 401);
        }



        $worker->verfication_token = $token;
    $worker->save();

        return $this->createNewToken($token);


    }




    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:workers',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|min:6',
            'photo' => 'required|image|mimes:jpeg,png,JPEG',
            'location' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $worker = Worker::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password),
                     'photo'=>bcrypt($request->file('photo')->store('workeres'))
                    ]
                ));


                  // Notify the worker via email
    $worker->notify(new NewNotification());




    return response()->json(['message' => 'Worker registered successfully'], 201);






    }






    public function sendEmail($worker)
    {
        Mail::to($worker->email)->send(new OrderShipped($worker));









    }
    public function logout() {
        auth()->guard('worker')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }












    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */








    public function userProfile() {
        return response()->json(auth()->guard('worker')->user());
    }








    protected function createNewToken($token){

        $user = auth()->guard('worker')->user();
        $user->update(['verfication_token' => $token]);




        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user()
        ]);
    }











}
