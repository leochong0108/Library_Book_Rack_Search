<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Record;

class RecordController extends Controller
{
    public function getAllRecord(){

        return Record::All();

    }

    public function updateRecord(Request $request,$id){

        $record = Record::findOrFail($id);

        $request->validated([

            'remark'=>'nullable',

        ]);

        $record->update($request->all());

        return $record;

    }

    public function deleteRecord($id){

        return Record::destroy($id);

    }
}
