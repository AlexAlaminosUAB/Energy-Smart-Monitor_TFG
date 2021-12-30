<?php
function test_input($data) {
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

session_start();
  if (!empty($_POST) and isset($_SESSION["newsession"])){
    $username=test_input($_POST['username']);
    $oldPass=test_input($_POST['oldPass']);
    $newPass=test_input($_POST['newPass']);
    $newPass2=test_input($_POST['newPass2']);

    $validateData['username']= (bool) preg_match('/^[a-z\d_]{4,20}$/i',$username);
    $validateData['oldPass']= (bool) preg_match('/^[a-z\d_]{8,40}$/i',$oldPass);
    $validateData['newPass']= (bool) preg_match('/^[a-z\d_]{8,40}$/i',$newPass);
    $validateData['newPass2']= (bool) preg_match('/^[a-z\d_]{8,40}$/i',$newPass2);


    $redis = new Redis();
    $redis->connect('redis', 6379);

    $json_userProfile = $redis->get("userProfile");
    $arrayValue_userProfile = json_decode($json_userProfile, true);
    $hash=$arrayValue_userProfile['pass'];

    if(!password_verify($oldPass, $hash)){
      $validateData['oldPass']= (bool) false;
    }

    if($newPass!=$newPass2){
      $validateData['newPass']= (bool) false;
      $validateData['newPass2']= (bool) false;
    }

    if($newPass=="" and $newPass2=="" and $oldPass==""){
      $validateData['newPass']= (bool) true;
      $validateData['newPass2']= (bool) true;
      $validateData['oldPass']= (bool) true;
    }


    echo json_encode($validateData);


    if($validateData['username'] and $validateData['oldPass'] and $validateData['newPass'] and $validateData['newPass2']){
      if($newPass!="" and $newPass2!="" and $oldPass!=""){
        $newHash=password_hash($newPass, PASSWORD_DEFAULT);
        $jsonProfileData = json_encode(array('username' => $username, 'pass' => $newHash));
        $redis->set("userProfile",$jsonProfileData);
      }else{
        $jsonProfileData = json_encode(array('username' => $username, 'pass' => $hash));
        $redis->set("userProfile",$jsonProfileData);
      }
    }


  }
?>
