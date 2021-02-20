<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Laravue\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\customers_tico;

class SellTicoController extends Controller
{
	private $username = 'phoenix2208@gmail.com';
	private $pass = 'sasazivkovic1';


	public function articles()
    {
    	$response = Http::withBasicAuth($this->username, $this->pass)->get('http://dev.tico.rs/api/v1/articles');
  		$articles = $response->json();
        return response()->json(new JsonResponse($articles));
    }

	public function customers()
    {
    	function points($quantity, $web_price, $point_value=1000)
    	{
    		return intval($quantity * $web_price / $point_value);
    	}

    	$response = Http::withBasicAuth($this->username, $this->pass)->get('http://dev.tico.rs/api/v1/b2c-orders');
  		$customers = $response->json();


  		for ($i=0; $i < count($customers['orders']); $i++) { 
			
			$customer_tico = new customers_tico;

  			$cus[$i] = $customers['orders'][$i]['customer'];
  			$customer_tico->customer_id = $cus[$i]['id'];
  			
	  		for ($j=0; $j < count($customers['orders'][$i]['items']); $j++) { 
				$order = customers_tico::where('order_id', $customers['orders'][$i]['items'][$j]['order_id'])->get();
				if ($order->count() == 0) {
		  			$pom[$i] = $customers['orders'][$i]['items'][$j];
		  			$quantity = $pom[$i]['quantity'];
		  			$customer_tico->article_id = $pom[$i]['id'];
		  			$customer_tico->order_id = $pom[$i]['order_id'];

					$pom[$i] = $pom[$i]['article'];
					$customer_tico->category_id = $pom[$i]['category_id'];
					$web_price = $pom[$i]['web_price'];

		  			$customer_tico->total_points = points($quantity, $web_price);
		  			$customer_tico->updated_at = date("Y-m-d H:m:s");
		  			$customer_tico->save();
		  		}
	  		}
  		}

        return response()->json(new JsonResponse($customers));
    }

}
