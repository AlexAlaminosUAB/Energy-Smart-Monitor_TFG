/**
 * strings_en.h
 * engligh strings for
 * WiFiManager, a library for the ESP8266/Arduino platform
 * for configuration of WiFi credentials using a Captive Portal
 * 
 * @author Creator tzapu
 * @author tablatronix
 * @version 0.0.0
 * @license MIT
 */

#ifndef _WM_STRINGS_H_
#define _WM_STRINGS_H_

#ifndef WIFI_MANAGER_OVERRIDE_STRINGS
// !!! THIS DOES NOT WORK, you cannot define in a sketch, if anyone one knows how to order includes to be able to do this help!

const char HTTP_HEAD_START[]       PROGMEM = "<!DOCTYPE html><html lang='en'><head><meta name='format-detection' content='telephone=no'><meta charset='UTF-8'><meta  name='viewport' content='width=device-width,initial-scale=1,user-scalable=no'/><title>{v}</title>";
const char HTTP_SCRIPT[]           PROGMEM = "<script>function c(l){"
"document.getElementById('s').value=l.innerText||l.textContent;"
"p = l.nextElementSibling.classList.contains('l');"
"document.getElementById('p').disabled = !p;"
"if(p)document.getElementById('p').focus();}</script>";

const char HTTP_HEAD_END[]         PROGMEM = "</head><body class='{c}'><div class='logo'><img width='200px' height='200px' src='data:image/png;base64,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'></div><br><div class='wrap'>";
// example of embedded logo, base64 encoded inline, No styling here
// const char HTTP_ROOT_MAIN[]        PROGMEM = "<img title=' alt=' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAADQElEQVRoQ+2YjW0VQQyE7Q6gAkgFkAogFUAqgFQAVACpAKiAUAFQAaECQgWECggVGH1PPrRvn3dv9/YkFOksoUhhfzwz9ngvKrc89JbnLxuA/63gpsCmwCADWwkNEji8fVNgotDM7osI/x777x5l9F6JyB8R4eeVql4P0y8yNsjM7KGIPBORp558T04A+CwiH1UVUItiUQmZ2XMReSEiAFgjAPBeVS96D+sCYGaUx4cFbLfmhSpnqnrZuqEJgJnd8cQplVLciAgX//Cf0ToIeOB9wpmloLQAwpnVmAXgdf6pwjpJIz+XNoeZQQZlODV9vhc1Tuf6owrAk/8qIhFbJH7eI3eEzsvydQEICqBEkZwiALfF70HyHPpqScPV5HFjeFu476SkRA0AzOfy4hYwstj2ZkDgaphE7m6XqnoS7Q0BOPs/sw0kDROzjdXcCMFCNwzIy0EcRcOvBACfh4k0wgOmBX4xjfmk4DKTS31hgNWIKBCI8gdzogTgjYjQWFMw+o9LzJoZ63GUmjWm2wGDc7EvDDOj/1IVMIyD9SUAL0WEhpriRlXv5je5S+U1i2N88zdPuoVkeB+ls4SyxCoP3kVm9jsjpEsBLoOBNC5U9SwpGdakFkviuFP1keblATkTENTYcxkzgxTKOI3jyDxqLkQT87pMA++H3XvJBYtsNbBN6vuXq5S737WqHkW1VgMQNXJ0RshMqbbT33sJ5kpHWymzcJjNTeJIymJZtSQd9NHQHS1vodoFoTMkfbJzpRnLzB2vi6BZAJxWaCr+62BC+jzAxVJb3dmmiLzLwZhZNPE5e880Suo2AZgB8e8idxherqUPnT3brBDTlPxO3Z66rVwIwySXugdNd+5ejhqp/+NmgIwGX3Py3QBmlEi54KlwmjkOytQ+iJrLJj23S4GkOeecg8G091no737qvRRdzE+HLALQoMTBbJgBsCj5RSWUlUVJiZ4SOljb05eLFWgoJ5oY6yTyJp62D39jDANoKKcSocPJD5dQYzlFAFZJflUArgTPZKZwLXAnHmerfJquUkKZEgyzqOb5TuDt1P3nwxobqwPocZA11m4A1mBx5IxNgRH21ti7KbAGiyNn3HoF/gJ0w05A8xclpwAAAABJRU5ErkJggg==' /><h1>{v}</h1><h3>WiFiManager</h3>";
//const char HTTP_ROOT_MAIN[]        PROGMEM = "<h1>{v}</h1><h3>WiFiManager</h3>";
const char HTTP_ROOT_MAIN[]        PROGMEM = "<h1>Energy Smart Monitor</h1>";
const char * const HTTP_PORTAL_MENU[] PROGMEM = {
"<form action='/wifi'    method='get'><button>Configure WiFi</button></form><br/>\n", // MENU_WIFI
//"<form action='/0wifi'   method='get'><button>Configure WiFi (No Scan)</button></form><br/>\n", // MENU_WIFINOSCAN
//"<form action='/info'    method='get'><button>Info</button></form><br/>\n", // MENU_INFO
//"<form action='/param'   method='get'><button>Setup</button></form><br/>\n",//MENU_PARAM
//"<form action='/close'   method='get'><button>Close</button></form><br/>\n", // MENU_CLOSE
//"<form action='/restart' method='get'><button>Restart</button></form><br/>\n",// MENU_RESTART
//"<form action='/exit'    method='get'><button>Exit</button></form><br/>\n",  // MENU_EXIT
"<form action='/erase'   method='get'><button class='D'>Erase</button></form><br/>\n", // MENU_ERASE
"<hr><br/>" // MENU_SEP
};

// const char HTTP_PORTAL_OPTIONS[]   PROGMEM = strcat(HTTP_PORTAL_MENU[0] , HTTP_PORTAL_MENU[3] , HTTP_PORTAL_MENU[7]);
const char HTTP_PORTAL_OPTIONS[]   PROGMEM = "";
const char HTTP_ITEM_QI[]          PROGMEM = "<div role='img' aria-label='{r}%' title='{r}%' class='q q-{q} {i} {h}'></div>"; // rssi icons
const char HTTP_ITEM_QP[]          PROGMEM = "<div class='q {h}'>{r}%</div>"; // rssi percentage
const char HTTP_ITEM[]             PROGMEM = "<div><a href='#p' onclick='c(this)'>{v}</a>{qi}{qp}</div>"; // {q} = HTTP_ITEM_QI, {r} = HTTP_ITEM_QP
//const char HTTP_ITEM[]            PROGMEM = "<div><a href='#p' onclick='c(this)'>{v}</a> {R} {r}% {q} {e}</div>"; // test all tokens

const char HTTP_FORM_START[]       PROGMEM = "<div id='selNetwork'><label>Select your WiFi Network</label><hr></div><form method='POST' action='{v}'>";
const char HTTP_FORM_WIFI[]        PROGMEM = "<label for='s'>SSID</label><input id='s' name='s' maxlength='32' autocorrect='off' autocapitalize='none' placeholder='{v}'><br/><label for='p'>Password</label><input id='p' name='p' maxlength='64' type='password' placeholder='{p}'>";
const char HTTP_FORM_WIFI_END[]    PROGMEM = "";
const char HTTP_FORM_STATIC_HEAD[] PROGMEM = "<hr><br/>";
const char HTTP_FORM_END[]         PROGMEM = "<br/><br/><button type='submit' id='btnSave_new'><svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' id='mdi-content-save' width='24' height='24' viewBox='0 0 24 24' style='/* color: white; */'><path d='M15,9H5V5H15M12,19A3,3 0 0,1 9,16A3,3 0 0,1 12,13A3,3 0 0,1 15,16A3,3 0 0,1 12,19M17,3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V7L17,3Z'></path></svg></button></form>";
const char HTTP_FORM_LABEL[]       PROGMEM = "<label for='{i}'>{t}</label>";
const char HTTP_FORM_PARAM_HEAD[]  PROGMEM = "<hr><br/>";
const char HTTP_FORM_PARAM[]       PROGMEM = "<br/><input id='{i}' name='{n}' maxlength='{l}' value='{v}' {c}>";

const char HTTP_SCAN_LINK[]        PROGMEM = "<br/><form action='/wifi?refresh=1' method='POST'><button name='refresh' value='1'>Refresh</button></form>";
//const char HTTP_SAVED[]            PROGMEM = "<div class='msg'>Saving Credentials<br/>Trying to connect ESP to network.<br />If it fails reconnect to AP to try again</div>";
const char HTTP_SAVED[]            PROGMEM = "<div id='animAlert' class='w3-panel w3-green w3-round'> <h2>Saving Credentials</h2> <p>Trying to connect ESP to network. <br> If it fails reconnect to AP to try again </p></div>";
const char HTTP_PARAMSAVED[]       PROGMEM = "<div class='msg S'>Saved<br/></div>";
const char HTTP_END[]              PROGMEM = "</div></body></html>";
const char HTTP_ERASEBTN[]         PROGMEM = "<br/><form action='/erase' method='get'><button class='D'>Erase WiFi Config</button></form>";
const char HTTP_BACKBTN[]          PROGMEM = "<hr><br/><form action='/' method='get'><button>Back</button></form>";

const char HTTP_STATUS_ON[]        PROGMEM = "<div class='msg S'><strong>Connected</strong> to {v}<br/><em><small>with IP {i}</small></em></div>";
const char HTTP_STATUS_OFF[]       PROGMEM = "<div class='msg {c}'><strong>Not Connected</strong> to {v}{r}</div>";
const char HTTP_STATUS_OFFPW[]     PROGMEM = "<br/>Authentication Failure"; // STATION_WRONG_PASSWORD,  no eps32
const char HTTP_STATUS_OFFNOAP[]   PROGMEM = "<br/>AP not found";   // WL_NO_SSID_AVAIL
const char HTTP_STATUS_OFFFAIL[]   PROGMEM = "<br/>Could not Connect"; // WL_CONNECT_FAILED
const char HTTP_STATUS_NONE[]      PROGMEM = "<div class='msg' style='display:none;'>No AP set</div>";
const char HTTP_BR[]               PROGMEM = "<br/>";

const char HTTP_STYLE[]            PROGMEM = "<style>"
".c,body{text-align:center;font-family:verdana;color:white;background-color:#303030;}div,input{padding:5px;font-size:1em;margin:5px 0;box-sizing:border-box;}"
"input,button,.msg{border-radius:.3rem;width: 100%}"
"button,input[type='button'],input[type='submit']{cursor:pointer;border:0;background-color:#8BC34A;color:#fff;line-height:2.4rem;font-size:1.2rem;width:100%}"
"input[type='file']{border:1px solid #1fa3ec}"
".wrap {text-align:left;display:inline-block;min-width:260px;max-width:500px}"
"#btnSave_new{border-radius: 55px;width: 55px;height: 55px;position: fixed;right: 20px;bottom: 20px; box-shadow: 0px 1px 1.5px 1px rgba(0, 0, 0, 0.12);}"
"form[action=wifisave] input {border: none;height:34px;background-color:gray;color:white;}"
"button:hover{background-color: #688f3b;} form[action=wifisave] input:focus{outline: none;}"
"#mdi-content-save {fill: white;height: 30px;width: 30px;margin-top: 10px;}"
"#selNetwork {padding: 0px;top: 255px;margin: auto;width: 100%;max-width: 490px;}"
"@keyframes animationAlert {0% {transform: translatey(1000%);}100% {transform: translatey(40px);}}"
"#animAlert {animation: 1s ease-out 0s 1 animationAlert;font-family: verdana;max-width: 600px;margin: auto;top: 40px;transform: translatey(40px);}"
".w3-green, .w3-hover-green:hover {color: #fff;background-color: #8BC34A;} .w3-panel {margin-top: 16px;margin-bottom: 16px;} .w3-round, .w3-round-medium {border-radius: 4px;} .w3-container, .w3-panel {padding: 0.01em 16px;}"
"form[action=wifisave] select {border: none;height: 34px;background-color: gray;color: white; border-radius: .3rem; width: 100%; padding: 5px;font-size: 1em; margin: 5px 0; box-sizing: border-box; -webkit-appearance: none;-moz-appearance: none;appearance: none; outline:none;}"
// links
"a{color:#fff;font-weight:700;text-decoration:none}a:hover{color:#8BC34A;text-decoration:underline}"
// quality icons
".q{height:16px;margin:0;padding:0 5px;text-align:right;min-width:38px;float:right}.q.q-0:after{background-position-x:0}.q.q-1:after{background-position-x:-16px}.q.q-2:after{background-position-x:-32px}.q.q-3:after{background-position-x:-48px}.q.q-4:after{background-position-x:-64px}.q.l:before{background-position-x:-80px;padding-right:5px}.ql .q{float:left}.q:after,.q:before{content:'';width:16px;height:16px;display:inline-block;background-repeat:no-repeat;background-position: 16px 0;filter: invert(100%);-webkit-filter: invert(100%);"
"background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAAAQCAMAAADeZIrLAAAAJFBMVEX///8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADHJj5lAAAAC3RSTlMAIjN3iJmqu8zd7vF8pzcAAABsSURBVHja7Y1BCsAwCASNSVo3/v+/BUEiXnIoXkoX5jAQMxTHzK9cVSnvDxwD8bFx8PhZ9q8FmghXBhqA1faxk92PsxvRc2CCCFdhQCbRkLoAQ3q/wWUBqG35ZxtVzW4Ed6LngPyBU2CobdIDQ5oPWI5nCUwAAAAASUVORK5CYII=');}"
// icons @2x media query (32px rescaled)
"@media (-webkit-min-device-pixel-ratio: 2),(min-resolution: 192dpi){.q:before,.q:after {"
"background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAALwAAAAgCAMAAACfM+KhAAAALVBMVEX///8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADAOrOgAAAADnRSTlMAESIzRGZ3iJmqu8zd7gKjCLQAAACmSURBVHgB7dDBCoMwEEXRmKlVY3L//3NLhyzqIqSUggy8uxnhCR5Mo8xLt+14aZ7wwgsvvPA/ofv9+44334UXXngvb6XsFhO/VoC2RsSv9J7x8BnYLW+AjT56ud/uePMdb7IP8Bsc/e7h8Cfk912ghsNXWPpDC4hvN+D1560A1QPORyh84VKLjjdvfPFm++i9EWq0348XXnjhhT+4dIbCW+WjZim9AKk4UZMnnCEuAAAAAElFTkSuQmCC');"
"background-size: 95px 16px;}}"
// msg callouts
".msg{padding:20px;margin:20px 0;border:1px solid #eee;border-left-width:5px;border-left-color:#777}.msg h4{margin-top:0;margin-bottom:5px}.msg.P{border-left-color:#1fa3ec}.msg.P h4{color:#1fa3ec}.msg.D{border-left-color:#dc3630}.msg.D h4{color:#dc3630}.msg.S{border-left-color: #5cb85c}.msg.S h4{color: #5cb85c}"
// lists
"dt{font-weight:bold}dd{margin:0;padding:0 0 0.5em 0;min-height:12px}"
"td{vertical-align: top;}"
".h{display:none}"
"button.D{background-color:#dc3630}"
// invert
"body.invert,body.invert a,body.invert h1 {background-color:#060606;color:#fff;}"
"body.invert .msg{color:#fff;background-color:#282828;border-top:1px solid #555;border-right:1px solid #555;border-bottom:1px solid #555;}"
"body.invert .q[role=img]{-webkit-filter:invert(1);filter:invert(1);}"
"input:disabled {opacity: 0.5;}"
"</style>";

const char HTTP_HELP[]             PROGMEM =
 "<br/><h3>Available Pages</h3><hr>"
 "<table class='table'>"
 "<thead><tr><th>Page</th><th>Function</th></tr></thead><tbody>"
 "<tr><td><a href='/'>/</a></td>"
 "<td>Menu page.</td></tr>"
 "<tr><td><a href='/wifi'>/wifi</a></td>"
 "<td>Show WiFi scan results and enter WiFi configuration.(/0wifi noscan)</td></tr>"
 "<tr><td><a href='/wifisave'>/wifisave</a></td>"
 "<td>Save WiFi configuration information and configure device. Needs variables supplied.</td></tr>"
 "<tr><td><a href='/param'>/param</a></td>"
 "<td>Parameter page</td></tr>"
 "<tr><td><a href='/info'>/info</a></td>"
 "<td>Information page</td></tr>"
 "<tr><td><a href='/close'>/close</a></td>"
 "<td>Close the captiveportal popup,configportal will remain active</td></tr>"
 "<tr><td><a href='/exit'>/exit</a></td>"
 "<td>Exit Config Portal, configportal will close</td></tr>"
 "<tr><td><a href='/restart'>/restart</a></td>"
 "<td>Reboot the device</td></tr>"
 "<tr><td><a href='/erase'>/erase</a></td>"
 "<td>Erase WiFi configuration and reboot Device. Device will not reconnect to a network until new WiFi configuration data is entered.</td></tr>"
 "</table>"
 "<p/>More information about WiFiManager at <a href='https://github.com/tzapu/WiFiManager'>https://github.com/tzapu/WiFiManager</a>.";

#ifdef WM_JSTEST
const char HTTP_JS[] PROGMEM = 
"<script>function postAjax(url, data, success) {"
"    var params = typeof data == 'string' ? data : Object.keys(data).map("
"            function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }"
"        ).join('&');"
"    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject(\"Microsoft.XMLHTTP\");"
"    xhr.open('POST', url);"
"    xhr.onreadystatechange = function() {"
"        if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }"
"    };"
"    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');"
"    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');"
"    xhr.send(params);"
"    return xhr;}"
"postAjax('/status', 'p1=1&p2=Hello+World', function(data){ console.log(data); });"
"postAjax('/status', { p1: 1, p2: 'Hello World' }, function(data){ console.log(data); });"
"</script>";
#endif

// Info html
#ifdef ESP32
	const char HTTP_INFO_esphead[]    PROGMEM = "<h3>esp32</h3><hr><dl>";
	const char HTTP_INFO_chiprev[]    PROGMEM = "<dt>Chip Rev</dt><dd>{1}</dd>";
  const char HTTP_INFO_lastreset[]    PROGMEM = "<dt>Last reset reason</dt><dd>CPU0: {1}<br/>CPU1: {2}</dd>";
  const char HTTP_INFO_aphost[]       PROGMEM = "<dt>Acccess Point Hostname</dt><dd>{1}</dd>";  
#else 
	const char HTTP_INFO_esphead[]    PROGMEM = "<h3>esp8266</h3><hr><dl>";
	const char HTTP_INFO_flashsize[]  PROGMEM = "<dt>Real Flash Size</dt><dd>{1} bytes</dd>";
	const char HTTP_INFO_fchipid[]    PROGMEM = "<dt>Flash Chip ID</dt><dd>{1}</dd>";
	const char HTTP_INFO_corever[]    PROGMEM = "<dt>Core Version</dt><dd>{1}</dd>";
	const char HTTP_INFO_bootver[]    PROGMEM = "<dt>Boot Version</dt><dd>{1}</dd>";
	const char HTTP_INFO_memsketch[]  PROGMEM = "<dt>Memory - Sketch Size</dt><dd>Used / Total bytes<br/>{1} / {2}";
	const char HTTP_INFO_memsmeter[]  PROGMEM = "<br/><progress value='{1}' max='{2}'></progress></dd>";
	const char HTTP_INFO_lastreset[]  PROGMEM = "<dt>Last reset reason</dt><dd>{1}</dd>";
#endif

const char HTTP_INFO_freeheap[]   PROGMEM = "<dt>Memory - Free Heap</dt><dd>{1} bytes available</dd>"; 
const char HTTP_INFO_wifihead[]   PROGMEM = "<br/><h3>WiFi</h3><hr>";
const char HTTP_INFO_uptime[]     PROGMEM = "<dt>Uptime</dt><dd>{1} Mins {2} Secs</dd>";
const char HTTP_INFO_chipid[]     PROGMEM = "<dt>Chip ID</dt><dd>{1}</dd>";
const char HTTP_INFO_idesize[]    PROGMEM = "<dt>Flash Size</dt><dd>{1} bytes</dd>";
const char HTTP_INFO_sdkver[]     PROGMEM = "<dt>SDK Version</dt><dd>{1}</dd>";
const char HTTP_INFO_cpufreq[]    PROGMEM = "<dt>CPU Frequency</dt><dd>{1}MHz</dd>";
const char HTTP_INFO_apip[]       PROGMEM = "<dt>Access Point IP</dt><dd>{1}</dd>";
const char HTTP_INFO_apmac[]      PROGMEM = "<dt>Access Point MAC</dt><dd>{1}</dd>";
const char HTTP_INFO_apssid[]     PROGMEM = "<dt>SSID</dt><dd>{1}</dd>";
const char HTTP_INFO_apbssid[]    PROGMEM = "<dt>BSSID</dt><dd>{1}</dd>";
const char HTTP_INFO_staip[]      PROGMEM = "<dt>Station IP</dt><dd>{1}</dd>";
const char HTTP_INFO_stagw[]      PROGMEM = "<dt>Station Gateway</dt><dd>{1}</dd>";
const char HTTP_INFO_stasub[]     PROGMEM = "<dt>Station Subnet</dt><dd>{1}</dd>";
const char HTTP_INFO_dnss[]       PROGMEM = "<dt>DNS Server</dt><dd>{1}</dd>";
const char HTTP_INFO_host[]       PROGMEM = "<dt>Hostname</dt><dd>{1}</dd>";
const char HTTP_INFO_stamac[]     PROGMEM = "<dt>Station MAC</dt><dd>{1}</dd>";
const char HTTP_INFO_conx[]       PROGMEM = "<dt>Connected</dt><dd>{1}</dd>";
const char HTTP_INFO_autoconx[]   PROGMEM = "<dt>Autoconnect</dt><dd>{1}</dd>";
const char HTTP_INFO_temp[]       PROGMEM = "<dt>Temperature</dt><dd>{1} C&deg; / {2} F&deg;</dd>";

// Strings
const char S_y[]                  PROGMEM = "Yes";
const char S_n[]                  PROGMEM = "No";
const char S_enable[]             PROGMEM = "Enabled";
const char S_disable[]            PROGMEM = "Disabled";
const char S_GET[]                PROGMEM = "GET";
const char S_POST[]               PROGMEM = "POST";
const char S_NA[]                 PROGMEM = "Unknown";
const char S_passph[]             PROGMEM = "********";
const char S_titlewifisaved[]     PROGMEM = "Credentials Saved";
const char S_titlewifisettings[]  PROGMEM = "Settings Saved";
const char S_titlewifi[]          PROGMEM = "Config ESP";
const char S_titleinfo[]          PROGMEM = "Info";
const char S_titleparam[]         PROGMEM = "Setup";
const char S_titleparamsaved[]    PROGMEM = "Setup Saved";
const char S_titleexit[]          PROGMEM = "Exit";
const char S_titlereset[]         PROGMEM = "Reset";
const char S_titleerase[]         PROGMEM = "Erase";
const char S_titleclose[]         PROGMEM = "Close";
const char S_options[]            PROGMEM = "options";
const char S_nonetworks[]         PROGMEM = "No networks found. Refresh to scan again.";
const char S_staticip[]           PROGMEM = "Static IP";
const char S_staticgw[]           PROGMEM = "Static Gateway";
const char S_staticdns[]          PROGMEM = "Static DNS";
const char S_subnet[]             PROGMEM = "Subnet";
const char S_exiting[]            PROGMEM = "Exiting";
const char S_resetting[]          PROGMEM = "Module will reset in a few seconds.";
const char S_closing[]            PROGMEM = "You can close the page, portal will continue to run";
const char S_error[]              PROGMEM = "An Error Occured";
const char S_notfound[]           PROGMEM = "File Not Found\n\n";
const char S_uri[]                PROGMEM = "URI: ";
const char S_method[]             PROGMEM = "\nMethod: ";
const char S_args[]               PROGMEM = "\nArguments: ";
const char S_parampre[]           PROGMEM = "param_";

// debug strings
const char D_HR[]                 PROGMEM = "--------------------";

// END WIFI_MANAGER_OVERRIDE_STRINGS
#endif

// -----------------------------------------------------------------------------------------------
// DO NOT EDIT BELOW THIS LINE

const uint8_t _nummenutokens = 9;
const char * const _menutokens[9] PROGMEM = {
    "wifi",
    "wifinoscan",
    "info",
    "param",
    "close",
    "restart",
    "exit",
    "erase",
    "sep"
};

const char R_root[]               PROGMEM = "/";
const char R_wifi[]               PROGMEM = "/wifi";
const char R_wifinoscan[]         PROGMEM = "/0wifi";
const char R_wifisave[]           PROGMEM = "/wifisave";
const char R_info[]               PROGMEM = "/info";
const char R_param[]              PROGMEM = "/param";
const char R_paramsave[]          PROGMEM = "/paramsave";
const char R_restart[]            PROGMEM = "/restart";
const char R_exit[]               PROGMEM = "/exit";
const char R_close[]              PROGMEM = "/close";
const char R_erase[]              PROGMEM = "/erase"; 
const char R_status[]             PROGMEM = "/status";


//Strings
const char S_ip[]                 PROGMEM = "ip";
const char S_gw[]                 PROGMEM = "gw";
const char S_sn[]                 PROGMEM = "sn";
const char S_dns[]                PROGMEM = "dns";

// softap ssid default prefix
#ifdef ESP8266
	const char S_ssidpre[]        PROGMEM = "ESP";
#elif defined(ESP32)
	const char S_ssidpre[]        PROGMEM = "ESP32";
#else
	const char S_ssidpre[]        PROGMEM = "WM";
#endif

//Tokens
//@todo consolidate and reduce
const char T_ss[]                 PROGMEM = "{"; // token start sentinel
const char T_es[]                 PROGMEM = "}"; // token end sentinel
const char T_1[]                  PROGMEM = "{1}"; // @token 1
const char T_2[]                  PROGMEM = "{2}"; // @token 2
const char T_v[]                  PROGMEM = "{v}"; // @token v
const char T_I[]                  PROGMEM = "{I}"; // @token I
const char T_i[]                  PROGMEM = "{i}"; // @token i
const char T_n[]                  PROGMEM = "{n}"; // @token n
const char T_p[]                  PROGMEM = "{p}"; // @token p
const char T_t[]                  PROGMEM = "{t}"; // @token t
const char T_l[]                  PROGMEM = "{l}"; // @token l
const char T_c[]                  PROGMEM = "{c}"; // @token c
const char T_e[]                  PROGMEM = "{e}"; // @token e
const char T_q[]                  PROGMEM = "{q}"; // @token q
const char T_r[]                  PROGMEM = "{r}"; // @token r
const char T_R[]                  PROGMEM = "{R}"; // @token R
const char T_h[]                  PROGMEM = "{h}"; // @token h

// http
const char HTTP_HEAD_CL[]         PROGMEM = "Content-Length";
const char HTTP_HEAD_CT[]         PROGMEM = "text/html";
const char HTTP_HEAD_CT2[]        PROGMEM = "text/plain";
const char HTTP_HEAD_CORS[]       PROGMEM = "Access-Control-Allow-Origin";
const char HTTP_HEAD_CORS_ALLOW_ALL[]  PROGMEM = "*";

const char * const WIFI_STA_STATUS[] PROGMEM
{
  "WL_IDLE_STATUS",     // 0 STATION_IDLE
  "WL_NO_SSID_AVAIL",   // 1 STATION_NO_AP_FOUND
  "WL_SCAN_COMPLETED",  // 2
  "WL_CONNECTED",       // 3 STATION_GOT_IP
  "WL_CONNECT_FAILED",  // 4 STATION_CONNECT_FAIL, STATION_WRONG_PASSWORD(NI)
  "WL_CONNECTION_LOST", // 5
  "WL_DISCONNECTED",    // 6 
  "WL_STATION_WRONG_PASSWORD" // 7 KLUDGE 
};

#ifdef ESP32
const char * const AUTH_MODE_NAMES[] PROGMEM
{
    "OPEN",
    "WEP",             
    "WPA_PSK",         
    "WPA2_PSK",        
    "WPA_WPA2_PSK",    
    "WPA2_ENTERPRISE", 
    "MAX"
};
#elif defined(ESP8266)
const char * const AUTH_MODE_NAMES[] PROGMEM
{
    "",
    "",
    "WPA_PSK",      // 2 ENC_TYPE_TKIP
    "",
    "WPA2_PSK",     // 4 ENC_TYPE_CCMP
    "WEP",          // 5 ENC_TYPE_WEP
    "",
    "OPEN",         //7 ENC_TYPE_NONE
    "WPA_WPA2_PSK", // 8 ENC_TYPE_AUTO
};
#endif

const char* const WIFI_MODES[] PROGMEM = { "NULL", "STA", "AP", "STA+AP" };


#ifdef ESP32
// as 2.5.2
// typedef struct {
//     char                  cc[3];   /**< country code string */
//     uint8_t               schan;   /**< start channel */
//     uint8_t               nchan;   /**< total channel number */
//     int8_t                max_tx_power;   /**< This field is used for getting WiFi maximum transmitting power, call esp_wifi_set_max_tx_power to set the maximum transmitting power. */
//     wifi_country_policy_t policy;  /**< country policy */
// } wifi_country_t;
const wifi_country_t WM_COUNTRY_US{"US",1,11,CONFIG_ESP32_PHY_MAX_TX_POWER,WIFI_COUNTRY_POLICY_AUTO};
const wifi_country_t WM_COUNTRY_CN{"CN",1,13,CONFIG_ESP32_PHY_MAX_TX_POWER,WIFI_COUNTRY_POLICY_AUTO};
const wifi_country_t WM_COUNTRY_JP{"JP",1,14,CONFIG_ESP32_PHY_MAX_TX_POWER,WIFI_COUNTRY_POLICY_AUTO};
#elif defined(ESP8266)
// typedef struct {
//     char cc[3];               /**< country code string */
//     uint8_t schan;            /**< start channel */
//     uint8_t nchan;            /**< total channel number */
//     uint8_t policy;           /**< country policy */
// } wifi_country_t;
const wifi_country_t WM_COUNTRY_US{"US",1,11,WIFI_COUNTRY_POLICY_AUTO};
const wifi_country_t WM_COUNTRY_CN{"CN",1,13,WIFI_COUNTRY_POLICY_AUTO};
const wifi_country_t WM_COUNTRY_JP{"JP",1,14,WIFI_COUNTRY_POLICY_AUTO};
#endif

#endif