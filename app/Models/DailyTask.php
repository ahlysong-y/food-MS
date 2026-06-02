<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTask extends Model
{
    protected $guarded = [];

    // ទំនាក់ទំនង៖ កិច្ចការនេះធ្វើនៅសាខាណា?
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // ទំនាក់ទំនង៖ កិច្ចការនេះធ្វើដោយបុគ្គលិកណា?
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
