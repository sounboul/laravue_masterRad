<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Models\Categories;
use App\Laravue\JsonResponse;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends BaseController
{
    const ITEM_PER_PAGE = 15;

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $sort = $request->sort;
        $limit = $request->limit;

        if (!empty($keyword)) {
            $categoryQuery = Categories::where('name', 'LIKE', '%' .$keyword . '%')->get();
        }

        return response()->json(new JsonResponse(['items' => $categoryQuery, 'limit' => $limit]));
    }

    public function fetchCategories(Request $request)
    {
        if ($request->sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }

        $categories = Categories::orderBy('id', $order)->get();
    	
        $keyword = $request->keyword;
        $sort = $request->sort;
        $limit = $request->limit;

        if (!empty($keyword)) {
            $categories = Categories::where('name', 'LIKE', '%' .$keyword . '%')
                                ->orderBy('id', $order)
                                ->get();
        }

    	return response()->json(new JsonResponse(['items' => $categories, 'total' => count($categories), 'sort' => $request->sort]));
    }

    public function previewCategory($id)
    {
        
    }

    public function createCategory(Request $request, Categories $categories)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_category_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }
        dd($request);
    }

    public function updateCategory(Request $request, Categories $categories)
    {
        if ($categories === null) {
            return response()->json(['error' => 'category not found'], 404);
        }

        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_category_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $id = $request->id;
        $category = Categories::find($id);
        if ($category === null) {
            return response()->json(['error' => 'category not found'], 404);
        }

        $category->code = $request->code;
        $category->title = $request->title;
        $category->price = $request->price;
        $category->amount = $request->amount;
        $category->brand = $request->brand;
        $category->tags = $request->tags;
        $category->description = $request->description;
        $category->short_description = $request->short_description;
        $category->save();
        return;
    }

    public function deleteCategory($id)
    {
        $category = Categories::find($id);
        $category->delete();
        
        return response()->json(['status' => $id]);
    }
}
