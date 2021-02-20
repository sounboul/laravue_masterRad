<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Laravue\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\customers_tico;
use App\Laravue\Models\MemberLevel;

class SellTicoController extends Controller
{
	private $username = 'phoenix2208@gmail.com';
	private $pass = 'sasazivkovic1';
    const ITEM_PER_PAGE = 10;


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
		
  			$customer_id = $customers['orders'][$i]['customer']['id'];
  			$pom = customers_tico::where('customer_id', $customer_id)->get();
  			if ($pom->count() == 0) {
				$customer_tico = new customers_tico;
			  	$customer_tico->customer_id = $customer_id;
			  	$customer_tico->updated_at = $customers['orders'][$i]['date'];
	  			$cus[$i] = $customers['orders'][$i]['customer'];
	  			$customer_tico->email = $cus[$i]['email'];
	  			$customer_tico->first_name = $cus[$i]['first_name'];
	  			$customer_tico->last_name = $cus[$i]['last_name'];
	  			$customer_tico->name = $cus[$i]['name'];
	  			$customer_tico->phone = $cus[$i]['phone'];
	  			
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
	  					//$customer_tico->level = MemberLevel::findLevel($customer_tico->total_points);
			  			$customer_tico->save();
			  		}
		  		}
		  	}
		  	else {
		  		$temp = 0;
		  		foreach ($pom as $key => $value) {
		  			$value->total_points = $value->total_points + $temp;
		  			$temp = $value->total_points;
		  		}
		  			
		  		$help = customers_tico::find($customer_id);
		  		if(isset($help->total_points)){
			  		$help->total_points = $temp;
		  			$help->update();
		  		}
	  		}
		  	
  		}

        return response()->json(new JsonResponse($customers));
    }

    public function fetchListTico()
    {
    	$customers = customers_tico::orderBy('customer_id', 'asc')->paginate(10);

    	for ($i=0; $i < $customers->count() ; $i++) { 
    		$pom = 0;
    		$customers[$i]->level = MemberLevel::findLevel($customers[$i]->total_points);
    	}
  		
    	//dd($customers);
    	return response()->json(new JsonResponse($customers));
    }

}
