<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, 	     Authorization, X-Requested-With");
// files to connect to database
include_once 'config/database.php';
include_once 'objects/merchant.php';
 // get database connection
    $database = new Database();
    $db = $database->getConnection();
    // instantiate product object
    $merchant = new Merchant($db);
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    // set product property values
    $merchant->id = $data->id;
    $merchant->name = $data->name;
    $merchant->email = $data->email;
    $merchant->password = $data->password;
    $merchant->token = $data->token;
    $merchant->credit = $data->credit;
    // use the create() method here    
    // create the merchant
if
    (
    !empty($merchant->name) &&
    !empty($merchant->email) &&
    !empty($merchant->password) &&
    $merchant->create()
    )
{
    // set response code
    http_response_code(200);
    // display message: Merchant was created.
    echo json_encode(array("message" => "Merchant was created."));
    }
 
//if unable to create merchant
else
    {
    // set response code
    http_response_code(400);
    // display message: unable to create Merchant
    echo json_encode(array("message" => "Unable to create Merchant."));
    }
?>