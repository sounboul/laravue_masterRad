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
use App\Laravue\Models\Cashing;
use Illuminate\Support\Facades\Auth;
use App\Models\FileUpload;

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
    	  $response = Http::withBasicAuth(
                self::loginAPI()->username, 
                self::loginAPI()->password
            )->get('http://dev.tico.rs/api/v1/categories');

        /*  $response = Http::withBasicAuth(
                self::loginAPI()->username, 
                self::loginAPI()->password
            )->get(web_services::find(1)->route_prefix.route_name::find(1)->api_routes[0]->name);*/



        /*  $url = 'https://laravue.bexter.rs/api/v1/get_customer_level/93';
            $response = Http::withBasicAuth(
                self::bexterAPI()->username, 
                self::bexterAPI()->password
            )->get($url);*/

        /*  $url = 'https://laravue.bexter.rs/api/v1/update_list';
            $response = Http::withBasicAuth(
                self::bexterAPI()->username, 
                self::bexterAPI()->password
            )->get($url);*/

       /*   $data = [
                'customer_id' => 93,
                'cashed_points' => 25,
                'user' => 'editor@laravue.dev',
            ];

            $url = 'https://laravue.bexter.rs/api/v1/cashing';

            $response = Http::withBasicAuth(
                    self::bexterAPI()->username, 
                    self::bexterAPI()->password
                )->post($url, $data);*/
        

        //$categories = Cashing::get_cashing('214', '2021-05-21');

        $categories = $response->json();
        //dd($categories);

        return response()->json(new JsonResponse($categories));
    }


    public function getCategories()
    {
        $categories = categories::all();
        return response()->json(new JsonResponse(['categories' => $categories]));
    }

    public function uploadFoto(Request $request)
    {
        /*$resData['image'] = 'users/SlikaSokoBanja.png';
        return response()->json(new JsonResponse(['resData' => $resData]));*/
         $request->validate([
           'file' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:2048'
        ]);

        $fileUpload = new User;

        if($request->file()) {
            $file_name = time().'_'.$request->file->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');

            $fileUpload->name = time().'_'.$request->file->getClientOriginalName();
            $fileUpload->path = '/storage/' . $file_path;
            $fileUpload->save();

            return response()->json(['success'=>'File uploaded successfully.']);
        }
    }
}
