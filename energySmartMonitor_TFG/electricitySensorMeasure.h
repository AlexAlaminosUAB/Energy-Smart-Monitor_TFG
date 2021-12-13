#include <Arduino.h>
#include "EmonLib.h"
#include "configuration.h"
#include "uploadDataRedis.h"

extern EnergyMonitor emon1;
extern float measurements[];
extern unsigned long measurementsTime[];
float measurementsLocal[LOCAL_MEASUREMENTS];
unsigned long measurementsTimeLocal[LOCAL_MEASUREMENTS];
extern unsigned char measureIndex;

extern float rmsAmps;
extern float rmsWatts;
extern float kW_peak;
extern float kWh_acum;

extern bool timeSetted;
extern bool measure_initValuesSetted;

extern String global_voltValue;

extern EnergyMonitor emon2;
extern unsigned char measureIndexGeneration;

extern byte currentHour;
extern byte nextHourUpload;

extern float kWh_acumGeneration;
extern float rmsWattsGeneration;
extern float kW_peakGeneration;
extern float rmsAmpsGeneration;
extern float kWhf_acumGeneration_upd;

unsigned long epochTimeNow = 0;

unsigned long epochTimeOLD = 0;

bool gettedTimeOK = false;

int warningETime=0;

void measureConsumption(void * parameter){
  for(;;){
    Serial.println(F("Measuring Consumption..."));
    long start = millis();
    
    if(timeSetted){
      epochTimeNow = getEpoch();
      gettedTimeOK = true;
    }else{
      Serial.println(F("Time not setted yet!"));  
    }
    
    rmsAmps = emon1.calcIrms(1676);
    rmsWatts = (rmsAmps * global_voltValue.toFloat())/1000;

    if(epochTimeNow!=epochTimeOLD and gettedTimeOK){
        measurementsLocal[measureIndex] = rmsWatts;
        measurementsTimeLocal[measureIndex] = epochTimeNow;

        measureIndex++;
    }else{
      Serial.println(F("WARNING!! epochTimeNow==epochTimeOLD")); 
      //Si salta esto quiere decir que se puede solucionar con un if epochTimeNow==epochTimeOLD pues el epochTime a insertar sera +1 o -1 (Depende de si se atrasa la tarea que es lo que yo creo, o si se adelanta debido a la planificacion y actualizacion del reloj)
      warningETime++;
      Serial.println("");
      Serial.print(F("warningETime: "));
      Serial.print(warningETime);
      Serial.println("");
    }
    
    epochTimeOLD=epochTimeNow;

    if(measure_initValuesSetted){
      kWh_acum += rmsWatts*0.0002777777778;

      if(kW_peak < rmsWatts){
        kW_peak = rmsWatts;
      }
    }

    if(measureIndex%9 == 0 and measure_initValuesSetted and timeSetted){
      xTaskCreate(uploadSingleConsumption, "uploadSingleConsumption", 8192, NULL, tskIDLE_PRIORITY, NULL);
    }

    if(measureIndex == LOCAL_MEASUREMENTS){

      for(int i=0; i<LOCAL_MEASUREMENTS; i++){
        measurements[i]=measurementsLocal[i];
        measurementsTime[i]=measurementsTimeLocal[i];
      }
      
      Serial.println(F("Data!"));
      xTaskCreate(uploadMultipleConsumption, "uploadMultipleConsumption", 8192, NULL, 5, NULL);
      measureIndex = 0;
    }

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

    if(timeSetted){
      measureIndexGeneration++;
    }else{
      Serial.println(F("Time not setted yet!"));  
    }

    if(measureIndexGeneration%5 == 0){
      xTaskCreate(uploadGeneration, "uploadGeneration", 8192, NULL, tskIDLE_PRIORITY, NULL);
      measureIndexGeneration = 0;
    }

    if(currentHour == nextHourUpload and timeSetted){
      Serial.println(F("Data Genration Hourly upload!"));

      kWh_acumGeneration_upd = kWh_acumGeneration;

      if(currentHour == 0){
          kW_peakGeneration = 0.0;
          kW_peak = 0.0;
          kWh_acumGeneration = 0.0;
          kWh_acum = 0.0;
      }

      if(currentHour == 23){
        nextHourUpload = 0;
      }else{
        nextHourUpload = currentHour+1;
      }

      kWh_acumGeneration = 0.0;

      xTaskCreate(uploadGenerationHourly, "uploadGenerationHourly", 8192, NULL, tskIDLE_PRIORITY, NULL);

    }

    long end = millis();

    vTaskDelay((60000-(end-start)) / portTICK_PERIOD_MS);
  }    
}
