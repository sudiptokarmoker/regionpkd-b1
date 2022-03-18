<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $table = 'orders';
    public $fillable = [
        'id_user',
        'amount',
        'currency',
        'reference',
        'description',
        'status',
    ];
}
