<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\Customers;
use App\Laravue\JsonResponse;

class CustomersController extends BaseController
{
    public function fetchCustomers(Request $request)
    {
        $showActiveCustomers = filter_var($request->showActiveCustomers, FILTER_VALIDATE_BOOLEAN);

        $keyword = $request->keyword;
        $sort = $request->sort;
        $limit = $request->limit;
        

        if ($request->sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }

        if(!$showActiveCustomers || (!$showActiveCustomers && ($request->sort == "-id"))) {
            $customers = Customers::orderBy('id', $order)->get();
            if (!empty($keyword)) {
                $customers = Customers::where(function($query) use ($keyword) {
                                    $query->orWhere('name', 'LIKE', '%' . $keyword . '%')
                                            ->orWhere('member_since', 'LIKE', '%' . $keyword . '%')
                                            ->orWhere('updated_at', 'LIKE', '%' . $keyword . '%');
                                    })
                                    ->orderBy('id', $order)
                                    ->get();
            }
        }
        else {
            $customers = Customers::where('active', 'active')->orderBy('id', $order)->get();

            if (!empty($keyword)) {
                $customers = Customers::where('active', 'active')
                                ->where(function($query) use ($keyword) {
                                    $query->orWhere('name', 'LIKE', '%' . $keyword . '%')
                                            ->orWhere('member_since', 'LIKE', '%' . $keyword . '%')
                                            ->orWhere('updated_at', 'LIKE', '%' . $keyword . '%');
                                    })
                                    ->orderBy('id', $order)
                                    ->get();
            }
        }

    	return response()->json(new JsonResponse(['items' => $customers, 'total' => count($customers)]));
    }

    public function fetchAllCustomers(Request $request)
    {
        $showActiveCustomers = filter_var($request->showActiveCustomers, FILTER_VALIDATE_BOOLEAN);

        if ($request->sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }
        //dd($showActiveCustomers);
        if (!$showActiveCustomers || (!$showActiveCustomers && ($request->sort == "-id"))) {
            $customers = Customers::orderBy('id', $order)->get();
        }
        else {
            $customers = Customers::where('active', 'active')->orderBy('id', $order)->get();
        }

    	return response()->json(new JsonResponse(['items' => $customers, 'total' => count($customers)]));
    }
    
    public function fetchActiveCustomers(Request $request)
    {
        $showActiveCustomers = filter_var($request->showActiveCustomers, FILTER_VALIDATE_BOOLEAN);

        if ($request->sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }

        
        if (!$showActiveCustomers || ($showActiveCustomers && ($request->sort == "-id"))) {

            $customers = Customers::orderBy('id', $order)->get();
        }
        else {
            $customers = Customers::where('active', 'active')->orderBy('id', $order)->get();
        }

        return response()->json(new JsonResponse(['items' => $customers, 'total' => count($customers)]));
    }

    public function fetchDeletedCustomers()
    {
    	$customers = Customers::where('active', 'deleted')->get();
    	return response()->json(new JsonResponse(['items' => $customers, 'total' => count($customers)]));
    }
}
