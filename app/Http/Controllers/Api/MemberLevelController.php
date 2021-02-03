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

    	$discount = MemberLevel::orderBy('from_point', $order)->get();
        

    	return response()->json(new JsonResponse(['items' => $discount, 'type' => $type, 'limit' => $limit]));
    }
}
