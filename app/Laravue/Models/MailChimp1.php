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


    public static function create($email_address, $fname, $tags, $lname='', $address='', $phone='', $birthday='')
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
                        'tags' 			=> [$tags],
                        'status'        => 'subscribed',
                    ]);
    	return $result;
    }


    public static function update1($email_address, $address=null, $phone=null)
	{
		$MailChimp1 = new MailChimp(env('MAILCHIMP_KEY'));
    	$list_id = env('MAILCHIMP_LIST_ID');

		// Update a list member with more information (using patch to update)
        $subscriber_hash = MailChimp::subscriberHash($email_address);

        $result = $MailChimp1->patch("lists/$list_id/members/$subscriber_hash", [
        				'merge_fields' 	=> ['ADDRESS'=>$address,
                    						'PHONE'=>$phone
                    					],
                        //'interests'    => ['4c18f8c13a' => true, 'e5c9a2241e' => true],
                    ]);
        return;
    }

    public static function tags($email_address, $tag=null)
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
}
