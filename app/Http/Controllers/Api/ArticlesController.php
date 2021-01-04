<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\Articles;
use App\Laravue\Models\Stores;
use App\Laravue\JsonResponse;

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
}
