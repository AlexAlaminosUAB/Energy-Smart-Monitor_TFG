#ifndef LED_BUILTIN
  #define LED_BUILTIN 2 // ESP32 DOES NOT DEFINE LED_BUILTIN
#endif

#define DEVICE_NAME "EnergySmartMonitor_01"

#define ADC_INPUT 35
#define ADC_INPUT2 34

#define WIFI_TIMEOUT 20000
#define WIFI_RECOVER_TIME_MS 20000

#define emonTxV3 1

#define REDIS_CONNECT_DELAY 200
#define REDIS_CONNECT_TIMEOUT 20000

#if CONFIG_FREERTOS_UNICORE
  #define ARDUINO_RUNNING_CORE 0
#else
  #define ARDUINO_RUNNING_CORE 1
#endif