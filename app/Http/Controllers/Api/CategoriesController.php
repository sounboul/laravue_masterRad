<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Models\Categories;
use App\Laravue\Models\customers_category_api;
use App\Laravue\JsonResponse;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Http;
use App\Laravue\Models\Credentials;
use App\Laravue\Models\api_routes;
use App\Laravue\Models\route_name;
use App\Laravue\Models\web_services;
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
    	/*$response = Http::withBasicAuth(
                self::loginAPI()->username, 
                self::loginAPI()->password
            )->get('http://dev.tico.rs/api/v1/categories');*/

        /*$response = Http::withBasicAuth(
                self::bexterAPI()->username, 
                self::bexterAPI()->password
            )->get('https://laravue.bexter.rs/api/v1/customers_level_API/167');*/

        $response = Http::withBasicAuth(
                self::loginAPI()->username, 
                self::loginAPI()->password
            )->get(web_services::find(1)->route_prefix.route_name::find(1)->api_routes[0]->name);
        
        $categories = $response->json();

        return response()->json(new JsonResponse($categories));
    }


    public function getCategories()
    {
        $categories = categories::all();
        return response()->json(new JsonResponse(['categories' => $categories]));
    }
}