<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Models\Suppliers;
use App\Laravue\JsonResponse;

class SuppliersController extends BaseController
{
    public function getSuppliers()
    {
    	$suppliers = Suppliers::all();

    	return response()->json(new JsonResponse(['items' => $suppliers]));
    }
}
