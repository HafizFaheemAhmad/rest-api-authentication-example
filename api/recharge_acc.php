    <?php
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// files to connect to database
include_once 'config/database.php';
include_once 'objects/merchant.php';
require '../vendor/autoload.php';
require_once 'validate_token.php';
$data = json_decode(file_get_contents("php://input"));
//require jwt token 
if (!empty($data->jwt)) 
{
    $result=jwt_validate($data->jwt,$key);
    //check status true
if ($result['status']===true)
{

// generate json web token
  // get database connection
  $database = new Database();
  $db = $database->getConnection();  
  $merchant = new Merchant($db);
  $data = json_decode(file_get_contents("php://input"));
  // set product property values
  // $merchant->email = $data->email;

  

    $response = ['error' => true , 'message' => '' ,'status' => 200];

    \Stripe\Stripe::setApiKey(
    'sk_test_51Jp9bqG3llWKgSGQW7lKIh6fQdcSc0EsMrsZLGo64s37w3FIjp7eNj05sRM6V1FDet3m3ke3UNylmBDLOpWqjDcS00oCZvbfEO'
    );
$stripe = new \Stripe\StripeClient(
  'sk_test_51Jp9bqG3llWKgSGQW7lKIh6fQdcSc0EsMrsZLGo64s37w3FIjp7eNj05sRM6V1FDet3m3ke3UNylmBDLOpWqjDcS00oCZvbfEO'
);

  $token=$stripe->tokens->create([
  'card' => [
    'number' => '4242424242424242',
    'exp_month' => 10,
    'exp_year' => 22,
    'cvc' => '314',
  ],
]);

    $customer=$stripe->customers->create([
    'source' =>$token->id,
    'name' => "ALi",
    'email' => "hafizfaheem034@gmail.com",

    ]);
     //print_r( $customer->id);

    $transation=$stripe->charges->create([
    'amount' => 2000,
    'currency' => 'usd',
    'customer' => $customer->id,
    'description' => 'Test Charge',
    ]);

    if($transation->captured)
    {

        //echo $transation->id;
     $add_amou = $merchant->add_amount($transation->id,$data->merchant_id,'100',$db);

   // $database = new Database();
   // $db = $database->getConnection();
   // $query="update merchant set credit='100' where email='{$data['From_email']}'";
   // $query->execute();
   // print_r($query);
   // exit();
   //  //var_dump($add_amou);
   echo $transation->id;
   echo $transation->customer;
   echo $transation->amount;
   echo $transation->description;

   //  }
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