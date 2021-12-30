<?php
function test_input($data) {
  //$data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

  if (!empty($_POST))
  {
    $username=test_input($_POST['username']);
    $password=test_input($_POST['password']);

    $validateData['username']= (bool) preg_match('/^[a-z\d_]{4,20}$/i',$username);
    $validateData['password']= (bool) preg_match('/^[a-z\d_]{8,40}$/i',$password);

    $redis = new Redis();
    $redis->connect('redis', 6379);

    $json_userProfile = $redis->get("userProfile");
    $arrayValue_userProfile = json_decode($json_userProfile, true);
    $hash=$arrayValue_userProfile['pass'];
    $user=$arrayValue_userProfile['username'];

    if(password_verify($password, $hash) and strcmp($user, $username) == 0){
      $validateData['login']= (bool) true;
    }else{
      $validateData['login']= (bool) false;
    }


    echo json_encode($validateData);

    if($validateData['username'] and $validateData['password'] and $validateData['login']){
      session_start();
      $_SESSION["newsession"]=true;
    }

  }
?>
