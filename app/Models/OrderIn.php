<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderIn extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function modelType()
    {
        return $this->belongsTo(ModelType::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
