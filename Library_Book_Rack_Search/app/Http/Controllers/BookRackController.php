<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book_Rack;

class BookRackController extends Controller
{
    public function getAllRack(){

        return Book_Rack::all();

    }

    public function createRack(Request $request){

        $request->validated([

            'rack_layer' => 'nullable',
            'floor' => 'nullable',
            'range' => 'nullable',

        ]);

        return Book_Rack::create($request->all());

    }

    public function updateRack(Request $request, $id){

        $book_rack = Book_Rack::findOrFail($id);

        $request->validated([

            'rack_layer' => 'nullable',
            'floor' => 'nullable',
            'range' => 'nullable',

        ]);

        $book_rack->update($request->all());

        return $book_rack;
    }

    public function deleteRack($id){

        return Book_Rack::destroy($id);

    }
}
