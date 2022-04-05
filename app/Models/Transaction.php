<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
