<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UsersCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'bio' => $user->bio,
                'image' => url('/') . $user->image,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'followers' => $user->followers->map(function ($follower) {
                    return [
                        'id' => $follower->id,
                        'follower_id' => $follower->follower_id,
                        'user' => [
                            'id' => $follower->follower->id,
                            'name' => $follower->follower->name,
                            'image' => url('/') . $follower->follower->image
                        ],
                    ];
                }),
                'followings' => $user->following->map(function ($following) {
                    return [
                        'id' => $following->id,
                        'followee_id' => $following->followee_id,
                        'user' => [
                            'id' => $following->followee->id,
                            'name' => $following->followee->name,
                            'image' => url('/') . $following->followee->image
                        ],
                    ];
                }),
            ];
        });
    }
}
