<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <i class="bi bi-book-half me-1"></i> Library
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('books_list') }}"><i class="bi bi-collection"></i> Books</a>
                </li>
                @auth
                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'librarian')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('books_edit') }}"><i class="bi bi-plus-circle"></i> Add Book</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('members') }}"><i class="bi bi-people"></i> Members</a>
                        </li>
                    @endif
                    @if(auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('create_librarian') }}"><i class="bi bi-person-plus"></i> Create Librarian</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('roles') }}"><i class="bi bi-shield-lock"></i> Roles</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('borrows_history') }}"><i class="bi bi-clock-history"></i> My Borrows</a>
                    </li>
                @endauth
            </ul>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            <span class="badge bg-info ms-1">{{ auth()->user()->role }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('borrows_history') }}"><i class="bi bi-clock-history me-2"></i>My Borrows</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="{{ route('do_logout') }}"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus"></i> Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
