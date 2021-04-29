<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Models\Categories;
use App\Laravue\Models\customers_category_tico;
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

    private function bexterAPI()
    {
        $bexterAPI = Credentials::find(2);
        return $bexterAPI;
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
        //$response = Http::withBasicAuth(self::bexterAPI()->username, self::bexterAPI()->password)->get('https://laravue.bexter.rs/api/v1/customers_level_API/167');
  		//$categories = $response->json();

        //dd($categories['categories'][0]);

        $categories = categories::all();
        //dd(json_encode($categories));

        return response()->json(new JsonResponse(['categories' => $categories]));
    }

}