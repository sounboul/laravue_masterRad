<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\Customers;
use App\Laravue\Models\MemberLevel;
use App\Laravue\JsonResponse;
use App\Http\Resources\CustomerResources;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;

class CustomersController extends BaseController
{
    const ITEM_PER_PAGE = 15;
    
    public function fetchCustomers(Request $request)
    {

       /* $data = [];
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
        $data[] = $customers->toArray();
    	return response()->json(new JsonResponse(['items' => $data]));*/
    }


    public function fetchAllCustomers(Request $request)
    {

        $showActiveCustomers = filter_var($request->showActiveCustomers, FILTER_VALIDATE_BOOLEAN);

        $searchParams = $request->all();
        $customerQuery = Customers::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $active = Arr::get($searchParams, 'active', '');
        $keyword = Arr::get($searchParams, 'keyword', '');

        if ($request->sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }

        $customerQuery->where('active', 'active');

        return CustomerResources::collection($customerQuery->paginate($limit));



/*
        $helper_customer_level = memberLevel::findLevel($request->to_point);
        // dd($helper_customer_level);
        

        if (!$showActiveCustomers || (!$showActiveCustomers && ($request->sort == "-id"))) {
            $customers = Customers::orderBy('id', $order)->get();
        }
        else {
            $customers = Customers::where('active', 'active')->orderBy('id', $order)->get();
        }

        $data[] = $customers->toArray();
        dd($data);

    	return response()->json(new JsonResponse(['items' => $data]));*/
    }

    
    public function fetchCustomer($id)
    {

        //$customerQuery = Customers::query();
        //$limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);

        $customerQuery = Customers::where('id', $id)->first();

        $customerQuery->dob = date_format(date_create($customerQuery->dob), "d.m.Y.");
        $customerQuery->member_since = date_format(date_create($customerQuery->member_since), "d.m.Y.");

        return response()->json(new JsonResponse(['items' => $customerQuery]));
    }


    public function editCustomer(Request $request, $id)
    {
        $customer = Customers::find($id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->mobile = $request->mobile;
        // $customer->dob = $request->dob;
        // $customer->ID_number = $request->ID_number;
        $customer->street = $request->street;
        $customer->number = $request->number;
        $customer->city = $request->city;
        $customer->postal_code = $request->postal_code;
        $customer->country = $request->country;
        $customer->updated_at = date("Y-m-d H:i:s");
        $customer->save();

        return;
    }


    public function fetchDeletedCustomers()
    {        $data = [];
    	$customers = Customers::where('active', 'deleted')->get();
        $data[] = $customers;
    	return response()->json(new JsonResponse(['items' => $data]));
    }
}
