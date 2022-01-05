var globalModeChart="minute";
var globalModeChartAppliances="cost";
var globalChartDoughnutAppliances;
var globalChartLine;
var globalModeListAppliances="day";

function logout(){
  var formData = new FormData();

  var request = new XMLHttpRequest();

  request.open("POST", "logout.php", false);
  request.send(formData);

  window.open("index.php", "_self");
}

function resizeWidgets(){
  var elem_heightChartLine = document.getElementById("line_chart_body").clientHeight;
  document.getElementById("bodyResizeCard").style.height=elem_heightChartLine;

  //resizeDoughnut();

  var elem_heightCardDoughnut = document.getElementById("cardDoughnut").clientHeight;
  document.getElementById("bodyResizeCardAppliances").style.height=elem_heightCardDoughnut;
}

function showDoughnut(){

  var formData = new FormData();
  formData.append("consumptionAppliances", true);

  var request = new XMLHttpRequest();

  request.open("POST", "dashboardGetDataWidgets.php", false);
  request.send(formData);

  var json_response;

  if (request.readyState === request.DONE) {
      if (request.status === 200) {
          json_response=JSON.parse(request.responseText);
          //console.log(json_response);
      }
  }

  var colorsArray = {"Electric Vehicle":'#F44336',"Air Conditioner":'#E91E63',"Dryer":'#9C27B0',"Microwave":'#673AB7',"Oven":'#3F51B5',"Electric heater":'#795548',"Refrigerator":'#03A9F4',"Dishwasher":'#00BCD4',"Washer":'#4CAF50',"Electric Hob":'#9E9E9E',"Light":'#CDDC39',"Outlet":'#FFC107',"Other":'#607D8B'};


    // chart colors
  var colors = [];
  var labels = [];
  var data = [];

  for (var key in json_response["consumptionAppliances"]){

      labels.push(json_response["consumptionAppliances"][key].appliance);
      if(globalModeChartAppliances=="kWh"){
        data.push(json_response["consumptionAppliances"][key].kWh);
      }else if(globalModeChartAppliances=="cost"){
        data.push(json_response["consumptionAppliances"][key].cost);
      }
      colors.push(colorsArray[json_response["consumptionAppliances"][key].appliance]);

  }

  /* 3 donut charts */
  var donutOptions = {
    cutoutPercentage: 70,
    legend: {display:false, position:'bottom', padding:5, labels: {pointStyle:'circle', usePointStyle:true}},
    tooltips: {bodyFontSize: 8}
  };

  // donut 1
  var chDonutData1 = {
      labels: labels,
      datasets: [
        {
          backgroundColor: colors,
          borderWidth: 0,
          data: data
        }
      ]
  };



  var chDonut1 = document.getElementById("myChart");
  globalChartDoughnutAppliances=new Chart(chDonut1, {
      type: 'pie',
      data: chDonutData1,
      options: donutOptions
  });

}

function updateDoughnut(){

  var formData = new FormData();
  formData.append("consumptionAppliances", true);

  var request = new XMLHttpRequest();

  request.open("POST", "dashboardGetDataWidgets.php", false);
  request.send(formData);

  var json_response;

  if (request.readyState === request.DONE) {
      if (request.status === 200) {
          json_response=JSON.parse(request.responseText);
          console.log(json_response);
      }
  }

  var colorsArray = {"Electric Vehicle":'#F44336',"Air Conditioner":'#E91E63',"Dryer":'#9C27B0',"Microwave":'#673AB7',"Oven":'#3F51B5',"Heater":'#795548',"Refrigerator":'#03A9F4',"Dishwasher":'#00BCD4',"Washer":'#4CAF50',"Electric Hob":'#9E9E9E',"Light":'#CDDC39',"Outlet":'#FFC107',"Other":'#607D8B'};


    // chart colors
  var colors = [];
  var labels = [];
  var data = [];

  for (var key in json_response["consumptionAppliances"]){

      labels.push(json_response["consumptionAppliances"][key].appliance);
      if(globalModeChartAppliances=="kWh"){
        data.push(json_response["consumptionAppliances"][key].kWh);
      }else if(globalModeChartAppliances=="cost"){
        data.push(json_response["consumptionAppliances"][key].cost);
      }
      colors.push(colorsArray[json_response["consumptionAppliances"][key].appliance]);

  }

  globalChartDoughnutAppliances.data.datasets[0].data = data;
  globalChartDoughnutAppliances.data.datasets[0].backgroundColor = colors;
  globalChartDoughnutAppliances.data.labels = labels;
  globalChartDoughnutAppliances.update();

}

function resizeDoughnut(){
  document.getElementById("myChart").style.width="500px";
  document.getElementById("myChart").style.height="250px";
}

function getUpdateData(){
  var formData = new FormData();
  formData.append("consumption", true);
  formData.append("cost", true);
  formData.append("peak", true);
  formData.append("solar", true);


  var request = new XMLHttpRequest();

  request.open("POST", "dashboardGetDataWidgets.php", false);
  request.send(formData);

  var json_response;

  if (request.readyState === request.DONE) {
      if (request.status === 200) {
          json_response=JSON.parse(request.responseText);
          //console.log(json_response);
      }
  }

  document.getElementById("kWhConsumptionCard").getElementsByTagName("span")[0].innerText = parseFloat(json_response["consumption"]).toFixed(3)+" kW";
  document.getElementById("kWhConsumptionCard").getElementsByTagName("span")[1].innerText = "out of "+parseFloat(json_response["max"]).toFixed(3)+" kW";


  document.getElementById("costConsumptionCard").innerText = json_response["cost"].toFixed(2)+" €";
  document.getElementById("acumConsumptionCard").innerText = "for the "+json_response["acum"].toFixed(3)+" kWh consumed today";

  document.getElementById("peakConsumptionCard").getElementsByTagName("span")[0].innerText = parseFloat(json_response["peak"]).toFixed(3)+" kW";
  document.getElementById("peakConsumptionCard").getElementsByTagName("span")[1].innerText = "out of "+parseFloat(json_response["max"]).toFixed(3)+" kW";

  document.getElementById("solarGenerationCard").getElementsByTagName("span")[0].innerText = parseFloat(json_response["generationSP"]).toFixed(3)+" kW";
  document.getElementById("solarGenerationCard").getElementsByTagName("span")[1].innerText = "out of "+parseFloat(json_response["maxSP"]).toFixed(3)+" kW";


  $('#knob-kWh')
      .val(((parseFloat(json_response["consumption"])/parseFloat(json_response["max"]))*100).toFixed(2))
      .trigger('change');

  $('#knob-peak')
      .val(((parseFloat(json_response["peak"])/parseFloat(json_response["max"]))*100).toFixed(2))
      .trigger('change');

  $('#knob-solar')
      .val(((parseFloat(json_response["generationSP"])/parseFloat(json_response["maxSP"]))*100).toFixed(2))
      .trigger('change');

}

function showChartLine() {

  var formData = new FormData();
  formData.append("now", true);

  var request = new XMLHttpRequest();

  request.open("POST", "dashboardGetDataWidgets.php", false);
  request.send(formData);

  var json_response;

  if (request.readyState === request.DONE) {
      if (request.status === 200) {
          json_response=JSON.parse(request.responseText);
          //console.log(json_response);
      }
  }

  var numInterval;
  var maxDataRange;

  if(globalModeChart=="minute"){
    numInterval=5; //5seg
    maxDataRange=60;
  }else if(globalModeChart=="hour"){
    numInterval=300; //5mins
    maxDataRange=3600;
  }else if(globalModeChart=="day"){
    numInterval=3600; //1h
    maxDataRange=86400;
  }

  var arrayLabels=[];
  var arrayData=[];
  var contLabels=numInterval-1;
  var tempDataAVG=0;

  for (var key in json_response["now"]){

      if(contLabels==0){
        var d = new Date(parseInt(json_response["now"][key].epochTime)*1000);
        var dateDay = d.toISOString().split("T")[0].split("-")[2];
        var dateMonth = d.toISOString().split("T")[0].split("-")[1];
        var dateYear = d.toISOString().split("T")[0].split("-")[0].substr(2,4);
        var dateFullTime = d.toISOString().split("T")[1].split(".")[0];
        var dateFormatted = dateDay+"-"+dateMonth+"-"+dateYear+" "+dateFullTime;
        //console.log(dateFormatted);

        if(globalModeChart=="day"){
          arrayLabels.push(dateFormatted.split(" ")[1].split(":")[0]);
        }else if(globalModeChart=="hour"){
          arrayLabels.push(dateFormatted.split(" ")[1].split(":")[0]+':'+dateFormatted.split(" ")[1].split(":")[1]);
        }else if(globalModeChart=="minute"){
          arrayLabels.push(dateFormatted.split(" ")[1]);
        }


        tempDataAVG+=json_response["now"][key].kW;
        contLabels=numInterval;
        arrayData.push((tempDataAVG/contLabels).toFixed(3));
        tempDataAVG=0;
      }else{
        tempDataAVG+=json_response["now"][key].kW;
      }

      contLabels--;

      if(key==maxDataRange){
        break;
      }

  }

  //console.log(arrayLabels);
  //console.log(arrayData);


    var config = null;


        config = {
            type: 'line',
            data: {
                labels: arrayLabels.reverse(),
                datasets: [{
                        label: "kW",
                        data: arrayData.reverse(),
                        borderColor: 'rgba(233, 30, 99, 0.75)',
                        backgroundColor: 'rgba(233, 30, 99, 0.3)',
                        pointBorderColor: 'rgba(233, 30, 99, 0)',
                        pointBackgroundColor: 'rgba(233, 30, 99, 0.9)',
                        pointBorderWidth: 1
                    }]
            },
            options: {
                responsive: true,
                legend: false
            }
        }

    globalChartLine = new Chart(document.getElementById("line_chart_now").getContext("2d"), config);

}

function updateChartLine() {

  var formData = new FormData();
  formData.append("now", true);

  var request = new XMLHttpRequest();

  request.open("POST", "dashboardGetDataWidgets.php", false);
  request.send(formData);

  var json_response;

  if (request.readyState === request.DONE) {
      if (request.status === 200) {
          json_response=JSON.parse(request.responseText);
          //console.log(json_response);
      }
  }

var numInterval;
var maxDataRange;

if(globalModeChart=="minute"){
  numInterval=5;
  maxDataRange=60;
}else if(globalModeChart=="hour"){
  numInterval=300;
  maxDataRange=3600;
}else if(globalModeChart=="day"){
  numInterval=3600;//7200
  maxDataRange=86400;
}

var arrayLabels=[];
var arrayData=[];
var contLabels=numInterval-1;
var tempDataAVG=0;

for (var key in json_response["now"]){

    if(contLabels==0){
      var d = new Date(parseInt(json_response["now"][key].epochTime)*1000);
      var dateDay = d.toISOString().split("T")[0].split("-")[2];
      var dateMonth = d.toISOString().split("T")[0].split("-")[1];
      var dateYear = d.toISOString().split("T")[0].split("-")[0].substr(2,4);
      var dateFullTime = d.toISOString().split("T")[1].split(".")[0];
      var dateFormatted = dateDay+"-"+dateMonth+"-"+dateYear+" "+dateFullTime;

      if(globalModeChart=="day"){
        arrayLabels.push(dateFormatted.split(" ")[1].split(":")[0]);
      }else if(globalModeChart=="hour"){
        arrayLabels.push(dateFormatted.split(" ")[1].split(":")[0]+':'+dateFormatted.split(" ")[1].split(":")[1]);
      }else if(globalModeChart=="minute"){
        arrayLabels.push(dateFormatted.split(" ")[1]);
      }

      tempDataAVG+=json_response["now"][key].kW;
      contLabels=numInterval;
      arrayData.push((tempDataAVG/contLabels).toFixed(3));
      tempDataAVG=0;
    }else{
      tempDataAVG+=json_response["now"][key].kW;
    }

    contLabels--;

    if(key==maxDataRange){
      break;
    }

}

console.log(arrayLabels);
console.log(arrayData);

    globalChartLine.data.datasets[0].data = arrayData.reverse();
    globalChartLine.data.labels = arrayLabels.reverse();
    globalChartLine.update();

}

function setModeChart(option){
  globalModeChart = document.getElementById("sel_modeChartNow").value;

  var formData = new FormData();
  formData.append("modeChart", globalModeChart);

  var request = new XMLHttpRequest();

  request.open("POST", "dashboardGetDataWidgets.php", false);
  request.send(formData);

  if(option==true){
    updateChartLine();
  }else if(option==false){
    showChartLine();
  }

}


function setModeChartAppliances(option){
  globalModeChartAppliances = document.getElementById("sel_modeChartAppliances").value;

  var formData = new FormData();
  formData.append("modeChartAppliances", globalModeChartAppliances);

  var request = new XMLHttpRequest();

  request.open("POST", "dashboardGetDataWidgets.php", false);
  request.send(formData);

  if(option==false){
    showDoughnut();
  }else if(option==true){
    updateDoughnut();
  }

}

function setModeListAppliances(option){
  globalModeListAppliances = document.getElementById("sel_modeListAppliances").value;

  var formData = new FormData();
  formData.append("modeListAppliances", globalModeListAppliances);

  var request = new XMLHttpRequest();

  request.open("POST", "dashboardGetDataWidgets.php", false);
  request.send(formData);

  if(globalModeListAppliances=="day"){
    document.getElementById("appliancesDay").style.display = "table-row-group";
    document.getElementById("appliancesMonth").style.display = "none";
    document.getElementById("appliancesYear").style.display = "none";
  }else if(globalModeListAppliances=="month"){
    document.getElementById("appliancesMonth").style.display = "table-row-group";
    document.getElementById("appliancesDay").style.display = "none";
    document.getElementById("appliancesYear").style.display = "none";
  }else if(globalModeListAppliances=="year"){
    document.getElementById("appliancesYear").style.display = "table-row-group";
    document.getElementById("appliancesDay").style.display = "none";
    document.getElementById("appliancesMonth").style.display = "none";
  }

  /*if(option==false){
    showDoughnut();
  }else if(option==true){
    updateDoughnut();
  }*/

}


function load() {

  var elem_btLogOut = document.getElementById("btLogOut");
  elem_btLogOut.addEventListener("click", logout, false);

  setModeChart(false);

  var elem_modeChartNow = document.getElementById("sel_modeChartNow");
  elem_modeChartNow.addEventListener("change", function(){ setModeChart(true);}, false);

  window.addEventListener("resize", resizeWidgets, false);

  var elem_heightChartLine = document.getElementById("line_chart_body").clientHeight;
  document.getElementById("bodyResizeCard").style.height=elem_heightChartLine;

  //showDoughnut();
  setModeChartAppliances(false);
  //resizeDoughnut();
  var elem_modeChartAppliances = document.getElementById("sel_modeChartAppliances");
  elem_modeChartAppliances.addEventListener("change", function(){setModeChartAppliances(true);}, false);

  var elem_heightCardDoughnut = document.getElementById("cardDoughnut").clientHeight;
  document.getElementById("bodyResizeCardAppliances").style.height=elem_heightCardDoughnut;

  var elem_modeListAppliances = document.getElementById("sel_modeListAppliances");
  elem_modeListAppliances.addEventListener("change", function(){setModeListAppliances(true);}, false);

  getUpdateData();

  window.setInterval(function(){
    getUpdateData();
    updateChartLine();
  }, 30000);
}

document.addEventListener("DOMContentLoaded", load, false);
