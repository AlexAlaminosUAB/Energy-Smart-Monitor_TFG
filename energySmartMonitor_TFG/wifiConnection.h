#include <Arduino.h>
#include "WiFi.h"
#include "configuration.h"

extern int LED;

extern String global_wifiSSID;
extern String global_wifiPASS;

void wifiConnect(void * parameter){
  for(;;){
    if(WiFi.status() == WL_CONNECTED){
        vTaskDelay(10000 / portTICK_PERIOD_MS);
        continue;
    }

    Serial.println(F("Connecting WIFI"));

    WiFi.mode(WIFI_STA);
    WiFi.setHostname(DEVICE_NAME);
    WiFi.begin(global_wifiSSID.c_str(), global_wifiPASS.c_str());

    unsigned long startAttemptTime = millis();

    while (WiFi.status() != WL_CONNECTED && millis() - startAttemptTime < WIFI_TIMEOUT){}

    if(WiFi.status() != WL_CONNECTED){
        digitalWrite(LED, HIGH);
        Serial.println(F("Failed to Connect WIFI"));
        vTaskDelay(WIFI_RECOVER_TIME_MS / portTICK_PERIOD_MS);
    }

    WiFi.setSleep(false);

    Serial.println(F("WIFI Connected successfully"));
    Serial.println(WiFi.localIP());
    digitalWrite(LED, LOW);
  }
}
