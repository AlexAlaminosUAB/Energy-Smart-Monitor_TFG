#include <Arduino.h>
#include "EmonLib.h"
#include <WiFi.h>
#include <driver/adc.h>
#include "configuration.h"

#include "wifiConnection.h"
#include "ntpTime.h"
#include "electricitySensorMeasure.h"

#include <WiFiManager.h>
#include <Preferences.h>
Preferences preferences;

#include <Ticker.h>
Ticker ticker;

EnergyMonitor emon1;

float measurements[LOCAL_MEASUREMENTS];
unsigned long measurementsTime[LOCAL_MEASUREMENTS];
unsigned char measureIndex = 0;

unsigned long contTestElementsRT = 0;

float rmsAmps = 0.0;
float rmsWatts = 0.0;
float kW_peak = 0.0;
float kWh_acum = 0.0;

bool timeSetted = false;
bool measure_initValuesSetted = false;

int LED = LED_BUILTIN;

int btnResetSettings = 0;

String global_RedisSrvIP = "";
String global_RedisSrvPORT = "";
String global_RedisSrvPASS = "";
String global_timeUTC = "";
String global_voltValue = "";
String global_wifiSSID = "";
String global_wifiPASS = "";

EnergyMonitor emon2;
unsigned char measureIndexGeneration = 0;

byte currentHour;
byte nextHourUpload;

float kWh_acumGeneration = 0.0;
float kWh_acumGeneration_upd = 0.0;
float rmsWattsGeneration = 0.0;
float kW_peakGeneration = 0.0;
float rmsAmpsGeneration = 0.0;


void tick(){
  digitalWrite(LED, !digitalRead(LED));
}

//gets called when WiFiManager enters configuration mode
void configModeCallback (WiFiManager *myWiFiManager){
  //Serial.println("Entered config mode");
  WiFi.softAPIP();
  //Serial.println(WiFi.softAPIP());
  //if you used auto generated SSID, print it
  myWiFiManager->getConfigPortalSSID();
  //Serial.println(myWiFiManager->getConfigPortalSSID());
  //entered config mode, make led toggle faster
  ticker.attach(0.1, tick);
}

void setup(){

  preferences.begin("prefsESP", false);

  Serial.begin(115200);
 
  pinMode(LED, OUTPUT);

  pinMode(btnResetSettings, INPUT);
  ticker.attach(1.0, tick);

  int contResetSettingsBtn = 0;
  for(int i=0; i<50; i++){
    delay(150);
    if(digitalRead(btnResetSettings)==LOW){
      contResetSettingsBtn++;
    }
  }

  if(contResetSettingsBtn>=5){
    //Serial.println("Reseteando ajustes ESP!!");
    preferences.putString("redisSrvIP", "");
    preferences.putString("redisSrvPORT", "");
    preferences.putString("redisSrvPASS", "");
    preferences.putString("timeUTC", "");
    preferences.putString("voltValue", "");

    preferences.putString("wifiSSID", "");
    preferences.putString("wifiPASS", "");
    
    WiFiManager wm;
    wm.resetSettings();
  }

  global_RedisSrvIP = preferences.getString("redisSrvIP", "");
  global_RedisSrvPORT = preferences.getString("redisSrvPORT", "");
  global_RedisSrvPASS = preferences.getString("redisSrvPASS", "");
  global_timeUTC = preferences.getString("timeUTC", "");
  global_voltValue = preferences.getString("voltValue", "");
  global_wifiSSID = preferences.getString("wifiSSID", "");
  global_wifiPASS = preferences.getString("wifiPASS", "");

  Serial.println(F("SSID: "));
  Serial.println(global_wifiSSID);
  Serial.println(" ");
  Serial.println(F("PASS: "));
  Serial.println(global_wifiPASS);
  Serial.println(" ");

  if(global_wifiSSID != "" and global_wifiPASS != ""){
      
      ticker.detach();
      digitalWrite(LED, LOW);
      
      //Part de configuració de l'ADC
      adc1_config_channel_atten(ADC1_CHANNEL_0, ADC_ATTEN_DB_11);
      analogReadResolution(ADC_BITS);
      pinMode(ADC_INPUT, INPUT);
      pinMode(ADC_INPUT2, INPUT);
    
      //Inicialització dels sensors de corrent
      emon1.current(ADC_INPUT, 16.67);
      emon2.current(ADC_INPUT2, 16.67);  
  
      //Task WIFI, conecta i comproba la conexio wifi, en cas de perdre la conexio wifi, torna a conectar.
      xTaskCreatePinnedToCore(wifiConnect,"wifiConnect",4096,NULL,1,NULL,ARDUINO_RUNNING_CORE);
    
      //Task per conectarse a la base de dades, en cas de perdre la connexió, torna a conectar-se.
      xTaskCreate(redisConnect,"redisConnect",8192,NULL,5,NULL);
    
      //Task per mesurar el consum d'energia (amps) cada segon.
      xTaskCreate(measureConsumption,"measureConsumption",4096,NULL,4,NULL);

      //Task per mesurar la generació d'energia (amps).
      xTaskCreate(measureGeneration,"measureGeneration",4096,NULL,6,NULL);
    
      //Task per sincronitzar el rellotge.
      xTaskCreate(updateTimeNTP,"updateTimeNTP",4096,NULL,1,NULL);
    
  }else{

      WiFiManager wm;

      WiFi.mode(WIFI_STA); // explicitly set mode, esp defaults to STA+AP
      
      //set callback that gets called when connecting to previous WiFi fails, and enters Access Point mode
      wm.setAPCallback(configModeCallback);
      
      WiFiManagerParameter custom_redis_server_ip("serverIP", "Redis server IP", "", 15, " pattern='^([0-9]{1,3}\.){3}[0-9]{1,3}$' required ");
      wm.addParameter(&custom_redis_server_ip);
      WiFiManagerParameter custom_redis_server_port("serverPORT", "Redis server Port", "6379", 5, " value='6379' type='number' max='65535' min='0' required ");
      wm.addParameter(&custom_redis_server_port);
      WiFiManagerParameter custom_redis_server_pass("serverPASS", "Redis server Password", "", 25, " type='password' ");
      wm.addParameter(&custom_redis_server_pass);
      WiFiManagerParameter custom_time_utc2("<br><br><label for=\"timeUTC\">Select your time zone</label><select name=\"timeUTC\"> <option value=\"-12\">(GMT -12:00) Eniwetok, Kwajalein</option> <option value=\"-11\">(GMT -11:00) Midway Island, Samoa</option> <option value=\"-10\">(GMT -10:00) Hawaii</option> <option value=\"-9.5\">(GMT -9:30) Taiohae</option> <option value=\"-9\">(GMT -9:00) Alaska</option> <option value=\"-8\">(GMT -8:00) Pacific Time (US &amp; Canada)</option> <option value=\"-7\">(GMT -7:00) Mountain Time (US &amp; Canada)</option> <option value=\"-6\">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option> <option value=\"-5\">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option> <option value=\"-4.5\">(GMT -4:30) Caracas</option> <option value=\"-4\">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option> <option value=\"-3.5\">(GMT -3:30) Newfoundland</option> <option value=\"-3\">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option> <option value=\"-2\">(GMT -2:00) Mid-Atlantic</option> <option value=\"-1\">(GMT -1:00) Azores, Cape Verde Islands</option> <option value=\"+1\">(GMT +1:00) Western Europe Time, London, Lisbon, Casablanca</option> <option value=\"+2\" selected=\"selected\">(GMT +2:00) Brussels, Copenhagen, Madrid, Paris</option> <option value=\"+2\">(GMT +2:00) Kaliningrad, South Africa</option> <option value=\"+3\">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option> <option value=\"+3.5\">(GMT +3:30) Tehran</option> <option value=\"+4\">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option> <option value=\"+4.5\">(GMT +4:30) Kabul</option> <option value=\"+5\">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option> <option value=\"+5.5\">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option> <option value=\"+5.75\">(GMT +5:45) Kathmandu, Pokhara</option> <option value=\"+6\">(GMT +6:00) Almaty, Dhaka, Colombo</option> <option value=\"+6.5\">(GMT +6:30) Yangon, Mandalay</option> <option value=\"+7\">(GMT +7:00) Bangkok, Hanoi, Jakarta</option> <option value=\"+8\">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option> <option value=\"+8.75\">(GMT +8:45) Eucla</option> <option value=\"+9\">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option> <option value=\"+9.5\">(GMT +9:30) Adelaide, Darwin</option> <option value=\"+10\">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option> <option value=\"+10.5\">(GMT +10:30) Lord Howe Island</option> <option value=\"+11\">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option> <option value=\"+11.5\">(GMT +11:30) Norfolk Island</option> <option value=\"+12\">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option> <option value=\"+12.75\">(GMT +12:45) Chatham Islands</option> <option value=\"+13\">(GMT +13:00) Apia, Nukualofa</option> <option value=\"+14\">(GMT +14:00) Line Islands, Tokelau</option> </select>");
      wm.addParameter(&custom_time_utc2);
      WiFiManagerParameter custom_time_utc("timeUTC", "", "", 25, " type='number' min='-12' max='14' style='display:none;' disabled ");
      wm.addParameter(&custom_time_utc);
      WiFiManagerParameter custom_volt_value("voltValue", "Voltage of your home", "230", 3, " value='230' type='number' max='250' min='100' required ");
      wm.addParameter(&custom_volt_value);
  
      //fetches ssid and pass and tries to connect
      //if it does not connect it starts an access point with the specified name
      //here  "AutoConnectAP"
      //and goes into a blocking loop awaiting configuration
      if (!wm.autoConnect(DEVICE_NAME, "")) {
        //Serial.println("failed to connect and hit timeout");
        //reset and try again, or maybe put it to deep sleep
        ESP.restart();
        delay(1000);
      }

      String redisServerIP = custom_redis_server_ip.getValue();
      String redisServerPORT = custom_redis_server_port.getValue();
      String redisServerPASS = custom_redis_server_pass.getValue();
      String timeUTC = custom_time_utc.getValue();
      String voltValue = custom_volt_value.getValue();
      
      String gettedWiFiSSID = wm.getWiFiSSID(true);
      String gettedWiFiPass = wm.getWiFiPass(true);
      
      Serial.print(F("Stored SSID: "));
      Serial.println(gettedWiFiSSID);
      Serial.print(F("Stored passphrase: "));
      Serial.println(gettedWiFiPass);

      if(redisServerIP!="" and redisServerPORT!=""){
        preferences.putString("redisSrvIP", redisServerIP);
        preferences.putString("redisSrvPORT", redisServerPORT);
        preferences.putString("redisSrvPASS", redisServerPASS);
        preferences.putString("timeUTC", timeUTC);
        preferences.putString("voltValue", voltValue);
        preferences.putString("wifiSSID", gettedWiFiSSID);
        preferences.putString("wifiPASS", gettedWiFiPass);
        Serial.println(F("Dades desades a la memoria flash!"));
      }else{
        Serial.println(F("NO s'han pogut desar les dades a la memoria flash!"));
      }


      ticker.detach();
      digitalWrite(LED, LOW);
      
      delay(1000);
      ESP.restart();
      delay(1000);

  }

  preferences.end();
  
}

void loop(){
  vTaskDelay(10000 / portTICK_PERIOD_MS);
}
