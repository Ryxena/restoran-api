<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionReport extends Model
{
    use HasFactory;

    protected $table = 'transaction_reports';

    protected $fillable = ['order_id', 'date', 'amount', 'status'];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_id', 'id');
    }
}
