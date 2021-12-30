function changeActiveBtn(id){

}


function load() {

  var elem_btnDash = document.getElementById("btnDash");
  elem_btnDash.addEventListener("click", function() { changeActiveBtn("btnDash"); }, false);
  var elem_btnHistData = document.getElementById("btnHistData");
  elem_btnHistData.addEventListener("click", function() { changeActiveBtn("btnHistData"); }, false);
  var elem_btnEnerGenData = document.getElementById("btnEnerGenData");
  elem_btnEnerGenData.addEventListener("click", function() { changeActiveBtn("btnEnerGenData"); }, false);
  var elem_btnRecom = document.getElementById("btnRecom");
  elem_btnRecom.addEventListener("click", function() { changeActiveBtn("btnRecom"); }, false);
  var elem_btnSett = document.getElementById("btnSett");
  elem_btnSett.addEventListener("click", function() { changeActiveBtn("btnSett"); }, false);


}

document.addEventListener("DOMContentLoaded", load, false);
