
function updateProfile(){
  var in_username=document.getElementById("in_username").value;
  var in_oldPass=document.getElementById("in_oldPass").value;
  var in_newPass=document.getElementById("in_newPass").value;
  var in_newPass2=document.getElementById("in_newPass2").value;


  var formData = new FormData();
  formData.append("username", in_username);
  formData.append("oldPass", in_oldPass);
  formData.append("newPass", in_newPass);
  formData.append("newPass2", in_newPass2);


  var request = new XMLHttpRequest();

  request.open("POST", "submitDataProfile.php", false);
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
  document.getElementById("errorLabel-oldPass").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-newPass").classList.add("err_invalidLabel");
  document.getElementById("errorLabel-newPass2").classList.add("err_invalidLabel");

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

  if(contValid==4){
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


function load() {

  var elem_btLogOut = document.getElementById("btLogOut");
  elem_btLogOut.addEventListener("click", logout, false);

  var elem_buttonSaveProfile = document.getElementById("btnFloatSaveProfile");
  elem_buttonSaveProfile.addEventListener("click", updateProfile, false);

  var elem_in_username = document.getElementById("in_username");
  elem_in_username.addEventListener("input", function() { hiddenErrorLabel("errorLabel-username"); } , false);
  var elem_in_oldPass = document.getElementById("in_oldPass");
  elem_in_oldPass.addEventListener("input", function() { hiddenErrorLabel("errorLabel-oldPass"); } , false);
  var elem_newPass = document.getElementById("in_newPass");
  elem_newPass.addEventListener("input", function() { hiddenErrorLabel("errorLabel-newPass"); } , false);
  var elem_newPass2 = document.getElementById("in_newPass2");
  elem_newPass2.addEventListener("input", function() { hiddenErrorLabel("errorLabel-newPass2"); } , false);
}

document.addEventListener("DOMContentLoaded", load, false);
