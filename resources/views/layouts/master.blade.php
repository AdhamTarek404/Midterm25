<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Library - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { background: #f0f2f5; min-height: 100vh; }
        .navbar { box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .card { border: none; border-radius: .75rem; box-shadow: 0 1px 6px rgba(0,0,0,.07); }
        .card-header { border-radius: .75rem .75rem 0 0 !important; }
        .table { margin-bottom: 0; }
        .btn { border-radius: .5rem; }
        .badge { font-weight: 500; }
        .page-header { margin-bottom: 1.5rem; padding-bottom: .75rem; border-bottom: 2px solid #e9ecef; }
        .alert { border-radius: .5rem; border: none; }
    </style>
</head>
<body>
    @include('layouts.menu')
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>
