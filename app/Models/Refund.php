<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'User',
        'type',
        'description',
        'value',
        'date',
    
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


}
