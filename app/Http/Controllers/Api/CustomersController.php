<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\Customers;
use App\Laravue\Models\MemberLevel;
use App\Laravue\JsonResponse;
use App\Http\Resources\CustomerResources;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CustomersController extends BaseController
{
    const ITEM_PER_PAGE = 10;
    
    public function fetchCustomers(Request $request)
    {


    }


    public function fetchAllCustomers(Request $request)
    {

        $searchParams = $request->all();
        $showActiveCustomers = filter_var($request->showActiveCustomers, FILTER_VALIDATE_BOOLEAN);
        $showPendingCustomers = filter_var($request->showPendingCustomers, FILTER_VALIDATE_BOOLEAN);
        $showDeletedCustomers = filter_var($request->showDeletedCustomers, FILTER_VALIDATE_BOOLEAN);
        $customerQuery = Customers::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $active = Arr::get($searchParams, 'active', '');
        $keyword = Arr::get($searchParams, 'keyword', '');
        $sort = Arr::get($searchParams, 'sort', '');

        if ($sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }
        if($showActiveCustomers === false)
        {
            $customerQuery->where('active', 'active')->orderBy('id', $order);            
        }
        if($showPendingCustomers === false)
        {
            $customerQuery->where('active', 'pending')->orderBy('id', $order);            
        }
        if($showDeletedCustomers === false)
        {
            $customerQuery->where('active', 'deleted')->orderBy('id', $order);            
        }
        else
        {
            $customerQuery->orderBy('id', $order);            
        }
/*
        if(!$showActiveCustomers || (!$showActiveCustomers && ($order == "-id"))) {
            
            $customerQuery->orderBy('id', $order);

            if (!empty($keyword)) {
                if($showActiveCustomers || (!$showActiveCustomers && ($order == "-id"))) {
                    $customerQuery->where(function($query) use ($keyword) {
                                    $query->orWhere('name', 'LIKE', '%' . $keyword . '%')
                                            ->orWhere('email', 'LIKE', '%' . $keyword . '%');
                                    })
                                    ->orderBy('id', $order);
                }
                else
                {
                    $customerQuery->where('active', 'active')
                                    ->where(function($query) use ($keyword) {
                                    $query->orWhere('name', 'LIKE', '%' . $keyword . '%')
                                            ->orWhere('email', 'LIKE', '%' . $keyword . '%');
                                    })
                                    ->orderBy('id', $order);
                }
            }
        }
        else 
        {*/

         
            if (!empty($keyword)) {
                if($showActiveCustomers === true) {
                    $customerQuery->where('active', 'active')
                                    ->where(function($query) use ($keyword) {
                                    $query->orWhere('name', 'LIKE', '%' . $keyword . '%')
                                            ->orWhere('code', 'LIKE', '%' . $keyword . '%')
                                            ->orWhere('email', 'LIKE', '%' . $keyword . '%');
                                    })
                                    ->orderBy('id', $order);
                }
                else
                {
                    $customerQuery->where(function($query) use ($keyword) {
                                    $query->orWhere('name', 'LIKE', '%' . $keyword . '%')
                                            ->orWhere('email', 'LIKE', '%' . $keyword . '%');
                                    })
                                    ->orderBy('id', $order);
                }
            }
        /*}*/
       


        return CustomerResources::collection($customerQuery->paginate($limit));
    }

    
    public function fetchCustomer($id)
    {

        $customerQuery = Customers::where('id', $id)->first();

        if($customerQuery->dob != null) {
            $customerQuery->dob = date_format(date_create($customerQuery->dob), "d.m.Y.");
        }
        $customerQuery->member_since = date_format(date_create($customerQuery->member_since), "d.m.Y.");

        $level = MemberLevel::findLevel($customerQuery->total_points);
//dd($customerQuery);
        return response()->json(new JsonResponse(['items' => $customerQuery, 'level' => $level]));
    }



    public function createCustomer(Request $request, Customers $customers)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_CUSTOMER_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }
        // dd($request);

        if ($request->name == '' || $request->email == '' || $request->mobile == '') {
            return response()->json(['error' => 'Fill the required fields'], 403);
        }

        $customer = new Customers;

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->mobile = $request->mobile;
        $customer->ID_number = $request->ID_number;
        $customer->street = $request->street;
        $customer->number = $request->number;
        $customer->city = $request->city;
        $customer->postal_code = $request->postal_code;
        $customer->country = $request->country;
        $customer->facebook_account = $request->facebook;
        $customer->instagram_account = $request->instagram;
        $customer->twitter_account = $request->twitter;
        $customer->updated_at = date("Y-m-d H:i:s");
        //$customer->member_since = date("Y-m-d H:i:s");
        $customer->dob = date_format(date_create($request->dob), "Y-m-d");
        $customer->save();

        $customer->code = $customer->id + 100000;
        $customer->update();
    }



    public function update(Request $request, Customers $customers)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_CUSTOMER_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $customer = Customers::find($request->id);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->mobile = $request->mobile;
        $customer->ID_number = $request->ID_number;
        $customer->street = $request->street;
        $customer->number = $request->number;
        $customer->city = $request->city;
        $customer->postal_code = $request->postal_code;
        $customer->country = $request->country;
        $customer->facebook_account = $request->facebook;
        $customer->instagram_account = $request->instagram;
        $customer->twitter_account = $request->twitter;
        $customer->updated_at = date("Y-m-d H:i:s");
        $customer->dob = date_format(date_create($request->dob), "Y-m-d");
        $customer->update();

        return;
    }


    public function fetchDeletedCustomers()
    {        $data = [];
    	$customers = Customers::where('active', 'deleted')->get();
        $data[] = $customers;
    	return response()->json(new JsonResponse(['items' => $data]));
    }


    public function customerUpdate1(Request $request)
    {
        
        return response()->json(new JsonResponse(['items' => $request->all()]));
    }



    public function deleteCustomer($id)
    {
        $customer = Customers::find($id);
        $customer->active = 'deleted';
        $customer->update();
        
        return;
    }
/*
    public function pom()    // random upis total_points u customers
    {        
        $customers = Customers::all();
        foreach ($customers as $key => $customer) {
            $art = Customers::find($customer->id);
            $art->total_points = rand(0,500) / 1.4;
            $art->save();
        }
    }*/
}
