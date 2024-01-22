<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;


    protected $fillable=[
        'post_id',
        'client_id',
        'status'
    ];



    public function post()
    {
        return $this->belongsTo(Post::class)->select('id', 'content');
    }


    public function client()
    {
        return $this->belongsTo(Client::class)->select('id', 'name');
    }
}
