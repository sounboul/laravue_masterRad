<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\Articles;
use App\Laravue\Models\Bill;
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
                                ->orWhere('title', 'LIKE', '%' .$keyword . '%')
                                ->orderBy('title', $order)
                                ->paginate($limit);
        }    

        foreach ($articles as $key => $article) {
            $store[$key] = $article->store;
            $category[$key] = $article->categories;
        }

    	return response()->json(new JsonResponse(['items' => $articles]));
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

        if (!empty($keyword)) {
            $articles = Articles::where('code', 'LIKE', '%' .$keyword . '%')
                                ->orWhere('title', 'LIKE', '%' .$keyword . '%')
                                ->orderBy('title', 'asc')
                                ->get();
        }
        foreach ($articles as $key => $article) {
            if ($article->discount > 0) {
                $temp_percent = $article->discount / 10;
            }
            else{
                $level = MemberLevel::findLevel($customer->total_points);
                $temp_percent = MemberLevel::where('level', $level)->first()->discount_percent / 10;
            }
            
            $article->price = (1 - $temp_percent/100) * $article->price;
            $store[$key] = $article->store;
            $category[$key] = $article->categories;
        }

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
//dd($price);

        if($request->price1 == 0 || $request->amount == 0 || $request->supplier == 0 || $request->title == '' || $request->category == 0){

            $title = 'discounts.warning';
            $message = 'discounts.timestamp_is_required';
            $type = 'error';
        
            return response()->json(new JsonResponse(['title' => $title, 'message' => $message, 'type' => $type ]));
        }
//dd($request->all());

        $price = filter_var($request->price1 * 100, FILTER_SANITIZE_NUMBER_INT);

        $article = new Articles;

        $article->code = 0;
        $article->title = $request->title;
        $article->categories_id = $request->category;
        $article->price = $price;
        $article->supplier_id = $request->supplier;
        $article->amount = $request->amount;
        if($request->discount > 0){
            $discount = filter_var($request->discount * 10, FILTER_SANITIZE_NUMBER_INT);
            $article->discount = $discount;
        }
        $article->brand = $request->brand;
        $article->tags = $request->tags;
        $article->description = $request->description;
        $article->short_description = $request->short_description;
        $article->save();

        $article = Articles::find($article->id);
        $article->code = $article->id + 10000;
        $article->update();

        return;
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

        $price = filter_var($request->price1 * 100, FILTER_SANITIZE_NUMBER_INT);
//dd($price);
        $article->code = $request->code;
        $article->title = $request->title;
        if($request->category){
            $article->categories_id = $request->category;
        }
        if($request->price1){
            $article->price = $price;
        }
        if($request->discount){
            $article->discount = $request->discount * 10;
        }
        $article->amount = $request->amount;
        $article->brand = $request->brand;
        $article->tags = $request->tags;
        $article->supplier_id = $request->supplier;
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


    public function articleUpdate1(Request $request)
    {
        $bills = Bill::where('order_id', $request->articleUpdate['time'])->orderBy('id', 'desc')->get();
        foreach ($bills as $key => $bill) {
            $bill->customer = $request->id;
            $bill->earned_points = $request->earnedPoints;
            $bill->bill = $request->bill;
        }
        $bill->update();

        $customer = Customers::find($request->id);
        $customer->total_points = $customer->total_points + $request->earnedPoints;
        if($customer->total_points > $request->max_points){
            $customer->total_points = $request->max_points;
        }
        $customer->updated_at = date("Y-m-d H:i:s");
        $customer->update();

        return response()->json(['status' => $bill]);
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
