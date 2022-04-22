<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';

    protected $fillable = [
        'transactionReferenceNumber',
        'transactionType',
    ];

    public function isTransactionExists($transRefNum) {
        $res = self::where('transactionReferenceNumber', $transRefNum)->get();
        return count($res) > 0 ? true : false;
    }

       public function getHeaders() {
        return ['Transaction Reference Number', 'Transaction Type', 'Created at'];
    }

     public function getColumns() {
        return ['transactionReferenceNumber', 'transactionType', 'created_at'];
    }

    public function getAllPaginate($per_page) {
        return self::orderBy('created_at', 'desc')
            ->whereDate('created_at', date('Y-m-d'))
            ->paginate($per_page);
    }

   public function filterPaginate($per_page) {
        return self::orderBy('created_at', 'desc')
            ->whereBetween(DB::raw('DATE(created_at)'), [request()->date_from, request()->date_to])
            ->paginate($per_page);
    }

    public function filter($date_from, $date_to) {
        $date_from = $date_from ? $date_from : date('Y-m-d');
        $date_to = $date_to ? $date_to : date('Y-m-d');
        return self::select('transactionReferenceNumber', 'transactionType', 'created_at')
        ->orderBy('created_at', 'desc')
            ->whereBetween(DB::raw('DATE(created_at)'), [$date_from, $date_to])
            ->get();
    }
}
