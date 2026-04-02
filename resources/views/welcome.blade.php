@extends('layouts.master')
@section('title', 'Home')
@section('content')
<div class="text-center py-5">
    <h1 class="display-5 fw-bold"><i class="bi bi-book-half"></i> Online Library</h1>
    <p class="lead text-muted">Browse, borrow, and manage books in one place.</p>
    <hr class="my-4 w-50 mx-auto">
    @auth
        <p>Welcome back, <strong>{{ auth()->user()->name }}</strong>!</p>
        <a href="{{ route('books_list') }}" class="btn btn-primary btn-lg me-2"><i class="bi bi-collection me-1"></i> Browse Books</a>
        <a href="{{ route('profile') }}" class="btn btn-outline-secondary btn-lg"><i class="bi bi-person me-1"></i> My Profile</a>
    @else
        <p class="text-muted">Please login or register to start borrowing books.</p>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a>
        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg"><i class="bi bi-person-plus me-1"></i> Register</a>
    @endauth
</div>
@endsection
