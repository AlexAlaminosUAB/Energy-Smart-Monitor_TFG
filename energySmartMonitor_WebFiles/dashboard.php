<!DOCTYPE html>
<html>
<?php

session_start();

if(!isset($_SESSION["newsession"])){
  include 'login.php';
  exit;
}else{
  $_SESSION["page"]="dash";
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

   $json_userProfile = $redis->get("userProfile");
   $arrayValue_userProfile = json_decode($json_userProfile, true);

   $hoyFecha = date("Y-m-d");
   $hoyDiaFecha = date("l j\, F \of Y");
   $hoyHoraOld = date("H:i:s");
   $hoyHora = date("H:i:s", strtotime('now +2 hours'));
   $serverIP=$_SERVER['SERVER_ADDR'];

   $json_dataCompany = $redis->get("dataCompany");
   $arrayValue_dataCompany = json_decode($json_dataCompany, true);


   $json_consumptionHistory = $redis->hgetall("consumptionHistoryHashList");
   $arrV_consumptionHistory = json_encode($json_consumptionHistory, true);
   $newjson_consumptionHistory = str_replace('"{', '{', $arrV_consumptionHistory);
   $newjson_consumptionHistory2 = str_replace('}"', '}', $newjson_consumptionHistory);
   $newjson_consumptionHistory3 = preg_replace('/\\\"/',"\"", $newjson_consumptionHistory2);
   $arrayValue_consumptionHistory = json_decode($newjson_consumptionHistory3, true);

   $colorsAppliances["Electric Vehicle"]="bg-red";
   $colorsAppliances["Air Conditioner"]="bg-pink";
   $colorsAppliances["Dryer"]="bg-purple";
   $colorsAppliances["Microwave"]="bg-deep-purple";
   $colorsAppliances["Oven"]="bg-indigo";
   $colorsAppliances["Electric heater"]="bg-brown";
   $colorsAppliances["Refrigerator"]="bg-light-blue";
   $colorsAppliances["Dishwasher"]="bg-cyan";
   $colorsAppliances["Water Heater"]="bg-teal";
   $colorsAppliances["Washer"]="bg-green";
   $colorsAppliances["Electric Hob"]="bg-grey";
   $colorsAppliances["Light"]="bg-lime";
   $colorsAppliances["Outlet"]="bg-amber";
   $colorsAppliances["Other"]="bg-blue-grey";

   $iconsArray["Electric Vehicle"]='<div class="span3" title="Code: 0xe807"><i class="demo-icon icon-car-solid"></i></div>';
   $iconsArray["Air Conditioner"]='<div class="span3" title="Code: 0xe802"><i class="demo-icon icon-air-conditioner"></i></div>';
   $iconsArray["Dryer"]='<div class="span3" title="Code: 0xe809"><i class="demo-icon icon-dryer"></i></div>';
   $iconsArray["Microwave"]='<div class="span3" title="Code: 0xe80e"><i class="demo-icon icon-microwave"></i></div>';
   $iconsArray["Oven"]='<div class="span3" title="Code: 0xe80b"><i class="demo-icon icon-oven"></i></div>';
   $iconsArray["Electric heater"]='<div class="span3" title="Code: 0xe80f"><i class="demo-icon icon-heater"></i></div>';
   $iconsArray["Refrigerator"]='<div class="span3" title="Code: 0xe803"><i class="demo-icon icon-refrigerator"></i></div>';
   $iconsArray["Dishwasher"]='<div class="span3" title="Code: 0xe80c"><i class="demo-icon icon-dishwasher"></i></div>';
   $iconsArray["Water Heater"]='<div class="span3" title="Code: 0xe810"><i class="demo-icon icon-water-heater"></i></div>';
   $iconsArray["Washer"]='<div class="span3" title="Code: 0xe808"><i class="demo-icon icon-washer"></i></div>';
   $iconsArray["Electric Hob"]='<div class="span3" title="Code: 0xe80d"><i class="demo-icon icon-electric-stove"></i></div>';
   $iconsArray["Light"]='<div class="span3" title="Code: 0xe811"><i class="demo-icon icon-lightbulb"></i></div>';
   $iconsArray["Outlet"]='<div class="span3" title="Code: 0xe812"><i class="demo-icon icon-plug"></i></div>';
   $iconsArray["Other"]='<div class="span3" title="Code: 0xe813"><i class="demo-icon icon-plus-circle"></i></div>';

   $json_consumptionAppliances = $redis->get("consumptionAppliances");
   $arrayValue_consumptionAppliances = json_decode($json_consumptionAppliances, true);

   $json_consumptionAppliancesMonth = $redis->get("consumptionAppliancesMonth");
   $arrayValue_consumptionAppliancesMonth = json_decode($json_consumptionAppliancesMonth, true);

   $json_consumptionAppliancesYear = $redis->get("consumptionAppliancesYear");
   $arrayValue_consumptionAppliancesYear = json_decode($json_consumptionAppliancesYear, true);

   $gettedModeChart = $redis->get("modeChart");
   $gettedModeChartAppliances = $redis->get("modeChartAppliances");
   $gettedModeListAppliances = $redis->get("modeListAppliances");

?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <meta name="theme-color" content="<?php echo $accentColorCode ?>">
    <meta name="msapplication-TileColor" content="<?php echo $accentColorCode ?>">
    <meta name="msapplication-navbutton-color" content="<?php echo $accentColorCode ?>">
    <meta name="apple-mobile-web-app-status-bar-style" content="<?php echo $accentColorCode ?>">

    <title>Energy Smart Monitor | Dashboard</title>
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
                <h2>DASHBOARD</h2>
            </div>

            <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box-2 bg-<?php echo $accentColorName ?> hover-zoom-effect">
                        <div class="icon">
                          <i class="material-icons">event</i>
                        </div>
                        <div class="content">
                            <div class="text">DATE</div>
                            <h4><?php echo $hoyDiaFecha; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Widgets -->
            <div class="row clearfix">
                <!-- Line Chart -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card <?php echo $baseTypeThemeClass;?>" id="chartNow">
                        <div class="header">
                            <h2>CONSUMPTION</h2>
                            <select class="form-control show-tick" id="sel_modeChartNow" required>
                                <option value="minute" <?php if($gettedModeChart=="minute"){echo "selected";}?> >Minute</option>
                                <option value="hour" <?php if($gettedModeChart=="hour"){echo "selected";}?> >Hour</option>
                                <option value="day" <?php if($gettedModeChart=="day"){echo "selected";}?> >Day</option>
                            </select>
                        </div>
                        <div class="body" id="line_chart_body">
                            <canvas id="line_chart_now" height="150"></canvas>
                        </div>
                    </div>
                </div>
                <!-- #END# Line Chart -->
                <!-- Cost history Info -->
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>CONSUMPTION HISTORY</h2>
                        </div>
                        <div class="body bodyCardScroll" id="bodyResizeCard">
                            <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>Start Period</th>
                                            <th>End Period</th>
                                            <th>kWh</th>
                                            <th>Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      foreach ($arrayValue_consumptionHistory as $key => $consHist){
                                        if($consHist!=null){
                                          echo "<tr>";
                                            echo "<td>".$key."</td>";
                                            echo "<td>".$consHist['endPeriod']."</td>";
                                            echo "<td>".$consHist['kWh']." kWh</td>";
                                            echo "<td>".$consHist['cost']." €</td>";
                                          echo "</tr>";
                                        }
                                      }
                                      ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# cost history Info -->

            </div>


            <div class="row clearfix">
                <!-- Appliance Info -->
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="card <?php echo $baseTypeThemeClass;?>" id="listAppliances">
                        <div class="header">
                            <h2>APPLIANCES</h2>
                            <select class="form-control show-tick" id="sel_modeListAppliances" required>
                                <option value="day" <?php if($gettedModeListAppliances=="day"){echo "selected";}?> >Day</option>
                                <option value="month" <?php if($gettedModeListAppliances=="month"){echo "selected";}?> >Month</option>
                                <option value="year" <?php if($gettedModeListAppliances=="year"){echo "selected";}?> >Year</option>
                            </select>
                        </div>
                        <div class="body bodyCardScroll" id="bodyResizeCardAppliances">
                            <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Appliance</th>
                                            <th>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                                            <th>kWh</th>
                                            <th>Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appliancesDay" <?php if($gettedModeListAppliances!="day"){echo "style='display:none'";}?>>
                                      <?php
                                      foreach ($arrayValue_consumptionAppliances as $consAppli){
                                        if($consAppli!=null){
                                          echo "<tr>";
                                            echo "<td>".$iconsArray[$consAppli['appliance']]."</td>";
                                            echo "<td>".$consAppli['appliance']."</td>";
                                            echo '<td><div class="progress"><div class="progress-bar '.$colorsAppliances[$consAppli['appliance']].'" role="progressbar" aria-valuenow="'.$consAppli['percent'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$consAppli['percent'].'%"></div></div></td>';
                                            echo "<td>".number_format((float)$consAppli['kWh'],3)." kWh</td>";
                                            echo "<td>".$consAppli['cost']." €</td>";
                                          echo "</tr>";
                                        }
                                      }
                                      ?>
                                    </tbody>
                                    <tbody id="appliancesMonth" <?php if($gettedModeListAppliances!="month"){echo "style='display:none'";}?>>
                                      <?php
                                      foreach ($arrayValue_consumptionAppliancesMonth as $consAppliM){
                                        if($consAppliM!=null){
                                          echo "<tr>";
                                            echo "<td>".$iconsArray[$consAppliM['appliance']]."</td>";
                                            echo "<td>".$consAppliM['appliance']."</td>";
                                            echo '<td><div class="progress"><div class="progress-bar '.$colorsAppliances[$consAppliM['appliance']].'" role="progressbar" aria-valuenow="'.$consAppliM['percent'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$consAppliM['percent'].'%"></div></div></td>';
                                            echo "<td>".number_format((float)$consAppliM['kWh'],3)." kWh</td>";
                                            echo "<td>".$consAppliM['cost']." €</td>";
                                          echo "</tr>";
                                        }
                                      }
                                      ?>
                                    </tbody>
                                    <tbody id="appliancesYear" <?php if($gettedModeListAppliances!="year"){echo "style='display:none'";}?>>
                                      <?php
                                      foreach ($arrayValue_consumptionAppliancesYear as $consAppliY){
                                        if($consAppliY!=null){
                                          echo "<tr>";
                                            echo "<td>".$iconsArray[$consAppliY['appliance']]."</td>";
                                            echo "<td>".$consAppliY['appliance']."</td>";
                                            echo '<td><div class="progress"><div class="progress-bar '.$colorsAppliances[$consAppliY['appliance']].'" role="progressbar" aria-valuenow="'.$consAppliY['percent'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$consAppliY['percent'].'%"></div></div></td>';
                                            echo "<td>".number_format((float)$consAppliY['kWh'],3)." kWh</td>";
                                            echo "<td>".$consAppliY['cost']." €</td>";
                                          echo "</tr>";
                                        }
                                      }
                                      ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Appliance Info -->
                <!-- Appliance Usage -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card <?php echo $baseTypeThemeClass;?>" id="chartAppliances">
                        <div class="header">
                            <h2>USAGE</h2>
                            <select class="form-control show-tick" id="sel_modeChartAppliances" required>
                                <option value="cost" <?php if($gettedModeChartAppliances=="cost"){echo "selected";}?> >Cost</option>
                                <option value="kWh" <?php if($gettedModeChartAppliances=="kWh"){echo "selected";}?> >kWh</option>
                            </select>
                        </div>
                        <div class="body" id="cardDoughnut">
                              <canvas id="myChart"></canvas>
                              <h4 class="titleOfDoughnut">Consumption</h4>
                        </div>
                    </div>
                </div>
                <!-- #END# Appliance Usage -->
            </div>

            <div class="row clearfix">
            <!-- Energy consumption-->
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-5">
                <div class="card <?php echo $baseTypeThemeClass;?>">
                    <div class="header">
                        <h2>ENERGY CONSUMPTION</h2>
                    </div>
                    <div class="body">
                      <span class="circleConsumption">%</span>
                      <input type="text" class="knob" id="knob-kWh" data-linecap="round" value="46" data-width="125" data-height="125" data-thickness="0.15"
                             data-fgColor="#F44336"  data-bgColor="#00000052" readonly>
                      <div class="textBigSmall"  id="kWhConsumptionCard">
                        <span class="textBig"></span>
                        <br>
                        <span class="textSmall">on this period</span>
                      </div>

                    </div>
                </div>
            </div>
            <!-- #END# Energy consumption -->

            <!-- Peak Energy consumption-->
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-5">
                <div class="card <?php echo $baseTypeThemeClass;?>">
                    <div class="header">
                        <h2>PEAK CONSUMPTION</h2>
                    </div>
                    <div class="body">
                      <span class="circlePeak">%</span>
                      <input type="text" class="knob" id="knob-peak" data-linecap="round" value="46" data-width="125" data-height="125" data-thickness="0.15"
                             data-fgColor="#FF9800"  data-bgColor="#00000052" readonly>
                      <div class="textBigSmall" id="peakConsumptionCard">
                        <span class="textBig"></span>
                        <br>
                        <span class="textSmall"></span>
                      </div>

                    </div>
                </div>
            </div>
            <!-- #END# Peak Energy consumption -->

            <!-- Energy cost-->
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-5">
                <div class="card <?php echo $baseTypeThemeClass;?>">
                    <div class="header">
                        <h2>ENERGY TODAY</h2>
                    </div>
                    <div class="body">
                      <div>
                        <span class="textBig" id="costConsumptionCard"></span>
                        <br>
                        <span class="textSmall" id="acumConsumptionCard"></span>
                      </div>
                    </div>
                </div>
            </div>
            <!-- #END# Energy cost -->
          </div>
          <div class="row clearfix <?php if($arrayValue_dataCompany['solarPanel']=="false"){ echo "spHidden"; }?>">
            <!-- Energy generation-->
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-5">
                <div class="card <?php echo $baseTypeThemeClass;?>">
                    <div class="header">
                        <h2>SOLAR ENERGY GENERATION</h2>
                    </div>
                    <div class="body">
                      <span class="circleGeneration">%</span>
                      <input type="text" class="knob" id="knob-solar" data-linecap="round" value="81" data-width="125" data-height="125" data-thickness="0.15"
                             data-fgColor="#8BC34A"  data-bgColor="#00000052" readonly>
                      <div class="textBigSmall" id="solarGenerationCard">
                        <span class="textBig"></span>
                        <br>
                        <span class="textSmall"></span>
                      </div>

                    </div>
                </div>
            </div>
            <!-- #END# Energy generation -->
          </div>


        </div>
    </section>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

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

    <!-- Sparkline Chart Plugin Js -->
    <script src="plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Jquery Knob Plugin Js -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>

    <script src="js/pages/charts/jquery-knob.js"></script>
    <!--<script src="js/pages/charts/chartjs.js"></script>-->

    <script src="js/scriptDashboard.js"></script>

    <!-- Demo Js -->
    <!--<script src="js/demo.js"></script>-->




</body>

</html>
