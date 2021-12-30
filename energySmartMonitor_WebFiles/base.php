<!DOCTYPE html>
<html>
<?php

session_start();

if(!isset($_SESSION["newsession"])){
  exit;
}

if(!isset($_SESSION["page"])){
  exit;
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

   $json_dataCompany = $redis->get("dataCompany");
   $arrayValue_dataCompany = json_decode($json_dataCompany, true);
?>




    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-<?php echo $accentColorName ?>">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="#">Energy Smart Monitor</a>
                <ul class="nav navbar-nav navbar-left only-xs">

                </ul>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">

                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar <?php echo $baseTypeThemeClass;?>">
            <!-- User Info -->
            <div class="user-info">
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $arrayValue_userProfile['username']; ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="profile.php"><i class="material-icons">person</i>Profile</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a id="btLogOut"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li <?php if($_SESSION["page"]=="dash"){echo 'class="active"';}?> >
                        <a href="index.php">
                            <i class="material-icons">widgets</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li <?php if($_SESSION["page"]=="histData"){echo 'class="active"';}?> >
                        <a href="historical.php">
                            <i class="material-icons">poll</i>
                            <span>Hostorical Data</span>
                        </a>
                    </li>
                    <li <?php if($_SESSION["page"]=="applData"){echo 'class="active"';}?> >
                        <a href="appliances.php">
                            <i class="material-icons">lightbulb</i>
                            <span>Appliances</span>
                        </a>
                    </li>


                    <?php
                    if($arrayValue_dataCompany['solarPanel']=="true"){
                      if($_SESSION["page"]=="genData"){
                        $tempClass= 'class="active"';
                      }else{
                        $tempClass="";
                      }
                      echo '<li '.$tempClass.'>';
                      echo '<a href="generation.php">';
                          echo '<i class="demo-icon icon-solar-panel-solid"></i>';
                          echo '<span>Energy generation</span>';
                      echo '</a>';
                    }
                    ?>

                    <li <?php if($_SESSION["page"]=="sett"){echo 'class="active"';}?> >
                        <a href="settings.php">
                            <i class="material-icons">settings</i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    <a href="javascript:void(0);">Energy Smart Monitor - Github</a>
                </div>
                <div class="version">
                  TFG - Alex Alaminos Reyne
                </div>
            </div>

            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>
