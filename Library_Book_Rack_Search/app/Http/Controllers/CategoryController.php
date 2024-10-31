<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getAllCategory(){

        return Category::all();

    }

    public function createCategory(Request $request){

        $request->validated([

            'name'=>'nullable'

        ]);

        return Category::create($request->all());

    }

    public function updateCategory(Request $request,$id){

        $category = Category::findOrFail($id);

        $request->validated([

            'name'=>'nullable'

        ]);

        $category->update($request->all());

        return $category;

    }

    public function deleteCategory($id){

        return Category::destroy($id);

    }
}
