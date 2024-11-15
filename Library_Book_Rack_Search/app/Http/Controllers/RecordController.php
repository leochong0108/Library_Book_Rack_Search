<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Record;

class RecordController extends Controller
{
    public function getAllRecord(){

        $record = Record::with('user','book')->get();

        return response()->json([
            'success'=> true,
            'data'=> $record,
        ]);

    }

    public function updateRecord(Request $request,$id){

        $record = Record::findOrFail($id);

        $validated = $request->validate([

            'remark'=>'nullable',

        ]);

        $record->update($validated);

        return response()->json([
            'success'=> true,
        ]);

    }

    public function deleteRecord($id){

        $record = Record::findOrFail($id);

        $record->delete();

        return response()->json([
            'success'=>true,
        ]);
    }

    public function countRentedBook(Request $request){
        $returned_book_ids = Record::select('book_id')->where('remark', 'return')->where('user_id', auth()->id())->pluck('book_id')->toArray();

        $count_rented_book = Record::where('remark', 'rented')->where('user_id', auth()->id())->whereNotIn('book_id', $returned_book_ids)->count();

        return response()->json([
            'count_rented_book' => $count_rented_book,
        ]);
    }

    public function getRentedBook(Request $request){
        $returned_book_ids = Record::select('book_id')->where('remark', 'return')->where('user_id', auth()->id())->pluck('book_id')->toArray();

        $rented_books = Record::with('book')->where('remark', 'rented')->where('user_id', auth()->id())->whereNotIn('book_id', $returned_book_ids)->get();

        return response()->json([
            'rented_books' => $rented_books,
        ]);
    }
}
