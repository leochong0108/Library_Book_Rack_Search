<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function getAllBook(){

        return Book::all();
    }


    public function search($input)
    {
        $Posts = Book::query()
            ->where('title', 'like', '%' . $input . '%')
            ->orWhere('category_id', $input)
            ->get();

        return $Posts;
    }

    public function searchByTitleOrId(Request $request)
    {
        $query = Book::query();

        // Check if a title is provided
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        // Check if a category_id is provided
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $books = $query->get();

        return $books;
    }

    public function createBook(Request $request){

        $request->validated([
            'category_id'=>'nullable',
            'book_rack_id'=>'nullable',
            'rack_layer'=>'nullable',
            'floor'=>'nullable',
            'location_id'=>'nullable',
            'image'=>'nullable',
            'title'=>'nullable',
            'author'=>'nullable',
            'description'=>'nullable',
            'duration'=>'nullable',
        ]);

        return Book::create($request->all());

    }

    public function updateBook(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validated([

            'category_id'=>'nullable',
            'book_rack_id'=>'nullable',
            'rack_layer'=>'nullable',
            'floor'=>'nullable',
            'location_id'=>'nullable',
            'image'=>'nullable',
            'title'=>'nullable',
            'author'=>'nullable',
            'description'=>'nullable',
            'duration'=>'nullable',

        ]);

        $book->update($request->all());

        return $book;

    }

    public function deleteBook($id){

        return Book::destroy($id);

    }

}
