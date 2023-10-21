@extends('layouts.app')

@section('content')
    <div class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Dashboard') }}
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <canvas id="gaugeCanvas" width="200" height="100"></canvas>

            </div>
        </div>
    </div>
@endsection
@push('css')
    <style>
        
        .gauge-container {
            position: relative;
            width: 200px;
            /* Set the width of the gauge container */
            height: 100px;
            /* Set the height of the gauge container */
            border-radius: 50%;
            overflow: hidden;
        }

        .gauge-background,
        .gauge-foreground {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            clip: rect(0, 100%, 100%, 50%);
        }

        .gauge-background {
            background-color: #ddd;
            /* Set the background color of the gauge */
        }

        .gauge-foreground {
            background-color: #4caf50;
            /* Set the foreground color of the gauge */
            transform-origin: right center;
            transform: rotate(0deg);
            transition: transform 0.3s ease-in-out;
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var canvas = document.getElementById('gaugeCanvas');
            var ctx = canvas.getContext('2d');

            function drawGauge(value) {
                // Your gauge drawing logic here

                // Example: Draw a semi-circle gauge
                var centerX = canvas.width / 2;
                var centerY = canvas.height;
                var radius = canvas.height;

                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, Math.PI, 2 * Math.PI);
                ctx.lineWidth = 10; // Adjust the gauge line width
                ctx.strokeStyle = '#4caf50'; // Adjust the gauge color
                ctx.stroke();

                // Draw the needle or any other gauge elements here

                // Example: Draw a needle
                ctx.beginPath();
                ctx.moveTo(centerX, centerY);
                ctx.lineTo(centerX, centerY - radius);
                ctx.lineWidth = 3; // Adjust the needle width
                ctx.strokeStyle = '#ff5722'; // Adjust the needle color
                ctx.stroke();

                // Update the gauge value
                // ...
            }

            // Example usage:
            var gaugeValue = 75; // Set the gauge value (between 0 and 100)
            drawGauge(gaugeValue);
        });
    </script>
@endpush
