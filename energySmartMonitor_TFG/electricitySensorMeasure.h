#include <Arduino.h>
#include "EmonLib.h"

#include "configuration.h"

extern EnergyMonitor emon1;

extern float rmsAmps;
extern float rmsWatts;
extern float kW_peak;
extern float kWh_acum;

extern String global_voltValue;

extern EnergyMonitor emon2;

extern float kWh_acumGeneration;
extern float rmsWattsGeneration;
extern float kW_peakGeneration;
extern float rmsAmpsGeneration;

void measureConsumption(void * parameter){
  for(;;){
    Serial.println(F("Measuring Consumption..."));
    long start = millis();
    
    rmsAmps = emon1.calcIrms(1676);
    rmsWatts = (rmsAmps * global_voltValue.toFloat())/1000;

    Serial.println("");
    Serial.print("rmsAmps: ");
    Serial.print(rmsAmps);
    Serial.println("");
    Serial.print("rmsWatts: ");
    Serial.print(rmsWatts);
    Serial.println("");

    kWh_acum += rmsWatts*0.0002777777778;

    if(kW_peak < rmsWatts){
      kW_peak = rmsWatts;
    }

    Serial.print("kWh_acum: ");
    Serial.print(kWh_acum);
    Serial.println("");
    Serial.print("kW_peak: ");
    Serial.print(kW_peak);
    Serial.println("");


    long end = millis();

    vTaskDelay((1000-(end-start)) / portTICK_PERIOD_MS);
  }    
}

void measureGeneration(void * parameter){
  for(;;){
    Serial.println(F("Measuring Generation..."));
    long start = millis();

    rmsAmpsGeneration = emon2.calcIrms(1676);
    rmsWattsGeneration = (rmsAmpsGeneration * global_voltValue.toFloat())/1000;
    
    kWh_acumGeneration += rmsWattsGeneration*0.01666666667; //(1h*60)/3600 Corresponents a aquest minut

    if(kW_peakGeneration<rmsWattsGeneration){
        kW_peakGeneration = rmsWattsGeneration;
    }

    Serial.println("");
    Serial.print("rmsAmpsGeneration: ");
    Serial.print(rmsAmpsGeneration);
    Serial.println("");
    Serial.print("rmsWattsGeneration: ");
    Serial.print(rmsWattsGeneration);
    Serial.println("");
    Serial.print("kWh_acumGeneration: ");
    Serial.print(kWh_acumGeneration);
    Serial.println("");
    Serial.print("kW_peakGeneration: ");
    Serial.print(kW_peakGeneration);
    Serial.println("");

    long end = millis();

    vTaskDelay((60000-(end-start)) / portTICK_PERIOD_MS);
  }    
}
