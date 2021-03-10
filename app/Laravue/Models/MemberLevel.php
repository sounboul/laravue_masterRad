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
    	$members = memberLevel::all();
    	foreach ($members as $member) {
    		if ($customer_total_points >= $member->from_point 
                && 
                $customer_total_points <= $member->to_point) 
            {
    			$pom = $member->level; 
    		}
    	}
    	return $pom;
    }

}
