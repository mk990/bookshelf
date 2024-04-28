<?php

namespace App\Http\Controllers;

use App\Models\category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return category::latest()->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string',
            'description'=> 'nullable|string'
        ]);

        try {
            $category = category::create($request->all());
            return response()->json($category);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not created'], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        try {
            $category = category::where('category_id', category::get()->id);
            return response()->json($category);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not found'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name'       => 'required|string',
            'description'=> 'nullable|string'
        ]);

        try {
            $category = category::findOrFail($id);
            $category->update($request->all());
            return response()->json($category);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not created'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $category = category::findOrFail($id);
            $category->delete();
            $id = $category->id;
            return response()->json("book $id deleted");
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not deleted'], 400);
        }
    }
}
