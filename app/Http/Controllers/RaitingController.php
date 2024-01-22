<?php

namespace App\Http\Controllers;

use App\Models\raiting;
use Illuminate\Http\Request;
use App\Models\post;

class RaitingController extends Controller
{

    public function store(Request $request ){



        $request->validate([
            'rate' => 'required|integer|min:0|max:5',

        ]);
$raiting= $request->all();
$raiting['client_id']= auth()->guard('client')->id();
$review= raiting::create($raiting);
return response()->json([
    "review" => $review
]);


    }


    public function getAverageRating($id)
    {
        try {
            $post = Post::findOrFail($id);
            $ratings = $post->ratings;

            if ($ratings->isEmpty()) {
                return response()->json([
                    'error' => 'No ratings found for this post.',
                ], 404);
            }

            $averageRating = $ratings->avg('rate');

            $ratingsInfo = $ratings->map(function ($rating) {
                return [
                    'client_name' => $rating->client->name, 
                    'rate' => $rating->rate,
                    'comment' => $rating->comment,
                ];
            });

            return response()->json([
                'post_id' => $id,
                'average_rating' => $averageRating,
                'ratings_info' => $ratingsInfo,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Post not found.',
            ], 404);
        }
    }
}
