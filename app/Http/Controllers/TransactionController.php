<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index() { 
        $transactions = Transaction::orderBy('created_at', 'desc')->paginate(15);
        return view('transaction.index', compact('transactions'));
    }
}
