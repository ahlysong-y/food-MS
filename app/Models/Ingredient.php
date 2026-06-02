<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $guarded = [];

    // ទំនាក់ទំនង៖ គ្រឿងផ្សំមួយមុខ អាចមាននៅក្នុងស្តុក (Inventories) នៃសាខាផ្សេងៗគ្នា
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
