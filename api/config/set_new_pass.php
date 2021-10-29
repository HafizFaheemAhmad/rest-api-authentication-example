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
    $token = $data->token;
    $new_pass = $data->new_pass;
    $confirm_pass = $data->confirm_pass;
 
    $database = new Database();
    $db = $database->getConnection();
    $user = new User();
		
if($new_pass === $confirm_pass)
    {
    $new_pass=$user->set_new_pass($new_pass, $confirm_pass, $token, $db);
if($new_pass === true)
    {
    $message = "Pass changed successfully";
    $status_code = "201";
    echo "null, $message, $status_code";
    http_response_code(201);

    }
else if($new_pass === false)
    {
    $message = "token expire please create another one.";
    $status_code = "406";
    echo "$message, $status_code";

    }
    }
else
    {
    $message = "password field does not match";
    $status_code = "401";
    echo "$message, $status_code";

    }
?>

