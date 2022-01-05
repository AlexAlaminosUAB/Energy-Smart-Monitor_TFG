var globalChartBarGeneration;

function logout(){
  var formData = new FormData();

  var request = new XMLHttpRequest();

  request.open("POST", "logout.php", false);
  request.send(formData);

  window.open("index.php", "_self");
}

function showBarChartGeneration() {

  var formData = new FormData();
  formData.append("generationToday", true);

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
    var contDataChart=0;
    var maxDataChart=24;

    for (var key in json_response["generationToday"]){
      if(contDataChart<maxDataChart){
        arrayLabels.push(json_response["generationToday"][key].time);
        arrayData.push( parseFloat(json_response["generationToday"][key].kWh).toFixed(3) );
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
                labels: arrayLabels.reverse(),
                datasets: [{
                        label: 'kWh',
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

      globalChartBarGeneration = new Chart(document.getElementById("bar_chart_generation").getContext("2d"), config);

}

function load() {

  var elem_btLogOut = document.getElementById("btLogOut");
  elem_btLogOut.addEventListener("click", logout, false);

  showBarChartGeneration();

}

document.addEventListener("DOMContentLoaded", load, false);
