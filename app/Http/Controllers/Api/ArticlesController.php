<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\Articles;
use App\Laravue\Models\Categories;
use App\Laravue\Models\Customers;
use App\Laravue\Models\MemberLevel;
use App\Laravue\Models\Stores;
use App\Laravue\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;

class ArticlesController extends BaseController
{
    const ITEM_PER_PAGE = 10;

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $sort = $request->sort;
        $limit = $request->limit;

        if (!empty($keyword)) {
            $articleQuery = Articles::where('code', 'LIKE', '%' .$keyword . '%')
                                    ->orWhere('title', 'LIKE', '%' .$keyword . '%')
                                    ->get();
        }
        
        foreach ($articleQuery as $key => $article) {
            $store[$key] = $article->store;
        }
$articleQuery = toArray($articleQuery);
        return response()->json(new JsonResponse([$articleQuery->paginate($limit)]));
        //return response()->json(new JsonResponse(['items' => $articleQuery, 'limit' => $limit]));
    }


    function objectToArray(&$object)
    {
        return @json_decode(json_encode($object), true);
    }


    public function fetchArticles(Request $request)
    {
        if ($request->sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }

        $keyword = $request->keyword;
        $sort = $request->sort;
        $limit = $request->limit;

        $articles = Articles::orderBy('title', $order)->paginate($limit);    

        if (!empty($keyword)) {
            $articles = Articles::where('code', 'LIKE', '%' .$keyword . '%')
                                ->orWhere('title', 'LIKE', '%' .$keyword . '%')/*
                                ->orWhere('title', 'LIKE', '%' .$keyword . '%')*/
                                ->orderBy('title', $order)
                                ->paginate($limit);
        }    

        foreach ($articles as $key => $article) {
            $store[$key] = $article->store;
            $category[$key] = $article->categories;
        }
//dd($articles);
        //$array = $this->objectToArray($articles);
//dd($array);
    	return response()->json(new JsonResponse(['items' => $articles]));
        //return response()->json(new JsonResponse(['items' => $articles, 'total' => count($articles), 'sort' => $request->sort]));
    }

    public function previewArticle($id)
    {
        
    }



    public function fetchArticles1(Request $request)
    {
        $customer = Customers::find($request->id);
/*
        if ($request->sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }*/

        $keyword = $request->keyword;
        //$sort = $request->sort;
        //$limit = $request->limit;

        $articles = Articles::orderBy('id', 'asc')->get();        

     /*   foreach ($articles as $key => $article) {
            $store[$key] = $article->store;
            $category[$key] = $article->categories;
        }
*/
        if (!empty($keyword)) {
            $articles = Articles::where('code', 'LIKE', '%' .$keyword . '%')
                                ->orWhere('title', 'LIKE', '%' .$keyword . '%')/*
                                ->orWhere('title', 'LIKE', '%' .$keyword . '%')*/
                                ->orderBy('title', 'asc')
                                ->get();
        }
        foreach ($articles as $key => $article) {
            $level = MemberLevel::findLevel($customer->total_points);
            $temp_percent = MemberLevel::where('level', $level)->first()->discount_percent / 10;
            $article->price = (1 - $temp_percent/100) * $article->price;
            $store[$key] = $article->store;
            $category[$key] = $article->categories;
        }
//dd($articles);
        return response()->json(new JsonResponse(['items' => $articles]));
        
    }



    public function createArticle(Request $request, Articles $articles)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_ARTICLE_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }
        dd($request);
    }



    public function update(Request $request, Articles $articles)
    {
        if ($articles === null) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_ARTICLE_MANAGE)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $id = $request->id;
        $article = Articles::find($id);
        if ($article === null) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        $article->code = $request->code;
        $article->title = $request->title;
        $article->categories_id = $request->category;
        $article->price = $request->price;
        $article->amount = $request->amount;
        $article->brand = $request->brand;
        $article->tags = $request->tags;
        $article->description = $request->description;
        $article->short_description = $request->short_description;
        $article->save();
        return;
    }

    public function deleteArticle($id)
    {
        $article = Articles::find($id);
        $article->delete();
        
        return response()->json(['status' => $id]);
    }

/*    public function pom()  // Upisuje u bazu random artikle od 402 do 1000
    {
        for ($i=402; $i < 1001; $i++) {  
            $article = new Articles;
            $article->code = 10000 + $i;
            $article->title = 'Artikal'. $i;
            $article->categories_id = rand(1,12);
            $article->supplier_id = rand(1,4);
            $article->price = rand(10000, 25000000);
            $article->amount = rand(5,50);
            $article->brand = 'Brend'. rand(1,10);
            $article->tags = '';
            $article->description = '';
            $article->short_description = '';
            $article->save();
        } 
    }*/
}
