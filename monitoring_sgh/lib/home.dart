import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:syncfusion_flutter_gauges/gauges.dart';
import 'package:monitoring_sgh/load_api.dart';

class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  List<Map<String, dynamic>> sensors = [];
  int _currentIndex = 0;

  @override
  void initState() {
    super.initState();
    _loadSensorValue();
  }

  Future<void> _loadSensorValue() async {
    try {
      List<Map<String, dynamic>> sensorData = await getSensorValueFromAPI();

      if (sensorData.isNotEmpty) {
        setState(() {
          sensors = sensorData;
        });
      }
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
      body:
          _buildBody(), // Ganti dengan metode yang mengembalikan body yang sesuai

      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        selectedItemColor: Colors.blue,
        items: [
          BottomNavigationBarItem(
            icon: Icon(Icons.bar_chart),
            label: 'Sensor',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.build),
            label: 'Actuator',
          ),
        ],
        onTap: (int index) {
          setState(() {
            _currentIndex = index;
          });
        },
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

  Widget _buildBody() {
    // Tentukan fragment yang sesuai berdasarkan nilai _currentIndex
    if (_currentIndex == 0) {
      return SensorFragment(sensors); // Ganti dengan fragment Sensor
    } else if (_currentIndex == 1) {
      return ActuatorFragment(); // Ganti dengan fragment Actuator
    } else {
      return Container(); // Default, atau fragment lain jika diperlukan
    }
  }
}

class GaugeWidget extends StatelessWidget {
  final double sensorValue;
  final String sensorName;

  GaugeWidget({
    required this.sensorValue,
    required this.sensorName,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      mainAxisSize: MainAxisSize.min,
      children: <Widget>[
        Container(
          // Set background color to white
          child: SfRadialGauge(
            axes: <RadialAxis>[
              RadialAxis(
                  minimum: 0,
                  maximum: 100,
                  ranges: <GaugeRange>[
                    GaugeRange(
                        startValue: 0, endValue: 50, color: Colors.green),
                    GaugeRange(
                        startValue: 50, endValue: 75, color: Colors.orange),
                    GaugeRange(startValue: 75, endValue: 100, color: Colors.red)
                  ],
                  radiusFactor: 0.7,
                  pointers: <GaugePointer>[
                    NeedlePointer(value: sensorValue),
                  ],
                  annotations: <GaugeAnnotation>[
                    GaugeAnnotation(
                        widget: Container(
                            child: Text(
                                '$sensorValue' + '° C' + '\n$sensorName',
                                style: TextStyle(
                                    fontSize: 18, fontWeight: FontWeight.bold),
                                textAlign: TextAlign.center)),
                        angle: 90,
                        positionFactor: 0.5)
                  ]),
            ],
          ),
        ),
      ],
    );
  }
}

class SensorFragment extends StatelessWidget {
  final List<Map<String, dynamic>> sensors;

  SensorFragment(this.sensors);

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Center(
        child: Column(
          children: [
            for (var i = 0; i < sensors.length; i += 2)
              LayoutBuilder(
                builder: (BuildContext context, BoxConstraints constraints) {
                  return Row(
                    mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                    children: [
                      Flexible(
                        child: GaugeWidget(
                          sensorValue: sensors[i]['temp'],
                          sensorName: sensors[i]['sensor'],
                        ),
                      ),
                      if (i + 1 < sensors.length && constraints.maxWidth > 600)
                        Flexible(
                          child: GaugeWidget(
                            sensorValue: sensors[i + 1]['temp'],
                            sensorName: sensors[i + 1]['sensor'],
                          ),
                        ),
                    ],
                  );
                },
              ),
          ],
        ),
      ),
    );
  }
}

class ActuatorFragment extends StatefulWidget {
  @override
  _ActuatorFragmentState createState() => _ActuatorFragmentState();
}

class _ActuatorFragmentState extends State<ActuatorFragment> {
  bool isSwitched = false;
  String nameActuator = "";
  int id = 0;

  @override
  void initState() {
    super.initState();
    _loadLastActuatorStatus(); // Panggil fungsi untuk mendapatkan status awal dari API
  }

  Future<void> _loadLastActuatorStatus() async {
    try {
      List<dynamic> actuatorStatus = await getLastActuatorStatus();
      String actuatorName = actuatorStatus[0];
      bool lastStatus = actuatorStatus[1];
      int idActuator = actuatorStatus[2];
      setState(() {
        isSwitched = lastStatus;
        nameActuator = actuatorName;
        id = idActuator;
      });
    } catch (e) {
      print('Error: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: <Widget>[
          Text(
            nameActuator,
            style: TextStyle(fontSize: 24),
          ),
          SizedBox(height: 20),
          Switch(
            value: isSwitched,
            onChanged: (value) async {
              setState(() {
                isSwitched = value;
              });
              // Panggil fungsi untuk mengirim status ke API di sini
              bool success = await sendStatusToAPI(isSwitched, id);
              if (success) {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                    content: Text('Status successfully updated'),
                    duration: Duration(seconds: 2),
                  ),
                );
              }
            },
          ),
        ],
      ),
    );
  }
}
