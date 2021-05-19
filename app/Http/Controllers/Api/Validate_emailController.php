<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Laravue\Models\User;
use App\Laravue\Models\MailChimp1;


class Validate_emailController extends Controller
{

    public function send_mail(Request $request)
    {       
		$main_email = MailChimp1::get_env_mail();
		$main_password = MailChimp1::get_env_pass();
		$company_title = env('COMPANY_NAME');

    	$test = $request->email;

		require __DIR__.'/../../../../vendor/autoload.php';

		$mail = new PHPMailer(true);

		try 
		{
			//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			$mail->isSMTP();     
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = $main_email;
			$mail->Password = $main_password;         
			$mail->SMTPSecure = 'tls';                 
			$mail->Port = 587; 
			$mail->setFrom($main_email, $company_title);
			$mail->addAddress($test);
			//$mail->addAddress($user->email, $user->name);
			$mail->isHTML(true);			      
			$mail->addCC($main_email);
			/*for ($i=1; $i < count($request->all()) ; $i++) { 
				$mail->addBCC($request[$i]);
			} */
			//$mail->addBCC($request1);  
			//$mail->addReplyTo('your-email@gmail.com', 'Your Name');
			// print_r($_FILES['file']); exit;

			/*for ($i=0; $i < count($_FILES['file']['tmp_name']) ; $i++) { 
				$mail->addAttachment($_FILES['file']['tmp_name'][$i], $_FILES['file']['name'][$i]); // Optional name
			}*/
			$mail->Subject = 'Potvrda e-mail adrese';
			$mail->Body = '
				<html>
				<head>
					<title>Potvrda e-mail adrese</title>
					<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-2">
				</head>
				<body>
					<h2>Potvrda e-mail adrese</h2>
					<h3>Kliknite na link ispod da biste verifikovali e-mail adresu:</h3> 
					<p><a href="laravue.bexter.rs/verify/'.self::randomPassword($test).'">Link za potvrdu e-mail adrese</a></p>
				</body>
				</html>
			';
			$mail->send();

      		//return response()->json(['type' =>'success', 'title' => 'table.success', 'message' => 'marketing.success_sending']);
      		return;
		} 
		catch (Exception $e) 
		{
			return response()->json(['type' =>'error', 'title' => 'discounts.warning', 'message' => 'errors.error_mail']);
		} 
	    return response()->json(['type' =>'error', 'title' => 'table.danger', 'message' => 'errors.error_mail']);      	
    }


    private function randomPassword($test) 
    {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array();
	    $alphaLength = strlen($alphabet) - 1;
	    for ($i = 0; $i < 64; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }

	    $new_user = user::where('email', $test)->first();
	    $new_user->remember_token = implode($pass);
	    $new_user->update();

	    return implode($pass);
	}
}