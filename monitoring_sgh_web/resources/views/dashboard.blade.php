@extends('layouts.app')

@section('content')
    <div class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Dashboard') }}
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container">
                    <div class="row">
                        @foreach ($lastThreeSensors as $sensor)
                            <div class="col-md-3">
                                @if ($sensor->DetailSensor && $sensor->DetailSensor->isNotEmpty() && $sensor->DetailSensor->first()->temp !== null)
                                    <canvas id="tempGauge_{{ $sensor->id }}" class="gaugeCanvas"
                                        data-value="{{ $sensor->DetailSensor->first()->temp }}"></canvas>
                                    <div class="tittleLabel mt-2">{{ $sensor->sensor_name }} - Temperatur</div>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        No Temperature data available for {{ $sensor->sensor_name }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-3">
                                @if ($sensor->DetailSensor && $sensor->DetailSensor->isNotEmpty() && $sensor->DetailSensor->first()->humidity !== null)
                                    <canvas id="humidityGauge_{{ $sensor->id }}" class="gaugeCanvas"
                                        data-value="{{ $sensor->DetailSensor->first()->humidity }}"></canvas>
                                    <div class="tittleLabel mt-2">{{ $sensor->sensor_name }} - Humidity</div>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        No Humidity data available for {{ $sensor->sensor_name }}
                                    </div>
                                @endif
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container">
                    <div class="row">
                        @foreach ($lastActuator as $actuator)
                            <div class="col-md-3 mb-3 mt-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    {{ $actuator->actuator_name }}
                                    <span>&nbsp;</span>
                                    <label class="switch m-0">
                                        <input type="checkbox" @if ($actuator->status == 1) checked @endif
                                            data-actuator-id="{{ $actuator->id }}">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container">
                    <div class="row">
                        <canvas id="liveChart" width="600" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <style>
        /* CSS untuk switch button */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        .gaugeCanvas {
            /* padding: 10px; */
            position: relative;
            top: 20px;
            /* Adjust the value to your preference */
        }

        .tittleLabel {
            text-align: center !important;
            font-size: 12px !important;
            position: relative !important;
            top: -30px !important;
            left: 40px;
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var gaugeCanvases = document.getElementsByClassName('gaugeCanvas');

            for (var i = 0; i < gaugeCanvases.length; i++) {
                var canvas = gaugeCanvases[i];
                var ctx = canvas.getContext('2d');
                var value = parseFloat(canvas.dataset.value);

                function drawGauge(value, type) {
                    // Bersihkan canvas
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    // Tentukan parameter tambahan untuk masing-masing jenis gauge (temp atau humidity)
                    var gaugeColor = (type === 'temp') ? '#FF6384' : '#36A2EB';
                    var gaugeLabel = (type === 'temp') ? 'Temperature' : 'Humidity';

                    // Gambar latar belakang gauge
                    var centerX = canvas.width / 2;
                    var centerY = canvas.height / 2; // Center vertically
                    var radius = Math.min(centerX, centerY) - 10; // Reduce radius to fit inside canvas

                    ctx.beginPath();
                    ctx.arc(centerX, centerY, radius, Math.PI, 2 * Math.PI);
                    ctx.lineWidth = 10;
                    ctx.strokeStyle = '#ddd';
                    ctx.stroke();

                    // Gambar nilai pada gauge
                    var angle = (value / 100) * Math.PI;

                    ctx.beginPath();
                    ctx.arc(centerX, centerY, radius, Math.PI, Math.PI + angle);
                    ctx.lineWidth = 10;
                    ctx.strokeStyle = gaugeColor;
                    ctx.stroke();

                    // Gambar teks nilai
                    ctx.fillStyle = '#000';
                    ctx.font = 'bold 20px Arial';
                    ctx.textAlign = 'center';
                    ctx.fillText(value + (type === 'temp' ? '°C' : '%'), centerX, centerY - 20);

                    // Gambar label jenis gauge
                    ctx.fillStyle = '#000';
                    ctx.font = 'bold 16px Arial';
                    ctx.textAlign = 'center';
                    ctx.fillText(gaugeLabel, centerX, centerY + 20);
                }

                // Periksa jenis gauge dari ID canvas
                if (canvas.id.includes('tempGauge')) {
                    drawGauge(value, 'temp');
                } else if (canvas.id.includes('humidityGauge')) {
                    drawGauge(value, 'humidity');
                }
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('liveChart').getContext('2d');
            let chart;

            function updateChart(labels, datasets) {
                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            x: {
                                type: 'category',
                                labels: labels, // Tambahkan ini untuk menampilkan label waktu
                                position: 'bottom'
                            },
                            y: {}

                        }
                    }
                });
            }

            function fetchSensorData() {
                fetch(`/chart-data`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data && data.data) {
                            console.log(typeof data);

                            const lbl = Object.keys(data.data[0].data).map(timestamp => {
                                const formattedTimestamp = moment(timestamp, 'YYYY-MM-DD HH:mm').format(
                                    'HH:mm');
                                console.log(
                                    `Original Timestamp: ${timestamp}, Formatted Timestamp: ${formattedTimestamp}`
                                    );
                                return formattedTimestamp;
                            });
                            console.log(lbl);
                            const datasets = data.data.map(sensor => ({
                                label: sensor.label,
                                data: Object.values(sensor.data), // Mengambil nilai suhu
                                borderColor: `#${Math.floor(Math.random() * 16777215).toString(16)}`,
                                borderWidth: 1,
                                fill: false
                            }));
                            // console.log(datasets);
                            console.log('Labels:', lbl);
                            console.log('Datasets:', datasets);
                            updateChart(lbl, datasets);
                        } else {
                            console.error('Invalid data format');
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }
            // Panggil fungsi fetchSensorData untuk pertama kali
            fetchSensorData();
            // Set interval untuk memperbarui grafik setiap 60 detik
            setInterval(fetchSensorData, 60000);

            const adapters = Chart._adapters;
            adapters._date.override({
                formats: function(type, time) {
                    if (!time) return 'MMM D, YYYY';
                    if (type === 'time' && time.displayFormat === 'auto') {
                        return 'MMM D, YYYY h:mm:ss a';
                    }
                    return time.displayFormat || 'MMM D, YYYY';
                },
                parse: function(value, format) {
                    if (format === 'MMMM' || format === 'MMM') {
                        value += ' 1';
                    }
                    return moment(value, format);
                },
                format: function(time, format) {
                    return time.format(format);
                },
                add: function(time, amount, unit) {
                    return time.add(amount, unit);
                },
                diff: function(max, min, unit) {
                    return max.diff(min, unit, true);
                },
                startOf: function(time, unit, weekday) {
                    return moment(time).startOf(unit);
                },
                endOf: function(time, unit) {
                    return moment(time).endOf(unit);
                },
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const switches = document.querySelectorAll('.switch input');

            switches.forEach(function(switchElement) {
                switchElement.addEventListener('change', function() {
                    const actuatorId = this.dataset.actuatorId;
                    const status = this.checked ? 1 : 2;

                    // Kirim permintaan AJAX ke server untuk memperbarui status
                    fetch(`/update-actuator/${actuatorId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                status: status
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Di sini Anda dapat menanggapi respon dari server (jika perlu)
                            console.log(data);
                            if (data.success) {
                                alert(data.message);
                            } else {
                                alert('Gagal memperbarui status aktuator: ' + data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
@endpush
