<?php

namespace App\Laravue\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class memberLevel extends Model
{
    public $guarded = [];
    protected $table = "memberLevel";
    public $timestamps = false;


    public static function findLevel($customer_total_points)
    {
    	$levels = memberLevel::all();
    	foreach ($levels as $key => $level) {
    		if ($customer_total_points >= $level->from_point && $customer_total_points < $level->to_point) {
    			$pom = $level->level; 
    		}
    	}
    	return $pom;
    }

}
