function logout(){
  var formData = new FormData();

  var request = new XMLHttpRequest();

  request.open("POST", "logout.php", false);
  request.send(formData);

  window.open("index.php", "_self");
}


function load() {

  var elem_btLogOut = document.getElementById("btLogOut");
  elem_btLogOut.addEventListener("click", logout, false);

}

document.addEventListener("DOMContentLoaded", load, false);
