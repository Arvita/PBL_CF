@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Sensors</h1>
        <br>
        <form action="{{ route('sensor.update', $sensor->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $sensor->sensor_name }}" required>
            </div>
            <br>
            <div class="form-group ">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('sensor.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
            
        </form>
    </div>
@endsection
