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
}
