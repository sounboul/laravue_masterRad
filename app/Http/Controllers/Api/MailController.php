<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class MailController extends Controller
{
	private $main_email = 'bexterdesign@gmail.com';
	private $main_password = 'jovance1';
	private $company_title = 'Bexter Design';


    public function send_mail(Request $request)
    {       
		$test = [];
    	$test[0] = "bexterdesign@gmail.com";
    	/*$test[1] = "petkovic.boban@gmail.com";
    	$test[2] = "boban.petkovic1971@gmail.com";
    	$test[3] = "jovanovtata@gmail.com";*/

		require __DIR__.'/../../../../vendor/autoload.php';

		$mail = new PHPMailer(true);

		try 
		{
			//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			$mail->isSMTP();     
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = $this->main_email;
			$mail->Password = $this->main_password;         
			$mail->SMTPSecure = 'tls';                 
			$mail->Port = 587; 
			$mail->setFrom($this->main_email, $this->company_title);
			$mail->addAddress($test[0]);
			//$mail->addAddress($user->email, $user->name);
			$mail->isHTML(true);			      
			$mail->addCC($this->main_email);
			/*for ($i=1; $i < count($request->all()) ; $i++) { 
				$mail->addBCC($request[$i]);
			} */
			//$mail->addBCC($request1);  
			//$mail->addReplyTo('your-email@gmail.com', 'Your Name');
			// print_r($_FILES['file']); exit;

			/*for ($i=0; $i < count($_FILES['file']['tmp_name']) ; $i++) { 
				$mail->addAttachment($_FILES['file']['tmp_name'][$i], $_FILES['file']['name'][$i]); // Optional name
			}*/
			$mail->Subject = 'SellTico test email';
			$mail->Body = '
				<html>
				<head>
					<title>Test SellTico</title>
					<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-2">
				</head>
				<body>
					<h2>SellTico test email</h2>
					<h3>Lep pozdrav od  <i>Petković Bobana!</i></h3> 
					<h3>Test e-mail...</h3>
				</body>
				</html>
			';
			$mail->send();	
      		return response()->json(['type' =>'success', 'title' => 'table.success', 'message' => 'marketing.success_sending']);
		} 
		catch (Exception $e) 
		{
			return response()->json(['type' =>'error', 'title' => 'discounts.warning', 'message' => 'errors.error_mail']);
		} 
	    return response()->json(['type' =>'error', 'title' => 'table.danger', 'message' => 'errors.error_mail']);      	
    }
}