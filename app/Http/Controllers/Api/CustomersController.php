<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\Customers;

class CustomersController extends BaseController
{
    public function fetchCustomers()
    {
    	$customers = Customers::all();
    	return response()->json(['customers' => $customers]);
    }
}
