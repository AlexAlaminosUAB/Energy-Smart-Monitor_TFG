function componentFromStr(numStr, percent) {
    var num = Math.max(0, parseInt(numStr, 10));
    return percent ?
        Math.floor(255 * Math.min(100, num) / 100) : Math.min(255, num);
}

function rgbToHex(rgb) {
    var rgbRegex = /^rgb\(\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*\)$/;
    var result, r, g, b, hex = "";
    if ( (result = rgbRegex.exec(rgb)) ) {
        r = componentFromStr(result[1], result[2]);
        g = componentFromStr(result[3], result[4]);
        b = componentFromStr(result[5], result[6]);

        hex = (0x1000000 + (r << 16) + (g << 8) + b).toString(16).slice(1);
    }
    return hex;
}

function updateThemeSettings() {
  var elem_baseType = document.getElementById("baseTypeTheme");
  var baseType = elem_baseType.options[elem_baseType.selectedIndex].value;

  var elem_accentColor = document.getElementById("accentColorTheme");
  var accentColorName = elem_accentColor.options[elem_accentColor.selectedIndex].value;
  var accentColorCode_temp = getComputedStyle(elem_accentColor.options[elem_accentColor.selectedIndex]).getPropertyValue("background-color");
  var accentColorCode = rgbToHex(accentColorCode_temp);

  console.log(baseType);
  console.log(accentColorName);
  console.log(accentColorCode);

  var formData = new FormData();
  formData.append("baseType", baseType);
  formData.append("accentColorName", accentColorName);
  formData.append("accentColorCode", accentColorCode);

  var request = new XMLHttpRequest();
  request.open("POST", "submitDataThemeSettings.php");
  request.send(formData);

  var elemBodyClasses=document.getElementsByTagName("body")[0].className.split(" ");
  var classAccentOld;
  var classBaseOld;
  var newAccentColorNameClass="theme-"+accentColorName;
  var newBaseTypeClass="th-"+baseType;
  if(elemBodyClasses[0]=="th-light"){
    classAccentOld=elemBodyClasses[1];
  }else{
    classAccentOld=elemBodyClasses[0];
  }

  if(document.getElementsByTagName("body")[0].classList.contains('th-light')){
    classBaseOld="th-light";
  }else{
    classBaseOld="dark";
  }
  console.log("classBaseOld: "+classBaseOld);
  console.log("newBaseTypeClass: "+newBaseTypeClass);
  if(classBaseOld!=newBaseTypeClass){
    if(newBaseTypeClass=="th-light"){
      document.getElementsByTagName("body")[0].classList.add('th-light');
      document.getElementById("leftsidebar").classList.add('th-light');
      var arrayCard = document.getElementsByClassName("card");
      for (var i = 0; i < arrayCard.length; i++) {
        arrayCard[i].classList.add('th-light');
      }
    }else{
      document.getElementsByTagName("body")[0].classList.remove('th-light');
      document.getElementById("leftsidebar").classList.remove('th-light');
      var arrayCard = document.getElementsByClassName("card");
      for (var i = 0; i < arrayCard.length; i++) {
        arrayCard[i].classList.remove('th-light');
      }
    }
  }

  if(classAccentOld!=newAccentColorNameClass){
    document.getElementsByTagName("body")[0].classList.remove(classAccentOld);
    document.getElementsByTagName("body")[0].classList.add(newAccentColorNameClass);

    var classAccentOld2=classAccentOld.replace('theme', 'bg');
    var newAccentColorNameClass2=newAccentColorNameClass.replace('theme', 'bg');
    document.getElementById("btnFloatSave").classList.remove(classAccentOld2);
    document.getElementById("btnFloatSave").classList.add(newAccentColorNameClass2);

    var classAccentOld3=classAccentOld.replace('theme', 'radio-col');
    var newAccentColorNameClass3=newAccentColorNameClass.replace('theme', 'radio-col');
    document.getElementById("house").classList.remove(classAccentOld3);
    document.getElementById("house").classList.add(newAccentColorNameClass3);
    document.getElementById("apartment").classList.remove(classAccentOld3);
    document.getElementById("apartment").classList.add(newAccentColorNameClass3);


  }


}

function updateSolarPanelsSettings(){
  if(document.getElementById("md_checkbox_solarPanel").checked){
    document.getElementById("solarPanelGenerate").disabled=false;
    document.getElementById("solarPanelsData").style.display="block";
  }else if (document.getElementById("md_checkbox_solarPanel").checked==false){
    document.getElementById("solarPanelGenerate").disabled=true;
    document.getElementById("solarPanelsData").style.display="none";
  }
}

function hourlyDiscriminationSettings(){
  if(document.getElementById("md_checkbox_hourlyDiscrimination").checked){
    document.getElementById("in_pricekWh").disabled=true;
    document.getElementById("in_pricekWhPunta").disabled=false;
    document.getElementById("in_pricekWhLlano").disabled=false;
    document.getElementById("in_pricekWhValle").disabled=false;
    document.getElementById("row_PeriodPrice").style.display="block";
    document.getElementById("row_SamePrice").style.display="none";
  }else if (document.getElementById("md_checkbox_hourlyDiscrimination").checked==false){
    document.getElementById("in_pricekWh").disabled=false;
    document.getElementById("in_pricekWhPunta").disabled=true;
    document.getElementById("in_pricekWhLlano").disabled=true;
    document.getElementById("in_pricekWhValle").disabled=true;
    document.getElementById("row_PeriodPrice").style.display="none";
    document.getElementById("row_SamePrice").style.display="block";
  }
}

function changeRateTypeSettings(){
  if(document.getElementById("regulated").checked){
    document.getElementById("md_checkbox_hourlyDiscrimination").checked=true;
    document.getElementById("md_checkbox_hourlyDiscrimination").disabled=true;
    document.querySelector("[for='md_checkbox_hourlyDiscrimination']").style.cursor="not-allowed";
  }else if(document.getElementById("fixed").checked){
    document.getElementById("md_checkbox_hourlyDiscrimination").disabled=false;
    document.querySelector("[for='md_checkbox_hourlyDiscrimination']").style.cursor="pointer";
  }
  hourlyDiscriminationSettings();
}

function updateSettings(){
  var sel_country=document.getElementById("sel_country").value;
  //var sel_state=document.getElementById("sel_state").value;
  //var sel_city=document.getElementById("sel_city").value;
  var in_cp=document.getElementById("in_cp").value;
  var in_numTenants=document.getElementById("in_numTenants").value;



  var sel_housingType;
  if(document.getElementById("house").checked){
    sel_housingType="house";
  }else if(document.getElementById("apartment").checked){
    sel_housingType="apartment";
  }

  var sel_rateType;
  if(document.getElementById("regulated").checked){
    sel_rateType="regulated";
  }else if(document.getElementById("fixed").checked){
    sel_rateType="fixed";
  }

  var in_nameMarketer=document.getElementById("in_nameMarketer").value;
  var in_pricekWh=document.getElementById("in_pricekWh").value;
  var in_powerHired=document.getElementById("in_powerHired").value;
  //var in_firstDayBC=document.getElementById("in_firstDayBC").value;
  //var in_numDayBC=document.getElementById("in_numDayBC").value;

  var in_pricekWhPunta=document.getElementById("in_pricekWhPunta").value;
  var in_pricekWhLlano=document.getElementById("in_pricekWhLlano").value;
  var in_pricekWhValle=document.getElementById("in_pricekWhValle").value;

  var checkbox_solarPanel = Boolean(document.getElementById("md_checkbox_solarPanel").checked);
  var in_priceSolarPanelGenerate = document.getElementById("solarPanelGenerate").value;
  var checkbox_hourlyDiscrimination = Boolean(document.getElementById("md_checkbox_hourlyDiscrimination").checked);
  //var checkbox_regulated = Boolean(document.getElementById("md_checkbox_regulated").checked);
  var in_maxkWhSP = document.getElementById("in_maxkWhSP").value;

  console.log(checkbox_solarPanel);

  var selMul_appliances = {};
  for (var option of document.getElementById('optgroup').options){
    if (option.selected) {
      selMul_appliances[option.value]=true;
    }else{
      selMul_appliances[option.value]=false;
    }
  }

  var formData = new FormData();
  formData.append("country", sel_country);
  //formData.append("state", sel_state);
  //formData.append("city", sel_city);
  formData.append("cp", in_cp);
  formData.append("numTenants", in_numTenants);
  formData.append("housingType", sel_housingType);
  formData.append("nameMarketer", in_nameMarketer);
  formData.append("pricekWh", in_pricekWh);
  formData.append("powerHired", in_powerHired);

  formData.append("pricekWhPunta", in_pricekWhPunta);
  formData.append("pricekWhLlano", in_pricekWhLlano);
  formData.append("pricekWhValle", in_pricekWhValle);
  //formData.append("firstDayBC", in_firstDayBC);
  //formData.append("numDayBC", in_numDayBC);
  formData.append("solarPanel", checkbox_solarPanel);
  formData.append("priceSolarPanelGenerate", in_priceSolarPanelGenerate);
  formData.append("hourlyDiscrimination", checkbox_hourlyDiscrimination);
  //formData.append("regulated", checkbox_regulated);
  formData.append("rateType", sel_rateType);
  formData.append("maxkWhSP", in_maxkWhSP);
  formData.append("appliances", JSON.stringify(selMul_appliances));
  console.log(JSON.stringify(selMul_appliances));

  var request = new XMLHttpRequest();

  request.open("POST", "submitDataSettings.php", false);
  request.send(formData);

  var json_IsValid;

  if (request.readyState === request.DONE) {
      if (request.status === 200) {
          json_IsValid=JSON.parse(request.responseText);
          console.log(json_IsValid);
          //console.log(request.responseText);
      }
  }

  document.getElementById("errorLabel-cp").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-numTenants").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-housingType").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-nameMarketer").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-pricekWh").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-powerHired").classList.add("err_invalidLabel");
  //document.getElementById("errorLabel-firstDayBC").classList.add("err_invalidLabel");
  //document.getElementById("errorLabel-numDayBC").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-priceSolarPanelGenerate").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-maxkWhSP").classList.add("err_invalidLabel");

  document.getElementById("errorLabel-pricekWhPunta").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-pricekWhLlano").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-pricekWhValle").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-rateType").classList.add("err_invalidLabel");

  var contValid=0;

  for (var key in json_IsValid){
    if (json_IsValid.hasOwnProperty(key)) {
      if(json_IsValid[key]==false){
        document.getElementById("errorLabel-"+String(key)).classList.remove("err_invalidLabel");
        console.log(key);

      }else if(json_IsValid[key]==true){
        contValid++;
      }
    }
  }
  console.log(contValid);

  if( (contValid==13 && sel_rateType=="fixed" && checkbox_hourlyDiscrimination==false) || (contValid==15 && checkbox_hourlyDiscrimination==true)){
    showNotification("alert-success", "The changes have been saved", "bottom", "center", "", "");
  }else{
    showNotification("alert-danger", "The changes have not been saved", "bottom", "center", "", "");
  }

}

function hiddenErrorLabel(id){
  document.getElementById(id).classList.add("err_invalidLabel");
}

function logout(){
  var formData = new FormData();

  var request = new XMLHttpRequest();

  request.open("POST", "logout.php", false);
  request.send(formData);

  window.open("index.php", "_self");
}


function generateModel(){
  var val_applianceTypeModel = document.getElementById("applianceTypeModel").value;
  var val_endTimeModel = document.getElementById("endTimeModel").value;
  var val_startTimeModel = document.getElementById("startTimeModel").value;
  var val_startTimeDay = document.getElementById("startTimeDay").value;
  var val_endTimeDay = document.getElementById("endTimeDay").value;

  var formData = new FormData();

  formData.append("applianceTypeModel", val_applianceTypeModel);
  formData.append("endTimeModel", val_endTimeModel);
  formData.append("startTimeModel", val_startTimeModel);
  formData.append("startTimeDay", val_startTimeDay);
  formData.append("endTimeDay", val_endTimeDay);

  var request = new XMLHttpRequest();

  request.open("POST", "submitModelGeneration.php", false);
  request.send(formData);

  var json_IsValid;

  if (request.readyState === request.DONE) {
      if (request.status === 200) {
          json_IsValid=JSON.parse(request.responseText);
          console.log(json_IsValid);
          console.log(request.responseText);
      }
  }

  document.getElementById("errorLabel-applianceTypeModel").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-startTimeModel").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-endTimeModel").classList.add("err_invalidLabel");

  var contValid=0;

  for (var key in json_IsValid){
    if (json_IsValid.hasOwnProperty(key)) {
      if(json_IsValid[key]==false){
        document.getElementById("errorLabel-"+String(key)).classList.remove("err_invalidLabel");
        console.log(key);

      }else if(json_IsValid[key]==true){
        contValid++;
      }
    }
  }
  console.log(contValid);

  if( contValid==3 ){
    showNotification("alert-success", "Model generated successfully", "bottom", "center", "", "");
  }else{
    showNotification("alert-danger", "Error!, The model could not be generated", "bottom", "center", "", "");
  }

}



function load() {
  var elem_baseType = document.getElementById("baseTypeTheme");
  elem_baseType.addEventListener("change", updateThemeSettings, false);

  var elem_accentColor = document.getElementById("accentColorTheme");
  elem_accentColor.addEventListener("change", updateThemeSettings, false);

  var elem_checkbox_solarPanel = document.getElementById("md_checkbox_solarPanel");
  elem_checkbox_solarPanel.addEventListener("change", updateSolarPanelsSettings, false);

  var elem_rateType_selR = document.getElementById("regulated");
  elem_rateType_selR.addEventListener("change", changeRateTypeSettings, false);
  var elem_rateType_selF = document.getElementById("fixed");
  elem_rateType_selF.addEventListener("change", changeRateTypeSettings, false);


  var elem_checkbox_hourlyDiscrimination = document.getElementById("md_checkbox_hourlyDiscrimination");
  elem_checkbox_hourlyDiscrimination.addEventListener("change", hourlyDiscriminationSettings, false);

  var elem_buttonSave = document.getElementById("btnFloatSave");
  elem_buttonSave.addEventListener("click", updateSettings, false);

  var elem_btLogOut = document.getElementById("btLogOut");
  elem_btLogOut.addEventListener("click", logout, false);

  var elem_in_cp = document.getElementById("in_cp");
  elem_in_cp.addEventListener("input", function() { hiddenErrorLabel("errorLabel-cp"); } , false);
  var elem_in_numTenants = document.getElementById("in_numTenants");
  elem_in_numTenants.addEventListener("input", function() { hiddenErrorLabel("errorLabel-numTenants"); } , false);
  var elem_apartment = document.getElementById("apartment");
  elem_apartment.addEventListener("change", function() { hiddenErrorLabel("errorLabel-housingType"); } , false);
  var elem_house = document.getElementById("house");
  elem_house.addEventListener("change", function() { hiddenErrorLabel("errorLabel-housingType"); } , false);

  var elem_fixed = document.getElementById("fixed");
  elem_fixed.addEventListener("change", function() { hiddenErrorLabel("errorLabel-rateType"); } , false);
  var elem_regulated = document.getElementById("regulated");
  elem_regulated.addEventListener("change", function() { hiddenErrorLabel("errorLabel-rateType"); } , false);

  var elem_in_nameMarketer = document.getElementById("in_nameMarketer");
  elem_in_nameMarketer.addEventListener("input", function() { hiddenErrorLabel("errorLabel-nameMarketer"); } , false);
  var elem_in_pricekWh = document.getElementById("in_pricekWh");
  elem_in_pricekWh.addEventListener("input", function() { hiddenErrorLabel("errorLabel-pricekWh"); } , false);

  var elem_in_pricekWhPunta = document.getElementById("in_pricekWhPunta");
  elem_in_pricekWhPunta.addEventListener("input", function() { hiddenErrorLabel("errorLabel-pricekWhPunta"); } , false);
  var elem_in_pricekWhLlano = document.getElementById("in_pricekWhLlano");
  elem_in_pricekWhLlano.addEventListener("input", function() { hiddenErrorLabel("errorLabel-pricekWhLlano"); } , false);
  var elem_in_pricekWhValle = document.getElementById("in_pricekWhValle");
  elem_in_pricekWhValle.addEventListener("input", function() { hiddenErrorLabel("errorLabel-pricekWhValle"); } , false);

  var elem_in_powerHired = document.getElementById("in_powerHired");
  elem_in_powerHired.addEventListener("input", function() { hiddenErrorLabel("errorLabel-powerHired"); } , false);
  //var elem_in_firstDayBC = document.getElementById("in_firstDayBC");
  //elem_in_firstDayBC.addEventListener("input", function() { hiddenErrorLabel("errorLabel-firstDayBC"); } , false);
  //var elem_in_numDayBC = document.getElementById("in_numDayBC");
  //elem_in_numDayBC.addEventListener("input", function() { hiddenErrorLabel("errorLabel-numDayBC"); } , false);
  var elem_solarPanelGenerate = document.getElementById("solarPanelGenerate");
  elem_solarPanelGenerate.addEventListener("input", function() { hiddenErrorLabel("errorLabel-priceSolarPanelGenerate"); } , false);
  var elem_in_maxkWhSP = document.getElementById("in_maxkWhSP");
  elem_in_maxkWhSP.addEventListener("input", function() { hiddenErrorLabel("errorLabel-maxkWhSP"); } , false);

  var elem_applianceTypeModel = document.getElementById("applianceTypeModel");
  elem_applianceTypeModel.addEventListener("select", function() { hiddenErrorLabel("errorLabel-applianceTypeModel"); } , false);
  var elem_startTimeModel = document.getElementById("startTimeModel");
  elem_startTimeModel.addEventListener("input", function() { hiddenErrorLabel("errorLabel-startTimeModel"); } , false);
  var elem_endTimeModel = document.getElementById("endTimeModel");
  elem_endTimeModel.addEventListener("input", function() { hiddenErrorLabel("errorLabel-endTimeModel"); } , false);

  var elem_btnGenerationModel = document.getElementById("btnGenerationModel");
  elem_btnGenerationModel.addEventListener("click", generateModel, false);

  hourlyDiscriminationSettings();
  changeRateTypeSettings();
}

document.addEventListener("DOMContentLoaded", load, false);
