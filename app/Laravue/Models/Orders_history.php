<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Orders_history extends Model
{
    public $guarded = [];
    protected $table = "orders_history";
    public $timestamps = false;
}
