<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Laravue\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\customers_api;
use App\Laravue\Models\customers_category_api;
use App\Laravue\Models\MemberLevel;
use App\Laravue\Models\MailChimp1;
use App\Laravue\Models\Credentials;
use App\Laravue\Models\Categories;
use App\Laravue\Models\PointsDefinitions;
use App\Laravue\Models\api_routes;
use App\Laravue\Models\route_name;
use App\Laravue\Models\web_services;
use App\Laravue\Models\Orders;
use \DrewM\MailChimp\MailChimp;
use \DrewM\MailChimp\Batch;
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
                  ->get(web_services::find(1)->route_prefix.route_name::find(4)->api_routes[0]->name);

		  $articles = $response->json();

      return response()->json(new JsonResponse($articles));
    }

  public function update()
  {
      $response = Http::withBasicAuth(
                      self::loginAPI()->username, 
                      self::loginAPI()->password
                    )
                  ->get(web_services::find(1)->route_prefix.route_name::find(12)->api_routes[0]->name);
      $customers = $response->json();

      $customer_help = customers_api::find(-1);
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
                  ->get(web_services::find(1)->route_prefix.route_name::find(12)->api_routes[0]->name);

      $customers = $response->json();
      $value_point = PointsDefinitions::getData('value_point')/100;
      $limitPoint = PointsDefinitions::limitPoint();

      if(customers_api::find(-1) === null)
      {
        $test = new customers_api;
        $test->id = -1;
        $test->customer_id = -1;
        $test->order_id = -1;
        $test->email = 'test@email.com';
        $test->phone = '060/1111111';
        $test->total_points = count($customers['orders']);
        $test->save();

        $categories0 = Http::withBasicAuth(
                        self::loginAPI()->username, 
                        self::loginAPI()->password
                      )->get(web_services::find(1)->route_prefix.route_name::find(1)->api_routes[0]->name);
        $category0 = $categories0->json();
        
        for ($y=0; $y < count($category0['categories']); $y++) { 
          $category = new categories;
          $category->category_id = $category0['categories'][$y]['id'];
          $category->name = $category0['categories'][$y]['name'];
          $category->save();
          if (count($category0['categories'][$y]['childs']) > 0) {
            for($x=0; $x < count($category0['categories'][$y]['childs']); $x++) {
              $category = new categories;
              $category->category_id = $category0['categories'][$y]['childs'][$x]['id'];
              $category->parent_id = $category0['categories'][$y]['id'];
              $category->name = $category0['categories'][$y]['childs'][$x]['name'];
              $category->save();
              if (count($category0['categories'][$y]['childs'][$x]['childs']) > 0) {
                for($z=0; $z < count($category0['categories'][$y]['childs'][$x]['childs']); $z++){
                  $category1 = new categories;
                  $category1->category_id = $category0['categories'][$y]['childs'][$x]['childs'][$z]['id'];
                  $category1->parent_id = $category0['categories'][$y]['childs'][$x]['id'];
                  $category1->name = $category0['categories'][$y]['childs'][$x]['childs'][$z]['name'];
                  $category1->save();
                }  
              }
            }
          }
        }

    		for ($i=0; $i < count($customers['orders']); $i++) {
    			$temp = 0;
          self::store_customers($customers, $i, $value_point, $limitPoint);
        }

        $orders = Orders::all();
        foreach($orders as $key => $order){
          $customer = customers_api::where('customer_id', $order->customer_id)->first();
          if ($customer === null) {
            $customers_api = new customers_api;
            $customers_api->customer_id = $order->customer_id;
            $customers_api->order_id = $order->order_id;
            $customers_api->category_id = $order->item_article_category_id === null || $order->item_article_category_id < 0 ? 126 : $order->item_article_category_id;
            $customers_api->email = $order->customer_email;
            $customers_api->updated_at = $order->updated_at;
            $customers_api->first_name = $order->customer_first_name == null || $order->customer_first_name == '' ? '?' : $order->customer_first_name;
            $customers_api->last_name = $order->customer_last_name == null || $order->customer_last_name == '' ? '?' : $order->customer_last_name;
            $customers_api->name = $order->customer_name;
            $customers_api->phone = $order->customer_phone;

            $pom = Orders::where('customer_id', $order->customer_id)->sum('item_article_web_price');
            if ($pom > $limitPoint) {
              $pom = $limitPoint;
            }
            $customers_api->total_points = $pom;

            $customers_api->save();
          }
        }
      

      
        // Registruje suscribere na MailChimp
        /*$customers_mailchimp = customers_api::where('customer_id', '>', 0)->get();
        foreach ($customers_mailchimp as $key => $value) {
          $category_mailchimp = categories::where('category_id', $value->category_id)->first()->name;
          if ($value->name !== null) {
            $value->first_name = $value->name;
          }
          $test = MailChimp1::create($value->email, $value->first_name, $category_mailchimp, $value->last_name, 'AAA', $value->phone);
        }*/
      
      }
      else
      {  


          $old_customers = customers_api::where('customer_id', '>', 0)->get();
          foreach ($old_customers as $key => $value) {
            
            $category_name = categories::where('category_id', $value->category_id)->first()->name;

            //$test0 = MailChimp1::tags($value->email, $category_name);
            $test = MailChimp1::getSubscriber($value->email);
            
            if($test['status'] == 'subscribed')
            {
                $update_tags = MailChimp1::tags($value->email, $category_name);
            }
            else
            {
              if ($value->name !== null) {
                $value->first_name = $value->name;
              }
              
              $new_subscriber = MailChimp1::create($value->email, $value->first_name, $category_name, $value->last_name, 'AAA', $value->phone);
            }
          }
          

        $counter = customers_api::where('order_id', '>', 0)->orderBy('updated_at', 'DESC')->first();
        $i=0;
        while($counter->updated_at < $customers['orders'][$i]['date'])
        {
          $temp=0;
          $new_customer = customers_api::where('customer_id', $customers['orders'][$i]['customer']['id'])->first();
          if ($new_customer === null) {
            $new_customer = new customers_api;
            $new_customer->customer_id = $customers['orders'][$i]['id'];
            $new_customer->email = $customers['orders'][$i]['customer']['email'];
            $new_customer->first_name = $customers['orders'][$i]['customer']['first_name'];
            $new_customer->last_name = $customers['orders'][$i]['customer']['last_name'];
            $new_customer->name = $customers['orders'][$i]['customer']['name'];
            $new_customer->phone = $customers['orders'][$i]['customer']['phone'];
            for ($y=0; $y < count($customers['orders'][$i]['items']); $y++) {
              $new_customer->order_id = $customers['orders'][$i]['items'][$y]['order_id'];
              $new_customer->updated_at = $customers['orders'][$i]['date'];
              $temp = $temp + $customers['orders'][$i]['items'][$y]['article']['web_price'] * $customers['orders'][$i]['items'][$y]['quantity'];
            }
            $new_customer->total_points = intval($temp/$value_point) > $limitPoint ? $limitPoint : intval($temp/$value_point);
            $new_customer->save();
          }
          else
          {
            $updated_customer = customers_api::where('customer_id', $new_customer->customer_id)->first();
            for ($x=0; $x < count($customers['orders'][$i]['items']); $x++) {
              $updated_customer->order_id = $customers['orders'][$i]['items'][$x]['order_id'];
              $updated_customer->updated_at = $customers['orders'][$i]['date'];
              $temp = $temp + $customers['orders'][$i]['items'][$x]['article']['web_price'] * $customers['orders'][$i]['items'][$x]['quantity'];
            }
            $helper = intval($temp/$value_point) > $limitPoint ? $limitPoint : intval($temp/$value_point);
            $updated_customer->total_points = $updated_customer->total_points + $helper > $limitPoint ? $limitPoint : $updated_customer->total_points + $helper;
            $updated_customer->update();
          }
          $i++;
        }

          // Dodavanje novog subscribera u MailChimp
          /*$old_customers = customers_api::where('customer_id', '>', 0)->get();
          foreach ($old_customers as $key => $value) {
            
            $category_name = categories::where('category_id', $value->category_id)->first()->name;

            //$test0 = MailChimp1::tags($value->email, $category_name);
            $test = MailChimp1::getSubscriber($value->email);
            
            if($test['status'] == 'subscribed')
            {
                $update_tags = MailChimp1::tags($value->email, $category_name);
            }
            else
            {
              if ($value->name !== null) {
                $value->first_name = $value->name;
              }
              
              $new_subscriber = MailChimp1::create($value->email, $value->first_name, $category_name, $value->last_name, 'AAA', $value->phone);
            }
          }*/
      }

  		return;
  	}


    private function store_customers($customers, $a, $value_point, $limitPoint)
    {
      $temp = 0;
      $orders = new Orders;
      $orders->order_id = $customers['orders'][$a]['id'];
      $orders->updated_at = $customers['orders'][$a]['date'];
      $orders->customer_email = $customers['orders'][$a]['customer']['email'];
      $orders->customer_id = $customers['orders'][$a]['customer']['id'];
      if ($customers['orders'][$a]['customer']['name'] !== null) {
        $orders->customer_name = $customers['orders'][$a]['customer']['name'];
      }
      else{
        $orders->customer_first_name = $customers['orders'][$a]['customer']['first_name'];
        $orders->customer_last_name = $customers['orders'][$a]['customer']['last_name'];
      }
      $orders->customer_address = $customers['orders'][$a]['customer']['address'];
      $orders->customer_phone = $customers['orders'][$a]['customer']['phone'];
      if(count($customers['orders'][$a]['items']) > 0){
        for ($i=0; $i < count($customers['orders'][$a]['items']); $i++) { 
          $orders->item_quantity = $customers['orders'][$a]['items'][$i]['quantity'];
          $orders->item_order_id = $customers['orders'][$a]['items'][$i]['id'];
          $orders->item_article_category_id = $customers['orders'][$a]['items'][$i]['article']['category_id'];
          $temp = $temp + $customers['orders'][$a]['items'][$i]['article']['web_price'] * $customers['orders'][$a]['items'][$i]['quantity'];
        }
          $orders->item_article_web_price = intval($temp/$value_point) > $limitPoint ? $limitPoint : intval($temp/$value_point);
      }
      $orders->save();
    }


    private function store_customers1($customers, $a, $value_point, $limitPoint)
    {
      //if (count($customers['orders'][$a]['items']) > 0) {
        
        $customer_tico = new customers_api;
        $temp = 0;

        $customer_tico->updated_at = $customers['orders'][$a]['date'];
        $cus[$a] = $customers['orders'][$a]['customer'];
        $customer_tico->email = $cus[$a]['email'];
        $customer_tico->first_name = $cus[$a]['first_name'];
        $customer_tico->last_name = $cus[$a]['last_name'];
        $customer_tico->name = $cus[$a]['name'];
        $customer_tico->phone = $cus[$a]['phone'];  
        $customer_tico->customer_id = $customers['orders'][$a]['customer']['id'];

        if(count($customers['orders'][$a]['items']) > 0){
          $items = $customers['orders'][$a]['items'];

          for($j=0; $j < count($items); $j++){
            $customer_tico->order_id = $items[$j]['order_id'];
            if ($customer_tico->order_id > 0 && $customer_tico->order_id !== null) {
              $category_id = $items[$j]['article']['category_id'];
              if ($category_id !== null) {
                $customer_tico->category_id = $category_id;
                $customers_category_api = new customers_category_api;
                $customers_category_api->customer_id = $customers['orders'][$a]['customer']['id'];
                $customers_category_api->category_id = $category_id;
                $customers_category_api->save();
              }
              //$customer_tico->total_points = $temp;
              $temp = $items[$j]['quantity'] * $items[$j]['article']['web_price'] + $temp;
            }
          }

          $total_points = intval($temp/$value_point);

          if ($total_points > $limitPoint) {
            $total_points = $limitPoint;
          }

          $customer_tico->total_points = $total_points;
        }
        else {
          $customer_tico->total_points = 0;
        }
        $customer_tico->save();
      //}
    }


    public function fetchListTico(Request $request)
    {
  		$clear = customers_api::where('order_id', 0)->get();
  		foreach ($clear as $key => $value) {
  			$value->delete();
  		}

    	$limit = $request->limit;
    	$customers = customers_api::where('id', '>', 0)->orderBy('customer_id', 'asc')->paginate($limit);

    	for ($i=0; $i < count($customers); $i++) {
    		$customers[$i]->level = MemberLevel::findLevel($customers[$i]->total_points);
    	}
  		
    	return response()->json(new JsonResponse($customers));
    }


    public function marketing_tico($category_id){

    	$categories = customers_category_api::where('category_id', $category_id)->get();
    	
    	$customer = [];
    	
    	foreach ($categories as $key => $category) {
    		$customer[$key] = customers_api::where('customer_id', $category->customer_id)->first();
  		}

    	return response()->json(new JsonResponse($customer));
    }

    public function APIcredentials(Request $request)
    {
      $response = Http::withBasicAuth(
          $request->username, 
          $request->pass
        )->get(web_services::find(1)->route_prefix.route_name::find(1)->api_routes[0]->name);
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
          $customer = customers_api::where('customer_id', $request->id)->first();
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
