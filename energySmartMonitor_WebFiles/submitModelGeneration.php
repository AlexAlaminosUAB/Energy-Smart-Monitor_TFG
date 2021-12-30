<?php
function test_input($data) {
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$arrayFileNames = array(
  "oven" => "channel_3.dat",
  "refrigerator" => "channel_4.dat",
  "dishwasher" => "channel_5.dat",
  "washer_dryer" => "channel_6.dat",
  "microwave" => "channel_7.dat",
  "electric_heat" => "channel_8.dat",
  "stove" => "channel_9.dat"
);

session_start();
  if (!empty($_POST) and isset($_SESSION["newsession"])){
    $applianceTypeModel=test_input($_POST['applianceTypeModel']);
    $startTimeModel=test_input($_POST['startTimeModel']);
    $endTimeModel=test_input($_POST['endTimeModel']);

    $startTimeDay=test_input($_POST['startTimeDay']);
    $endTimeDay=test_input($_POST['endTimeDay']);

    $dayH = 3600*24;

    $timeLimit_epoch=strtotime(date("H:i", strtotime('-2 hour -10 minute')));

    if($startTimeDay=="today"){
      $startTimeModel_epoch=strtotime($startTimeModel);
    }else if($startTimeDay=="yesterday"){
      $startTimeModel_epoch=(strtotime($startTimeModel)-$dayH);
    }

    if($endTimeDay=="today"){
      $endTimeModel_epoch=strtotime($endTimeModel);
    }else if($endTimeDay=="yesterday"){
      $endTimeModel_epoch=(strtotime($endTimeModel)-$dayH);
    }

    if( ($startTimeModel_epoch<$endTimeModel_epoch) and ($endTimeModel_epoch<=$timeLimit_epoch) ){
      $validateData['startTimeModel']=true;
      $validateData['endTimeModel']=true;
    }else{
      $validateData['startTimeModel']=false;
      $validateData['endTimeModel']=false;
    }

    $validateData['applianceTypeModel']= (bool) ($applianceTypeModel=="oven" or $applianceTypeModel=="refrigerator" or $applianceTypeModel=="dishwasher" or $applianceTypeModel=="washer_dryer" or $applianceTypeModel=="microwave" or $applianceTypeModel=="electric_heat" or $applianceTypeModel=="stove");

    echo json_encode($validateData);

    $numValid=0;
    foreach ($validateData as $key => $value) {
      if($value==true){
        $numValid++;
      }
    }

    $redis = new Redis();
    $redis->connect('redis', 6379);

    if( $numValid==3 ){

      $rtlist = $redis->lrange('rtList', 0, 86400);
      $reversed_rtList = array_reverse($rtlist);

      $nameFile = $arrayFileNames[$applianceTypeModel];
      $dir="/models/";

      $myfile = fopen($dir.$nameFile, "w") or die("Unable to open file!");

      foreach ($reversed_rtList as $elem_rtList){
        $elemtemp_rtList = json_decode($elem_rtList, true);

        if($elemtemp_rtList["epochTime"]>=$startTimeModel_epoch and $elemtemp_rtList["epochTime"]<=$endTimeModel_epoch){
          $rowCSV = $elemtemp_rtList["epochTime"]." ".number_format(round($elemtemp_rtList["kW"]*1000,2),2)."\n";
          fwrite($myfile, $rowCSV);
        }else{
          $rowCSV = $elemtemp_rtList["epochTime"]." 0.00\n";
          fwrite($myfile, $rowCSV);
        }

      }

      fclose($myfile);

    }

  }
?>
