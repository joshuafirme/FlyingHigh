<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pickup;
use App\Exports\PickupExport;
use Maatwebsite\Excel\Facades\Excel;
use Utils;

class PickupReportController extends Controller
{
    public function index($slug, Pickup $pickup) {

        $status = Utils::getPickupStatusBySlug($slug);
        $pickups = $pickup->getAllPaginate(10, $status);
        $status_title = ucfirst(str_replace('-', ' ', $slug));
        return view('reports.pickup.index', compact('pickups','status_title','status','slug'));
    }

    public function filterPickup(Pickup $pickup) {

        $status = request()->status;
   
        $pickups = $pickup->filterPaginate(10, $status);
        $status_title = '';
        return view('reports.pickup.index', compact('pickups','status_title','status'));
    }

    public function previewReport($date_from, $date_to, $status, Pickup $pickup){
        
        $items = Utils::objectToArray($pickup->filter($date_from, $date_to, $status));
        $title = Utils::getPickupStatusText($status) . " Report";
        $headers = $pickup->getHeaders();
        $columns = $pickup->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->stream($date_from.'-to-'.$date_to.'.pdf');
    }
    
    public function downloadReport($date_from, $date_to, Pickup $pickup){
        
        $items = Utils::objectToArray($pickup->filter($date_from, $date_to));
        $title = "Pickup Report";
        $headers = $pickup->getHeaders();
        $columns = $pickup->getColumns();
        
        $output = Utils::renderReport($items, $title, $headers, $columns, $date_from, $date_to);
       
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download('pickup-report-'.$date_from.'-to-'.$date_to.'.pdf');
    }

    public function exportReport()
    {
         return Excel::download(new PickupExport, 'pickup-report.xlsx');
    }
}
