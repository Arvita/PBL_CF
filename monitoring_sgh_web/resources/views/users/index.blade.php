@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('All User') }}
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 0)
                                <span class="badge badge-primary">Admin</span>
                            @elseif($user->role == 1)
                                <span class="badge badge-success">Staff</span>
                            @endif
                        </td>
                        
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary">
                                <i class="bi bi-pencil"></i> <!-- Bootstrap Icon -->
                            </a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
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
