#include <Arduino.h>
#include "configuration.h"
#include <Redis.h>
#include <WiFiClient.h>

extern int LED;

extern String global_RedisSrvIP;
extern String global_RedisSrvPORT;
extern String global_RedisSrvPASS;

Redis *redisOBJ = nullptr;
WiFiClient redisConn2;

void redisConnect(void * parameter){
  for(;;){
   
      if(!WiFi.isConnected()){
          digitalWrite(LED, HIGH);
          vTaskDelay(1000 / portTICK_PERIOD_MS);
          continue;
      }            

      long startAttemptTime = millis();

      
      while (!redisConn2.connect(global_RedisSrvIP.c_str(), global_RedisSrvPORT.toInt()) && millis() - startAttemptTime < REDIS_CONNECT_TIMEOUT){
          vTaskDelay(REDIS_CONNECT_DELAY);
      }
      

      if(!redisConn2.connected()){
          digitalWrite(LED, HIGH);
          Serial.println(F("Redis db connection timeout... Retry in 30s."));
          vTaskDelay(30000 / portTICK_PERIOD_MS);
      }
      
      redisOBJ = new Redis(redisConn2);
      auto connRet = redisOBJ->authenticate(global_RedisSrvPASS.c_str());
      if (connRet == RedisSuccess){
          Serial.println(F("Connected to the Redis server!"));
          digitalWrite(LED, LOW);
          
      }else{
          Serial.printf("Failed to authenticate to the Redis server! Errno: %d\n", (int)connRet);
          digitalWrite(LED, HIGH);
          continue;
      }

      Serial.println(F("Redis db Connected!"));
    
  }
}
