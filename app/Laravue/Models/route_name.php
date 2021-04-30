<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class route_name extends Model
{
    protected $guarded = [];
    protected $table = "route_name";
    public $timestamps = false;

    public function api_routes()
    {
    	return $this->hasMany(api_routes::class);
    }
}
