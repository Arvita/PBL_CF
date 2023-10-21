@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Actuators</h1>
        <br>
        <form action="{{ route('actuator.update', $actuator->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $actuator->actuator_name }}" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" class="form-control" required>
                    <option value=0>Active</option>
                    <option value=1>Not Active</option>
                </select>
            </div>
            <br>
            <div class="form-group ">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('actuator.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
            
        </form>
    </div>
@endsection
