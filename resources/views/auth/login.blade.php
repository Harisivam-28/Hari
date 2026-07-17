<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        * { font-family: 'Inter', sans-serif; }

        body.login-page {
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 420px;
        }

        .card {
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .card-body {
            padding: 40px;
        }

        .login-logo {
            margin-bottom: 25px;
        }

        .login-logo a {
            color: #fff;
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -1px;
            text-decoration: none;
        }

        .login-logo .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            border-radius: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin-bottom: 15px;
            box-shadow: 0 10px 30px rgba(102,126,234,0.4);
        }

        .login-logo .icon-wrapper i {
            font-size: 28px;
            color: white;
        }

        .login-title {
            color: #fff;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .login-subtitle {
            color: rgba(255,255,255,0.5);
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-group label {
            color: rgba(255,255,255,0.7);
            font-weight: 500;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .input-group {
            border-radius: 12px;
            overflow: hidden;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.2);
        }

        .form-control {
            background: transparent !important;
            border: none !important;
            color: #fff !important;
            padding: 12px 16px;
            font-size: 0.95rem;
            height: auto;
        }

        .form-control::placeholder {
            color: rgba(255,255,255,0.3);
        }

        .input-group-append .input-group-text {
            background: transparent;
            border: none;
            color: rgba(255,255,255,0.4);
            padding: 0 16px;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            width: 100%;
            color: white;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102,126,234,0.4);
            color: white;
        }

        .remember-me {
            color: rgba(255,255,255,0.6);
            font-size: 0.85rem;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 6px;
        }

        .demo-creds {
            margin-top: 25px;
            padding: 15px;
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .demo-creds p {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
            margin-bottom: 5px;
        }

        .demo-creds .cred {
            color: rgba(255,255,255,0.7);
            font-size: 0.8rem;
            font-family: monospace;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff6b7a;
            border-radius: 12px;
        }

        /* Floating particles animation */
        .particles {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        .particles .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(102,126,234,0.15);
            animation: float-up linear infinite;
        }
        @keyframes float-up {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
        }
    </style>
</head>
<body class="login-page">

<div class="particles" id="particles"></div>

<div class="login-box">
    <div class="login-logo text-center">
        <div class="icon-wrapper">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <br>
        <span class="login-title">School Portal</span>
        <p class="login-subtitle">Sign in to your account</p>
    </div>

    <div class="card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope mr-1"></i> Email Address</label>
                    <div class="input-group">
                        <input type="email" name="email" id="email" class="form-control"
                               placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock mr-1"></i> Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="Enter your password" required>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="remember-me mb-0">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                </button>
            </form>

            <div class="demo-creds">
                <p class="mb-2 text-center" style="font-weight: 600; color: rgba(255,255,255,0.6);">
                    <i class="fas fa-info-circle mr-1"></i> Demo Credentials
                </p>
                <div class="row">
                    <div class="col-6">
                        <p class="mb-0"><i class="fas fa-user-shield mr-1"></i> Admin</p>
                        <span class="cred">admin@school.com</span><br>
                        <span class="cred">password</span>
                    </div>
                    <div class="col-6">
                        <p class="mb-0"><i class="fas fa-user-graduate mr-1"></i> Student</p>
                        <span class="cred">student@school.com</span><br>
                        <span class="cred">password</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Generate floating particles
    const container = document.getElementById('particles');
    for (let i = 0; i < 15; i++) {
        const p = document.createElement('div');
        p.classList.add('particle');
        const size = Math.random() * 20 + 5;
        p.style.width = size + 'px';
        p.style.height = size + 'px';
        p.style.left = Math.random() * 100 + '%';
        p.style.animationDuration = (Math.random() * 15 + 10) + 's';
        p.style.animationDelay = (Math.random() * 10) + 's';
        container.appendChild(p);
    }
</script>
</body>
</html>
