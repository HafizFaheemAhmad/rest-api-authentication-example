<?php
// 'user' object
class Request
{
    // database connection and table name
    private $conn;
    private $table_name = "request";
    public $id;
    public $from_email;
    public $to_email;
    public $cc;
    public $bcc;
    public $body;
   // constructor
    public function __construct($db){
    $this->conn = $db;
}

public function add_email($from_email, $to_email, $cc, $bcc, $body)
{
    $query = "INSERT INTO request
    SET
    from_email = '$from_email',
    to_email = '$to_email',
    Bcc = '$bcc',
    Cc = '$cc',
    body = '$body'";
    // prepare the query
    $stmt = $this->conn->prepare($query);
if($stmt->execute())
    {
    return true;
    }
    return false;
    }
    }
?>