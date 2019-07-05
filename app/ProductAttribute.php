<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
