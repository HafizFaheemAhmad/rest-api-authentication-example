<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, 	     Authorization, X-Requested-With");
// files to connect to database
include_once 'config/database.php';
include_once 'objects/Secondary_merchant.php';
require_once 'validate_token.php';
$data = json_decode(file_get_contents("php://input"));
//require jwt token 
if (!empty($data->jwt)) 
{
	$result=jwt_validate($data->jwt,$key);
	//check status true
if ($result['status']===true)
{
	// get database connection
	$database = new Database();
	$db = $database->getConnection();
	// instantiate product object
	$S_merchant = new S_Merchant($db);
	// get posted data

	// set product property values
	$S_merchant->name = $data->name;
	$S_merchant->email = $data->email;
	$S_merchant->password = $data->password;
	//get id from merchant table
	$S_merchant->merchant_id = $result['data']->id;
	//check email exit
if ($S_merchant->emailExists()===false)
{
	// create the merchant
if(!empty($S_merchant->name) &&!empty($S_merchant->email) &&!empty($S_merchant->password)&&$S_merchant->create())
	{
	// set response code
	http_response_code(200);
	// display message: S.Merchant was created.
	echo json_encode(array("message" => "S.Merchant was created."));
	}
			 
			//if unable to create merchant
else
	{
	// set response code
	http_response_code(400);
	// display message: Unable to create S.Merchant.
	echo json_encode(array("message" => "Unable to create S.Merchant."));
	}
	}
else
{
	// set response code
	http_response_code(400);
	// display message: email already exist
	echo json_encode(array("message" => "email already exist."));
}
}
}
else
{// set response code
	http_response_code(400);
	// display message: jwt token field required.
	echo json_encode(array("message" => "jwt token field required."));
}
?>