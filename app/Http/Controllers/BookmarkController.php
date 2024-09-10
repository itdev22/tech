<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormat;
use App\Http\Requests\StoreBookmarkRequest;
use App\Http\Requests\UpdateBookmarkRequest;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $bookmarks = Bookmark::paginate();
            return response()->json(ResponseFormat::Success($bookmarks, 'Success Get Data', 200), 200);
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
            'post_id' => 'required|integer',
        ];
        try {
            $validated = $request->validate($rules);
            $bookmark = Bookmark::create([
                'post_id' => $validated['post_id'],
                'user_id' => auth()->user()->id
            ]);
            return response()->json(ResponseFormat::Success($bookmark, 'Bookmark Created', 201), 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
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
            $bookmark = Bookmark::find($id);
            if (!$bookmark) {
                return response()->json(ResponseFormat::BadRequest('Bookmark not found', 404), 404);
            }
            return response()->json(ResponseFormat::Success($bookmark, 'Bookmark Detail', 200), 200);
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
    public function edit(Bookmark $bookmark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookmarkRequest $request, Bookmark $bookmark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bookmark $bookmark)
    {
        //
    }
}
