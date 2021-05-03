<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    public $guarded = [];
    protected $table = "orders";
    public $timestamps = false;
}
