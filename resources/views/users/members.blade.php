@extends('layouts.master')
@section('title', 'Members')
@section('content')
<h2 class="page-header"><i class="bi bi-people me-2"></i>All Registered Members</h2>

<div class="card">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $index => $member)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="fw-semibold">{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>
                        @if($member->role == 'admin')
                            <span class="badge bg-danger">{{ $member->role }}</span>
                        @elseif($member->role == 'librarian')
                            <span class="badge bg-warning text-dark">{{ $member->role }}</span>
                        @else
                            <span class="badge bg-primary">{{ $member->role }}</span>
                        @endif
                    </td>
                    <td class="text-muted">{{ $member->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
