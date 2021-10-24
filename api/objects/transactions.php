<?php
// 'user' object
class Transactions
{
 
    // database connection and table name
    private $conn;
    private $table_name = "transactions";
    public $id;
    public $balance;
    public $merchant_id;
    public $CD;
    public $payment_time;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
}

 public function list_trasaction($balance, $merchant_id, $CD, $payment_time)
    {
    	$query = "INSERT INTO transactions
            SET
                
               	Balance = '$balance',
                merchant_id = '$merchant_id',";

    // prepare the query
    $stmt = $this->conn->prepare($query);
    var_dump($query);


    if($stmt->execute())
    {
        return true;
    }
    return false;
}
        }
?>