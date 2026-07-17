<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Portal') | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .main-sidebar { background: linear-gradient(180deg, #0f3460 0%, #16213e 100%); }
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%);
            border-radius: 8px;
            margin: 0 8px;
        }
        .nav-sidebar .nav-link { border-radius: 8px; margin: 2px 8px; transition: all 0.3s ease; }
        .nav-sidebar .nav-link:hover { background: rgba(255,255,255,0.08); }
        .brand-link { border-bottom: 1px solid rgba(255,255,255,0.1) !important; padding: 15px !important; }
        .brand-text { font-weight: 700 !important; letter-spacing: -0.5px; }
        .small-box {
            border-radius: 12px; overflow: hidden; border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .small-box:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .card { border-radius: 12px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .btn { border-radius: 8px; font-weight: 500; transition: all 0.3s ease; }
        .btn-info { background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%); border: none; }
        .btn-info:hover { background: linear-gradient(135deg, #0096c7 0%, #005f8d 100%); transform: translateY(-1px); }
        .content-wrapper { background: #f4f6f9; }
        .badge { border-radius: 6px; font-weight: 500; padding: 5px 10px; }
        .alert { border-radius: 10px; border: none; }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle mr-1"></i> {{ auth()->user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('student.profile') }}">
                        <i class="fas fa-id-card mr-2"></i> My Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('student.dashboard') }}" class="brand-link text-center">
            <i class="fas fa-graduation-cap fa-lg mr-2" style="color: #00b4d8;"></i>
            <span class="brand-text font-weight-bold text-white">School Portal</span>
        </a>
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    @if(auth()->user()->student && auth()->user()->student->photo)
                        <img src="{{ asset('storage/' . auth()->user()->student->photo) }}" class="img-circle elevation-2" alt="Photo" style="width:35px;height:35px;object-fit:cover;">
                    @else
                        <i class="fas fa-user-graduate fa-2x text-light mt-1"></i>
                    @endif
                </div>
                <div class="info">
                    <a href="{{ route('student.profile') }}" class="d-block text-white">{{ auth()->user()->name }}</a>
                    <span class="text-muted" style="font-size: 0.8rem;"><i class="fas fa-circle text-success mr-1" style="font-size:8px;"></i> Student</span>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i> <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.profile') }}" class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-id-card"></i> <p>My Profile</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.fees.index') }}" class="nav-link {{ request()->routeIs('student.fees.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill-wave"></i> <p>Fees & Payments</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.receipts.index') }}" class="nav-link {{ request()->routeIs('student.receipts.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice"></i> <p>Fee Receipts</p>
                        </a>
                    </li>
                    <li class="nav-header" style="padding-top: 15px;">ACCOUNT</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                            <i class="nav-icon fas fa-sign-out-alt text-danger"></i> <p class="text-danger">Logout</p>
                        </a>
                        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1 class="m-0" style="font-weight: 700;">@yield('page-title', 'Dashboard')</h1></div>
                    <div class="col-sm-6"><ol class="breadcrumb float-sm-right">@yield('breadcrumb')</ol></div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
                @endif
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer text-center" style="font-size: 0.85rem;">
        <strong>&copy; {{ date('Y') }} School Portal.</strong> All rights reserved.
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
</script>
@stack('scripts')
</body>
</html>
