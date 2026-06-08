<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Toko Bu Nardi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #1d4ed8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .logo-icon {
            width: 64px; height: 64px;
            background: #f59e0b;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            margin-bottom: 14px;
            box-shadow: 0 4px 12px rgba(245,158,11,.4);
        }
        .login-logo h1 { font-size: 20px; font-weight: 800; color: #0f172a; }
        .login-logo p  { font-size: 13px; color: #64748b; margin-top: 4px; }

        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 11px 14px 11px 38px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #0f172a;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        input:focus { border-color: #1e40af; box-shadow: 0 0 0 3px rgba(30,64,175,.12); }
        input.is-invalid { border-color: #ef4444; }

        .error-msg { color: #ef4444; font-size: 12px; margin-top: 5px; display: flex; align-items: center; gap: 4px; }

        .remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 22px; }
        .remember-row input[type="checkbox"] { width: 15px; height: 15px; accent-color: #1e40af; cursor: pointer; }
        .remember-row label { margin: 0; font-weight: 500; cursor: pointer; }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #1e40af;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background .2s, transform .1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover { background: #1e3a8a; transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }

        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .login-footer { text-align: center; margin-top: 24px; font-size: 12px; color: #94a3b8; }
        .default-creds { background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 12px 14px; margin-top: 20px; font-size: 12px; color: #475569; }
        .default-creds strong { color: #1e40af; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-logo">
                <div class="logo-icon"><i class="fas fa-store"></i></div>
                <h1>Toko Bu Nardi</h1>
                <p>Sistem Manajemen Inventaris</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="admin@tokobn.com"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                               autofocus autocomplete="email">
                    </div>
                    @error('email')
                        <div class="error-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password"
                               placeholder="••••••••"
                               autocomplete="current-password">
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Masuk ke Sistem
                </button>
            </form>

            <div class="default-creds">
                <i class="fas fa-info-circle"></i>
                <strong>Default Login:</strong><br>
                Email: <strong>admin@tokobn.com</strong> &nbsp;|&nbsp; Password: <strong>admin123</strong>
            </div>
        </div>
        <div class="login-footer">© {{ date('Y') }} Toko Bu Nardi</div>
    </div>
</body>
</html>
