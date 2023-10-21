@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('All Sensors') }}
            </div>
            <a href="{{ route('sensor.create') }}" class="btn btn-primary">Create Sensors</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sensor as $sen)
                    <tr>
                        <td>{{ $sen->sensor_name }}</td>
                        <td>
                            <a href="{{ route('sensor.edit', $sen->id) }}" class="btn btn-secondary">
                                <i class="bi bi-pencil"></i> <!-- Bootstrap Icon -->
                            </a>

                            <form action="{{ route('sensor.destroy', $sen->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this account?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> <!-- Bootstrap Icon -->
                                </button>
                            </form>


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
