<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
    ];

    public $timestamps = false;

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
