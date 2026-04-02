@extends('layouts.master')
@section('title', 'My Profile')
@section('content')
<h2 class="page-header"><i class="bi bi-person-circle me-2"></i>My Profile</h2>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-4">
                <div class="display-1 text-muted mb-2"><i class="bi bi-person-circle"></i></div>
                <h5>{{ $user->name }}</h5>
                <p class="text-muted mb-1">{{ $user->email }}</p>
                <span class="badge bg-primary">{{ $user->role }}</span>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header fw-semibold"><i class="bi bi-bar-chart me-1"></i> Borrowing Status</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Used</span>
                    <span class="fw-bold">{{ $borrowCount }} / {{ $borrowLimit }}</span>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar {{ $borrowCount >= $borrowLimit ? 'bg-danger' : 'bg-success' }}"
                         style="width: {{ ($borrowCount / $borrowLimit) * 100 }}%"></div>
                </div>
                <p class="text-muted small mt-2 mb-0">
                    @if($borrowCount >= $borrowLimit)
                        Limit reached — return a book to borrow more.
                    @else
                        {{ $borrowLimit - $borrowCount }} borrow(s) remaining.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-book me-1"></i> Borrowed Books</div>
            @if($borrows->count() > 0)
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Borrowed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrows as $index => $borrow)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $borrow->book->title }}</td>
                                <td>{{ $borrow->book->author }}</td>
                                <td><code>{{ $borrow->book->isbn }}</code></td>
                                <td>{{ $borrow->borrowed_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-inbox display-4 d-block mb-2"></i>
                    No books borrowed yet.
                    <br><a href="{{ route('books_list') }}" class="btn btn-sm btn-primary mt-2">Browse Books</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
