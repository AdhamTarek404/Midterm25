@extends('layouts.master')
@section('title', 'Login')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card mt-4">
            <div class="card-header bg-dark text-white text-center">
                <h4 class="mb-0"><i class="bi bi-box-arrow-in-right me-2"></i>Login</h4>
            </div>
            <div class="card-body p-4">
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger py-2"><i class="bi bi-exclamation-circle me-1"></i> {{ $error }}</div>
                @endforeach
                <form action="{{ route('do_login') }}" method="post">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="you@example.com" name="email" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Login</button>
                </form>
                <p class="text-center text-muted mt-3 mb-0">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
