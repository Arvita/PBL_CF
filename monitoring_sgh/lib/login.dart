import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:cookie_jar/cookie_jar.dart';

class LoginPage extends StatefulWidget {
  @override
  _LoginPageState createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final TextEditingController usernameController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();

  Future<void> loginUser() async {
    var cookieJar = CookieJar();
    var cookies = await cookieJar
        .loadForRequest(Uri.parse('http://localhost:8000/api/login'));
    var csrfCookie;
    for (var cookie in cookies) {
      if (cookie.name == 'XSRF-TOKEN') {
        csrfCookie = cookie;
        break;
      }
    }
    var csrfToken = csrfCookie?.value ?? '';

    var url = Uri.parse(
        'http://localhost:8000/api/login'); // Ganti dengan URL API Anda

    try {
      var response = await http.post(
        url,
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: jsonEncode({
          'username': usernameController.text,
          'password': passwordController.text,
        }),
      );

      if (response.statusCode == 200) {
        // Autentikasi berhasil
        SharedPreferences prefs = await SharedPreferences.getInstance();
        prefs.setString('username', usernameController.text);

        // Navigasi ke halaman beranda atau halaman setelah login
        Navigator.pushReplacementNamed(context, '/home');
      } else {
        // Autentikasi gagal
        print('Login gagal, Status Code: ${response.statusCode}');
      }
    } catch (e) {
      print('Error: $e');
    }
  }

  @override
  bool isPasswordVisible =
      false; // State untuk mengontrol visibilitas kata sandi

  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Login')),
      body: Center(
        child: Padding(
          padding: EdgeInsets.all(16.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: <Widget>[
              // Tambahkan gambar sensor
              Image.asset(
                'assets/sensor_image.png', // Sesuaikan dengan path gambar Anda
                height: 150, // Sesuaikan tinggi gambar
              ),
              SizedBox(height: 5),
              TextField(
                controller: usernameController,
                decoration: InputDecoration(labelText: 'Username'),
              ),
              TextField(
                controller: passwordController,
                obscureText: !isPasswordVisible, // Sesuaikan visibilitas
                decoration: InputDecoration(
                  labelText: 'Password',
                  suffixIcon: GestureDetector(
                    onTap: () {
                      setState(() {
                        isPasswordVisible = !isPasswordVisible;
                      });
                    },
                    child: Icon(
                      isPasswordVisible
                          ? Icons.visibility
                          : Icons.visibility_off,
                    ),
                  ),
                ),
              ),
              SizedBox(height: 20),
              ElevatedButton(
                onPressed: loginUser,
                child: Text('Login'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
