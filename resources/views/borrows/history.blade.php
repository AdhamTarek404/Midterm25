@extends('layouts.master')
@section('title', 'Borrow History')
@section('content')
<h2 class="page-header"><i class="bi bi-clock-history me-2"></i>My Borrowed Books</h2>

@if($borrows->count() > 0)
    <div class="card">
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
    </div>
@else
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-inbox display-4 d-block mb-2"></i>
            You haven't borrowed any books yet.
            <br><a href="{{ route('books_list') }}" class="btn btn-sm btn-primary mt-2"><i class="bi bi-collection me-1"></i> Browse Books</a>
        </div>
    </div>
@endif
@endsection
