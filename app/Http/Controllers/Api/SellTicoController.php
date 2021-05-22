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
use App\Laravue\Models\Orders_history;
use App\Laravue\Models\Cashing;
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

        if(customers_api::find(-1) === null)    // Upis u prazne tabele - prvo pokretanje aplikacije (Refresh u 'Kupci')
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
                    $customers_api->email = time().$order->customer_email;
                    $customers_api->updated_at = $order->updated_at;
                    $customers_api->first_name = $order->customer_first_name === null || $order->customer_first_name == '' ? '?' : $order->customer_first_name;
                    $customers_api->last_name = $order->customer_last_name === null || $order->customer_last_name == '' ? '?' : $order->customer_last_name;
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
            
            $old_customers = customers_api::where('customer_id', '>', 0)->get();
            foreach ($old_customers as $key => $value) {          
                $category_name = categories::where('category_id', $value->category_id)->first()->name;
                $test = MailChimp1::getSubscriber($value->email);
                if($test['status'] == 'subscribed')
                {
                    $update_tags = MailChimp1::update1($value->email, $category_name);
                }
                else
                {
                    if ($value->name !== null) {
                        $first_name = $value->name;
                        $last_name = '';
                    }
                    $new_subscriber = MailChimp1::create($value->email, $value->first_name, $value->last_name, 'AAA', $value->phone, '01/10');
                    $category_pom = customers_category_api::where('customer_id', $value->customer_id)->get();
                    
                    if($category_pom !== null){
                        foreach($category_pom as $category1){
                            $name_category = categories::where('category_id', $category1->category_id)->first();
                            if($name_category !== null) {
                                $update_tags = MailChimp1::update1($value->email, $name_category->name);
                            }
                        }
                    }
                }
            }
        }
        else  // upis novih kupaca pored postojecih
        { 
            $counter = customers_api::where('order_id', '>', 0)->orderBy('order_id', 'DESC')->first();

            $num_customers = count($customers['orders'])-1;
            for ($i=$num_customers; $i >= 0 ; $i--) { 
                $temp = 0;
                if ($counter->order_id < $customers['orders'][$i]['id']) {
                    $new_customer = customers_api::where('email', $customers['orders'][$i]['customer']['email'])->first();
                    if ($new_customer === null) {         // novi kupac
                        $new_customer = new customers_api;
                        $new_customer->customer_id = $customers['orders'][$i]['customer']['id'];
                        $new_customer->email = $customers['orders'][$i]['customer']['email'];
                        $new_customer->first_name = $customers['orders'][$i]['customer']['name'] === null ? $customers['orders'][$i]['customer']['first_name'] : "?";
                        $new_customer->last_name = $customers['orders'][$i]['customer']['name'] === null ? $customers['orders'][$i]['customer']['last_name'] : "?";
                        $new_customer->name = $customers['orders'][$i]['customer']['name'];
                        $new_customer->phone = $customers['orders'][$i]['customer']['phone'];
                        if($customers['orders'][$i]['items'] == []){
                            $new_customer->category_id = 0;
                        }
                        else
                        {
                            $new_customer->category_id = $customers['orders'][$i]['items'][0]['article']['category_id'];
                            for ($y=0; $y < count($customers['orders'][$i]['items']); $y++) {
                                $new_customer->order_id = $customers['orders'][$i]['items'][$y]['order_id'];
                                $new_customer->updated_at = $customers['orders'][$i]['date'];
                                self::categories_api($customers, $i, $y);
                                $temp = $temp + $customers['orders'][$i]['items'][$y]['article']['web_price'] * $customers['orders'][$i]['items'][$y]['quantity'];
                            }
                        }
                        $new_customer->total_points = intval($temp/$value_point) > $limitPoint ? $limitPoint : intval($temp/$value_point);
                        $new_customer->save();

                        self::optimize_categories_api();

                        if ($new_customer->name !== null) {
                            $first_name = $new_customer->name;
                            $last_name = '';
                        }

                        $new_categories = customers_category_api::where('customer_id', $customers['orders'][$i]['customer']['id'])->get();
                        
                        foreach ($new_categories as $key => $new_category) {
                            $final_category[$key] = categories::where('category_id',$new_category->category_id)->first()->name;
                        }

                        $new_subscriber = MailChimp1::create($new_customer->email, $new_customer->first_name, $new_customer->last_name, 'AAA', $new_customer->phone, '01/10');
                        if (count($final_category) > 1) {
                            $update_tags = MailChimp1::tags($new_customer->email, $final_category);
                        }
                        else
                        {
                            $update_tags = MailChimp1::update1($new_customer->email, $final_category[0]);
                        }
                    }
                    else        // update postojecih kupaca
                    {
                        $updated_customer = customers_api::where('email', $new_customer->email)->first();
                        if (count($customers['orders'][$i]['items']) == 0) {
                            $pom_category = [0];
                        }
                        else{
                            for ($x=0; $x < count($customers['orders'][$i]['items']); $x++) {
                                $pom_category[$x] = $customers['orders'][$i]['items'][$x]['article']['category_id'];
                                $updated_customer->order_id = $customers['orders'][$i]['items'][$x]['order_id'];
                                self::orders_history($customers, $i);
                                $updated_customer->updated_at = $customers['orders'][$i]['date'];
                                self::categories_api($customers, $i, $x);
                                $temp = $temp + $customers['orders'][$i]['items'][$x]['article']['web_price'] * $customers['orders'][$i]['items'][$x]['quantity'];
                            }
                        }
                        $helper = intval($temp/$value_point) > $limitPoint ? $limitPoint : intval($temp/$value_point);
                        $updated_customer->total_points = $updated_customer->total_points + $helper > $limitPoint ? $limitPoint : $updated_customer->total_points + $helper;
                        $updated_customer->update();
                        self::optimize_categories_api();
                        $update_tags = MailChimp1::tags($updated_customer->email, $pom_category);
                    }
                }
            }
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
            for ($zi=0; $zi < count($customers['orders'][$a]['items']); $zi++) { 
                $orders->item_quantity = $customers['orders'][$a]['items'][$zi]['quantity'];
                $orders->item_order_id = $customers['orders'][$a]['items'][$zi]['id'];
                $orders->item_article_category_id = $customers['orders'][$a]['items'][$zi]['article']['category_id'];
                $temp = $temp + $customers['orders'][$a]['items'][$zi]['article']['web_price'] * $customers['orders'][$a]['items'][$zi]['quantity'];
                self::categories_api($customers, $a, $zi);
            }
            $orders->item_article_web_price = intval($temp/$value_point) > $limitPoint ? $limitPoint : intval($temp/$value_point);
            self::optimize_categories_api();
        }
        $orders->save();

        self::orders_history($customers, $a);

        return;
    }

    private function orders_history($customers, $ax)
    {
        $orders_history = new orders_history;
            $orders_history->order_id = $customers['orders'][$ax]['id'];
            $orders_history->customer_id = $customers['orders'][$ax]['customer']['id'];
            $orders_history->created_at = $customers['orders'][$ax]['date'];
        $orders_history->save();
    }

    private function categories_api($customers, $xa, $xi)
    {      
        $customers_category_api = new customers_category_api;
            $customers_category_api->customer_id = $customers['orders'][$xa]['customer']['id'];
            $customers_category_api->category_id = $customers['orders'][$xa]['items'][$xi]['article']['category_id'];
        $customers_category_api->save();
        return;
    }

    private function optimize_categories_api()
    {
        $starts = customers_category_api::all();

        foreach($starts as $key => $start){
            $double_category_customer[$key] = customers_category_api::select('*')
                                        ->where('customer_id', '=', $start->customer_id)
                                        ->where('category_id', '=', $start->category_id)
                                        ->first();

            $pomx = customers_category_api::select('*')
                                        ->where('id', '>', $double_category_customer[$key]->id)
                                        ->where('customer_id', '=', $start->customer_id)
                                        ->where('category_id', '=', $start->category_id)
                                        ->get(); 
            if($pomx !== null)
            {
                foreach($pomx as $pomy){
                    $pomy->delete();
                }
            }
        }
        return;
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
              $customers[$i]->total_points = $customers[$i]->total_points - Cashing::get_cashing($customers[$i]->customer_id);
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
            } 
            else 
            {
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
        } 
        else 
        {        
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


    public function chart_values()
    {
        $expectedData1 = array(100, 120, 161, 134, 105, 160, 165, 180, 160, 151, 106, 145, 150, 130);
        $actualData1 = array(120, 82, 91, 154, 162, 140, 145, 120, 90, 100, 138, 142, 130, 130);
        $expectedData2 = array(200, 192, 120, 144, 160, 130, 140, 120, 82, 91, 154, 162, 140, 130);
        $actualData2 = array(180, 160, 151, 106, 145, 150, 130, 80, 100, 121, 104, 105, 90, 100);
        $expectedData3 = array(80, 100, 121, 104, 105, 90, 100, 120, 90, 100, 138, 142, 130, 130);
        $actualData3 = array(120, 90, 100, 138, 142, 130, 130, 80, 100, 121, 104, 105, 90, 100);
        $expectedData4 = array(130, 140, 141, 142, 145, 150, 160, 120, 90, 100, 138, 142, 130, 130);
        $actualData4 = array(120, 82, 91, 154, 162, 140, 130, 80, 100, 121, 104, 105, 90, 100);

        $lineChartData = json_encode(
            array(
                'newVisitis' => array(
                    'expectedData' => $expectedData1,
                    'actualData' => $actualData1,
                ),
                'messages' => array(
                    'expectedData' => $expectedData2,
                    'actualData' => $actualData2,
                ),
                'purchases' => array(
                    'expectedData' => $expectedData3,
                    'actualData' => $actualData3,
                ),
                'shoppings' => array(
                    'expectedData' => $expectedData4,
                    'actualData' => $actualData4,
                ),
            )
        );

        return response()->json(['lineChartData' => json_decode($lineChartData)]); 
    }


    public function getDates()
    {
        $response = Http::withBasicAuth(
                        self::loginAPI()->username, 
                        self::loginAPI()->password
                      )
                    ->get(web_services::find(1)->route_prefix.route_name::find(12)->api_routes[0]->name);

        $customers = $response->json();
        $j=0;
        for ($i=0; $i < 14; $i++) { 
            $customers1[$i] = date_format(date_create($customers['orders'][$i]['date']), 'd.m.Y.');
            $date1[$i] = $customers1[$i];
            $j=$i;
            $temp=0;
            //$suma[$i] = $customers['orders'][$i]['items'][0]['article']['web_price'] * $customers['orders'][$i]['items'][0]['quantity'];
            if($i > 0){
                if($customers1[$j] == $customers1[$j-1]){
                    while ($customers1[$j] == $customers1[$j-1]) {
                        for($x=0; $x < count($customers['orders'][$i]['items']); $x++){
                            $suma[$i] = $temp + $customers['orders'][$i]['items'][$x]['article']['web_price'] * $customers['orders'][$i]['items'][$x]['quantity'];
                            $temp = $suma[$i];
                        }
                        $j--;
                    }
                }
                else
                {
                    $temp=0;
                    for($y=0; $y < count($customers['orders'][$i]['items']); $y++){
                        $suma[$i] = $temp + $customers['orders'][$i]['items'][$y]['article']['web_price'] * $customers['orders'][$i]['items'][$y]['quantity'];
                        $temp = $suma[$i];
                    }
                }
            }
        }
        //$customers1 = [1, 2, 3, 4, 5, 6, 7];
        //dd($suma);

        return response()->json(new JsonResponse($customers1));       
    }
}
