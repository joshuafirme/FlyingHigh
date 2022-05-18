<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransferRequestImport;
use App\Models\TransferRequest;
use Utils;

class TransferRequestController extends Controller
{
    public function index(TransferRequest $tr) 
    {
        $transfer_request = $tr->getAllPaginate(10);
        return view('transfer-request.index', compact('transfer_request'));
    }

    public function import(Request $request) 
    {
        Excel::import(new TransferRequestImport, $request->file('file')->store('temp'));
        return redirect()->back()->with('success', 'Data was successfully imported.');
    }
}
