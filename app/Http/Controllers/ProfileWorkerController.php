<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;

class ProfileWorkerController extends Controller
{




    public function showprofile()
{
    $workerId = auth()->guard('worker')->id();


    $workerData = Worker::with(['posts' => function ($query) {

        $query->with(['ratings' => function ($ratingQuery) {

            $ratingQuery->select('id', 'post_id', 'client_id', 'comment', 'rate', 'created_at', 'updated_at');
        }])->select('id', 'worker_id', 'content', 'price', 'status');
    }
    ])->findOrFail($workerId);

    return response()->json([
        'your_data' => [
            'id' => $workerData->id,
            'name' => $workerData->name,
            'email' => $workerData->email,
            'phone' => $workerData->phone,
            'photo' => $workerData->photo,
            'status' => $workerData->status,
            'location' => $workerData->location,
            'posts' => $workerData->posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'worker_id' => $post->worker_id,
                    'content' => $post->content,
                    'price' => $post->price,
                    'ratings' => $post->ratings->map(function ($rating) {
                        return [
                            'id' => $rating->id,
                            'post_id' => $rating->post_id,
                            'client_id' => $rating->client_id,
                            'comment' => $rating->comment,
                            'rate' => $rating->rate,

                        ];
                    }),
                ];
            }),
        ],
    ]);
}
}
