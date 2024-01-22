<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;



    protected $fillable = [
        'content',
        'price',
        'status',
        'worker_id'
    ];



    public function worker()
    {
        return $this->belongsTo(Worker::class,'worker_id');
    }



    public function postPhotos()
    {
        return $this->hasMany(post_photo::class);
    }




    public function orders()
    {
        return $this->hasMany(Order::class);
    }




    public function ratings()
    {
        return $this->hasMany(raiting::class);
    }





 /*   public function averageRating(){

        $ratings = $this->ratings;

        if ($ratings->count() > 0) {
            return $ratings->avg('rate');
        }

        return 0; // or any default value you prefer when there are no ratings
      }

*/
}
