<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// files to connect to database
include_once 'config/database.php';
include_once 'objects/merchant.php';
// generate json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 // get database connection
$database = new Database();
$db = $database->getConnection();
// instantiate user object
$merchant = new Merchant($db);
// check email existence here
// get posted data
$data = json_decode(file_get_contents("php://input"));
// set product property values
$merchant->email = $data->email;
$email_exists = $merchant->emailExists();
// check if email exists and if password is correct
  if($email_exists && ($data->password== $merchant->password))
{
 
    $token = array(
       "iat" => $issued_at,
       "exp" => $expiration_time,
       "iss" => $issuer,
       "data" => array(
           "id" => $merchant->id,
           "name" => $merchant->name,
           "email" => $merchant->email,
           "password" => $merchant->password
           
       )
    );
 
      // set response code
      http_response_code(200);
      // generate jwt
      $jwt = JWT::encode($token, $key);
      echo json_encode(
            array(
                "message" => "Successful login.",
                "Token_generate_jwt" => $jwt
            )
        );
 
}
// login failed
  else
{
    // set response code
    http_response_code(401);
    // tell the merchant login failed
    echo json_encode(array("message" => "Login failed."));
}
?>