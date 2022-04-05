<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function transaction($transNum, Transaction $trans) {
        if ($trans->isTransactionExists($transNum)) {
            return response()->json([
                'transactionReferenceNumber' => $transNum,
                'message' => 'Transferred',
                'status' => 1
            ], 200);
        }
        else {
            return response()->json([
                'transactionReferenceNumber' => $transNum,
                'message' => 'Pending',
                'status' => 0
            ], 200);
        }
    }
}
