<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Cashing extends Model
{
    public $guarded = [];
    protected $table = "cashing";

    public static function get_cashing($customer_id, $date_of_cashing=null)
    {
    	if ($date_of_cashing == null) {
    		$date_of_cashing = now();
    	}
    	$all_customers_cashings = Cashing::select('*')->where('customer_id', $customer_id)
    												->where('created_at', '<=', $date_of_cashing)
    												->sum('cashed_points');
    	return $all_customers_cashings;
    }
}
