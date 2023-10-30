import 'dart:async';

import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';

Future<List<Map<String, dynamic>>> getSensorValueFromAPI() async {
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
          return List<Map<String, dynamic>>.from(jsonResponse['sensors']);
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
    // return [0.0, '']; // Return default values for both temp and sensor_name
    return [];
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

Future<bool> sendStatusToAPI(bool isSwitched, int id) async {
  try {
    String? authToken = await getToken();
    var response = await http.post(
      Uri.parse(
          'http://localhost:8000/api/update_actuator_status/$id'), // Ganti dengan URL API Anda
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
        'Accept': 'application/json',
        'Authorization': 'Bearer $authToken',
      },
      body: jsonEncode(<String, bool>{'status': isSwitched}),
    );
    if (response.statusCode == 200) {
      print('Status berhasil diperbarui');
      return true; // Indicates success
    } else {
      print('Gagal memperbarui status, Status Code: ${response.statusCode}');
      return false; // Indicates failure
    }
  } catch (e) {
    print('Error: $e');
    return false; // Indicates failure
  }
}

Future<List<dynamic>> getLastActuatorStatus() async {
  Completer<List<dynamic>> completer = Completer<List<dynamic>>();
  try {
    String? authToken = await getToken();
    var url = Uri.parse('http://localhost:8000/api/last_actuator_status');
    var response = await http.get(url, headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer $authToken',
    });
    if (response.statusCode == 200) {
      Map<String, dynamic> data = json.decode(response.body);
      String actuator = data['actuator_name'];
      int status = data['status'];
      int id = data['id'];
      bool isSwitched =
          status == 1; // Assuming 1 represents true and 0 represents false
      // print(id);
      completer.complete([actuator, isSwitched, id]);
    } else {
      completer.completeError('Failed to fetch actuator status');
    }
  } catch (error) {
    completer.completeError(error.toString());
  }

  return completer.future;
}
