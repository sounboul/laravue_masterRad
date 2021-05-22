<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Laravue\Models\MemberLevel;
use App\Laravue\Models\customers_api;
use App\Laravue\JsonResponse;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Http;
use App\Laravue\Models\Credentials;
use App\Laravue\Models\customers_category_api;
use App\Laravue\Models\Categories;
use App\Laravue\Models\PointsDefinitions;
use App\Laravue\Models\web_services;
use App\Laravue\Models\api_routes;
use App\Laravue\Models\route_name;
use App\Laravue\Models\Orders;
use App\Laravue\Models\Cashing;
use \DrewM\MailChimp\MailChimp;

class BexterController extends BaseController
{
    private function loginAPI()
    {
        $loginAPI = Credentials::find(1);
        return $loginAPI;
    }

    private function bexterAPI()
    {
        $bexterAPI = Credentials::find(2);
        return $bexterAPI;
    }

    
    public function get_customer_level(Request $request)
    {
        $username = $request->header('php-auth-user');
        $pass = $request->header('php-auth-pw');
        
        if ($username == self::bexterAPI()->username && $pass == self::bexterAPI()->password)
        {
			$customer = customers_api::where('customer_id', $request->id)->first();
			if($customer === null)
			{
			    return response()->json(['error' => 'Nepostojeći podaci!'], 403);
			}
			$level = MemberLevel::findLevel($customer->total_points - Cashing::get_cashing($request->id));
			$pom = MemberLevel::findLevelAPI($level);

			return response()->json([
					'total_points' => $customer->total_points - Cashing::get_cashing($request->id),
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
          	return response()->json(['error' => 'Neispravni podaci'], 403);
        }
    }


    public function customers(Request $request)
    {
    	$username = $request->header('php-auth-user');
        $pass = $request->header('php-auth-pw');
        
        if ($username == self::bexterAPI()->username && $pass == self::bexterAPI()->password)
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
	        	return response()->json(['error' => 'ODMAH KONTAKTIRATI PODRŠKU!!! / CONTACT SUPPORT IMMEDIATELY!!!'], 403);
	            /*$test = new customers_api;
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
	          
	            // Registruje subscribere na MailChimp
	            
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
	                }
	            }*/
	        }
	        else  // upis/update novih kupaca pored postojecih
	        { 
	            $counter = customers_api::where('order_id', '>', 0)->orderBy('order_id', 'DESC')->first();

	            $num_customers = count($customers['orders'])-1;
	            for ($i=$num_customers; $i >= 0; $i--) { 
	                $temp = 0;
	                if ($counter->order_id < $customers['orders'][$i]['id']) {
	                    $new_customer = customers_api::where('customer_id', $customers['orders'][$i]['customer']['id'])->first();
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
	                        $updated_customer = customers_api::where('customer_id', $new_customer->customer_id)->first();
	                        if (count($customers['orders'][$i]['items']) == 0) {
	                            $pom_category = [0];
	                        }
	                        for ($x=0; $x < count($customers['orders'][$i]['items']); $x++) {
	                            $pom_category[$y] = $customers['orders'][$i]['items'][$x]['article']['category_id'];
	                            $updated_customer->order_id = $customers['orders'][$i]['items'][$x]['order_id'];
	                            $updated_customer->updated_at = $customers['orders'][$i]['date'];
	                            self::categories_api($customers, $i, $x);
	                            $temp = $temp + $customers['orders'][$i]['items'][$x]['article']['web_price'] * $customers['orders'][$i]['items'][$x]['quantity'];
	                        }
	                        $helper = intval($temp/$value_point) > $limitPoint ? $limitPoint : intval($temp/$value_point);
	                        $updated_customer->total_points = $updated_customer->total_points + $helper > $limitPoint ? $limitPoint : $updated_customer->total_points + $helper;
	                        $updated_customer->update();
	                        self::optimize_categories_api();

	                        if($pom_category !== [0]){
		                        foreach($pom_category as $category1){
		                            $name_category = categories::where('category_id', $category1->category_id)->first();
		                            if($name_category !== null) {
		                                $update_tags = MailChimp1::update1($updated_customer->email, $name_category->name);
		                            }
		                        }
		                    }
	                        //$update_tags = MailChimp1::tags($updated_customer->email, $pom_category);
	                    }
	                }
	            }
	        }
	    	return response()->json(['success' => 'Sve ok!!!']);
	    }
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
        return;
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


    public function cashing(Request $request)
    {
    	$username = $request->header('php-auth-user');
        $pass = $request->header('php-auth-pw'); 
        if ($username == self::bexterAPI()->username && $pass == self::bexterAPI()->password)
        {  
            $customer_id = $request['customer_id'];
            $cashed_points = $request['cashed_points'];
            $user = $request['user'];
            $check_user = user::where('email', $user)->first();
            if ($check_user === null) {
            	return response()->json(['error' => 'Nepostojeći radnik!'], 403);
            }
        	$customer = customers_api::where('customer_id', $customer_id)->first(); 
        	$customer_cashing = cashing::where('customer_id', $customer_id)->sum('cashed_points');
        	
			if($customer === null){
		        return response()->json(['error' => 'Nepostojeći kupac!'], 403);
			}
			else
			{	
    			if($customer->total_points - $customer_cashing < $cashed_points)
    			{
    			    return response()->json(['error' => 'Kupac nema toliki broj poena!'], 403);
    			}
    	    	$new_cashing = new Cashing;
    	    	$new_cashing->customer_id = $customer_id;
    	    	$new_cashing->cashed_points = $cashed_points;
    	    	$new_cashing->user = $user;
    	    	$new_cashing->save();
    
    			$customers_name = customers_api::where("customer_id", $customer_id)->first()->name !== null ? customers_api::where("customer_id", $customer_id)->first()->name : customers_api::where("customer_id", $customer_id)->first()->first_name . " " . customers_api::where("customer_id", $customer_id)->first()->last_name;
    	    	return response()->json(['success' => 'Korisniku '. $customers_name .' bodovi uspešno unovčeni!!!']);
			}
	    }
	    else
	    {
	    	return response()->json(['error' => 'Neispravni podaci!!!']);
	    }
    }

}
