@extends('layouts.master')
@section('title', 'Register')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card mt-4">
            <div class="card-header bg-dark text-white text-center">
                <h4 class="mb-0"><i class="bi bi-person-plus me-2"></i>Register</h4>
            </div>
            <div class="card-body p-4">
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger py-2"><i class="bi bi-exclamation-circle me-1"></i> {{ $error }}</div>
                @endforeach
                <form action="{{ route('do_register') }}" method="post">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" placeholder="Full Name" name="name" required value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="you@example.com" name="email" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" placeholder="Min 8 characters" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Repeat password" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Register</button>
                </form>
                <p class="text-center text-muted mt-3 mb-0">Already have an account? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
