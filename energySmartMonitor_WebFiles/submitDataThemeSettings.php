<?php
session_start();
  if (!empty($_POST) and isset($_SESSION["newsession"])){
    $baseType=$_POST['baseType'];
    $accentColorName=$_POST['accentColorName'];
    $accentColorCode=$_POST['accentColorCode'];

    $redis = new Redis();
    $redis->connect('redis', 6379);
    $json = json_encode(array('baseType' => $baseType, 'accentColorName' => $accentColorName, 'accentColorCode' => $accentColorCode));
    $redis->set("theme",$json);
  }
?>
