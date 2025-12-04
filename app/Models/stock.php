<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class stock extends Model
{
    //
    protected $table = 'stocks';

    protected $fillable = [
        'stock_no',
        'item_code',
        'item_name',
        'quantity',
        'location',
        'store_name',
        'in_stock_date',
        'status',
    ];

    protected $casts = ['in_stock_date' => 'date'];

    // public function store()
    // {
    //     return $this->belongsTo(store::class);
    // }
}
