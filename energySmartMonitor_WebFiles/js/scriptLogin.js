function loginProfile(){
  var formData = new FormData(document.querySelector('form'));


  var request = new XMLHttpRequest();

  request.open("POST", "submitLogin.php", false);
  request.send(formData);

  var json_IsValid;

  if (request.readyState === request.DONE) {
      if (request.status === 200) {
          json_IsValid=JSON.parse(request.responseText);
          console.log(json_IsValid);
          //console.log(request.responseText);
      }
  }

  document.getElementById("errorLabel-username").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-password").classList.add("err_invalidLabel");

  var contValid=0;

  for (var key in json_IsValid){
    if (json_IsValid.hasOwnProperty(key)) {
        if(json_IsValid[key]==false && key!="login"){
          document.getElementById("errorLabel-"+String(key)).classList.remove("err_invalidLabel");
          console.log(key);

        }else if(json_IsValid[key]==true){
          contValid++;
        }
    }
  }

  console.log(contValid);

  if(contValid==3){
    location.reload();
  }

  if(json_IsValid['login']==false){
    showNotification("alert-danger", "The username or password is incorrect", "top", "center", "", "");
  }

}


function hiddenErrorLabel(id){
  document.getElementById(id).classList.add("err_invalidLabel");
}


function load() {

  var elem_btnLogin = document.getElementById("btnLogin");
  elem_btnLogin.addEventListener("click", loginProfile, false);

  var elem_in_username = document.querySelector('input[name="username"]');
  elem_in_username.addEventListener("input", function() { hiddenErrorLabel("errorLabel-username"); } , false);
  var elem_in_password = document.querySelector('input[name="password"]');
  elem_in_password.addEventListener("input", function() { hiddenErrorLabel("errorLabel-password"); } , false);

}

document.addEventListener("DOMContentLoaded", load, false);
