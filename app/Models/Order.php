<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function diningTable()
    {
        return $this->belongsTo(DiningTable::class);
    }
    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
