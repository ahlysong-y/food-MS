<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
