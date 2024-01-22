<?php

namespace App\Http\Controllers;
use Illuminate\Notifications\Notification;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\post;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Notifications\NewPostCreated;
use App\Notifications\PostRejected;
use App\Notifications\PostApproved;
use Auth;

class postController extends Controller
{















    public function index()
    {


      $posts = Post::with('worker')->where('status', 'approved')->get();


$postsData = $posts->map(function ($post) {
    return [
        'id' => $post->id,
        'worker_id' => $post->worker_id,
        'content' => $post->content,
        'price' => $post->price,
        'created_at' => $post->created_at,
        'updated_at' => $post->updated_at,
        'worker_name' => $post->worker->name,
    ];
});

return response()->json([
    'this data' => $postsData
]);

    }


function filter(){
    $posts = QueryBuilder::for(post::class)->allowedFilters(['content', 'price' ,'worker.name' ])->where('status','approved')->get();

    return response()->json([
        'posts' => $posts]);


}
    }

    function pendingpost(){

        $post= post::where('status','pending')->get();
        return response()->json([
            "posts" => $post

        ]);
    }



    function aproved(){


        $aproved= post::where('status' , 'approved')->get();
        return response()->json([
            'posts'=>$aproved]);
    }


   function approvePost($id)
    {
        $post = Post::find($id);
        if ($post) {
            $post->status = 'approved';
            $post->save();
            $post->worker->notify(new PostApproved($post));

        }
    }

function rejectPost($id)
    {
        $post = Post::find($id);
        if ($post) {
            $post->status = 'rejected';
            $post->save();
            $post->worker->notify(new PostApproved($post));

        }
    }




 //   public function create_post(Request $request)
   /* {
        $worker_id = auth()->guard('worker')->id();

        $post = Post::create([
            'worker_id' => $worker_id,
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'status' => $request->input('status'),
        ]);






            if ($request->hasFile('photos')) {
                // Loop through each photo and associate it with the post
                foreach ($request->file('photos') as $photo) {
                    // Store the photo and get its path
                    $photoPath = $photo->store('post_photos', 'public');

                    // Create a new PostPhoto and associate it with the post
                  $path=  $post->postPhotos()->create(['photo' => $photoPath]);
                  $path->save();
                  if ($path){

                    return response()->json([' done' => $path]);
                  }

                }
            }
*/





   //     return response()->json(['post' => $post, 'message' => 'Post created successfully']);
 //   }





/* public function create_post(Request $request)
{
    // Validate the request data
    $request->validate([
        'content' => 'required|string',
        'price' => 'required|numeric',
        'status' => 'required|in:pending,approved,rejected',
        'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Assuming photo is part of the request
    ]);

    try {
        // Get the authenticated worker
        $worker = auth()->guard('worker')->user();

        // Create a new post
        // حيث posts()هو التابع بالموديل الخاص بالوركر يلي رابط العلاقة
        $post = $worker->posts()->create([
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'status' => $request->input('status'),
        ]);


        // Check if a photo is uploaded
        if ($request->hasFile('photo')) {
            // Upload the photo
            $photoPath = $request->file('photo')->store('post_photos', 'public');

            // Create a new post photo record
            $post->postPhotos()->create([
                'photo' => $photoPath,
            ]);
        }

        return response()->json(['post' => $post, 'message' => 'Post created successfully']);
    } catch (\Exception $e) {
        // Handle exceptions if any
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}*/

 function create_post(Request $request)
{
    // Validate the request data
    $request->validate([
        'content' => 'required|string',
        'price' => 'required|numeric',
        'status' => 'required|in:pending,approved,rejected',
        'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Assuming photo is part of the request
    ]);

    try {
        // Get the authenticated worker
        $worker = auth()->guard('worker')->user();

        // Create a new post
        $post = $worker->posts()->create([
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'status' => 'pending'
        ]);

        // Check if a photo is uploaded
        if ($request->hasFile('photo')) {
            // Upload the photo
            $photoPath = $request->file('photo')->store('post_photos', 'public');

            // Create a new post photo record
            $post->postPhotos()->create([
                'photo' => $photoPath,
            ]);
        }



        // ارسال ايميل للادمن بمجرد انو الوركر انشا بوست
        // Notify all admins
        $admins = Admin::all();
        foreach ($admins as $admin) {
            $admin->notify(new NewPostCreated($post));
        }

        return response()->json(['post' => $post, 'message' => 'Post created successfully']);
    } catch (\Exception $e) {
        // Handle exceptions if any
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

