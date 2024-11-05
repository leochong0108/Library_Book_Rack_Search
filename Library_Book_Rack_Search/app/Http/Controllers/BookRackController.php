<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book_Rack;

class BookRackController extends Controller
{
    public function getAllRack(){

        $bookRack = Book_Rack::all();

        return response()->json([
            'data' => $bookRack,
            'success' => true,
        ]);

    }

    public function createRack(Request $request){

        $validated = $request->validated([

            'rack_layer' => 'nullable',
            'floor' => 'nullable',
            'range' => 'nullable',

        ]);

        Book_Rack::create($validated);

        return response()->json([
            'success'=>true,
        ]);

    }

    public function updateRack(Request $request, $id){

        $book_rack = Book_Rack::findOrFail($id);

        $validated = $request->validated([

            'rack_layer' => 'nullable',
            'floor' => 'nullable',
            'range' => 'nullable',

        ]);

        $book_rack->update($validated);

        return $book_rack;
    }

    public function deleteRack($id){

        $bookRack = Book_Rack::findOrFail($id);

        $bookRack->delete();

        return response()->json([
            'success'=> true,
        ]);

    }
}
