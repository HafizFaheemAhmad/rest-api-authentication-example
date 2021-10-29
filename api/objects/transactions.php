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
    public function set_transactions()
    {
    $payment = array(
    "id" => $this->id,
    "balance" => $this->name,
    "merchant_id" => $this->email,
    "CD" => $this->designation,
    "payment_time" => $this->salary
    );
    return $payment;
    }
    public function get_transactions()
    {
    $payment = array(
    "id" => $this->id,
    "balance" => $this->name,
    "merchant_id" => $this->email,
    "CD" => $this->designation,
    "payment_time" => $this->salary
    );
    return $payment;
    }
    // constructor
    public function __construct($db){
    $this->conn = $db;
}
}
?>