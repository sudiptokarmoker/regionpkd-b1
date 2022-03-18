<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    public $table = 'payments';
    public $fillable = [
        'id_order',
        'gateway',
        'gateway_reference',
        'amount',
        'status',
        'result',
    ];
}
