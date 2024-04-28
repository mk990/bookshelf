<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Book::latest()->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'author'  => 'required',
            'price'   => 'required|numeric',
            'picture' => 'required|image',
        ]);
        try {
            $imagePath = '/picture/' . now()->year . '/' . now()->month . '/';
            $image = $request->file('picture');
            $upload = $this->uploadFile($imagePath, $image);

            $request->merge(['image'=> $upload]);
            $request->merge(['user_id' => auth()->user()->id]);
            $book = Book::create($request->all());
            return response()->json($book);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function uploadFile($path, $file)
    {
        $filename = date('ymd-hms') . '.' . $file->extension();
        $file->move(public_path($path), $filename);
        return response()->json($file . $path);
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        try {
            $book = Book::findOrFail($id);
            return response()->json($book);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not found'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Int $id)
    {
        $request->validate([
            'title'   => 'required',
            'author'  => 'required',
            'price'   => 'required|numeric',
            'picture' => 'required|url',
        ]);
        $request->merge(['user_id' => auth()->user()->id]);

        try {
            $book = Book::findOrFail($id);
            $book->update($request->all());
            return response()->json($book);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not created'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();
            $id = $book->id;
            return response()->json("book $id deleted");
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Book not deleted'], 400);
        }
    }
}
