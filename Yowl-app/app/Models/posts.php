<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comments extends Model
{
    use HasFactory;
    protected $fillable = ['body', 'post_id', 'user_id'];
    

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
        
    }
    public function post()
    {
        return $this->belongsTo(posts::class, 'post_id');  
    }
}