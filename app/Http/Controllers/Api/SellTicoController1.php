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
use App\Laravue\Models\PointsDefinitions;
use Validator;

class SellTicoController extends Controller
{
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

    	$response = Http::withBasicAuth(
                      self::loginAPI()->username, 
                      self::loginAPI()->password)
                  ->get('http://dev.tico.rs/api/v1/articles');

  		  $articles = $response->json();

        return response()->json(new JsonResponse($articles));
    }

  public function update()
  {
      $response = Http::withBasicAuth(
                self::loginAPI()->username, 
                self::loginAPI()->password
              )
            ->get('http://dev.tico.rs/api/v1/b2c-orders');
      $customers = $response->json();

      $customer_help = customers_tico::find(-1);
      if ($customer_help->total_points !== count($customers['orders'])) {
        $customer_help->total_points = count($customers['orders']);
        $customer_help->update();
      }
  }


  public function customers()
    {
    	$response = Http::withBasicAuth(
                self::loginAPI()->username, 
                self::loginAPI()->password
              )
            ->get('http://dev.tico.rs/api/v1/b2c-orders');
  		$customers = $response->json();

      if (customers_tico::find(-1) !== null) {
        $number_of_orders = customers_tico::find(-1)->total_points;
      }
      else {
        $test = new customers_tico;
        $test->id = -1;
        $test->customer_id = -1;
        $test->order_id = -1;
        $test->email = 'test@email.com';
        $test->phone = '060/1111111';
        $test->total_points = count($customers['orders']);
        $test->save();
        $number_of_orders = count($customers['orders']);
      }
        

      $value_point = PointsDefinitions::getData('value_point')/100;

      for ($i=count($customers['orders']); $i > $number_of_orders; $i--) { 
        echo $i;
      }
//dd($number_of_orders);
      if($number_of_orders !== count($customers['orders'])) 
      {
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
    					if ($temp > 500000) {
    						$temp = 500000;
    					}
    					$customer_tico->total_points = $temp;
    				}
    			}

    			$customer_tico->total_points = intval($temp/$value_point);
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
    			$doubles = customers_category_tico::select('*')->where('customer_id', $help->customer_id)->where('category_id', $help->category_id)->get();
  	  		foreach ($doubles as $key => $value) {
  	  			if ($key > 0) {
  	  				$value->delete();
  	  			}
  	  		}
    		}
      }

      $customers = customers_tico::where('id', '>', 0)->orderBy('customer_id', 'asc')->paginate(10);

      for ($i=0; $i < count($customers); $i++) { 
        $pom = 0;
        $customers[$i]->level = MemberLevel::findLevel($customers[$i]->total_points);
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
    	$customers = customers_tico::where('id', '>', 0)->orderBy('customer_id', 'asc')->paginate($limit);

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
            if ($checkApi !== null) {
              return response()->json(['errors' => 'Podaci već upisani u bazu'], 403);
            }

            $user = Credentials::create([
                'username' => $params['username'],
                'password' => $params['pass'],
            ]);

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
