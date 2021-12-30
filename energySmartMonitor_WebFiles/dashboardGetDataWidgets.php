<?php
session_start();
  if (!empty($_POST) and isset($_SESSION["newsession"])){
    $redis = new Redis();
    $redis->connect('redis', 6379);

    if (isset($_POST['now']) and $_POST['now']==true){

      $json_rtList = $redis->lrange("rtList",0,86400);

      $arrayValue_rtList = json_encode($json_rtList, true);
      $newjson_rtList = str_replace('"{', '{', $arrayValue_rtList);
      $newjson_rtList2 = str_replace('}"', '}', $newjson_rtList);
      $newjson_rtList3 = preg_replace('/\\\"/',"\"", $newjson_rtList2);
      $returnData['now'] = json_decode($newjson_rtList3, true);

    }

    if (isset($_POST['consumption']) and $_POST['consumption']==true){
      $json_energyConsumption = $redis->get("energyConsumption");
      $arrayValue_energyConsumption = json_decode($json_energyConsumption, true);

      $returnData['consumption'] = $arrayValue_energyConsumption['kW_now'];

    }

    if (isset($_POST['cost']) and $_POST['cost']==true){
      $json_energyConsumption = $redis->get("energyConsumption");
      $arrayValue_energyConsumption = json_decode($json_energyConsumption, true);

      $json_dataCompany = $redis->get("dataCompany");
      $arrayValue_dataCompany = json_decode($json_dataCompany, true);

      $cost = $arrayValue_energyConsumption['kWh_acum']*$arrayValue_dataCompany['pricekWh'];

      $returnData['cost'] = $cost;
      $returnData['acum'] = $arrayValue_energyConsumption['kWh_acum'];

    }

    if (isset($_POST['peak']) and $_POST['peak']==true){
      $json_energyConsumption = $redis->get("energyConsumption");
      $arrayValue_energyConsumption = json_decode($json_energyConsumption, true);

      $json_dataCompany = $redis->get("dataCompany");
      $arrayValue_dataCompany = json_decode($json_dataCompany, true);

      $returnData['peak'] = $arrayValue_energyConsumption['kW_peak'];
      $returnData['max'] = $arrayValue_dataCompany['powerHired'];

    }

    if (isset($_POST['solar']) and $_POST['solar']==true){
      $json_solarPanelGeneration = $redis->get("solarPanelGeneration");
      $arrayValue_solarPanelGeneration = json_decode($json_solarPanelGeneration, true);

      $json_dataCompany = $redis->get("dataCompany");
      $arrayValue_dataCompany = json_decode($json_dataCompany, true);

      $returnData['generationSP'] = $arrayValue_solarPanelGeneration['kW_nowGeneration'];
      $returnData['maxSP'] = $arrayValue_dataCompany['maxkWhSP'];

    }

    if (isset($_POST['consumptionAppliances']) and $_POST['consumptionAppliances']==true){
      $json_consumptionAppliances = $redis->get("consumptionAppliances");
      $arrayValue_consumptionAppliances = json_decode($json_consumptionAppliances, true);

      $returnData['consumptionAppliances'] = $arrayValue_consumptionAppliances;

    }

    if (isset($_POST['modeChart'])){
      $redis->set("modeChart",$_POST['modeChart']);
    }

    if (isset($_POST['modeChartHist'])){
      $redis->set("modeChartHist",$_POST['modeChartHist']);
    }

    if (isset($_POST['modeChartAppliances'])){
      $redis->set("modeChartAppliances",$_POST['modeChartAppliances']);
    }

    if (isset($_POST['modeListAppliances'])){
      $redis->set("modeListAppliances",$_POST['modeListAppliances']);
    }

    if (isset($_POST['consumptionHistory']) and $_POST['consumptionHistory']==true){

      $json_consumptionHistory = $redis->hgetall("consumptionHistoryHashList");

      $arrayValue_consumptionHistory = json_encode($json_consumptionHistory, true);
      $newjson_consumptionHistory = str_replace('"{', '{', $arrayValue_consumptionHistory);
      $newjson_consumptionHistory2 = str_replace('}"', '}', $newjson_consumptionHistory);
      $newjson_consumptionHistory3 = preg_replace('/\\\"/',"\"", $newjson_consumptionHistory2);
      $returnData['consumptionHistory'] = json_decode($newjson_consumptionHistory3, true);

    }

    if (isset($_POST['generationToday']) and $_POST['generationToday']==true){

      $json_generationToday = $redis->lrange("generationTodayList",0,23);

      $arrayValue_generationToday = json_encode($json_generationToday, true);
      $newjson_generationToday = str_replace('"{', '{', $arrayValue_generationToday);
      $newjson_generationToday2 = str_replace('}"', '}', $newjson_generationToday);
      $newjson_generationToday3 = preg_replace('/\\\"/',"\"", $newjson_generationToday2);
      $returnData['generationToday'] = json_decode($newjson_generationToday3, true);


    }


    echo json_encode($returnData);

  }
?>
