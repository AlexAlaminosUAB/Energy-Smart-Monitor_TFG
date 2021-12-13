#include <Arduino.h>
#include "configuration.h"
#include <Redis.h>
#include <ArduinoJson.h>
#include <WiFiClient.h>
#include <AsyncTCP.h>

extern int LED;

Redis *redisOBJ = nullptr;
WiFiClient redisConn2;
AsyncClient redisConn;

extern float measurements[];
extern unsigned long measurementsTime[];

extern unsigned long contTestElementsRT;

String measurementSingleSTR = "";
String measurementsSTR = "";
String generationSingleSTR = "";
String generationHourlySTR = "";

int contStrLpush = 2; 

bool lastUploadErr = false;

extern float rmsAmps;
extern float rmsWatts;
extern float kW_peak;
extern float kWh_acum;

extern String global_RedisSrvIP;
extern String global_RedisSrvPORT;
extern String global_RedisSrvPASS;

extern bool measure_initValuesSetted;

extern float kWh_acumGeneration;
extern float rmsWattsGeneration;
extern float kW_peakGeneration;
extern float rmsAmpsGeneration;

extern float kWh_acumGeneration_upd;

extern byte currentHour;

void redisConnect(void * parameter){
  for(;;){
   
      if(!WiFi.isConnected()){
          digitalWrite(LED, HIGH);
          vTaskDelay(1000 / portTICK_PERIOD_MS);
          continue;
      }            

      if(measure_initValuesSetted){
          vTaskDelay(500 / portTICK_PERIOD_MS);
          continue;
      }

      long startAttemptTime = millis();

      
      while (!redisConn2.connect(global_RedisSrvIP.c_str(), global_RedisSrvPORT.toInt()) && millis() - startAttemptTime < REDIS_CONNECT_TIMEOUT){
          vTaskDelay(REDIS_CONNECT_DELAY);
      }
      

      if(!redisConn2.connected()){
          //redisConn2.stop();
          digitalWrite(LED, HIGH);
          Serial.println(F("Redis db connection timeout... Retry in 30s."));
          vTaskDelay(30000 / portTICK_PERIOD_MS);
      }
      
      redisOBJ = new Redis(redisConn2);
      auto connRet = redisOBJ->authenticate(global_RedisSrvPASS.c_str());
      if (connRet == RedisSuccess){
          Serial.println(F("Connected to the Redis server!"));
          digitalWrite(LED, LOW);

          if(kWh_acum == 0 and kW_peak == 0){
            //Get Redis JSON
            StaticJsonDocument<128> docRecv;
            deserializeJson(docRecv, redisOBJ->get("energyConsumption"));
            JsonObject objRecv = docRecv.as<JsonObject>();
            kWh_acum = objRecv["kWh_acum"];
            kW_peak = objRecv["kW_peak"];
            measure_initValuesSetted = true;
            docRecv.clear();
          }
          
      }else{
          //redisConn2.flush();
          //redisConn2.stop();
          Serial.printf("Failed to authenticate to the Redis server! Errno: %d\n", (int)connRet);
          digitalWrite(LED, HIGH);
          continue;
      }

      Serial.println(F("Redis db Connected!"));
    
  }
}


void uploadMultipleConsumption(void * parameter){

  Serial.print("Heap al inicio de la tarea: "); 
  Serial.println(xPortGetFreeHeapSize());

  Serial.println(F(""));
  Serial.print(F("Watts: "));
  Serial.print(measurements[29]);
  Serial.print(F("W"));
  Serial.println(F(""));

  if(!WiFi.isConnected()){
    Serial.println(F("Redis db (Multiple Consumption) Failed to upload data, no connection..."));
    vPortFree(NULL);
    vTaskDelete(NULL);
  }

  redisConn.connect(global_RedisSrvIP.c_str(), global_RedisSrvPORT.toInt());
  redisConn.setNoDelay(true);

  if(!lastUploadErr){
    contStrLpush = 2; 
    measurementsSTR = "";
  }else{
    contTestElementsRT+=LOCAL_MEASUREMENTS;  
  }
  
  StaticJsonDocument<64> doc;
  for (short i = 0; i < LOCAL_MEASUREMENTS; i++){
    doc["kW"] = measurements[i];
    doc["epochTime"] = measurementsTime[i];
    
    //String jsonStr="{\"kW\":"+String(measurements[i])+",\"epochTime\":"+String(measurementsTime[i])+"}";

    String jsonStr;
    serializeJson(doc, jsonStr);

    measurementsSTR += "$"+String(jsonStr.length())+"\r\n"+jsonStr+"\r\n";
    contStrLpush++;

    doc.clear();

    contTestElementsRT++;
  }

  int getState = redisConn.write( String(String("*"+String(contStrLpush)+"\r\n")+"$5\r\n"+"LPUSH\r\n"+"$6\r\n"+"rtList\r\n"+measurementsSTR).c_str() );

  Serial.println(getState);

  vTaskDelay(10);

  redisConn.close(true);
  redisConn.stop();
  redisConn.abort();
  redisConn.free();
  
  if(getState==0){
    lastUploadErr = true;
    contTestElementsRT-=LOCAL_MEASUREMENTS;
  }else{
    lastUploadErr = false;
  }

  Serial.println(F(""));
  Serial.print(F("contTestElementsRT: "));
  Serial.print(contTestElementsRT);
  Serial.println(F(""));
  
  Serial.print("Heap antes de vPortFree(NULL): "); 
  Serial.println(xPortGetFreeHeapSize());
  
  vPortFree(NULL);

  Serial.print("Heap despues de vPortFree(NULL): "); 
  Serial.println(xPortGetFreeHeapSize());

  vTaskDelete(NULL);
}

void uploadSingleConsumption(void * parameter){

  Serial.print("Heap al inicio de la tarea: "); 
  Serial.println(xPortGetFreeHeapSize());
  
  if(!WiFi.isConnected()){
      Serial.println(F("Redis db (1Measurement) Failed to upload data, no connection..."));
      vPortFree(NULL);
      vTaskDelete(NULL);
  }

  redisConn.connect(global_RedisSrvIP.c_str(), global_RedisSrvPORT.toInt());
  redisConn.setNoDelay(true);

  char *ptr = (char*)pvPortMalloc(128 * sizeof(char));
  strcpy(ptr, String("{\"kW_now\":"+String(rmsWatts, 6)+",\"kWh_acum\":"+String(kWh_acum, 6)+",\"kW_peak\":"+String(kW_peak, 6)+",\"amps_now\":"+String(rmsAmps, 6)+"}").c_str());

  measurementSingleSTR = "$"+String(strlen(ptr))+"\r\n"+ptr+"\r\n";

  int getState = redisConn.write( String(String("*3\r\n")+"$3\r\n"+"SET\r\n"+"$17\r\n"+"energyConsumption\r\n"+measurementSingleSTR).c_str() );
  
  Serial.print("Heap antes de vPortFree(NULL): "); 
  Serial.println(xPortGetFreeHeapSize());

  vPortFree(ptr);

  Serial.print("Heap despues de vPortFree(NULL): "); 
  Serial.println(xPortGetFreeHeapSize());

  vTaskDelete(NULL);
}

void uploadGeneration(void * parameter){

  Serial.print("Heap al inicio de la tarea: "); 
  Serial.println(xPortGetFreeHeapSize());
  
  if(!WiFi.isConnected()){
      Serial.println(F("Redis db Generation (1Measurement) Failed to upload data, no connection..."));
      vPortFree(NULL);
      vTaskDelete(NULL);
  }

  redisConn.connect(global_RedisSrvIP.c_str(), global_RedisSrvPORT.toInt());
  redisConn.setNoDelay(true);

  char *ptr = (char*)pvPortMalloc(128 * sizeof(char));
  strcpy(ptr, String("{\"kW_nowGeneration\":"+String(rmsWattsGeneration, 6)+",\"kWh_acumGeneration\":"+String(kWh_acumGeneration, 6)+",\"kW_peakGeneration\":"+String(kW_peakGeneration, 6)+",\"amps_nowGeneration\":"+String(rmsAmpsGeneration, 6)+"}").c_str()); 
    
  generationSingleSTR = "$"+String(strlen(ptr))+"\r\n"+ptr+"\r\n";

  int getState = redisConn.write( String(String("*3\r\n")+"$3\r\n"+"SET\r\n"+"$20\r\n"+"solarPanelGeneration\r\n"+generationSingleSTR).c_str() );
 
  Serial.print("Heap antes de vPortFree(NULL): "); 
  Serial.println(xPortGetFreeHeapSize());

  vPortFree(ptr);

  Serial.print("Heap despues de vPortFree(NULL): "); 
  Serial.println(xPortGetFreeHeapSize());

  vTaskDelete(NULL);
}

void uploadGenerationHourly(void * parameter){

  Serial.print("Heap al inicio de la tarea: "); 
  Serial.println(xPortGetFreeHeapSize());
  
  if(!WiFi.isConnected()){
      Serial.println(F("Redis db Generation (hourly) Failed to upload data, no connection..."));
      vPortFree(NULL);
      vTaskDelete(NULL);
  }

  redisConn.connect(global_RedisSrvIP.c_str(), global_RedisSrvPORT.toInt());
  redisConn.setNoDelay(true);

  char *ptr = (char*)pvPortMalloc(64 * sizeof(char)); 
  
  if( currentHour == 0 ){
    strcpy(ptr, String("{\"kWh\":"+String(kWh_acumGeneration_upd, 6)+",\"time\":\"23:00\"}").c_str()); 
  }else{
    strcpy(ptr, String("{\"kWh\":"+String(kWh_acumGeneration_upd, 6)+",\"time\":\""+String(currentHour-1)+":00\"}").c_str()); 
  }

  generationHourlySTR += "$"+String(strlen(ptr))+"\r\n"+ptr+"\r\n";

  

  int getState = redisConn.write( String(String("*3\r\n")+"$5\r\n"+"LPUSH\r\n"+"$19\r\n"+"generationTodayList\r\n"+generationHourlySTR).c_str() ); 

  if(getState != 0){
    generationHourlySTR = "";
  }

  Serial.print("Heap antes de vPortFree(NULL): "); 
  Serial.println(xPortGetFreeHeapSize());

  vPortFree(ptr);

  Serial.print("Heap despues de vPortFree(NULL): "); 
  Serial.println(xPortGetFreeHeapSize());

  vTaskDelete(NULL);
}
