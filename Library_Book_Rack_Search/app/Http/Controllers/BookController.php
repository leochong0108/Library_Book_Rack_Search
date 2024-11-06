<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Record;
use Carbon\Carbon;

class BookController extends Controller
{
    public function getAllBook(){

        $books = Book::all();

        return response()->json([
            'data' => $books,
            'success' => true,
        ]);
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

        $validated = $request->validate([
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

        Book::create($validated);

        return response()->json(['success'=>true]);

    }

    public function updateBook(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([

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

        $book->update($validated);

        return response()->json([
            'success' => true,
        ]);

    }

    public function deleteBook($id){

        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            'success' => true,
        ]);

    }

    public function rentBook($id){

        DB::beginTransaction();

        try{
            $book = Book::findOrFail($id);

            if(!$book->is_available){
                return response()->json(['success' => false, 'message' => 'Book is unavailable']);
            }

            $expirationDate = Carbon::now()->addDays($book->duration);

            Record::create([
                'book_id' => $book->id,
                'remark' => 'rented',
                'action' => 'rent book',
                'expired_at' => $expirationDate,
            ]);

            $book->update(['is_available'=> false]);

            DB::commit();

            return response()->json([
                'success'=>true
            ]);
        }
        catch(\Exception $e){

            DB::rollBack();

            return response()->json([

                'success'=>false,
                'message' => 'An error occurred while renting the book: ' . $e->getMessage()

            ], 500);

        }
    }

    public function findBookByScan($id){

         $book = Book::findOrFail($id);

         return response()->json([
            'success' => true,
            'data' => $book,
         ]);
    }

}
