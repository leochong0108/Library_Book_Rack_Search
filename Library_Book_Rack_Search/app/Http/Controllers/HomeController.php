<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Book;
use App\Models\Record;
use App\Models\bookracks;

class HomeController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function getDashboardData(){
        $count_category = Category::count();
        $count_book = Book::count();
        $count_book_rack = bookracks::count();
        $count_record = Record::count();

        return response()->json([
            'count_category' => $count_category,
            'count_book' => $count_book,
            'count_book_rack' => $count_book_rack,
            'count_record' => $count_record,
        ]);
    }
}
