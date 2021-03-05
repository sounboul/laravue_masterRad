<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Laravue\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\customers_tico;
use App\Laravue\Models\customers_category_tico;
use App\Laravue\Models\MemberLevel;
use App\Laravue\Models\Credentials;
use Validator;

class SellTicoController extends Controller
{

   //private $username = $this->loginAPI->username;
   //private $pass = $this->loginAPI->password;
  private function loginAPI()
  {
    $loginAPI = Credentials::find(1);
    return $loginAPI;
  }

  const ITEM_PER_PAGE = 10;


	public function articles(Request $request)
    {
      $page = $request->page;
      $limit = $request->limit;
      //dd($this->loginAPI()->username);
    	$response = Http::withBasicAuth(self::loginAPI()->username, self::loginAPI()->password)->get('http://dev.tico.rs/api/v1/articles?page='. $page . '&limit=' . $limit);
  		$articles = $response->json();
        return response()->json(new JsonResponse($articles));
    }



public function customers()
    {
    	$response = Http::withBasicAuth(self::loginAPI()->username, self::loginAPI()->password)->get('http://dev.tico.rs/api/v1/b2c-orders');
  		$customers = $response->json();

  		for ($i=0; $i < count($customers['orders']); $i++) {

  			$customer_id = $customers['orders'][$i]['customer']['id'];
  			$pom = customers_tico::where('customer_id', $customer_id)->first();

  			$temp = 0;
			$customer_tico = new customers_tico;


			$customer_tico->updated_at = $customers['orders'][$i]['date'];
			$cus[$i] = $customers['orders'][$i]['customer'];
			$customer_tico->email = $cus[$i]['email'];
			$customer_tico->first_name = $cus[$i]['first_name'];
			$customer_tico->last_name = $cus[$i]['last_name'];
			$customer_tico->name = $cus[$i]['name'];
			$customer_tico->phone = $cus[$i]['phone'];	
			$customer_tico->customer_id = $customer_id;

			$items = $customers['orders'][$i]['items'];

			for($j=0; $j < count($items); $j++){
				$customer_tico->order_id = $items[$j]['order_id'];
				if ($customer_tico->order_id != 0) {
					$category_id = $items[$j]['article']['category_id'];
					if ($category_id != null) {
						$customer_tico->category_id = $category_id;
						$customers_category_tico = new customers_category_tico;
						$customers_category_tico->customer_id = $customer_id;
						$customers_category_tico->category_id = $category_id;
						$customers_category_tico->save();
					}

					$temp = $items[$j]['quantity'] * $items[$j]['article']['web_price'] + $temp;
					if ($temp > 5000000) {
						$temp = 5000000;
					}
					$customer_tico->total_points = $temp;
				}
			}

			$customer_tico->total_points = intval($temp/1000);
			$temp = 0;
			$customer_tico->save();
  			
  		}

  		for ($i=0; $i < count($customers['orders']); $i++) {

  			$customer_id = $customers['orders'][$i]['customer']['id'];

  			$pom = customers_tico::where('customer_id', $customer_id)->sum('total_points');

  			$ret = customers_tico::where('customer_id', $customer_id)->first();
  			$ret->total_points = $pom;
  			$ret->update();

  			$pom2 = customers_tico::select('*')->where('customer_id', $customer_id)->where('id', '!=', $ret->id)->get();

  			foreach ($pom2 as $key => $value) {
  				$value->delete();
  			}

  		}

  		$helpers = customers_category_tico::all();
  		foreach ($helpers as $key => $help) {
  			$trt = customers_category_tico::select('*')->where('customer_id', $help->customer_id)->where('category_id', $help->category_id)->get();
	  		foreach ($trt as $key => $value) {
	  			if ($key > 0) {
	  				$value->delete();
	  			}
	  		}
  		}

  		return;

  	}



	public function customers1()
    {
    	$response = Http::withBasicAuth(self::loginAPI()->username, self::loginAPI()->password)->get('http://dev.tico.rs/api/v1/b2c-orders');
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
  				$first_customer = customers_tico::where('customer_id', $customer_id)->first();
  				$help = customers_tico::select('*')->where('customer_id', $customer_id)
  													->where('id', '!=', $first_customer->id)
  													->first();
  				if(is_array($help)){
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
  		
    	return response()->json(new JsonResponse($customers));
    }


    public function marketing_tico($category_id){

    	$categories = customers_category_tico::where('category_id', $category_id)->get();
    	
    	$customer = [];
    	
    	foreach ($categories as $key => $category) {
    		$customer[$key] = customers_tico::where('customer_id', $category->customer_id)->first();
  		}

    	return response()->json(new JsonResponse($customer));
    }

    public function APIcredentials(Request $request)
    {
      $response = Http::withBasicAuth($request->username, $request->pass)->get('http://dev.tico.rs/api/v1/b2c-orders');
      $credentials = $response->status();
      if($credentials == 200)
      { 

        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                [
                    'username' => ['required'],
                    'pass' => ['required']
                ]
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            $checkApi = Credentials::where('username', $params['username'])->first();
            //dd($checkApi);
            if ($checkApi !== null) {
              return response()->json(['errors' => 'Podaci već upisani u bazu'], 403);
            }

            $user = Credentials::create([
                'username' => $params['username'],
                'password' => $params['pass'],
            ]);

            //return new UserResource($user);
            return response()->json(['success' => 'Podaci snimljeni']);
        }
      } else {        
            return response()->json(['errors' => 'Neispravni podaci za logovanje'], 403);
      }
    }


    private function getValidationRules($isNew = true)
    {
        return [
            'username' => 'required',
            'pass' => 'required',
        ];
    }


    private function checkAPI()
    {

    }
}
