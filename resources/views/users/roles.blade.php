@extends('layouts.master')
@section('title', 'Roles & Permissions')
@section('content')
<h2 class="page-header"><i class="bi bi-shield-lock me-2"></i>Roles & Permissions</h2>

<div class="row g-4 mb-4">
    @foreach($rolesInfo as $role => $permissions)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header fw-semibold">
                    @if($role == 'admin')
                        <span class="badge bg-danger me-1">{{ $role }}</span>
                    @elseif($role == 'librarian')
                        <span class="badge bg-warning text-dark me-1">{{ $role }}</span>
                    @else
                        <span class="badge bg-primary me-1">{{ $role }}</span>
                    @endif
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($permissions as $perm)
                            <li class="mb-1"><i class="bi bi-check-circle text-success me-1"></i> {{ $perm }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header fw-semibold"><i class="bi bi-people me-1"></i> Users by Role</div>
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="fw-semibold">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role == 'admin')
                            <span class="badge bg-danger">{{ $user->role }}</span>
                        @elseif($user->role == 'librarian')
                            <span class="badge bg-warning text-dark">{{ $user->role }}</span>
                        @else
                            <span class="badge bg-primary">{{ $user->role }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
