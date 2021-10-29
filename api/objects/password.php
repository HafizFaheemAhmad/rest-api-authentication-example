
<?php
class User
{
  private $id;
  private $name;
  private $email;
  private $password;

public function set_new_pass($new_pass, $confirm_pass, $token, $db)
  {

  $query="update merchant set password='{$confirm_pass}' where Token='".$token."'";

if ($db->query($query)) {
  $query="update merchant set token='NULL' where token='".$token."'";

  $db->query($query);

  return true;
  }
else 
  {
  return false;
  }
  $db->close();
  }

public function find_user($email, $db)
  {

  $query="select id  from merchant where email='".$email."'";
  $result=$db->query($query);

if ($result->rowcount() >0)
  {
  $forgot_token=rand(1000,100000);
  $query="update merchant set token='{$forgot_token}' where email='".$email."'";
  $sql=$db->query($query);  

if($sql)
  {  
  return array("token"=>$forgot_token);  
  }
else
  return false; 
  } 
  }
  }
?>