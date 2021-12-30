var globalModeChartHist="cost";
var globalChartBarHistorical;

function logout(){
  var formData = new FormData();

  var request = new XMLHttpRequest();

  request.open("POST", "logout.php", false);
  request.send(formData);

  window.open("index.php", "_self");
}

function showBarChartConsumption() {

  var formData = new FormData();
  formData.append("consumptionHistory", true);

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

      // chart colors
    var arrayLabels = [];
    var arrayData = [];
    var typeLabel;
    var contDataChart=0;
    var maxDataChart=12;

    for (var key in json_response["consumptionHistory"]){
      if(contDataChart<maxDataChart){
        dateSplitted=key.split("/");
        //arrayLabels.push( key+' - '+(json_response["consumptionHistory"][key].endPeriod) );
        arrayLabels.push( (dateSplitted[1]+'/'+dateSplitted[2]) );
        if(globalModeChartHist=="kWh"){
          arrayData.push( (parseFloat(json_response["consumptionHistory"][key].kWh)/1000).toFixed(3));
          typeLabel="kWh";
        }else if(globalModeChartHist=="cost"){
          arrayData.push(json_response["consumptionHistory"][key].cost);
          typeLabel="Cost";
        }
      }else{
        break;
      }
        contDataChart++;
    }


    //console.log(arrayLabels);
    //console.log(arrayData);


    var config = null;


        config = {
            type: 'bar',
            data: {
                labels: arrayLabels,
                datasets: [{
                        label: typeLabel,
                        data: arrayData,
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

      globalChartBarHistorical = new Chart(document.getElementById("bar_chart_consumption").getContext("2d"), config);

}

function updateBarChartConsumption() {

  var formData = new FormData();
  formData.append("consumptionHistory", true);

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

    // chart colors
  var arrayLabels = [];
  var arrayData = [];
  var typeLabel;
  var contDataChart=0;
  var maxDataChart=12;

  for (var key in json_response["consumptionHistory"]){
    if(contDataChart<maxDataChart){
      arrayLabels.push( key+' - '+(json_response["consumptionHistory"][key].endPeriod) );
      if(globalModeChartHist=="kWh"){
        arrayData.push( (parseFloat(json_response["consumptionHistory"][key].kWh)/1000).toFixed(3));
        typeLabel="kWh";
      }else if(globalModeChartHist=="cost"){
        arrayData.push(json_response["consumptionHistory"][key].cost);
        typeLabel="Cost";
      }
    }else{
      break;
    }
      contDataChart++;
  }

    //console.log(arrayLabels);
    //console.log(arrayData);


      globalChartBarHistorical.data.datasets[0].data = arrayData;
      globalChartBarHistorical.data.datasets[0].label = typeLabel;
      globalChartBarHistorical.data.labels = arrayLabels;
      globalChartBarHistorical.update();


}


function setModeChartHist(option){
  globalModeChartHist = document.getElementById("sel_modeChartHist").value;

  var formData = new FormData();
  formData.append("modeChartHist", globalModeChartHist);

  var request = new XMLHttpRequest();

  request.open("POST", "dashboardGetDataWidgets.php", false);
  request.send(formData);

  if(option==false){
    showBarChartConsumption();
  }else if(option==true){
    updateBarChartConsumption();
  }

}

function load() {

  var elem_btLogOut = document.getElementById("btLogOut");
  elem_btLogOut.addEventListener("click", logout, false);

  setModeChartHist(false);

  var elem_modeChartHist = document.getElementById("sel_modeChartHist");
  elem_modeChartHist.addEventListener("change", function(){setModeChartHist(true);}, false);

}

document.addEventListener("DOMContentLoaded", load, false);
