<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiningTable extends Model
{
    protected $guarded = [];

    // ទំនាក់ទំនង៖ តុនេះជារបស់សាខាណា?
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // ទំនាក់ទំនង៖ តុមួយ អាចមានភ្ញៀវមកអង្គុយកុម្ម៉ង់ (Orders) ច្រើនដង
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
