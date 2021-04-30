<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class api_routes extends Model
{
    protected $guarded = [];
    protected $table = "api_routes";
    public $timestamps = false;

    public function route_name()
    {
    	return $this->belongsTo(route_name::class);
    }
}
