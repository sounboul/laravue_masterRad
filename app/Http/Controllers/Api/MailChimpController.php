<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \DrewM\MailChimp\MailChimp;
use \DrewM\MailChimp\Batch;

class MailChimpController extends BaseController
{

	private function mailchimp_credentials()
	{
    	$MailChimp = new MailChimp(env('MAILCHIMP_KEY'));
    	$list_id = env('MAILCHIMP_LIST_ID');
    	return [
    		'MailChimp' => $MailChimp, 
    		'list_id' => $list_id
    	];
    }
	
	public function getSubscriber($email_address)
	{
		// List all the mailing lists
	    $MailChimp1 = new MailChimp(env('MAILCHIMP_KEY'));
	    $list_id = env('MAILCHIMP_LIST_ID');

        $subscriber_hash = MailChimp::subscriberHash($email_address);
        $result = $MailChimp1->get("lists/$list_id/members/$subscriber_hash");

        dd($result['status']);

	}

	public function create(Request $request)
	{
		// Subscribe someone to a list
        $result = self::mailchimp_credentials()['MailChimp']
        		->post("lists/".self::mailchimp_credentials()['list_id']."/members", [
                        'email_address'      => $request->email_address,
                        'merge_fields' 	     => ['FNAME'=>$request->fname, 
                        					'LNAME'    =>$request->lname, 
                        					'ADDRESS'  =>$request->address, 
                        					'PHONE'    =>$request->phone, 
                        					'BIRTHDAY' =>$request->birthday
                        				],
                        'tags' 			         => [$request->tags],
                        'status'             => 'subscribed',
                    ]);

        if ($result !== null) 
        {                 	
      		return response()->json([
      			'type' =>'success', 
      			'title' => 'table.success', 
      			'message' => 'marketing.success_sending'
      		]);  
        } 
        else 
        {
			return response()->json([
					'type' =>'error', 
					'title' => 'discounts.warning', 
					'message' => self::mailchimp_credentials()['MailChimp']->getLastError()
				]);
        }
	}

	public function update(Request $request)
	{
		// Update a list member with more information (using patch to update)
        $subscriber_hash = MailChimp::subscriberHash($request->email_address);

        $result = self::mailchimp_credentials()['MailChimp']
        		->patch("lists/".self::mailchimp_credentials()['list_id']."/members/$subscriber_hash", [
                        'merge_fields' 	  =>  [
                                  'ADDRESS' =>  $request->address,
                      						'PHONE'   =>  $request->phone
                    					 ],
                        //'interests'    => ['4c18f8c13a' => true, 'e5c9a2241e' => true],
                    ]);
        
        if ($result !== null) 
        {                 	
      		return response()->json([
      			'type' =>'success', 
      			'title' => 'table.success', 
      			'message' => 'marketing.success_sending'
      		]);  
        } 
        else 
        {
			return response()->json([
					'type' =>'error', 
					'title' => 'discounts.warning', 
					'message' => self::mailchimp_credentials()['MailChimp']->getLastError()
				]);
        }
	}

	public function delete($email_address)
	{		
        // Remove a list member
        $subscriber_hash = MailChimp::subscriberHash($email_address);

        $result = self::mailchimp_credentials()['MailChimp']
        		->delete("lists/".self::mailchimp_credentials()['list_id']."/members/$subscriber_hash");

        if ($result !== null) 
        {                 	
      		return response()->json([
      			'type' =>'success', 
      			'title' => 'table.success', 
      			'message' => 'marketing.success_sending'
      		]);  
        } 
        else 
        {
			return response()->json([
					'type' =>'error', 
					'title' => 'discounts.warning', 
					'message' => self::mailchimp_credentials()['MailChimp']->getLastError()
				]);
        }
	}

	public function tags(Request $request)
	{
		// Dodavanje tagova postojecim korisnicima
        $subscriber_hash = MailChimp::subscriberHash($request->email_address);
		$payload = array( 'tags' =>
		    array(
		        array('name' => $request->tag, 'status' => 'active' ),
		    ),
		);
		$result = self::mailchimp_credentials()['MailChimp']
				->post( "lists/".self::mailchimp_credentials()['list_id']."/members/$subscriber_hash/tags", $payload );

        if ($result !== null) 
        {                 	
      		return response()->json([
      			'type' =>'success', 
      			'title' => 'table.success', 
      			'message' => 'marketing.success_sending'
      		]);  
        } 
        else 
        {
			return response()->json([
					'type' =>'error', 
					'title' => 'discounts.warning', 
					'message' => self::mailchimp_credentials()['MailChimp']->getLastError()
				]);
        }
	}

}
