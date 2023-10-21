@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Sensors</h1>
        <br>
        <form action="{{ route('sensor.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('sensor.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
            
        </form>
    </div>
@endsection
