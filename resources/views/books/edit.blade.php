@extends('layouts.master')
@section('title', $book->id ? 'Edit Book' : 'Add Book')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card mt-2">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">
                    <i class="bi {{ $book->id ? 'bi-pencil' : 'bi-plus-circle' }} me-2"></i>
                    {{ $book->id ? 'Edit Book' : 'Add New Book' }}
                </h4>
            </div>
            <div class="card-body p-4">
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger py-2"><i class="bi bi-exclamation-circle me-1"></i> {{ $error }}</div>
                @endforeach
                <form action="{{ route('books_save', $book->id) }}" method="post">
                    {{ csrf_field() }}
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" placeholder="Book title" name="title" required value="{{ $book->title }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Author</label>
                            <input type="text" class="form-control" placeholder="Author name" name="author" required value="{{ $book->author }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">ISBN</label>
                            <input type="text" class="form-control" placeholder="e.g. 9781234567890" name="isbn" required value="{{ $book->isbn }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Copies</label>
                            <input type="number" class="form-control" placeholder="0" name="copies" required min="0" value="{{ $book->copies ?? 0 }}">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dark"><i class="bi bi-check-lg me-1"></i> Save Book</button>
                        <a href="{{ route('books_list') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
