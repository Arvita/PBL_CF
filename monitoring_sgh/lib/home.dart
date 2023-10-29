import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:syncfusion_flutter_gauges/gauges.dart';
import 'package:monitoring_sgh/load_api.dart';

class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  double sensorTemp = 0;
  String sensorName = "";

  @override
  void initState() {
    super.initState();
    _loadSensorValue();
  }

  Future<void> _loadSensorValue() async {
    try {
      List<dynamic> sensorData = await getSensorValueFromAPI();

      double tempValue = sensorData[0];
      String sensorValue = sensorData[1];
      setState(() {
        sensorTemp = tempValue;
        sensorName = sensorValue;
      });
    } catch (e) {
      print('Error: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Home'),
        actions: [
          IconButton(
            onPressed: () {
              logout(context);
            },
            icon: Icon(Icons.exit_to_app),
          ),
        ],
      ),
      body: Center(
        child: GaugeWidget(sensorTemp, sensorName),
      ),
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

class GaugeWidget extends StatelessWidget {
  final double sensorValue;
  final String sensorName;

  GaugeWidget(this.sensorValue, this.sensorName);

  @override
  Widget build(BuildContext context) {
    return Column(
      children: <Widget>[
        SfRadialGauge(
          axes: <RadialAxis>[
            RadialAxis(
              minimum: 0,
              maximum: 100,
              radiusFactor: 0.7, // Sesuaikan dengan faktor yang diinginkan
              pointers: <GaugePointer>[
                NeedlePointer(value: sensorValue),
              ],
            ),
          ],
        ),
        SizedBox(height: 5), // Sesuaikan dengan jarak yang diinginkan
        Text(
          sensorName,
          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
        ),
      ],
    );
  }
}
