<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormat;
use App\Http\Requests\StoreBookmarkRequest;
use App\Http\Requests\UpdateBookmarkRequest;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
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

    public function store(Request $request)
    {
        $rules = [
            'post_id' => 'required|integer',
        ];
        try {
            $validated = $request->validate($rules);
            $bookmarkCheck = Bookmark::where('post_id', $validated['post_id'])->where('user_id', auth()->user()->id)->first();
            if ($bookmarkCheck) {
                return response()->json(ResponseFormat::BadRequest('Bookmark already exist', 400), 400);
            }
            $bookmark = Bookmark::create([
                'post_id' => $validated['post_id'],
                'user_id' => auth()->user()->id
            ]);
            return response()->json(ResponseFormat::Success($bookmark, 'Bookmark Created', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

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

    public function destroy(Request $request)
    {
        $rules = [
            'post_id' => 'required|integer',
        ];
        try {
            $validated = $request->validate($rules);
            $bookmarkCheck = Bookmark::where('post_id', $validated['post_id'])->where('user_id', auth()->user()->id)->first();
            if (!$bookmarkCheck) {
                return response()->json(ResponseFormat::BadRequest('Bookmark Notfound', 400), 400);
            }
            $bookmarkCheck->delete();
            return response()->json(ResponseFormat::Success(null, 'Bookmark Deleted', 200), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
