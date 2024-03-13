<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\comment;
use App\Models\like;
use App\Models\User;

class post extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'body',
        'image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);

    }
    public function comments()
    {
        return $this->hasMany(comment::class);
    }

    public function likes()
    {
        return $this->hasMany(like::class);
    }

    public function flags()
    {
        return $this->hasMany(Flag::class);
    }
}
