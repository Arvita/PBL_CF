import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:syncfusion_flutter_gauges/gauges.dart';

class HomePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    double sensorValue = 75; // Ganti dengan nilai sensor yang sesuai

    return Scaffold(
      appBar: AppBar(title: Text('Home')),
      body: Center(
        child: GaugeWidget(sensorValue),
      ),
    );
  }
}

class GaugeWidget extends StatelessWidget {
  final double sensorValue;

  GaugeWidget(this.sensorValue);

  @override
  Widget build(BuildContext context) {
    return Column(
      children: <Widget>[
        SfRadialGauge(
          axes: <RadialAxis>[
            RadialAxis(
              minimum: 0,
              maximum: 100,
              pointers: <GaugePointer>[
                NeedlePointer(value: sensorValue),
              ],
            ),
          ],
        ),
        SizedBox(height: 20), // Jarak antara gauge dan tombol logout
        ElevatedButton(
          onPressed: () {
            logout(context);
          },
          child: Text('Logout'),
        ),
      ],
    );
  }

  void logout(BuildContext context) {
    // Lakukan tindakan logout di sini
    // Contoh: Hapus token otentikasi dan bersihkan data sesi
    // Misalnya, jika menggunakan SharedPreferences:
    SharedPreferences.getInstance().then((prefs) {
      prefs.remove('token'); // Hapus token otentikasi
      prefs.remove('username'); // Bersihkan data sesi lainnya jika ada
    });

    // Navigasi ke halaman login atau halaman awal aplikasi
    Navigator.pushReplacementNamed(
        context, '/login'); // Ganti '/login' dengan rute yang sesuai
  }
}
