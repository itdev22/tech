<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormat;
use App\Http\Requests\StorePostCategoryRequest;
use App\Http\Requests\UpdatePostCategoryRequest;
use App\Models\PostCategory;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $postCategories = PostCategory::all();
            return response()->json(ResponseFormat::Success($postCategories, 'Get Post Category', 200), 200);
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required|string|unique:post_categories'
        ];
        try {
            $validated = $request->validate($rules);
            $postCategory = PostCategory::create($validated);
            return response()->json(ResponseFormat::Success($postCategory, 'Post Category Created', 201), 201);

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
            $postCategory = PostCategory::find($id);
            if (!$postCategory) {
                return response()->json(ResponseFormat::BadRequest('Post Category not found', 404), 404);
            }
            return response()->json(ResponseFormat::Success($postCategory, 'Post Category Detail', 200), 200);

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
    public function edit(PostCategory $postCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name'=>'required|string|unique:post_categories'
        ];

        try {
            $validated = $request->validate($rules);
            $postCategory = PostCategory::find($id);
            if (!$postCategory) {
                return response()->json(ResponseFormat::BadRequest('Post Category not found', 404), 404);
            }
            $postCategory->update($validated);

            return response()->json(ResponseFormat::Success($postCategory, 'Post Category Updated', 200), 200);
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
            $postCategory = PostCategory::find($id);
            if (!$postCategory) {
                return response()->json(ResponseFormat::BadRequest('Post Category not found', 404), 404);
            }
            $postCategory->delete();

            return response()->json(ResponseFormat::Success('', 'Post Category Deleted', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
