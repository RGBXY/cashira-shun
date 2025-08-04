<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionCalculator;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaleController extends Controller
{
    public function index(){

        $transactions = Transaction::latest()->paginate(20);
        $transactions_cal = TransactionCalculator::latest()->paginate(20);

        return Inertia::render("sales/index", [
            'transactions' => $transactions,
            'transactions_cal' => $transactions_cal
        ]);
    }

    public function searchById(Request $request) {
        $transactions = TransactionDetail::where('transaction_id', $request->id)
                    ->get(); 

        return response()->json([
            'message' => 'Done',
            'transactions' => $transactions
        ]);
    }
}
