<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'config.php';
require_once '../validate_token.php';
require 'vendor/autoload.php'; 
include_once 'core.php';
require_once'../objects/request.php';
include_once 'database.php';
require_once'../objects/transactions.php';

$data=json_decode(file_get_contents("php://input"),true);
$result=jwt_validate($data['jwt'],$key);

//get total recipient when hit the email 
if($result==true)
	{
	$total_recipient=0;
	if ($data['To_email']) {
	$total_recipient+=1;
	}
	if ($data['Cc_email']) {
	$total_recipient+=1;
	}
	if ($data['BCC_email']) {
	$total_recipient+=1;
	}

if($result==true)
	{
	$database = new Database();
	$db = $database->getConnection();
	//get data from merchant table 
	$query="select * from merchant where email='".$data['From_email']."'";
	$res=$db->query($query);

if($res->rowcount()>0)
	{
	$row=$res->fetchAll();
	$balance=$row[0]['credit'];


	$total_charges=$total_recipient*0.0489;
if ($row[0]['credit']>=$total_charges) {
		            	
	$remaining_balance=$row[0]['credit']-$total_charges;

	//update data in merchant table 

	$query="update merchant set credit='{$remaining_balance}' where email='{$data['From_email']}'";

	$result=$db->query($query);

	//insert data in transactions table
			        	
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

	$esp= new Request($db);
	$mail_list=$esp->add_email($data['From_email'],$data['To_email'],$data['Cc_email'],$data['BCC_email'],$data['body']);
if($mail_list)
	{
	print $response->statusCode() . "\n";
	print_r($response->headers());
	print $response->body() . "\n";

	}
else
	{
	return "error";

	}
	} 
catch (Exception $e) 
	{    
	echo 'Caught exception: '. $e->getMessage() ."\n".$e->getline();
	}
	}
else
	{
	echo "insuficent balance please recharge ";
	}
	}
	}	
	}
?>