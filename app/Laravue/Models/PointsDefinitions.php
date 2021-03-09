<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class PointsDefinitions extends Model
{
    public $guarded = [];
    protected $table = "points_definitions";

    public static function getData($data)
    {
    	return PointsDefinitions::where($data, '!=', 0)->orderBy('created_at', 'desc')->first()->$data;
    }
}
