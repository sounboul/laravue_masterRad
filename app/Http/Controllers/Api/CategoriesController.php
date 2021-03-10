<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Models\Categories;
use App\Laravue\JsonResponse;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Http;
use App\Laravue\Models\Credentials;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends BaseController
{
    private function loginAPI()
    {
        $loginAPI = Credentials::find(1);
        return $loginAPI;
    }
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


    public function fetchCategories()
    {
    	$response = Http::withBasicAuth(self::loginAPI()->username, self::loginAPI()->password)->get('http://dev.tico.rs/api/v1/categories');
  		$categories = $response->json();

        return response()->json(new JsonResponse($categories));
    }

}