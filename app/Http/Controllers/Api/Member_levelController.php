<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Member_level;

class Member_levelController extends Controller
{
    public function index(Request $request)
    {
		$order = $request->sort;
        $limit = $request->limit;

        if ($request->sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }

    	$discount = Member_level::orderBy('id', $order)->get();

    	return response()->json(new JsonResponse(['items' => $discount, 'limit' => $limit]));
    }
}
