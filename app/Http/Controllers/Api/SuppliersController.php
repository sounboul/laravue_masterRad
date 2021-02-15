<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Models\Suppliers;
use App\Laravue\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SuppliersController extends BaseController
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $sort = $request->sort;
        $limit = $request->limit;

        if ($request->sort == "-id") {
            $order = 'desc';
        }
        else {
            $order = 'asc';
        }

        $suppliers = Suppliers::orderBy('name', $order)->paginate($limit);

        return response()->json(new JsonResponse(['items' => $suppliers]));

    }


    public function getSuppliers(Request $request)
    {
        $keyword = $request->keyword;
        $sort = $request->sort;
        $limit = $request->limit;

        if ($request->sort == "-id") {
            $order = 'desc';
        }
        else {
            $order = 'asc';
        }

    	$suppliers = Suppliers::orderBy('name', $order)->get();

    	return response()->json(new JsonResponse(['items' => $suppliers]));
    }


    public function updateSupplier(Request $request)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_SUPPLIER_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        if (empty($request->name) || empty($request->address)) {
            return response()->json(['error' => 'Nepotpuni podaci'], 404);
        }

        $supplier = Suppliers::find($request->id);

        if ($supplier === null) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }
// dd($supplier);
        
        $supplier->name = $request->name;
        $supplier->address = $request->address;
        $supplier->description = $request->description;
        $supplier->update();

        return;
    }



    public function createSupplier(Request $request, Suppliers $suppliers)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_SUPPLIER_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        if (empty($request->name) || empty($request->address)) {
            return response()->json(['error' => 'Nepotpuni podaci'], 404);
        }

        $supplier = new Suppliers;

        $supplier->name = $request->name;
        $supplier->address = $request->address;
        $supplier->description = $request->description;
        $supplier->save();

        return;
    }




    public function deleteSupplier($id)
    {
        $supplier = Suppliers::find($id);
        $supplier->delete();
        
        return;
    }
}
