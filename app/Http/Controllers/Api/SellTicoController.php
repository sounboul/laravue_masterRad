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
                      self::loginAPI()->password
                    )
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
      $value_point = PointsDefinitions::getData('value_point')/100;

      if(customers_tico::find(-1) === null)
      {
        $test = new customers_tico;
        $test->id = -1;
        $test->customer_id = -1;
        $test->order_id = -1;
        $test->email = 'test@email.com';
        $test->phone = '060/1111111';
        $test->total_points = count($customers['orders']);
        $test->save();


    		for ($i=0; $i < count($customers['orders']); $i++) {
    			$temp = 0;
          self::myFunction($customers, $i, $value_point);
    		}

    		for ($i=0; $i < count($customers['orders']); $i++) {

    			$customer_id = $customers['orders'][$i]['customer']['id'];

    			$pom = customers_tico::where('customer_id', $customer_id)->sum('total_points');

    			$ret = customers_tico::where('customer_id', $customer_id)->first();
    			$ret->total_points = $pom;
    			$ret->update();

    			$pom2 = customers_tico::select('*')
                    ->where('customer_id', $customer_id)
                    ->where('id', '!=', $ret->id)
                    ->get();

    			foreach ($pom2 as $key => $value) {
    				$value->delete();
    			}

    		}

    		$helpers = customers_category_tico::all();
    		foreach ($helpers as $key => $help) {
    			$doubles = customers_category_tico::select('*')
                      ->where('customer_id', $help->customer_id)
                      ->where('category_id', $help->category_id)
                      ->get();
  	  		foreach ($doubles as $key => $value) {
  	  			if ($key > 0) {
  	  				$value->delete();
  	  			}
  	  		}
    		}
      }

      else
      {        
        $customers_orders = $customers['orders'];
        $customers_orders_id = $customers['orders'][0]['id'];
        $customers_orders_items = $customers['orders'][0]['items'];

        $counter = customers_tico::where('customer_id', '>', 0)->orderBy('updated_at', 'DESC')->first();

        if ($counter->order_id < $customers_orders_id) {
          $ii = count($customers_orders_items)-1;
          while ($customers_orders_items[$ii] > $counter->order_id) {

            $points = 0;

            for ($j=0; $j < count($customers_orders[0]['items']); $j++) { 
              $points = $customers_orders[$ii]['items'][$j]['quantity'] * $customers_orders[$ii]['items'][$j]['article']['web_price'] + $points; 
            }

            $temp0 = customers_tico::where('customer_id', $customers_orders[$ii]['customer']['id'])->first();
            if($temp0 !== null)
            {
              $temp0->total_points = $temp0->total_points + intval($points/$value_point);
              $temp0->updated_at = now();
              $temp0->order_id = $customers_orders_id;
              $temp0->update();
            }
            else
            {
              self::myFunction($customers, $ii, $value_point);
            }

            $ii--;
            if ($ii < 0) {
              break;
            }
          }
        }
      }
  		return;
  	}

    private function myFunction($customers, $a, $value_point)
    {
      $customer_tico = new customers_tico;
      $temp = 0;
      $customer_tico->updated_at = $customers['orders'][$a]['date'];
      $cus[$a] = $customers['orders'][$a]['customer'];
      $customer_tico->email = $cus[$a]['email'];
      $customer_tico->first_name = $cus[$a]['first_name'];
      $customer_tico->last_name = $cus[$a]['last_name'];
      $customer_tico->name = $cus[$a]['name'];
      $customer_tico->phone = $cus[$a]['phone'];  
      $customer_tico->customer_id = $customers['orders'][$a]['customer']['id'];

      $items = $customers['orders'][$a]['items'];

      for($j=0; $j < count($items); $j++){
        $customer_tico->order_id = $items[$j]['order_id'];
        if ($customer_tico->order_id != 0) {
          $category_id = $items[$j]['article']['category_id'];
          if ($category_id != null) {
            $customer_tico->category_id = $category_id;
            $customers_category_tico = new customers_category_tico;
            $customers_category_tico->customer_id = $customers['orders'][$a]['customer']['id'];
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
      $customer_tico->save();
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

    
   /* public function customers_level_API(Request $request)
    {
        $username = $request->header('php-auth-user');
        $pass = $request->header('php-auth-pw');
        
        if ($username == self::bexterAPI()->username && $pass == self::bexterAPI()->password)
        {
          $customer = customers_tico::where('customer_id', $request->id)->first();
          $level = MemberLevel::findLevel($customer->total_points);
          $pom = MemberLevel::findLevelAPI($level);
          
          return response()->json([
              'total_points' => $customer->total_points,
              'level' => $level,
              'level_strength' => $pom->level_strength,
              'discount_percent' => $pom->discount_percent/10,
            ],
            200,
            [],
            JSON_NUMERIC_CHECK
          );
        }
        else
        {
          return response()->json(['error' => 'Neispravni podaci']);
        }
    }*/
}
