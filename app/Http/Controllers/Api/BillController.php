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
        //dd($request->all());
        $date = date_format(date_create($request->date1), "Y-m-d H:i:s");
$cat = $request->category;

        $articles = Articles::select('*')
                            ->where('categories_id', '=', $cat)
                            ->where('created_at', '>=', $date)
                            ->orderBy('created_at', 'asc')
                            ->get();
//dd($articles);
        foreach ($articles as $key => $article) {
            $bills[$key] = Bill::where('article', $article->id)->get();
        }
//dd($bills);
        for ($i=0; $i < count($bills); $i++) { 
            //echo $bills[$i];
            $poms = $bills[$i];
            foreach ($poms as $key => $pom) {
                if($pom->customer == null) {
                    $customer[$i] = Bill::select('*')
                            ->where('order_id', $pom->order_id)
                            ->where('customer', '!==' , null)
                            ->get();
                }
                else {
                    $customer[$i] = Bill::select('*')
                            ->where('order_id', $pom->order_id)
                            ->where('customer', $pom->customer)
                            ->get();
                }
            }
        }
dd($customer);
        return;
    }
}
