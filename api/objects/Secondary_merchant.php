<?php
// 'user' object
class S_Merchant
{
 
    // database connection and table name
    private $conn;
    private $table_name = "secondary_user";
    // object properties
    public $id;
    public $name;
    public $email;
    public $password;
    public $credit;
    public $merchant_id;
    // constructor
    public function __construct($db)
    {
    $this->conn = $db;
    }

    // create() method will be here
    // create new user record
function create()
{
    // insert query
    $query = "INSERT INTO " . $this->table_name . "
    SET
    name = '$this->name',
    email = '$this->email',
    password = '$this->password',
    merchant_id ='$this->merchant_id'";
    // prepare the query
    $stmt = $this->conn->prepare($query);
    // sanitize    
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->merchant_id=htmlspecialchars(strip_tags($this->merchant_id));
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
    $query = "SELECT id, name, email, password,credit,merchant_id
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
    $this->credit = $row['credit'];
    $this->merchant_id = $row['merchant_id'];
    // return true because email exists in the database
    return true;
    }
    // return false if email does not exist in the database
    return false;
    }
}