<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProcess extends Model
{
    use SoftDeletes;
    
    protected $guarded = ['id'];

    public function orderIn()
    {
        return $this->belongsTo(OrderIn::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }
}
