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
    	//dd($discounts);      

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
    	$pom = $level->discount_percent/10;
    	$level->discount_percent = $pom;
dd($level);
    	return response()->json(new JsonResponse(['items' => $level, 'type' => $request->type, 'limit' => $request->limit]));
    }

    public function updateLevelData(Request $request)
    {    	
    	if ($request->timestamp1 > $request->timestamp2) {
    		$title = 'table.update_failed';
    		$message = 'discounts.date_less_than';
    		$type_of_result = 'error';
    	}
    	else {
    		$title = 'table.success';
    		$message = 'table.updated_successfully';
    		$type_of_result = 'success';
    	}
    	$test = date_format(date_create($request->timestamp1), 'd.m.Y. H:i:s');

    	return response()->json(new JsonResponse(['title' => $title, 'message' => $message, 'type' => $type_of_result ]));
    }
}
