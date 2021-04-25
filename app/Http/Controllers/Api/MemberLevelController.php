<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Laravue\JsonResponse;
use App\Laravue\Models\MemberLevel;
use App\Laravue\Models\Discount_definitions;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Laravue\Models\PointsDefinitions;
use Illuminate\Support\Facades\Auth;

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


    	return response()->json(new JsonResponse([
    		'items' => $discounts, 
    		'type' => $type, 
    		'limit' => $limit]));
    }



    public function fetchLevels()
    {
    	function myfunction($v)
		{
			$v->discount_percent = $v->discount_percent/10;
		  	return($v);
		}

    	$levels = MemberLevel::orderBy('level_strength', 'asc')->get();
    	
    	foreach ($levels as $level) {
    		$level = myFunction($level);
    	}

    	return response()->json(new JsonResponse(['levels' => $levels]));
    }



    public function updateLevel(Request $request)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && $currentUser->id !== $user->id
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_DISCOUNT_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied!'], 403);
        }
    	$level_id = MemberLevel::where('level', $request->type)->first();
    	$level = MemberLevel::find($level_id->id);

    	return response()->json(new JsonResponse([
    		'items' => $level, 
    		'type' => $request->type, 
    		'limit' => $request->limit
    	]));
    }


    public function update(Request $request)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_DISCOUNT_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied!'], 403);
        }    	

    	$level_num_levels = count(MemberLevel::all());		// ukupan broj nivoa
        $points_limit = PointsDefinitions::limitPoint();
		$last_level_strength = MemberLevel::find($level_num_levels)->level_strength;	// najvisi nivo vaznosti u tabeli

        if ($request->to_point > $points_limit) {
        	if ($request->level_strength = $last_level_strength) {
        		$title = 'discounts.warning';
	    		$message = 'discounts.new_points_limit';
	    		$type = 'error';
    		
    			return response()->json(new JsonResponse([
    				'title' => $title, 
    				'message' => $message, 
    				'type' => $type 
    			]));
        	}
        }

    	if($request->no_switch === 0)    // Ukoliko je cekirano dugme za zahtev unosa datuma
    	{
	    	if ($request->timestamp1 === 'Invalid Date' || $request->timestamp2 === 'Invalid Date' ||
	    		$request->timestamp1 === '1/1/1970, 1:00:00 AM' || $request->timestamp2 === '1/1/1970, 1:00:00 AM' ||
	    		$request->timestamp1 === null || $request->timestamp2 === null)  
	    	{
	    		$title = 'discounts.warning';
	    		$message = 'discounts.timestamp_is_required';
	    		$type = 'error';
    		
    			return response()->json(new JsonResponse([
    				'title' => $title, 
    				'message' => $message, 
    				'type' => $type 
    			]));
	    	}

	    	if (date_format(date_create($request->timestamp1), 'Y-m-d H:i:s') > date_format(date_create($request->timestamp2), 'Y-m-d H:i:s')) 
	    	{
	    		$title = 'table.update_failed';
	    		$message = 'discounts.date_less_than';
	    		$type = 'error';
    			
    			return response()->json(new JsonResponse([
    				'title' => $title, 
    				'message' => $message, 
    				'type' => $type ]));
	    	}
	    }

	    if ($request->from_point > $request->to_point && ($request->from_point > 0 && $request->to_point > 0)) {  // pocetni bodovi veci ili jednaki krajnjim

	    	$title = 'discounts.warning';
    		$message = 'discounts.points_greater_than';
    		$type = 'error';
			
			return response()->json(new JsonResponse([
				'title' => $title, 
				'message' => $message, 
				'type' => $type 
			]));
	    }

	    if ($request->from_point < 0) {   // pocetni bodovi manji od nule

	    	$title = 'discounts.warning';
    		$message = 'discounts.from_points_overload';
    		$type = 'error';
			
			return response()->json(new JsonResponse([
				'title' => $title, 
				'message' => $message, 
				'type' => $type 
			]));
	    }

    	$level = MemberLevel::where('level', $request->level)->first();		// definisanje izabranog nivoa
		$helper_from_point = MemberLevel::where('level_strength', $request->level_strength + 1)->first();  	// pomocni red (row) sa prvom visom vaznoscu nivoa 
		$helper_to_point = MemberLevel::where('level_strength', $request->level_strength - 1)->first();		// pomocni red (row) sa prvom nizom vaznoscu nivoa 

	    
	    if ($request->level_strength < $last_level_strength)		// ukoliko se radi o bilo kom nivou nizem od najjaceg
	    {	
		    if ($request->from_point >= $helper_from_point->from_point)		// slucaj kada su pocetni bodovi request-a veci ili jednaki pocetnim bodovima iz odgovarajuceg reda viseg nivoa
		    {		    	
		    	$title = 'discounts.warning';
	    		$message = 'discounts.to_points_overload';
	    		$type = 'error';
				
				return response()->json(new JsonResponse([
					'title' => $title, 
					'message' => $message, 
					'type' => $type 
				]));
		    }
		}

		if ($request->level_strength > 1)		// slucaj kada se radi o bilo kom nivou jacem od prvog
		{
		    if ($request->from_point <= $helper_to_point->from_point) {		// ukoliko su pocetni bodovi request-a manji ili jednaki krajnjim bodovima prvog nizeg nivoa 
		    	$title = 'discounts.warning';
	    		$message = 'discounts.from_points_overload';
	    		$type = 'error';
				
				return response()->json(new JsonResponse([
					'title' => $title, 
					'message' => $message, 
					'type' => $type 
				]));
		    }

		    if ($request->to_point < $helper_to_point->to_point || 
		    	$request->from_point <= $helper_to_point->from_point) {		// slucaj kada su krajnji bodovi request-a manji od krajnjih bodova prvog nizeg nivoa ili pocetni bodovi request-a su manji ili jednaki pocetnim bodovima prvog nizeg nivoa
		       	$title = 'table.update_failed';
	    		$message = 'discounts.to_points_overload';
	    		$type = 'error';
				
				return response()->json(new JsonResponse([
					'title' => $title, 
					'message' => $message, 
					'type' => $type 
				]));
		    }

			$helper_to_point->to_point = $request->from_point - 1;		// pomocnom redu (redu viseg nivoa) se za krajnje bodove dodeljuju pocetni bodovi request-a umanjeni za 1
			$helper_to_point->updated_at = date('Y-m-d H:i:s');
			$helper_to_point->update();
		}
		
		if($request->level_strength < $level_num_levels)
		{
			if ($request->to_point > $helper_from_point->to_point) {
				
		    	$title = 'discounts.warning';
	    		$message = 'discounts.from_points_overload';
	    		$type = 'error';
				
				return response()->json(new JsonResponse([
					'title' => $title, 
					'message' => $message, 
					'type' => $type 
				]));
			}
		}

		if ($request->level_strength < $level_num_levels)		// u slucaju jacine nivoa manjeg od najjaceg
		{
			$helper_from_point->from_point = $request->to_point + 1;		// pomocnom redu (redu viseg nivoa) se za pocetne bodove dodeljuju krajnji bodovi request-a uvecani za 1
			$helper_from_point->updated_at = date('Y-m-d H:i:s');
			$helper_from_point->update();
		}

		if($request->no_switch === 0) 
		{
			$level->discount_start_date =  date_format(date_create($request->timestamp1), 'Y-m-d H:i:s');
			$level->discount_end_date = date_format(date_create($request->timestamp2), 'Y-m-d H:i:s');
		}
		else 
		{
			$level->discount_start_date = null;
			$level->discount_end_date = null;
		}

		if ($request->level_strength == 1)
		{
			$level->from_point = 0;
		}

		$level->updated_at = date('Y-m-d H:i:s');
		$level->from_point = $request->from_point;
		$level->to_point = $request->to_point;
		$level->discount_percent = $request->discount_percent * 10;
		$level->update();

		$title = 'table.success';
		$message = 'table.updated_successfully';
		$type = 'success';

    	return response()->json(new JsonResponse([
    		'title' => $title, 
    		'message' => $message, 
    		'type' => $type 
    	]));
    }

    public function get_points()
    {
    	$point_value = PointsDefinitions::getData('point_value');
    	$value_point = PointsDefinitions::getData('value_point');
    	$points_limit = PointsDefinitions::getData('points_limit');
    	$limit_points = PointsDefinitions::limitPoint();
    	
    	return response()->json(new JsonResponse([
    		'point_value' => $point_value, 
    		'value_point' => $value_point, 
    		'points_limit' => $points_limit, 
    		'limit_points' => $limit_points
    	]));
    }

    public function updateValue(Request $request)
    {
    	$value = new PointsDefinitions;
    	$value->value_point = $request->value * 100;
    	$value->save();

		$title = 'table.success';
		$message = 'table.updated_successfully';
		$type = 'success';

    	return response()->json(new JsonResponse([
    							'title' => $title, 
    							'message' => $message, 
    							'type' => $type 
    						]));
    }

    public function updatePoint(Request $request)
    {
    	$value = new PointsDefinitions;
    	$value->point_value = $request->point * 100;
    	$value->save();

		$title = 'table.success';
		$message = 'table.updated_successfully';
		$type = 'success';

    	return response()->json(new JsonResponse([
    							'title' => $title, 
    							'message' => $message, 
    							'type' => $type 
    						]));
    	
    }

    public function updateLimit(Request $request)
    {
    	$value = new PointsDefinitions;
    	$value->points_limit = $request->limit;
    	$value->save();

		$title = 'table.success';
		$message = 'table.updated_successfully';
		$type = 'success';

    	return response()->json(new JsonResponse([
    							'title' => $title, 
    							'message' => $message, 
    							'type' => $type 
    						]));
    	
    }
}
