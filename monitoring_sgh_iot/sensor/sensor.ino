#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <DHT.h>

const char* ssid = "Apika";
const char* password = "88888888";
const char* server = "http://192.168.154.16:8000/api/sensor1/1";   // Sesuaikan dengan rute API Laravel
const int DHTPIN = D5;             // Pin data DHT11 terhubung ke pin D5 di NodeMCU
const int DHTTYPE = DHT11;         // Jenis sensor DHT
const String token = "24|IDj9Jh8qGj1QOfoK3jdImXnEa2uNedtqeX8fLvZf7caaa9d4";


DHT dht(DHTPIN, DHTTYPE);

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }

  dht.begin();
}

void loop() {
  delay(300000); // Tunggu 5 menit sebelum membaca sensor lagi

  // Baca data dari DHT11
  float humidity = dht.readHumidity();
  float temp = dht.readTemperature();

  if (isnan(humidity) || isnan(temp)) {
    Serial.println("Gagal membaca data dari DHT");
    return;
  }

  // Hubungkan ke API Laravel menggunakan URL lengkap
  WiFiClient client;
  HTTPClient http;

  http.begin(client, server);
  // http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");
  http.addHeader("Authorization", "Bearer " + String(token));

  // String postData = "humidity=" + String(humidity) + "&temp=" + String(temp);
  String postData = "{\"humidity\":" + String(humidity) + ",\"temp\":" + String(temp) + "}";

  Serial.println(server);
  Serial.println(postData);


  int httpCode = http.POST(postData);
  if (httpCode == HTTP_CODE_OK) {
    String response = http.getString();
    Serial.println(httpCode);
    Serial.println(response);
  } else {
    Serial.println("Error pada permintaan HTTP");
  }


  http.end();
}