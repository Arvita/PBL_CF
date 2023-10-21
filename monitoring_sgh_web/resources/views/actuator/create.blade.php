@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Actuators</h1>
        <br>
        <form action="{{ route('actuator.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" class="form-control" required>
                    <option value=0>Active</option>
                    <option value=1>Not Active</option>
                </select>
            </div>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('actuator.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
            
        </form>
    </div>
@endsection
