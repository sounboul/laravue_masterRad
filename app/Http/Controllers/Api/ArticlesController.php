<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\Articles;
use App\Laravue\Models\Stores;
use App\Laravue\JsonResponse;
use App\Laravue\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;

class ArticlesController extends BaseController
{
    const ITEM_PER_PAGE = 15;

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
       /* else {
            if (!empty($sort)) {
                if ($sort == '+id') {
                    $articleQuery = DB::table('articles')->orderBy('id', 'asc')->get();
                }
                else {
                    $articleQuery = DB::table('articles')->orderBy('id', 'desc')->get();
                }
            }
        }*/

        foreach ($articleQuery as $key => $article) {
            $store[$key] = $article->store;
        }

        return response()->json(new JsonResponse(['items' => $articleQuery, 'limit' => $limit]));
    }

    public function fetchArticles(Request $request)
    {
        if ($request->sort == "+id") {
            $order = 'asc';
        }
        else {
            $order = 'desc';
        }

        $articles = Articles::orderBy('id', $order)->get();
    	
        $keyword = $request->keyword;
        $sort = $request->sort;
        $limit = $request->limit;

        if (!empty($keyword)) {
            $articles = Articles::where('code', 'LIKE', '%' .$keyword . '%')
                                ->orWhere('title', 'LIKE', '%' .$keyword . '%')
                                ->orderBy('id', $order)
                                ->get();
        }

        foreach ($articles as $key => $article) {
            $store[$key] = $article->store;
        }

    	return response()->json(new JsonResponse(['items' => $articles, 'total' => count($articles), 'sort' => $request->sort]));
    }

    public function previewArticle($id)
    {
        
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

    public function updateArticle(Request $request, Articles $articles)
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
}
