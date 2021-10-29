<?php
// 'user' object
class Merchant
{
    // database connection and table name
    private $conn;
    private $table_name = "merchant";
    // object properties
    public $id;
    public $name;
    public $email;
    public $password;
    public $credit;
    public $token;
    // constructor
    public function __construct($db){
    $this->conn = $db;
}
    // create new user record
function create()
{ 
    // insert query
    $query = "INSERT INTO " . $this->table_name . "
    SET
    id = '$this->id',
    name = '$this->name',
    email = '$this->email',
    password = '$this->password';
    token = '$this->token';
    credit = '$this->credit'";
    // prepare the query
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->token=htmlspecialchars(strip_tags($this->token));
    $this->credit=htmlspecialchars(strip_tags($this->credit));
    // bind the values
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':password', $this->password);
    // hash the password before saving to database
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password_hash);
    // execute the query, also check if query was successful
if($stmt->execute())
    {
    return true;
    }
    return false;
}
// check if given email exist in the database
function emailExists()
{
    // query to check if email exists
    $query = "SELECT id, name, email, password,token,credit
    FROM " . $this->table_name . "
    WHERE email = ?
    LIMIT 0,1";
    // prepare the query
    $stmt = $this->conn->prepare( $query );
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
    // bind given email value
    $stmt->bindParam(1, $this->email);
    // execute the query
    $stmt->execute();
    // get number of rows
    $num = $stmt->rowCount();
// if email exists, assign values to object properties for easy access and use for php sessions
if($num>0)
{
    // get record details / values
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // assign values to object properties
    $this->id = $row['id'];
    $this->name = $row['name'];
    $this->email = $row['email'];
    $this->password = $row['password'];
    $this->token = $row['token'];
    $this->credit = $row['credit'];
        // return true because email exists in the database
        return true;
}
    // return false if email does not exist in the database
    return false;
} 

function add_amount($transaction_id,$merchant_id,$amount,$db)
    {
    $query = "INSERT INTO transactions (transaction_id,merchant_id,amount)
    VALUES('{$transaction_id}','{$merchant_id}','{$amount}')";
    $result=$db->query($query);     
    $querys="UPDATE merchant set credit=$amount where id='{$merchant_id}'";
    $stmt = $this->conn->prepare($querys);     
    $stmt->execute();
    // sanitize
    $num = $stmt->rowCount();

if($num>0)
    {
    return true;
    }
    return false;
    }
}   
?>