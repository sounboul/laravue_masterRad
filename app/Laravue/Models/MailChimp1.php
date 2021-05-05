<?php

namespace App\Laravue\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use \DrewM\MailChimp\MailChimp;
use \DrewM\MailChimp\Batch;

class MailChimp1 extends Model
{
	public static function getSubscriber($email_address)
	{
		// Get specific subscriber
	    $MailChimp1 = new MailChimp(env('MAILCHIMP_KEY'));
	    $list_id = env('MAILCHIMP_LIST_ID');

        $subscriber_hash = MailChimp::subscriberHash($email_address);
        $result = $MailChimp1->get("lists/$list_id/members/$subscriber_hash");

        return $result;

	}


    public static function create($email_address, $fname, $lname='', $address='', $phone='', $birthday='')
    {
    	$MailChimp1 = new MailChimp(env('MAILCHIMP_KEY'));
    	$list_id = env('MAILCHIMP_LIST_ID');

    	$result = $MailChimp1->post("lists/$list_id/members", [
                        'email_address' => $email_address,
                        'merge_fields' 	=> ['FNAME'=>$fname, 
                        					'LNAME'=>$lname, 
                        					'ADDRESS'=>$address, 
                        					'PHONE'=>$phone,
                        					'BIRTHDAY'=>$birthday
                        				],
                        //'tags' 			=> [$tags],
                        'status'        => 'subscribed',
                    ]);
    	return $result;
    }


    public static function update1($email_address, $tag)
	{
		$MailChimp1 = new MailChimp(env('MAILCHIMP_KEY'));
    	$list_id = env('MAILCHIMP_LIST_ID');

		// Dodavanje tagova postojecim korisnicima
        $subscriber_hash = MailChimp::subscriberHash($email_address);
		$payload = array( 'tags' =>
		    array(
		        array('name' => $tag, 'status' => 'active' ),
		    ),
		);
		$result = $MailChimp1->post( "lists/$list_id/members/$subscriber_hash/tags", $payload );

        return;
    }

    public static function tags($email_address, $tag1)
	{
		$MailChimp1 = new MailChimp(env('MAILCHIMP_KEY'));
    	$list_id = env('MAILCHIMP_LIST_ID');

		// Dodavanje tagova postojecim korisnicima
        $subscriber_hash = MailChimp::subscriberHash($email_address);
		
		for ($key=0; $key < count($tag1); $key++) { 
	        $payload = array( 'tags' =>
			    array(
			        array('name' => $tag1[$key], 'status' => 'active' ),
			    ),
			);
			$result = $MailChimp1->post( "lists/$list_id/members/$subscriber_hash/tags", $payload );
		}
		//dd($result);
		return;
	}
}
