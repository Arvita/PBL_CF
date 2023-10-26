import 'package:http/http.dart' as http;
import 'dart:convert';

Future<Map<String, dynamic>> fetchData() async {
  final response = await http.get(Uri.parse('https://example.com/api/sensors'));

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Failed to load data');
  }
}
