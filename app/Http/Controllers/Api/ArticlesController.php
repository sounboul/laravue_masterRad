<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\Articles;
use App\Laravue\JsonResponse;

class ArticlesController extends BaseController
{
    public function fetchArticles()
    {
    	
    	$articles = Articles::all();

    	return response()->json(new JsonResponse(['items' => $articles, 'total' => count($articles)]));
    }
}
