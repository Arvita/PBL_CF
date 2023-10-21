import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> registerUser(String email, String password) async {
  final response = await http.post(
    Uri.parse('http://your_api_domain/register'),
    headers: <String, String>{
      'Content-Type': 'application/json',
    },
    body: jsonEncode(<String, String>{
      'email': email,
      'password': password,
    }),
  );

  if (response.statusCode == 200) {
    // User registered successfully
  } else {
    // Failed to register
  }
}
