<?php
ini_set("display_error", 1);
header("Content-Type: application/json; charset=UTF-8");
require_once 'config.php';
require_once '../validate_token.php';
require 'vendor/autoload.php'; 
include_once 'core.php';
require_once'../objects/password.php';
include_once 'database.php';

$data = json_decode(file_get_contents('php://input'));

if (empty($data->email)) 
{
	echo "please enter email","200";
	http_response_code(400);
}
else
	{

		//if data valid, create database object, get connection
		$database = new Database();
		$db = $database->getConnection();
		$user = new User();
		$user_exist=$user->find_user($data->email,$db);

if(isset($user_exist))
		{

		$email = new \SendGrid\Mail\Mail();
		$email->setFrom("help@ess.com", "system help");
		$email->setSubject("Forgot Password OTP  -- '".$user_exist['token']."' -- Localhost");
		$email->addTo($data->email);
		$email->addContent("text/plain", "here is your otp.");
		$email->addContent(
		"text/html", "Forgot OTP<br> '".$user_exist['token']);
		$sendgrid = new \SendGrid(SENDGRID_API_KEY);
	
try 
		{
		$response = $sendgrid->send($email);

		}

catch (Exception $e) 
		{    
		echo 'Caught exception: '. $e->getMessage() ."\n".$e->getline();
		}		 
if ($response)
		{
		echo "Please Check Your Mail for OTP","200";
		http_response_code(200);
		}
else
		{
		echo "Server Problem Try Again Later","500";
		http_response_code(500);
		}
		}
else
		{
		echo "Mail not found in our Database","404";
		http_response_code(404);
		}
}
?>