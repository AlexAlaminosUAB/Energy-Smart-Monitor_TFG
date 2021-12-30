<?php
   $redis = new Redis();
   $redis->connect('redis', 6379);
   //echo "Connection to server sucessfully";
   //echo "Server is running: " . $redis->ping();
   $allKeys = $redis->keys('*');

   $json = $redis->get("theme");
   $arrayValue = json_decode($json, true);

   if($arrayValue['baseType']=="light"){
     $baseTypeThemeClass="th-light";
   }else{
     $baseTypeThemeClass="";
   }

   $accentColorName=$arrayValue['accentColorName'];
   $accentColorCode='#'.$arrayValue['accentColorCode'];



?>

﻿<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <meta name="theme-color" content="<?php echo $accentColorCode ?>">
    <meta name="msapplication-TileColor" content="<?php echo $accentColorCode ?>">
    <meta name="msapplication-navbutton-color" content="<?php echo $accentColorCode ?>">
    <meta name="apple-mobile-web-app-status-bar-style" content="<?php echo $accentColorCode ?>">

    <title>Energy Smart Monitor | Login</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="/css/style.css" rel="stylesheet">
</head>

<body class="login-page bg-<?php echo $accentColorName;?>">
    <div class="login-box">
        <div class="logo">
            <a><b>Login</b></a>
            <small>Energy Smart Monitor</small>
        </div>
        <div class="card <?php echo $baseTypeThemeClass;?>">
            <div class="body">
                <form action="javascript:void(0);">
                  <br>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                        </div>
                        <label class="error err_invalidLabel" id="errorLabel-username" >This field is required or the data entered is invalid.</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <label class="error err_invalidLabel" id="errorLabel-password" >This field is required or the data entered is invalid.</label>
                    </div><br>
                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-block bg-<?php echo $accentColorName;?> waves-effect" id="btnLogin">SIGN IN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>


        <!-- Bootstrap Notify Plugin Js -->
        <script src="plugins/bootstrap-notify/bootstrap-notify.js"></script>

        <!-- Custom Js -->
        <script src="js/admin.js"></script>
        <script src="js/pages/ui/notifications.js"></script>


    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/examples/sign-in.js"></script>
    <script src="js/scriptLogin.js"></script>

</body>

</html>
