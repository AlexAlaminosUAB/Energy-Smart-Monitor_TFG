#ifndef LED_BUILTIN
  #define LED_BUILTIN 2 // ESP32 DOES NOT DEFINE LED_BUILTIN
#endif

#define DEVICE_NAME "EnergySmartMonitor_01"

#define ADC_INPUT 35
#define ADC_INPUT2 34

#define WIFI_TIMEOUT 20000
#define WIFI_RECOVER_TIME_MS 20000

#define emonTxV3 1 //Revisar el uso de este define, teoricamente por defecto ya toma este y no deberia ser necesario, pero igualmente testear el funcionamiento sin este define

#define LOCAL_MEASUREMENTS 60

#define REDIS_CONNECT_DELAY 200
#define REDIS_CONNECT_TIMEOUT 20000

/*#define NTP_TIME_SYNC_ENABLED true
#define NTP_SERVER "pool.ntp.org"
#define NTP_OFFSET_SECONDS 3600*/
#define NTP_UPDATE_INTERVAL_MS 600000

//#define MAX_IT_ITERS 30

#if CONFIG_FREERTOS_UNICORE
  #define ARDUINO_RUNNING_CORE 0
#else
  #define ARDUINO_RUNNING_CORE 1
#endif
