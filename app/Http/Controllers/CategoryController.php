<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::latest()->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string',
            'description'   => 'required|string',
        ]);
        $category = category::create($request->all());
        return response()->json($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        try {
            $category = category::findOrFail($id);
            $book = Book::where('category_id', $category->id)->get();
            return $this->success($book);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $this->error('category not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Int $id)
    {
        $request->validate([
            'name'          => 'required|string',
            'description'   => 'required|string',
        ]);

        try {
            $category = category::findOrFail($id);
            $category->update($request->all());
            return response()->json($category);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'category not update'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        try {
            $category = category::findOrFail($id);
            $category->delete();
            $id = $category->id;
            return response()->json("category $id deleted");
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'category not deleted'], 400);
        }
    }
}
