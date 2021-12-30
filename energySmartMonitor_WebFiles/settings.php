<!DOCTYPE html>
<html>
<?php

session_start();

if(!isset($_SESSION["newsession"])){
  include 'login.php';
  exit;
}else{
  $_SESSION["page"]="sett";
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

    <title>Energy Smart Monitor | Settings</title>
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
            <div class="block-header">
                <h2>SETTINGS</h2>
            </div>
            <!--Settings theme, color-->
            <!-- Select -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>
                                THEME AND COLORS
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                  <b>Base Type</b><br><br>
                                    <select class="form-control show-tick" id="baseTypeTheme">
                                      <?php
                                      if($baseTypeThemeClass=="th-light"){
                                        echo '<option value="dark">Dark</option>';
                                        echo '<option value="light" selected>light</option>';
                                      }else{
                                        echo '<option value="dark" selected>Dark</option>';
                                        echo '<option value="light">light</option>';
                                      }
                                      ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                  <b>Color Accent</b><br><br>
                                    <select class="form-control show-tick selectSettingsTheme" id="accentColorTheme">
                                        <?php
                                        foreach ($arrayValue_accentColorsTheme as $key=>$value){
                                          if($key==$accentColorName){
                                            echo '<option value="'.$key.'" class="selectSettingsTheme-'.$key.'" selected >'.$value.'</option>';
                                          }else{
                                            echo '<option value="'.$key.'" class="selectSettingsTheme-'.$key.'" >'.$value.'</option>';
                                          }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Select -->
            <!--End settings theme, color-->
            <!-- Home data -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>
                                HOME DATA
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-6">
                                  <label>Country</label>
                                  <select class="form-control show-tick" id="sel_country" required>
                                    <?php
                                    foreach ($arrayValue_countryList as $country){
                                      if($country['code']=="ES"){
                                        echo '<option value="'.$country['code'].'" selected >'.$country['name'].'</option>';
                                      }else{
                                        echo '<option value="'.$country['code'].'" >'.$country['name'].'</option>';
                                      }
                                    }
                                    ?>
                                  </select>
                                </div>

                                <div class="col-md-6">
                                  <label>Postal Code</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" min="0" id="in_cp" value="<?php echo $arrayValue_home['cp']; ?>" required maxlength="5" minlength="5">
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-cp" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>

                            </div>

                            <div class="row clearfix">

                                <div class="col-md-6">
                                  <label>Number of tenants</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" min="0" id="in_numTenants" value="<?php echo $arrayValue_home['numTenants']; ?>" required maxlength="1" minlength="2" >
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-numTenants" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                  <label>Housing type</label>
                                    <div class="form-group">

                                        <?php
                                          if($arrayValue_home["housingType"]=="house"){
                                            echo '<input type="radio" name="livingPlace" id="house" class="with-gap radio-col-'.$accentColorName.'" checked aria-required="true" aria-invalid="false">';
                                            echo '<label for="house">House</label>';
                                            echo '<input type="radio" name="livingPlace" id="apartment" class="with-gap radio-col-'.$accentColorName.'" aria-invalid="false">';
                                            echo '<label for="apartment" class="m-l-20">Apartment</label>';
                                          }else if($arrayValue_home["housingType"]=="apartment"){
                                            echo '<input type="radio" name="livingPlace" id="house" class="with-gap radio-col-'.$accentColorName.'" aria-required="true" aria-invalid="false">';
                                            echo '<label for="house">House</label>';
                                            echo '<input type="radio" name="livingPlace" id="apartment" class="with-gap radio-col-'.$accentColorName.'" checked aria-invalid="false">';
                                            echo '<label for="apartment" class="m-l-20">Apartment</label>';
                                          }else{
                                            echo '<input type="radio" name="livingPlace" id="house" class="with-gap radio-col-'.$accentColorName.'" aria-required="true" aria-invalid="false">';
                                            echo '<label for="house">House</label>';
                                            echo '<input type="radio" name="livingPlace" id="apartment" class="with-gap radio-col-'.$accentColorName.'" aria-invalid="false">';
                                            echo '<label for="apartment" class="m-l-20">Apartment</label>';
                                          }
                                        ?>

                                        <label class="error err_invalidLabel" id="errorLabel-housingType" >This field is required.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Home data -->
            <!-- Company data -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>
                                ELECTRIC COMPANY DATA
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-12">
                                  <label>Name of the electricity marketer</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" required id="in_nameMarketer" maxlength="15" value="<?php echo $arrayValue_company['nameMarketer']; ?>">
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-nameMarketer" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">

                                <div class="col-md-3">
                                  <label>Power hired (kW)</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" placeholder="Ex: 4,40 kW" step="any" min="0" id="in_powerHired" required value="<?php echo $arrayValue_company['powerHired']; ?>">
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-powerHired" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                  <label>Electricity rate type</label>
                                  <h6><h6>
                                    <div class="form-group">

                                        <?php
                                          if($arrayValue_company["rateType"]=="regulated"){
                                            echo '<input type="radio" name="rateType" id="regulated" class="with-gap radio-col-'.$accentColorName.'" checked aria-required="true" aria-invalid="false">';
                                            echo '<label for="regulated">Regulated rate (PVPC)</label>';
                                            echo '<input type="radio" name="rateType" id="fixed" class="with-gap radio-col-'.$accentColorName.'" aria-invalid="false">';
                                            echo '<label for="fixed" class="m-l-20">Free Market Fee (Fixed Price)</label>';
                                          }else if($arrayValue_company["rateType"]=="fixed"){
                                            echo '<input type="radio" name="rateType" id="regulated" class="with-gap radio-col-'.$accentColorName.'" aria-required="true" aria-invalid="false">';
                                            echo '<label for="regulated">Regulated rate (PVPC)</label>';
                                            echo '<input type="radio" name="rateType" id="fixed" class="with-gap radio-col-'.$accentColorName.'" checked aria-invalid="false">';
                                            echo '<label for="fixed" class="m-l-20">Free Market Fee (Fixed Price)</label>';
                                          }else{
                                            echo '<input type="radio" name="rateType" id="regulated" class="with-gap radio-col-'.$accentColorName.'" aria-required="true" aria-invalid="false">';
                                            echo '<label for="regulated">Regulated rate (PVPC)</label>';
                                            echo '<input type="radio" name="rateType" id="fixed" class="with-gap radio-col-'.$accentColorName.'" aria-invalid="false">';
                                            echo '<label for="fixed" class="m-l-20">Free Market Fee (Fixed Price)</label>';
                                          }
                                        ?>

                                        <label class="error err_invalidLabel" id="errorLabel-rateType" >This field is required.</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                  <label>&nbsp;</label>
                                  <div class="form-group">
                                        <div class="demo-checkbox">
                                          <?php
                                            if($arrayValue_company['hourlyDiscrimination']=="true"){
                                              echo '<input type="checkbox" id="md_checkbox_hourlyDiscrimination" checked class="filled-in chk-col-'.$accentColorName.'">';
                                            }else{
                                              echo '<input type="checkbox" id="md_checkbox_hourlyDiscrimination" class="filled-in chk-col-'.$accentColorName.'">';
                                            }
                                          ?>
                                          <label for="md_checkbox_hourlyDiscrimination">Hourly discrimination</label>
                                        </div>
                                  </div>
                                </div>
                            </div>

                            <div class="row clearfix" id="row_SamePrice">
                              <div class="col-md-4">
                                <label>Price of kWh</label>
                                  <div class="input-group">
                                    <span class="input-group-addon">
                                      <i class="material-icons">euro_symbol</i>
                                    </span>
                                      <div class="form-line">
                                          <input type="number" class="form-control money-euro" placeholder="Ex: 0,14382 €/kWh" step="any" min="0" id="in_pricekWh" required value="<?php echo $arrayValue_company['pricekWh']; ?>">
                                      </div>
                                      <label class="error err_invalidLabel" id="errorLabel-pricekWh" >This field is required or the data entered is invalid.</label>
                                  </div>
                              </div>
                            </div>

                            <div class="row clearfix" id="row_PeriodPrice">

                              <div class="col-md-4">
                                <label>Price of kWh (Punta)</label>
                                  <div class="input-group">
                                    <span class="input-group-addon">
                                      <i class="material-icons">euro_symbol</i>
                                    </span>
                                      <div class="form-line">
                                          <input type="number" class="form-control money-euro" placeholder="Ex: 0,14382 €/kWh" step="any" min="0" id="in_pricekWhPunta" required value="<?php echo $arrayValue_company['pricekWhPunta']; ?>">
                                      </div>
                                      <label class="error err_invalidLabel" id="errorLabel-pricekWhPunta" >This field is required or the data entered is invalid.</label>
                                  </div>
                              </div>

                              <div class="col-md-4">
                                <label>Price of kWh (Llano)</label>
                                  <div class="input-group">
                                    <span class="input-group-addon">
                                      <i class="material-icons">euro_symbol</i>
                                    </span>
                                      <div class="form-line">
                                          <input type="number" class="form-control money-euro" placeholder="Ex: 0,14382 €/kWh" step="any" min="0" id="in_pricekWhLlano" required value="<?php echo $arrayValue_company['pricekWhLlano']; ?>">
                                      </div>
                                      <label class="error err_invalidLabel" id="errorLabel-pricekWhLlano" >This field is required or the data entered is invalid.</label>
                                  </div>
                              </div>
                              <div class="col-md-4">
                                <label>Price of kWh (Valle)</label>
                                  <div class="input-group">
                                    <span class="input-group-addon">
                                      <i class="material-icons">euro_symbol</i>
                                    </span>
                                      <div class="form-line">
                                          <input type="number" class="form-control money-euro" placeholder="Ex: 0,14382 €/kWh" step="any" min="0" id="in_pricekWhValle" required value="<?php echo $arrayValue_company['pricekWhValle']; ?>">
                                      </div>
                                      <label class="error err_invalidLabel" id="errorLabel-pricekWhValle" >This field is required or the data entered is invalid.</label>
                                  </div>
                              </div>

                            </div>


                            <div class="row clearfix">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                          <div class="demo-checkbox">
                                            <?php
                                              if($arrayValue_company['solarPanel']=="true"){
                                                echo '<input type="checkbox" id="md_checkbox_solarPanel" checked class="filled-in chk-col-'.$accentColorName.'">';
                                              }else{
                                                echo '<input type="checkbox" id="md_checkbox_solarPanel" class="filled-in chk-col-'.$accentColorName.'">';
                                              }
                                            ?>
                                            <label for="md_checkbox_solarPanel">I have solar panels</label>
                                          </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <label class="midLabel">Benefit per kWh delivered to the grid</label>
                                </div>
                                <div class="col-sm-4">
                                  <div class="input-group">
                                    <span class="input-group-addon">
                                      <i class="material-icons">euro_symbol</i>
                                    </span>
                                      <div class="form-line">
                                        <?php
                                          if($arrayValue_company['solarPanel']=="true"){
                                            echo '<input type="number" id="solarPanelGenerate" class="form-control money-euro" placeholder="Ex: 0,14382 €/kWh generated" step="any" min="0" value="'.$arrayValue_company['priceSolarPanelGenerate'].'">';
                                          }else{
                                            echo '<input type="number" id="solarPanelGenerate" class="form-control money-euro" placeholder="Ex: 0,14382 €/kWh generated" step="any" min="0" disabled>';
                                          }
                                        ?>
                                      </div>
                                      <label class="error err_invalidLabel" id="errorLabel-priceSolarPanelGenerate">This field is required or the data entered is invalid.</label>
                                  </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# company data -->
            <!-- Solar panels-->
            <?php
              if($arrayValue_company['solarPanel']=="true"){
                $class_solarPanelsDataHidden="";
              }else{
                $class_solarPanelsDataHidden="solarPanelsDataHidden";
              }
            ?>
            <div class="row clearfix <?php echo $class_solarPanelsDataHidden; ?>" id="solarPanelsData">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>
                                SOLAR PANELS DATA
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-12">
                                  <label>Maximum energy production capacity in kW of the entire photovoltaic installation</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                          <input type="number" class="form-control" placeholder="Ex: 4,40 kWp" step="any" min="0" id="in_maxkWhSP" value="<?php echo $arrayValue_company['maxkWhSP']; ?>">
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-maxkWhSP" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# solar panels -->
            <!-- Multi Select -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>
                                APPLIANCES
                                <small>Select the appliances you have at home</small>
                            </h2>
                        </div>
                        <div class="body">
                            <label style="float: left;">Available appliances</label>
                            <label style="float: right;">Your appliances</label><br><br>
                            <select id="optgroup" class="ms" multiple="multiple">
                                    <?php
                                    foreach ($arrayValue_appliances as $key=>$value){
                                      if($value==true){
                                        echo '<option value="'.$key.'" selected >'.$key.'</option>';
                                      }else if($value==false){
                                        echo '<option value="'.$key.'" >'.$key.'</option>';;
                                      }
                                    }
                                    ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Multi Select -->
            <!-- Model Generation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>
                                Model Generation
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-12">
                                  <label>Enter the time range that the appliance has been working on</label>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-4">
                                  <label>Type of appliance</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                          <select class="form-control show-tick" id="applianceTypeModel">
                                            <option value="none" selected>Please select one</option>
                                            <option value="oven">Oven</option>
                                            <option value="refrigerator">Refrigerator</option>
                                            <option value="dishwasher">Dishwasher</option>
                                            <option value="washer_dryer">Washer Dryer</option>
                                            <option value="microwave">Microwave</option>
                                            <option value="electric_heat">Electric Heater</option>
                                            <option value="stove">Electric Hob</option>
                                          </select>
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-applianceTypeModel" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>





                                <div class="col-md-4">
                                  <label>Start time</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                          <select class="form-control show-tick" id="startTimeDay">
                                            <option value="today" selected>Today</option>
                                            <option value="yesterday">Yesterday</option>
                                          </select>
                                          <input type="time" class="form-control" id="startTimeModel">
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-startTimeModel" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                  <label>End time</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                          <select class="form-control show-tick" id="endTimeDay">
                                            <option value="today" selected>Today</option>
                                            <option value="yesterday">Yesterday</option>
                                          </select>
                                          <input type="time" class="form-control" id="endTimeModel">
                                        </div>
                                        <label class="error err_invalidLabel" id="errorLabel-endTimeModel" >This field is required or the data entered is invalid.</label>
                                    </div>
                                </div>




                            </div>
                            <div class="row clearfix">
                                <div class="col-md-2">
                                    <button type="button" class="btn bg-<?php echo $accentColorName ?> waves-effect" id="btnGenerationModel">Generate Model</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Model Generation -->
        </div>
    </section>

    <button id="btnFloatSave" class="btn bg-<?php echo $accentColorName ?> btn-circle-lg waves-effect waves-circle waves-float floatingButtonSaveSettings" >
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
    <script src="js/scriptSettings.js"></script>


    <!-- Demo Js -->
    <script src="js/demo.js"></script>













</body>

</html>
