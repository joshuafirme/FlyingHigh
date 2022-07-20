<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HubTransfer;
use App\Models\Hub;
use App\Exports\HubTransferExport;
use Maatwebsite\Excel\Facades\Excel;
use Utils;

class HubTransferReportController extends Controller
{
   public function index(HubTransfer $sa) {
        $hub_transfer = $sa->getAllPaginate(10);
        $hubs = Hub::where('status', 1)->get();
        return view('reports.hub-transfer.index', compact('hub_transfer','hubs'));
    }

    public function filterHubTransfer(HubTransfer $sa) {

        $hub_transfer = $sa->filterPaginate(10);
        $hubs = Hub::where('status', 1)->get();
        return view('reports.hub-transfer.index', compact('hub_transfer','hubs'));
    }

    public function previewReport($date_from, $date_to, HubTransfer $sa){
        
        $items = Utils::objectToArray($sa->filter($date_from, $date_to));
        $title = "Hub Transfer Report";
        $headers = $sa->getHeaders();
        $columns = $sa->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->stream($date_from.'-to-'.$date_to.'.pdf');
    }
    
    public function downloadReport($date_from, $date_to, HubTransfer $sa){
        
        $items = Utils::objectToArray($sa->filter($date_from, $date_to));
        $title = "Hub Transfer Report";
        $headers = $sa->getHeaders();
        $columns = $sa->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download('hub-transfer-report-'.$date_from.'-to-'.$date_to.'.pdf');
    }

    public function exportReport()
    {
         return Excel::download(new HubTransferExport, 'hub-transfer-report.xlsx');
    }
}
