<!DOCTYPE html>
<html>
<?php

session_start();

if(!isset($_SESSION["newsession"])){
  include 'login.php';
  exit;
}else{
  $_SESSION["page"]="prof";
}


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

   $json_accentColorsTheme = $redis->get("accentColorsTheme");
   $arrayValue_accentColorsTheme = json_decode($json_accentColorsTheme, true);

   $json_appliances = $redis->get("dataAppliances");
   $arrayValue_appliances = json_decode($json_appliances, true);

   $json_home = $redis->get("dataHome");
   $arrayValue_home = json_decode($json_home, true);


   $json_company = $redis->get("dataCompany");
   $arrayValue_company = json_decode($json_company, true);

   $json_countryList = $redis->get("countryList");
   $arrayValue_countryList = json_decode($json_countryList, true);

   $json_userProfile = $redis->get("userProfile");
   $arrayValue_userProfile = json_decode($json_userProfile, true);

?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <meta name="theme-color" content="<?php echo $accentColorCode ?>">
    <meta name="msapplication-TileColor" content="<?php echo $accentColorCode ?>">
    <meta name="msapplication-navbutton-color" content="<?php echo $accentColorCode ?>">
    <meta name="apple-mobile-web-app-status-bar-style" content="<?php echo $accentColorCode ?>">

    <title>Energy Smart Monitor | Profile</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="plugins/morrisjs/morris.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />

    <link href="css/fontello.css" rel="stylesheet">

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

    <!-- Bootstrap DatePicker Css -->
    <link href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />

    <!-- Wait Me Css -->
    <link href="plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- Multi Select Css -->
    <link href="plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- Bootstrap Tagsinput Css -->
    <link href="plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">





</head>



<body class="theme-<?php echo $accentColorName ?> <?php echo $baseTypeThemeClass;?>">

    <?php include 'base.php'; ?>

    <section class="content">
        <div class="container-fluid">

            <!-- profile data -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>
                                USER DATA
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-12">
                                  <label>Username</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" required id="in_username" minlength="4" maxlength="25" value="<?php echo $arrayValue_userProfile['username']; ?>">
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-username" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-4">
                                  <label>Old Password</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="password" class="form-control" id="in_oldPass" minlength="8">
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-oldPass" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                  <label>New Password</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="password" class="form-control" id="in_newPass" minlength="8">
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-newPass" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                  <label>Retype the new password</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="password" class="form-control" id="in_newPass2" minlength="8">
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-newPass2" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# profile data -->

        </div>
    </section>

    <button id="btnFloatSaveProfile" class="btn bg-<?php echo $accentColorName ?> btn-circle-lg waves-effect waves-circle waves-float floatingButtonSaveSettings" >
      <i class="material-icons">save</i>
    </button>


    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Bootstrap Colorpicker Js -->
    <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>


    <!-- Bootstrap Notify Plugin Js -->
    <script src="plugins/bootstrap-notify/bootstrap-notify.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/ui/notifications.js"></script>


    <!-- Dropzone Plugin Js -->
    <script src="plugins/dropzone/dropzone.js"></script>

    <!-- Input Mask Plugin Js -->
    <script src="plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <!-- Multi Select Plugin Js -->
    <script src="plugins/multi-select/js/jquery.multi-select.js"></script>

    <script src="js/pages/forms/advanced-form-elements.js"></script>






    <!-- Input Mask Plugin Js -->
    <script src="plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>


    <!-- Bootstrap Notify Plugin Js -->
    <script src="plugins/bootstrap-notify/bootstrap-notify.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="plugins/raphael/raphael.min.js"></script>
    <script src="plugins/morrisjs/morris.js"></script>

    <!-- ChartJs -->
    <script src="plugins/chartjs/Chart.bundle.js"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="plugins/flot-charts/jquery.flot.js"></script>
    <script src="plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="plugins/flot-charts/jquery.flot.time.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Custom Js -->
    <script src="js/pages/index.js"></script>
    <script src="js/scriptProfile.js"></script>


    <!-- Demo Js -->
    <script src="js/demo.js"></script>













</body>

</html>
