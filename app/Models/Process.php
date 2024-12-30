<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $guarded = ['id'];

    public function orderProcesses()
    {
        return $this->hasMany(OrderProcess::class);
    }
}
