<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookracks;

class BookRackController extends Controller
{
    public function getAllRack(){

        $bookRack = bookracks::all();

        return response()->json([
            'data' => $bookRack,
            'success' => true,
        ]);

    }

    public function createRack(Request $request){

        $validated = $request->validate([

            'rack_layer' => 'nullable',
            'floor' => 'nullable',
            'range' => 'nullable',
            'start' => 'nullable',
            'end' => 'nullable',

        ]);

        bookracks::create($validated);

        return response()->json([
            'success'=>true,
        ]);

    }

    public function updateRack(Request $request, $id){

        $book_rack = bookracks::findOrFail($id);

        $validated = $request->validate([

            'rack_layer' => 'nullable',
            'floor' => 'nullable',
            'range' => 'nullable',
            'start' => 'nullable',
            'end' => 'nullable',

        ]);

        $book_rack->update($validated);

        return $book_rack;
    }

    public function deleteRack($id){

        $bookRack = bookracks::findOrFail($id);

        $bookRack->delete();

        return response()->json([
            'success'=> true,
        ]);

    }
}
