<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'price' => 'required|numeric',
            'picture' => 'image'
        ]);

        Book::create($data);
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        return  Book::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book, int $id)
    {
        $book = $book->findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'price' => 'required|numeric',
            'picture' => 'image'
        ]);

        $book->update($request->all());
       return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book, int $id)
    {
        $book = $book->findOrFail($id)->delete();
        return response()->json(true);
    }
}
