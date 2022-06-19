<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Services\TripayService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __consruct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    public function show($reference)
    {
        $detail = $this->tripayService->detailTransaction($reference);
        return view('transaction.show', compact('detail'));
    }

    public function store(Request $request) 
    {
        //request transaction in tripay
        $book = Book::find($request->book_id);
        $method = $request->method;

        $transaction = $this->tripayService->requestTransaction($method, $book);

        //create new data
        Transaction::create([
            'user_id' => auth()->user()->id,
            'book_id' => $book->id,
            'reference' => $transaction->reference,
            'merchant_ref' => $transaction->merchant_ref,
            'total_amount' => $transaction->amount,
            'status' => $transaction->status
        ]);

        return redirect()->route('transaction.show', [
            'reference' => $transaction->reference
        ]);
    }
}
