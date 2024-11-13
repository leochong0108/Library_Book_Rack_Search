<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Book;
use App\Models\Record;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function getAllBook(){

        $books = Book::with('category')->orderBy('created_at', 'desc')->get();

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
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validate image files
            'title'=>'nullable',
            'author'=>'nullable',
            'description'=>'nullable',
            'duration'=>'nullable',
        ]);

        if($request->image){
            $image = $request->image;
            $filename = time().'_'.$image->getClientOriginalName();
            $path = $image->storeAs('images', $filename, 'public');

            $validated['image'] = $filename;
        }

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
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'title'=>'nullable',
            'author'=>'nullable',
            'description'=>'nullable',
            'duration'=>'nullable',

        ]);

        if($request->image){
            $image = $request->image;
            $filename = time().'_'.$image->getClientOriginalName();
            $path = $image->storeAs('images', $filename, 'public');

            if($book->image && Storage::disk('public')->exists('images/'.$book->image)){
                Storage::disk('public')->delete('images/'.$book->image);
            }

            $validated['image'] = $filename;
        }

        $book->update($validated);

        return response()->json([
            'success' => true,
        ]);

    }

    public function deleteBook($id){

        $book = Book::findOrFail($id);

        if($book->image && Storage::disk('public')->exists('images/'.$book->image)){
            Storage::disk('public')->delete('images/'.$book->image);
        }

        $book->delete();

        return response()->json([
            'success' => true,
        ]);

    }

    public function rentBook($id){

        if(!$user = Auth::user()){
            return response()->json(['success' => false]);
        }

        DB::beginTransaction();

        try{
            $book = Book::findOrFail($id);

            if(!$book->is_available){
                return response()->json(['success' => false, 'message' => 'Book is unavailable']);
            }

            $expirationDate = Carbon::now()->addDays($book->duration);

            Record::create([
                'book_id' => $book->id,
                'user_id' => $user->id,
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

    public function getBookCategory(){
        $categories = Category::select('id','name')->get()->map(function($category){
            return [
                'id' => $category->id,
                'name' => $category->name
            ];
        });

        return response()->json(['categories' => $categories]);
    }

    public function getBook(Request $request){
        $book = Book::findOrFail($request->id);

        return response()->json(['book' => $book]);
    }
}
