<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class store extends Model
{
    //
    protected $table = 'stores';

    protected $fillable = [
        'store_name',
        'location',

    ];

    // public function stocks()
    // {
    //     return $this->hasMany(Stock::class);
    // }
}
