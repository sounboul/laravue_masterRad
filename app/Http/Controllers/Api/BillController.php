<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Models\Bill;
use App\Laravue\Models\Articles;

class BillController extends Controller
{
    public function listItem(Request $request)
    {
    	$bills = $request->billList;
    	//dd($bills);
    	$time = time();
    	//dd($time);
    	for($i = 0; $i < count($bills); $i++) {
	    	$bill = new Bill;
			$bill->article = $bills[$i]['id'];
			$bill->price = $bills[$i]['price'];
			$bill->order_id = $time;
			$bill->updated_at = date("Y-m-d H:i:s");
	    	$bill->save();	        
    	}

    	$bills = $request->billList;
    	for($i = 0; $i < count($bills); $i++) {
	        $article = Articles::find($bills[$i]['id']);
	        $article->amount = $article->amount - 1;
	        $article->update();
	    }

        return response()->json(['time' => $time]);
    }


    public function executeQuery(Request $request)
    {
        $bills = array();
        $bills1 = array();
        $orders = array();
        $order = array();
        $customers = array();
        $date = date_format(date_create($request->date1), "Y-m-d H:i:s");
        $cat = $request->category;

        $articles = Articles::select('*')
                            ->where('categories_id', '=', $cat)
                            ->where('created_at', '>=', $date)
                            ->get();


        $cnt = 0;
        foreach ($articles as $key => $article) {     
            $bills[$key] = Bill::where('article', $article->id)->first();

            if (isset($bills[$key]->article)) {
                $bills1[$cnt] = $bills[$key];
                $orders[$cnt] = $bills[$key]->order_id;

                $cnt += 1;
            }
        }
    
        $count_bills1 = count($bills1);

        for ($i=0; $i < $count_bills1; $i++) { 
            $customers[$i] = Bill::select('*')->where('order_id', $bills1[$i]->order_id)
                                                ->where('customer', '!=', null)
                                                ->get();
        }

for ($i=0; $i < count($customers); $i++) { 

    foreach ($customers[$i] as $key => $value) {
        //echo $value->customer.", ";
    }
}



        return response()->json(['customers' => $customers]);
    }
}
