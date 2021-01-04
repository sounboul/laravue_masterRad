<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\Articles;
use App\Laravue\Models\Stores;
use App\Laravue\JsonResponse;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class ArticlesController extends BaseController
{
    public function fetchArticles()
    {
    	//$store = [];
    	$articles = Articles::all();
    	foreach ($articles as $key => $article) {
    		$store[$key] = $article->store;
    	}
    	

    	return response()->json(new JsonResponse(['items' => $articles, 'total' => count($articles)]));
    }

    public function updateArticles(Request $request, Articles $articles)
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
}
