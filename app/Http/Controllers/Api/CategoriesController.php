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
    const ITEM_PER_PAGE = 10;

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $sort = $request->sort;
        $limit = $request->limit;

        if (!empty($keyword)) {
            $categoryQuery = Categories::where('name', 'LIKE', '%' .$keyword . '%')->paginate($limit);
        }

        return response()->json(new JsonResponse(['items' => $categoryQuery, 'limit' => $limit]));
    }

    public function fetchCategories(Request $request)
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

        $categories = Categories::orderBy('name', $order)->paginate($limit);
    	
        $keyword = $request->keyword;
        $sort = $request->sort;
        $limit = $request->limit;

        if (!empty($keyword)) {
            $categories = Categories::where('name', 'LIKE', '%' .$keyword . '%')
                                ->orderBy('name', $order)
                                ->paginate($limit);
        }

        return response()->json(new JsonResponse(['items' => $categories]));
    	//return response()->json(new JsonResponse(['items' => $categories, 'total' => count($categories), 'sort' => $request->sort]));
    }

    public function getCategories(Request $request)
    {
        
        if ($request->sort == "-id") {
            $order = 'desc';
        }
        else {
            $order = 'asc';
        }

        $categories = Categories::orderBy('name', $order)->get();
        
        return response()->json(new JsonResponse(['items' => $categories]));
    }

    public function createCategory(Request $request, Categories $categories)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_CATEGORY_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        if (empty($request->name)) {
            return response()->json(['error' => 'Nepotpuni podaci'], 404);
        }

        $category = new Categories;

        $category->code = $request->last_code;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
        return;
    }

    public function updateCategory(Request $request)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_CATEGORY_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $category = Categories::find($request->id);

        if ($category === null) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        // $category->code = $request->code;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->update();
        return;
    }

    public function deleteCategory($id)
    {
        $category = Categories::find($id);
        $category->delete();
        
        return;
    }
}
