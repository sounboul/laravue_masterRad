<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Cache;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// use App\User1s;


class MailController extends Controller
{
	//private $main_email = config('mail.username');
	private $main_email = 'bexterdesign@gmail.com';
	private $main_password = 'jovance1';
	//private $main_password = 'jovance1';
	private $company_title = 'Bexter Design';
	//private $company_title = 'FeedBakc';


    public function send_mail(Request $request)
    {       
    	$log = count($request->all());
    	/*//dd($log);
    	for ($i=0; $i < $log; $i++) { 
    		print_r($request[$i]);
    	}
dd($request);*/
		$test = [];
    	$test[0] = "bexterdesign@gmail.com";
    	$test[1] = "petkovic.boban@gmail.com";
    	$test[2] = "boban.petkovic1971@gmail.com";
    	$test[3] = "jovanovtata@gmail.com";

		require __DIR__.'/../../../../vendor/autoload.php';

		$mail = new PHPMailer(true);

		try 
		{
			//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
    		//$mail->SMTPDebug = 2;
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
			for ($i=1; $i < count($test) ; $i++) { 
				$mail->addBCC($test[$i]);
			} 
			//$mail->addBCC($request1);  
			//$mail->addReplyTo('your-email@gmail.com', 'Your Name');
			// print_r($_FILES['file']); exit;

			/*for ($i=0; $i < count($_FILES['file']['tmp_name']) ; $i++) { 
				$mail->addAttachment($_FILES['file']['tmp_name'][$i], $_FILES['file']['name'][$i]); // Optional name
			}*/
			$mail->Subject = 'Pozdrav od Bobana!';
			$mail->Body = '
				<html>
				<head>
					<title>Test SellTico</title>
					<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-2">
				</head>
				<body>
					<h3>Lep pozdrav od  <i>Petković Bobana!</i> Test e-mail...</h3>
				</body>
				</html>
			';
			$mail->send();	
      		return response()->json(['success' => 'Message sent.']);
		} 
		catch (Exception $e) 
		{
			return response()->json(['error' => 'Message could not be sent.']);
		} 
	    return response()->json(['errors' => 'Unknown error!']);      	
    }


	public function verified($verified)
	{
		if ($verified != Cache::get('verification_link')) 
		{
			Cache::forget('verification_link');
			return view('thankyou', ['error' => 'Validation error!']);
		}	

		$user = user1s::where('email', Cache::get('email'))->first();

		$image = base64_encode($qr = \QrCode::format('png')->size(250)->errorCorrection('H')->generate( 'local.touch/selection/'. $user->id)); 
		//$image = base64_encode($qr = \QrCode::format('png')->size(250)->errorCorrection('H')->generate('https://email-validate.bexter.rs/selection/'. $user->id));

        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = str_random(10).'.'.'png';
        \File::put(public_path(). '/images/' . $imageName, base64_decode($image));

		require __DIR__.'/../../../vendor/autoload.php';

		$mail = new PHPMailer(true);

		try 
		{
    		$mail->SMTPDebug = 0;
			$mail->isSMTP();     
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = $this->main_email;
			$mail->Password = $this->main_password;            
			$mail->SMTPSecure = 'tls';                 
			$mail->Port = 587; 
			$mail->setFrom($this->main_email, $this->company_title);
			$mail->addAddress($user->email, $user->name);
			$mail->isHTML(true);			        
			$mail->Subject = 'Your new QR Code!';
        	$mail->addAttachment('images/'.$imageName);
			$mail->Body = '
				<html>
				<head>
					<title>test</title>
				</head>
				<body>
					<h4>Dear,</h4><br>
					
					<p>In Attachment of this email you can find your QR code.</p>
					<p>Save it on your computer for future use.</p>
					<p>All the best in further work!</p>

						<p>Yours '.$this->company_title.'.</p>
					
				</body>
				</html>
			';
			$mail->send();	
		} 
		catch (Exception $e) 
		{
			return back()->with('error', 'Message could not be sent.');
		} 

		$user->email_verified_at = time();
		$user->qr = $imageName;
		$user->save();

		return view('thankyou', ['error' => '']);		
	}

    public function checkEmail(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'enteredEmail' => 'required|string|email'
        ]);
    	if ($validator->passes()) {
	    	$user = user1s::where('email', $request->enteredEmail)->first();
	    	if(!empty($user)){
				require __DIR__.'/../../../vendor/autoload.php';
				$mail = new PHPMailer(true);

				try 
				{
	    			$secureCheck = $this->randomPassword();
	    			Cache::add('forgot_password_link', $secureCheck, now()->addMinutes(6));
					Cache::add('user_id', $user->id, now()->addMinutes(6));

		    		$mail->SMTPDebug = 0;
					$mail->isSMTP();     
					$mail->Host = 'smtp.gmail.com';
					$mail->SMTPAuth = true;
					$mail->Username = 'bexterdesign@gmail.com';
					$mail->Password = 'jovance1';              
					$mail->SMTPSecure = 'tls';                 
					$mail->Port = 587;
					$mail->setFrom('bexterdesign@gmail.com', 'FEEDBAKC');
					$mail->addAddress($user->email, $user->name);
					$mail->isHTML(true);
					$mail->Subject = 'Forgot password for FeedBakc';
					
					$mail->Body    = '
						<html>
						<head>
							<title>test</title>
						</head>
						<body>
							<h3>For changing password on FeedBakc please click on the link:</h3>
							<a href="local.touch/change_password/'. $secureCheck .'">Change password</a> <br> <br>
							/*<a href="https://email-validate.bexter.rs/change_password/'. $secureCheck .'">Change password</a> <br> <br>*/
								This link is active for 5 minutes!
						</body>
						</html>
					';
					$mail->send();
		      		return response()->json(['msg2'=>'Check email and click on recovery link.']);
				} 
				catch (Exception $e) 
				{
					return back()->with('error','Message could not be sent.');
				} 
	    	}
			return response()->json([
	            'msg' => 'There is no registered user with this e-mail!'
	        ]);
	    }
	    else
	    {
			return response()->json([
	            'msg' => 'Enter valid e-mail form!'
	        ]);	    	
	    }
    }

    public function randomPassword() 
    {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array();
	    $alphaLength = strlen($alphabet) - 1;
	    for ($i = 0; $i < 16; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass);
	}

	public function sanitize_my_email($field) 
	{
		$field = filter_var($field, FILTER_SANITIZE_EMAIL);
		if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
		    return true;
		} else {
		    return false;
		}
	} 

	public function secureCheck($secureCheck) 
	{
		if ($secureCheck != Cache::get('pressed_link')) 
		{
			Cache::forget('pressed_link');
		}			
		return view('forgot_pass');		
	} 

	public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|alpha_dash|min:5|max:15',
            'repeat_password'=>'required|same:new_password'
        ]);
        if ($validator->passes()) {
            $user = user1s::find($request->user_id);
	        $user->password = Hash::make($request->new_password);
	        if($user->save()){
	            return response()->json([
	                'msg' => 'Password Successfully Changed!'
	            ], 201);
	        }
	        else
	        {
	            return response()->json(['errors'=>$errors]);
	        }
	    }
	    else
	    {
	    	return response()->json(['errors'=>$validator->errors()->all()]);
	    }
    }
}