<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    // អនុញ្ញាតអោយបញ្ជូលទិន្នន័យបានគ្រប់ Columns
    protected $guarded = [];

    // ទំនាក់ទំនង៖ ម្ហូបមួយមុខ អាចមាននៅក្នុងការកុម្ម៉ង់ (Order Items) ច្រើន
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
