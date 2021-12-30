<!DOCTYPE html>
<html>
<?php

session_start();

if(!isset($_SESSION["newsession"])){
  include 'login.php';
  exit;
}else{
  $_SESSION["page"]="applData";
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


   $json_consumptionAppliancesMonth = $redis->get("consumptionAppliancesMonth");
   $arrayValue_consumptionAppliancesMonth = json_decode($json_consumptionAppliancesMonth, true);


   $json_consumptionAppliancesYear = $redis->get("consumptionAppliancesYear");
   $arrayValue_consumptionAppliancesYear = json_decode($json_consumptionAppliancesYear, true);

   $json_consumptionAppliancesToday = $redis->get("consumptionAppliancesToday");
   $arrV_consumptionAppliancesToday = json_encode($json_consumptionAppliancesToday, true);
   $newjson_consumptionAppliancesToday = str_replace('"{', '{', $arrV_consumptionAppliancesToday);
   $newjson_consumptionAppliancesToday2 = str_replace('}"', '}', $newjson_consumptionAppliancesToday);
   $newjson_consumptionAppliancesToday3 = str_replace('\n', '', $newjson_consumptionAppliancesToday2);
   $newjson_consumptionAppliancesToday4 = preg_replace('/\\\"/',"\"", $newjson_consumptionAppliancesToday3);
   $arrayValue_consumptionAppliancesToday = json_decode($newjson_consumptionAppliancesToday4, true);



   $json_consumptionAppliances = $redis->get("consumptionAppliances");
   $arrayValue_consumptionAppliances = json_decode($json_consumptionAppliances, true);

   $appliancesLabelHeaderTable = array();
   foreach ($arrayValue_consumptionAppliances as $consAppl){

     if ($consAppl["appliance"]=="Refrigerator"){
       array_push($appliancesLabelHeaderTable, "Fridge");
     }

     if ($consAppl["appliance"]=="Dryer"){
       array_push($appliancesLabelHeaderTable, "Washer dryer");
     }

     if ($consAppl["appliance"]=="Microwave"){
       array_push($appliancesLabelHeaderTable, "Microwave");
     }

     if ($consAppl["appliance"]=="Dishwasher"){
       array_push($appliancesLabelHeaderTable, "Dish washer");
     }

     if ($consAppl["appliance"]=="Oven"){
       array_push($appliancesLabelHeaderTable, "Electric oven");
     }

     if ($consAppl["appliance"]=="Electric Hob"){
       array_push($appliancesLabelHeaderTable, "Electric stove");
     }

     if ($consAppl["appliance"]=="Electric heater"){
       array_push($appliancesLabelHeaderTable, "Electric space heater");
     }

   }






   $colorsAppliances["Electric Vehicle"]="bg-red";
   $colorsAppliances["Air Conditioner"]="bg-pink";
   $colorsAppliances["Dryer"]="bg-purple";
   $colorsAppliances["Microwave"]="bg-deep-purple";
   $colorsAppliances["Oven"]="bg-indigo";
   $colorsAppliances["Heater"]="bg-brown";
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
   $iconsArray["Heater"]='<div class="span3" title="Code: 0xe80f"><i class="demo-icon icon-heater"></i></div>';
   $iconsArray["Refrigerator"]='<div class="span3" title="Code: 0xe803"><i class="demo-icon icon-refrigerator"></i></div>';
   $iconsArray["Dishwasher"]='<div class="span3" title="Code: 0xe80c"><i class="demo-icon icon-dishwasher"></i></div>';
   $iconsArray["Water Heater"]='<div class="span3" title="Code: 0xe810"><i class="demo-icon icon-water-heater"></i></div>';
   $iconsArray["Washer"]='<div class="span3" title="Code: 0xe808"><i class="demo-icon icon-washer"></i></div>';
   $iconsArray["Electric Hob"]='<div class="span3" title="Code: 0xe80d"><i class="demo-icon icon-electric-stove"></i></div>';
   $iconsArray["Light"]='<div class="span3" title="Code: 0xe811"><i class="demo-icon icon-lightbulb"></i></div>';
   $iconsArray["Outlet"]='<div class="span3" title="Code: 0xe812"><i class="demo-icon icon-plug"></i></div>';
   $iconsArray["Other"]='<div class="span3" title="Code: 0xe813"><i class="demo-icon icon-plus-circle"></i></div>';


?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <meta name="theme-color" content="<?php echo $accentColorCode ?>">
    <meta name="msapplication-TileColor" content="<?php echo $accentColorCode ?>">
    <meta name="msapplication-navbutton-color" content="<?php echo $accentColorCode ?>">
    <meta name="apple-mobile-web-app-status-bar-style" content="<?php echo $accentColorCode ?>">

    <title>Energy Smart Monitor | Appliances Consumption</title>
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
                <h2>CONSUMPTION OF APPLIANCES</h2>
            </div>

            <div class="row clearfix">
                <!-- Appliance Info -->
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>MONTHLY CONSUMPTION OF APPLIANCES</h2>
                        </div>
                        <div class="body bodyCardScroll">
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
                                    <tbody>
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
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Appliance Info -->

                <!-- Appliance Info -->
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>ANNUAL CONSUMPTION OF APPLIANCES</h2>
                        </div>
                        <div class="body bodyCardScroll">
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
                                    <tbody>
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
            </div>



            <div class="row clearfix">
                <!-- Cost history Info -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>HISTORY</h2>
                        </div>
                        <div class="body bodyCardScroll histCardData">
                            <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                          <th>Time</th>
                                          <?php
                                          foreach ($appliancesLabelHeaderTable as $nameAppl){
                                            if($nameAppl!=null){
                                              echo"<th>".$nameAppl."</th>";
                                            }
                                          }
                                          ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      foreach ($arrayValue_consumptionAppliancesToday as $key => $applToday){
                                        if($applToday!=null){
                                          echo "<tr>";

                                            $fecha = date_create();
                                            date_timestamp_set($fecha, ($key/1000));
                                            echo "<td>".date_format($fecha, 'd/m/y')."&nbsp;&nbsp;&nbsp;&nbsp;".date_format($fecha, 'H:i:s')."</td>";

                                            foreach ($appliancesLabelHeaderTable as $nAppl){
                                              echo "<td>".($applToday[$nAppl]/1000)." kWh</td>";
                                            }

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


    <!-- Sparkline Chart Plugin Js -->
    <script src="plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Jquery Knob Plugin Js -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/charts/jquery-knob.js"></script>

    <script src="js/scriptAppliances.js"></script>






</body>

</html>
