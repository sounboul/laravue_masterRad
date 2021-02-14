<?php

namespace App\Laravue\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    public $guarded = [];
    protected $table = "suppliers";
    public $timestamps = false;
}
