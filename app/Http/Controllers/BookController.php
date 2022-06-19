<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\TripayService;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::latest()->get();
         return view('book.index', compact('books'));
    }

    public function show(Book $book)
    {
         return view('book.show', compact('book'));
    }

    public function checkout(Book $book)
    {
        $tripay = new TripayService();
        $channels = $tripay->getPaymnet();
        
        return view('book.checkout', compact('book', 'channels'));
    }
}
