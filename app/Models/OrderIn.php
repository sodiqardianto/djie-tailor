<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderIn extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function model_type()
    {
        return $this->belongsTo(ModelType::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
