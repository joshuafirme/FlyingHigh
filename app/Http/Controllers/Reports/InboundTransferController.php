<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionLineItems;
use App\Exports\InboundTransferExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\InboundTransfer;
use Utils;

class InboundTransferController extends Controller
{
    public function index(InboundTransfer $trans) 
    {
        $transfers = $trans->getDataTodayPaginate(10);
        return view('reports.inbound-transfer.index', compact('transfers'));
    }

    public function filter(InboundTransfer $trans) 
    {
        $transfers = $trans->filterPaginate(10);
        return view('reports.inbound-transfer.index', compact('transfers'));
    }

    public function previewReport($date_from, $date_to, InboundTransfer $trans){
        
        $items = Utils::objectToArray($trans->filter($date_from, $date_to));
        $title = "Inbound Transfer Report";
        $headers = $trans->getHeaders();
        $columns = $trans->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->stream($date_from.'-to-'.$date_to.'.pdf');
    }
    
    public function downloadReport($date_from, $date_to, InboundTransfer $trans)
    {
      $items = Utils::objectToArray($trans->filter($date_from, $date_to));
        $title = "Inbound Transfer Report";
        $headers = $trans->getHeaders();
        $columns = $trans->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download('inbound-transfer-'.$date_from.'-to-'.$date_to.'.pdf');
    }

    public function exportReport()
    {
        return Excel::download(
            new InboundTransferExport, 'inbound-transfer-'.request()->date_from.'-to-'.request()->date_to.'.xlsx'
        );
    }

}
