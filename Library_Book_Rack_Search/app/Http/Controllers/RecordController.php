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
        $records = Record::where('user_id', auth()->id())->orderBy('book_id')->orderBy('created_at')->get();

        $count_rented_book = 0;

        foreach ($records->groupBy('book_id') as $book_id => $book_records) {
            $is_rented = false;

            foreach ($book_records as $record) {
                if ($record->remark === 'rented') {
                    $is_rented = true;
                }

                if ($record->remark === 'return') {
                    $is_rented = false;
                }
            }

            if ($is_rented) {
                $count_rented_book++;
            }
        }

        return response()->json([
            'count_rented_book' => $count_rented_book,
        ]);
    }

    public function getRentedBook(Request $request){
        $records = Record::where('user_id', auth()->id())->orderBy('book_id')->orderBy('created_at')->get();

        $rented_books = [];

        foreach ($records->groupBy('book_id') as $book_id => $book_records) {
            $is_rented = false;

            foreach ($book_records as $record) {
                if ($record->remark === 'rented') {
                    $is_rented = true;
                }

                if ($record->remark === 'return') {
                    $is_rented = false;
                }
            }

            if ($is_rented) {
                $latest_rental = $book_records->where('remark', 'rented')->last();
                $rented_books[] = $latest_rental->load('book');
            }
        }

        return response()->json([
            'rented_books' => $rented_books,
        ]);
    }
}
