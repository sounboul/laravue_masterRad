<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Laravue\JsonResponse;
use App\Laravue\Models\MemberLevel;
use App\Laravue\Models\Discount_definitions;
use App\Laravue\Models\PointsDefinitions;

class MemberLevelController extends Controller
{
    public function index(Request $request)
    {
    	function myfunction($v)
		{
			$v->discount_percent = $v->discount_percent/10;
		  	return($v);
		}

		$order = $request->sort;
        $limit = $request->limit;
        $type = $request->type;

        if ($request->sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }

    	$discounts = MemberLevel::orderBy('to_point', $order)
    								->where('level', $type)
    								->get();

    	foreach ($discounts as $discount) {
    		$discount = myFunction($discount);
    	}



    	return response()->json(new JsonResponse(['items' => $discounts, 'type' => $type, 'limit' => $limit]));
    }



    public function fetchLevels()
    {
    	function myfunction($v)
		{
			$v->discount_percent = $v->discount_percent/10;
		  	return($v);
		}

    	$levels = MemberLevel::orderBy('to_point', 'asc')->get();
    	
    	foreach ($levels as $level) {
    		$level = myFunction($level);
    	}

    	return response()->json(new JsonResponse(['levels' => $levels]));
    }



    public function updateLevel(Request $request)
    {
    	$level_id = MemberLevel::where('level', $request->type)->first();
    	$level = MemberLevel::find($level_id);

    	return response()->json(new JsonResponse(['items' => $level, 'type' => $request->type, 'limit' => $request->limit]));
    }



    public function updateLevelData(Request $request)
    {    	
    	if($request->no_switch === 0)
    	{
	    	if ($request->timestamp1 === 'Invalid Date' || $request->timestamp2 === 'Invalid Date' ||
	    		$request->timestamp1 === '1/1/1970, 1:00:00 AM' || $request->timestamp2 === '1/1/1970, 1:00:00 AM' ||
	    		$request->timestamp1 === null || $request->timestamp2 === null) 
	    	{
	    		$title = 'discounts.warning';
	    		$message = 'discounts.timestamp_is_required';
	    		$type = 'error';
	    	}

	    	elseif (date_format(date_create($request->timestamp1), 'd.m.Y. H:i:s') > date_format(date_create($request->timestamp2), 'd.m.Y. H:i:s')) 
	    	{
	    		$title = 'table.update_failed';
	    		$message = 'discounts.date_less_than';
	    		$type = 'error';
	    	}
	    }
    	else 
    	{
    		if($request->no_switch === 0) 
    		{
    			$discount_start_date = date_format(date_create($request->timestamp1), 'd.m.Y. H:i:s');
    			$discount_end_date = date_format(date_create($request->timestamp2), 'd.m.Y. H:i:s');
    		}
    		else 
    		{
    			$discount_start_date = null;
    			$discount_end_date = null;
    		}

    		$title = 'table.success';
    		$message = 'table.updated_successfully';
    		$type = 'success';
    	}

    	return response()->json(new JsonResponse(['title' => $title, 'message' => $message, 'type' => $type ]));
    }
}
