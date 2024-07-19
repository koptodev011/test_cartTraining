<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'plane_name',
        'plane_fees',
        'plane_duration',
        'active',
        'isdelete'
    ];

}
