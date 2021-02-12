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
}
