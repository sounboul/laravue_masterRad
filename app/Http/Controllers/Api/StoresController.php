<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Laravue\Models\Stores;

class StoresController extends BaseController
{
    public function fetchStores()
    {
    	$stores = Stores::all();
    	return response()->json(new JsonResponse(['stores' => $stores, 'total' => count($stores)]));
    }
}
