<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\Customers;
use App\Laravue\JsonResponse;

class CustomersController extends BaseController
{
    public function fetchCustomers()
    {
    	$customers = Customers::all();
    	return response()->json(new JsonResponse(['items' => $customers, 'total' => count($customers)]));
    }

    public function fetchActiveCustomers()
    {
    	$customers = Customers::where('active', 'active')->get();
    	return response()->json(new JsonResponse(['items' => $customers, 'total' => count($customers)]));
    }

    public function fetchDeletedCustomers()
    {
    	$customers = Customers::where('active', 'deleted')->get();
    	return response()->json(new JsonResponse(['items' => $customers, 'total' => count($customers)]));
    }
}
