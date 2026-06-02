<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function diningTables()
    {
        return $this->hasMany(DiningTable::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
