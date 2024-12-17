<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Follower;

class FollowerController extends Controller
{
    // Follow a User
    public function follow(Request $request)
    {
        $request->validate([
            'followee_id' => 'required|exists:users,id',
        ]);
        try {
            $follower_id = auth()->id();
            $followee_id = $request->input('followee_id');

            // Check if already following
            if (Follower::where('follower_id', $follower_id)->where('followee_id', $followee_id)->exists()) {
                return response()->json(['message' => 'Already following this user'], 400);
            }

            $follower = Follower::create([
                'follower_id' => $follower_id,
                'followee_id' => $followee_id,
            ]);

            return response()->json([
                'follower' => [
                    'id' => $follower->id,
                    'follower_id' => $follower->follower_id,
                    'user' => [
                        'id' => $follower->follower->id,
                        'name' => $follower->follower->name,
                        'image' => url('/') . $follower->follower->image
                    ],
                ],
                'following' => [
                    'id' => $follower->id,
                    'followee_id' => $follower->followee_id,
                    'user' => [
                        'id' => $follower->followee->id,
                        'name' => $follower->followee->name,
                        'image' => url('/') . $follower->followee->image
                    ],
                ],
                'success' => 'OK'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Unfollow a User
    public function unfollow($id)
    {
        try {
            $follower = Follower::find($id);
            if (count(collect($follower)) > 0) {
                $follower->delete();
            }

            return response()->json([
                'follower' => [
                    'id' => $follower->id,
                    'follower_id' => $follower->follower_id,
                    'user' => [
                        'id' => $follower->follower->id,
                        'name' => $follower->follower->name,
                        'image' => url('/') . $follower->follower->image
                    ],
                ],
                'following' => [
                    'id' => $follower->id,
                    'followee_id' => $follower->followee_id,
                    'user' => [
                        'id' => $follower->followee->id,
                        'name' => $follower->followee->name,
                        'image' => url('/') . $follower->followee->image
                    ],
                ],
                'success' => 'OK'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Count Following
    public function countFollowing()
    {
        $count = Follower::where('follower_id', auth()->id())->count();
        return response()->json(['following_count' => $count]);
    }

    // Count Followers
    public function countFollowers()
    {
        $count = Follower::where('followee_id', auth()->id())->count();
        return response()->json(['followers_count' => $count]);
    }
}
