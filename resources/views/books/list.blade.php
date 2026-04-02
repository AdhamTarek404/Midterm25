@extends('layouts.master')
@section('title', 'Books')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h2 class="mb-0"><i class="bi bi-collection me-2"></i>Library Catalogue</h2>
    @auth
        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'librarian')
            <a href="{{ route('books_edit') }}" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> Add Book</a>
        @endif
    @endauth
</div>

@if($books->count() > 0)
    <div class="card">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Copies</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $index => $book)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td><code>{{ $book->isbn }}</code></td>
                        <td>
                            @if($book->copies > 0)
                                <span class="badge bg-success">{{ $book->copies }} available</span>
                            @else
                                <span class="badge bg-danger">Unavailable</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @auth
                                @if($book->copies > 0)
                                    <a href="{{ route('books_borrow', $book->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-bag-plus me-1"></i>Borrow
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled>Unavailable</button>
                                @endif
                                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'librarian')
                                    <a href="{{ route('books_edit', $book->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('books_delete', $book->id) }}" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this book?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login to Borrow</a>
                            @endauth
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-inbox display-4 d-block mb-2"></i>
            No books in the catalogue yet.
        </div>
    </div>
@endif
@endsection
