<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllPostsCollection;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $posts = Post::orderBy('created_at', 'desc');
            if (isset($request->search)) {
                $posts = $posts->whereHas('user', function ($query) use($request) {
                    return $query->where('name', 'like', '%' . $request->search . '%');
                });
            }
            if (isset($request->following) && $request->following && auth()->user()) {
                $posts = $posts->whereHas('user', function ($query) {
                    return $query->whereIn('id', auth()->user()->following()->pluck('followee_id')->toArray());
                });
            }
            $posts = $posts->get();
            return response()->json(new AllPostsCollection($posts), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
