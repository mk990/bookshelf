<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return book::latest()->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'title' => 'required',
            'author' => 'required',
            'price' => 'required|numeric',
            'picture' => 'required|url',
        ]);
    
        $book = new Book();
        $book->user_id = $validatedData['user_id'];
        $book->title = $validatedData['title'];
        $book->author = $validatedData['author'];
        $book->price = $validatedData['price'];
        $book->picture = $validatedData['picture'];
        $book->save();
    
        return response()->json(['message' => 'Book stored successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
{
    $book = Book::find($id);

    if (!$book) {
        return response()->json(['message' => 'Book not found'], 404);
    }

    return response()->json($book, 200);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
{
    $validatedData = $request->validate([
        'user_id' => 'required',
        'title' => 'required',
        'author' => 'required',
        'price' => 'required|numeric',
        'picture' => 'required|url',
    ]);

    $book->user_id = $validatedData['user_id'];
    $book->title = $validatedData['title'];
    $book->author = $validatedData['author'];
    $book->price = $validatedData['price'];
    $book->picture = $validatedData['picture'];
    $book->save();

    return response()->json(['message' => 'Book updated successfully'], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
{
    $book->delete();

    return response()->json(['message' => 'Book deleted successfully'], 200);
}
}
