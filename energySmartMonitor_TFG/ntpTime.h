#include <Arduino.h>
#include <WiFi.h>
#include <NTPClient.h>
#include <WiFiUdp.h>

extern bool timeSetted;
extern String global_timeUTC;
extern byte currentHour;
extern byte nextHourUpload;

WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP); //Probar tal como esta el codigo ahora para ver si funciona y da la hora adecuada y a ver si soluciona el problema que en generation todayList no aparezcan mas o no guarde mas de las 22:00h.

void updateTimeNTP(void * parameter){
  for(;;){
    if(!WiFi.isConnected()){
        vTaskDelay(10*1000 / portTICK_PERIOD_MS);
        continue;
    }

    if(!timeSetted){
      timeClient.begin();
      int timeOffsetTemp = global_timeUTC.toInt()*3600;
      timeClient.setTimeOffset(timeOffsetTemp);
    }

    Serial.println(F("Updating Time..."));
    timeClient.update();

    currentHour = timeClient.getHours();
    if(!timeSetted){
      if(currentHour==23){
        nextHourUpload = 0;
      }else{
        nextHourUpload = currentHour+1;
      }
    }
    
    Serial.println(F("Time Updated!"));
    timeSetted = true;

    vTaskDelay(NTP_UPDATE_INTERVAL_MS / portTICK_PERIOD_MS);
  }
}

unsigned long getEpoch(){
  return (timeClient.getEpochTime());
}
