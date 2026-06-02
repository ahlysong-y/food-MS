<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    // ទំនាក់ទំនង៖ ការបង់ប្រាក់មួយ ជារបស់វិក្កយបត្រ (Order) មួយ
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
