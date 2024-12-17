<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    protected $fillable = ['follower_id', 'followee_id'];

    /**
     * The user who is following.
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * The user being followed.
     */
    public function followee()
    {
        return $this->belongsTo(User::class, 'followee_id');
    }
}
