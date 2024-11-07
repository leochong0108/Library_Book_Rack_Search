<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getAllCategory()
    {
        $categories = Category::all();

        return response()->json([
            'data' => $categories,
            'success' => true,
        ]);
    }


    public function createCategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string'
        ]);

        Category::create($validatedData);

        return response()->json(['success'=>true]);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string'
        ]);

        $category->update($validatedData);

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Category updated successfully'
        ]);
    }


    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function getCategory(Request $request){
        $category = Category::findOrFail($request->id);

        return response()->json(['category' => $category]);
    }
}
