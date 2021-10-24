<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'config.php';
require_once '../validate_token.php';
require 'vendor/autoload.php'; 
include_once 'core.php';
require_once'../objects/request.php';
include_once 'database.php';

$data=json_decode(file_get_contents("php://input"),true);
$result=jwt_validate($data['jwt'],$key);

	if($result==true)
{

	$email = new \SendGrid\Mail\Mail();

	$email->setFrom($data['From_email'], $data['From_name']);
	$email->setSubject("Sending with SendGrid is Fun");
	$email->addTo($data['To_email'], $data['To_name']);
	$email->addCc($data['Cc_email'],$data['CC_name']);
	$email->addBcc($data['BCC_email'],$data['BCC_name']);
	$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
	$email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>");
	$sendgrid = new \SendGrid(SENDGRID_API_KEY);
	
		try 
	{
    	$response = $sendgrid->send($email);

    	print $response->statusCode() . "\n";
    	print_r($response->headers());
    	print $response->body() . "\n";
	} 
		catch (Exception $e) 
	{
	    
    echo 'Caught exception: '. $e->getMessage() ."\n".$e->getline();
	}
}

$database = new Database();
$db = $database->getConnection();
$esp= new Request($db);
$mail_list=$esp->add_email($data['From_email'],$data['To_email'],$data['Cc_email'],$data['BCC_email'],$data['body']);
if($mail_list)
{
	echo $mail_list->esp();
}
?>