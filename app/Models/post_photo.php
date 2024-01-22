<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post_photo extends Model
{
    use HasFactory;



    protected $fillable = [
        'post_id',
        'photo', 
    ];


    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
