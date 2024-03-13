<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Flag extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'post_id',
        'reason',
        'body',
        'image'
    ];
}