<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Book;

class HomeController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function getDashboardData(){
        $count_category = Category::count();
        $count_book = Book::count();

        return response()->json([
            'count_category' => $count_category,
            'count_book' => $count_book
        ]);
    }
}
