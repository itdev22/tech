<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormat;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        try {
            $posts = Cache::remember('posts', 60, function () {
                return Post::with('media')->get();
            });

            // $posts = Post::with('media')->get();

            return response()->json(ResponseFormat::Success($posts, 'Success Get data', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|integer',
            'slug' => 'required|string|unique:posts'
        ];
        try {
            $validated = $request->validate($rules);
            $post = Post::create($validated);
            if($request->file('image')){
                $post->addMedia($request->file('image'))->toMediaCollection('posts');
            }

            return response()->json(ResponseFormat::Success($post, 'Post Created', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post =  Post::where('id', $id)->with('media')->first();

            return response()->json(ResponseFormat::Success($post, 'Success Get data', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'title' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|integer',
            'slug' => 'required|string|unique:posts'
        ];
        try {
            $validated = $request->validate($rules);
            $post = Post::find($id);
            if($request->file('image')){
                $post->addMedia($request->file('image'))->toMediaCollection('posts');
            }
            $validated['updated_by'] = Auth::user()->id;
            $post->update($validated);

            return response()->json(ResponseFormat::Success($post, 'Post Updated', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return response()->json(ResponseFormat::BadRequest('Post not found', 404), 404);
            }
            $post->delete();
            $post->media()->delete();
            return response()->json(ResponseFormat::Success($post, 'Post Deleted', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
