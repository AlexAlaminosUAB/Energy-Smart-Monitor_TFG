<?php
function test_input($data) {
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function json_validator($data=NULL) {
  if (!empty($data)) {
                @json_decode($data);
                return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
}


session_start();
  if (!empty($_POST) and isset($_SESSION["newsession"])){
    $country=test_input($_POST['country']);
    $cp=test_input($_POST['cp']);
    $numTenants=test_input($_POST['numTenants']);
    $housingType=test_input($_POST['housingType']);
    $nameMarketer=test_input($_POST['nameMarketer']);
    $pricekWh=test_input($_POST['pricekWh']);
    $powerHired=test_input($_POST['powerHired']);
    $solarPanel=test_input($_POST['solarPanel']);
    $priceSolarPanelGenerate=test_input($_POST['priceSolarPanelGenerate']);
    $hourlyDiscrimination=test_input($_POST['hourlyDiscrimination']);
    $maxkWhSP=test_input($_POST['maxkWhSP']);
    $appliances=$_POST['appliances'];
    $pricekWhPunta=test_input($_POST['pricekWhPunta']);
    $pricekWhLlano=test_input($_POST['pricekWhLlano']);
    $pricekWhValle=test_input($_POST['pricekWhValle']);
    $rateType=test_input($_POST['rateType']);


    $validateData['country']= (bool) preg_match("/^[a-zA-Z-' ]{1,35}$/",$country);
    $validateData['cp']= (bool) preg_match("/^[0-9]{5,5}$/",$cp);
    $validateData['numTenants']= (bool) preg_match("/^[1-9]{1,1}+([0-9]{0,1})$/",$numTenants);
    $validateData['housingType']= (bool) ($housingType=="house" or $housingType=="apartment");
    $validateData['nameMarketer']= (bool) preg_match("/^[a-zA-Z-' ]{1,35}$/",$nameMarketer);

    $validateData['powerHired']= (bool) preg_match("/^[0-9]+(\.[0-9]{1,8})?$/",$powerHired);
    $validateData['solarPanel']= (bool) ($solarPanel=="true" or $solarPanel=="false");
    $validateData['rateType']= (bool) ($rateType=="regulated" or $rateType=="fixed");

    if($solarPanel=="false"){
      $validateData['priceSolarPanelGenerate'] = true;
    }else if($solarPanel=="true"){
      $validateData['priceSolarPanelGenerate'] = (bool) preg_match("/^[0-9]{1,2}+(\.[0-9]{1,8})?$/",$priceSolarPanelGenerate);
    }

    if($rateType=="regulated"){
      $validateData['hourlyDiscrimination']= (bool) ($hourlyDiscrimination=="true");
    }else if($rateType=="fixed"){
      $validateData['hourlyDiscrimination']= (bool) ($hourlyDiscrimination=="true" or $hourlyDiscrimination=="false");
    }

    if($hourlyDiscrimination=="true"){
      $validateData['pricekWhPunta']= (bool) preg_match("/^[0-9]+(\.[0-9]{1,8})?$/",$pricekWhPunta);
      $validateData['pricekWhLlano']= (bool) preg_match("/^[0-9]+(\.[0-9]{1,8})?$/",$pricekWhLlano);
      $validateData['pricekWhValle']= (bool) preg_match("/^[0-9]+(\.[0-9]{1,8})?$/",$pricekWhValle);
    }else if($hourlyDiscrimination=="false"){
      $validateData['pricekWh']= (bool) preg_match("/^[0-9]+(\.[0-9]{1,8})?$/",$pricekWh);
    }

    if($solarPanel=="false"){
      $validateData['maxkWhSP'] = true;
    }else if($solarPanel=="true"){
      $validateData['maxkWhSP'] = (bool) preg_match("/^[0-9]{1,2}+(\.[0-9]{1,8})?$/",$maxkWhSP);
    }

    $validateData['appliances']= (bool) json_validator($appliances);

    echo json_encode($validateData);

    $numValid=0;
    foreach ($validateData as $key => $value) {
      if($value==true){
        $numValid++;
      }
    }


    $redis = new Redis();
    $redis->connect('redis', 6379);

    if( ($numValid==13 and $rateType=="fixed" and $hourlyDiscrimination=="false") or ($numValid==15 and $hourlyDiscrimination=="true") ){
      $jsonHomeData = json_encode(array('country' => $country, 'cp' => $cp, 'numTenants' => $numTenants, 'housingType' => $housingType));
      $redis->set("dataHome",$jsonHomeData);

      $jsonCompanyData = json_encode(array('nameMarketer' => $nameMarketer, 'rateType' => $rateType, 'hourlyDiscrimination' => $hourlyDiscrimination, 'pricekWh' => $pricekWh, 'pricekWhPunta' => $pricekWhPunta, 'pricekWhLlano' => $pricekWhLlano, 'pricekWhValle' => $pricekWhValle, 'powerHired' => $powerHired, 'solarPanel' => $solarPanel, 'priceSolarPanelGenerate' => $priceSolarPanelGenerate, 'maxkWhSP' => $maxkWhSP));
      $redis->set("dataCompany",$jsonCompanyData);

      $jsonAppliances = $appliances;
      $redis->set("dataAppliances",$jsonAppliances);
    }




  }
?>
