<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Laravue\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\customers_tico;
use App\Laravue\Models\customers_category_tico;
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
    	$response = Http::withBasicAuth($this->username, $this->pass)->get('http://dev.tico.rs/api/v1/b2c-orders');
  		$customers = $response->json();

  		for ($i=0; $i < count($customers['orders']); $i++) {

  			$customer_id = $customers['orders'][$i]['customer']['id'];
  			$pom = customers_tico::where('customer_id', $customer_id)->get();
  			if (count($pom) == 0) {
  				$temp = 0;
  				$customer_tico = new customers_tico;

  				$items = $customers['orders'][$i]['items'];

			  	$customer_tico->updated_at = $customers['orders'][$i]['date'];
	  			$cus[$i] = $customers['orders'][$i]['customer'];
	  			$customer_tico->email = $cus[$i]['email'];
	  			$customer_tico->first_name = $cus[$i]['first_name'];
	  			$customer_tico->last_name = $cus[$i]['last_name'];
	  			$customer_tico->name = $cus[$i]['name'];
	  			$customer_tico->phone = $cus[$i]['phone'];	
  				$customer_tico->customer_id = $customer_id;

  				for($j=0; $j < count($items); $j++){
	  				$customer_tico->order_id = $items[$j]['order_id'];
	  				$category_id = $items[$j]['article']['category_id'];
	  				$customer_tico->category_id = $category_id;
	  				$customers_category_tico = new customers_category_tico;
	  				$customers_category_tico->customer_id = $customer_id;
	  				$customers_category_tico->category_id = $category_id;
	  				$customers_category_tico->save();

	  				$customer_tico->total_points = $items[$j]['quantity'] * $items[$j]['article']['web_price'] + $temp;
	  				if ($customer_tico->total_points > 500000) {
	  					$customer_tico->total_points = 500000;
	  				}
	  				$temp = $customer_tico->total_points;
	  			}
				$customer_tico->total_points = intval($temp/1000);
				$customer_tico->save();
  			}
  			else {
  				$trt = customers_tico::where('customer_id', $customer_id)->first();
  				$help = customers_tico::select('*')->where('customer_id', $customer_id)
  													->where('id', '!=', $trt->id)
  													->first();
  				if(is_array($help)){

	  				//$help = customers_tico::find($trt->id); 
	  				$test = $help->total_points;
	  				$temp = 0; 				
	  				for ($j=0; $j < count($items); $j++) { 
	  					$help->total_points = $items[$j]['quantity'] * $items[$j]['article']['web_price'] + $temp;
		  				if ($help->total_points > 500000) {
		  					$help->total_points = 500000;
		  				}
		  				$temp = $help->total_points;
		  			}
		  			$help->total_points = intval($temp/1000) + $test;
		  			if ($help->total_points > 500) {
	  					$help->total_points = 500;
	  				}
		  			$help->update();	  			
  				}
  			}
  		}
        return response()->json(new JsonResponse($customers));
    }



    public function fetchListTico(Request $request)
    {
  		$clear = customers_tico::where('order_id', 0)->get();
  		foreach ($clear as $key => $value) {
  			$value->delete();
  		}

    	$limit = $request->limit;
    	$customers = customers_tico::orderBy('customer_id', 'asc')->paginate($limit);

    	for ($i=0; $i < count($customers); $i++) { 
    		$pom = 0;
    		$customers[$i]->level = MemberLevel::findLevel($customers[$i]->total_points);
    	}
  		
    	//dd($customers);
    	return response()->json(new JsonResponse($customers));
    }

}
