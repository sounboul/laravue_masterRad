<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\JsonResponse;
use App\Laravue\Models\MemberLevel;
use App\Laravue\Models\Discount_definitions;
use App\Laravue\Models\PointsDefinitions;

class MemberLevelController extends Controller
{
    public function index(Request $request)
    {
		$order = $request->sort;
        $limit = $request->limit;
        $type = $request->type;

        if ($request->sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }

    	$discount = MemberLevel::orderBy('to_point', $order)
    								->where('level', $type)
    								->get();
        

    	return response()->json(new JsonResponse(['items' => $discount, 'type' => $type, 'limit' => $limit]));
    }

    public function fetchLevels()
    {
    	$levels = MemberLevel::orderBy('to_point', 'asc')->get();
    	return response()->json(new JsonResponse(['levels' => $levels]));
    }

    public function updateLevel(Request $request)
    {
    	$level_id = MemberLevel::where('level', $request->type)->first();
    	$level = MemberLevel::find($level_id);
    	
    }
}
