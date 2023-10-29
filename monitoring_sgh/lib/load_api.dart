import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';

// Future<double> getSensorValueFromAPI() async {
//   try {
//     String? authToken = await getToken();
//     if (authToken != null) {
//       var url = Uri.parse('http://localhost:8000/api/sensorValue');
//       var response = await http.get(url, headers: {
//         'Authorization': 'Bearer $authToken',
//       });

//       if (response.statusCode == 200) {
//         return double.parse(response.body);
//       } else {
//         throw Exception('Failed to fetch sensorValue');
//       }
//     } else {
//       throw Exception('Token is not available');
//     }
//   } catch (e) {
//     print('Error: $e');
//     // Handle the error, you might return a default value here if needed
//     return 0.0;
//   }
// }
Future<List<dynamic>> getSensorValueFromAPI() async {
  try {
    String? authToken = await getToken();
    print(authToken);

    if (authToken != null) {
      var url = Uri.parse('http://localhost:8000/api/sensorValue');
      var response = await http.get(url, headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $authToken',
      });

      if (response.statusCode == 200) {
        try {
          var jsonResponse = json.decode(response.body);
          var sensorValue = jsonResponse['temp'];
          var sensorName = jsonResponse['sensor'];
          print(jsonResponse);
          return [sensorValue.toDouble(), sensorName];
        } catch (e) {
          print('Error parsing sensorValue: $e');
          throw Exception('Failed to fetch sensorValue');
        }
      } else {
        throw Exception(
            'Failed to fetch sensorValue: Status Code ${response.statusCode}');
      }
    } else {
      throw Exception('Token is not available');
    }
  } catch (e) {
    print('Error: $e');
    // Handle the error, you might return a default value here if needed
    return [0.0, '']; // Return default values for both temp and sensor_name
  }
}

void saveToken(String token) async {
  SharedPreferences prefs = await SharedPreferences.getInstance();
  prefs.setString('auth_token', token);
  // print("Sensor : " + token);
}

Future<String?> getToken() async {
  SharedPreferences prefs = await SharedPreferences.getInstance();
  return prefs.getString('auth_token');
}
