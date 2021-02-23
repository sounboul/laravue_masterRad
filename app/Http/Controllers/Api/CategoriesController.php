<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Models\Categories;
use App\Laravue\JsonResponse;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends BaseController
{
	private $username = 'phoenix2208@gmail.com';
	private $pass = 'sasazivkovic1';
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
    	$response = Http::withBasicAuth($this->username, $this->pass)->get('http://dev.tico.rs/api/v1/categories');
  		$categories = $response->json();
  		//dd($categories);



        return response()->json(new JsonResponse($categories));
    }

}