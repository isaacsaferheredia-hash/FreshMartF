<x-guest-layout>

    <style>
        body {
            background: #f3f4f6;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            max-width: 520px;
            width: 100%;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.12);
            padding: 48px;
        }

        .brand {
            background: #16a34a;
            color: #fff;
            font-weight: 700;
            padding: 12px 32px;
            border-radius: 999px;
            display: inline-block;
            font-size: 20px;
        }

        h2 {
            margin-top: 20px;
            margin-bottom: 8px;
            font-size: 26px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 15px;
        }

        label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 6px;
            display: block;
        }

        .input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            font-size: 15px;
            transition: all .2s ease;
        }

        .input:focus {
            outline: none;
            border-color: #16a34a;
            box-shadow: 0 0 0 2px rgba(22,163,74,0.2);
        }

        .input-error {
            border-color: #dc2626;
            background-color: #fef2f2;
        }

        .form-error {
            margin-top: 6px;
            font-size: 14px;
            color: #b91c1c;
            display: flex;
            gap: 6px;
        }

        .form-error::before {
            content: "⚠️";
        }

        .form-alert {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .btn-login {
            background: #16a34a;
            color: #fff;
            border: none;
            width: 100%;
            padding: 16px;
            font-size: 17px;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #15803d;
        }

        .footer-link {
            color: #6b7280;
            font-size: 14px;
            text-decoration: none;
        }

        .footer-link:hover {
            color: #16a34a;
        }

        /* 🔑 LINK ADMIN DISCRETO */
        .admin-link {
            display: inline-block;
            margin-top: 14px;
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
        }

        .admin-link:hover {
            color: #16a34a;
            text-decoration: underline;
        }



        .btn-register {
            display: block;
            text-align: center;
            margin-top: 14px;
            padding: 14px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            color: #16a34a;
            border: 2px solid #16a34a;
            text-decoration: none;
            transition: all .2s ease;
        }

        .btn-register:hover {
            background: #16a34a;
            color: #fff;
        }

    </style>

    <div class="login-wrapper">
        <div class="login-card">

            {{-- HEADER --}}
            <div style="text-align:center;margin-bottom:36px;">
                <div class="brand">FreshMart</div>
                <h2>Bienvenido</h2>
                <p class="subtitle">Inicia sesión para continuar</p>
            </div>

            {{-- ERROR GENERAL --}}
            @if ($errors->any())
                <div class="form-alert">
                    No pudimos iniciar sesión. Revisa la información e inténtalo nuevamente.
                </div>
            @endif

            {{-- LOGIN CLIENTE --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- EMAIL --}}
                <div style="margin-bottom:22px;">
                    <label>Correo electrónico</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="correo@ejemplo.com"
                        class="input @error('email') input-error @enderror"
                        required
                        autofocus
                    >

                    @error('email')
                    <div class="form-error">
                        Ingresa un correo electrónico válido.
                    </div>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div style="margin-bottom:22px;">
                    <label>Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        class="input @error('password') input-error @enderror"
                        required
                    >

                    @error('password')
                    <div class="form-error">
                        La contraseña es obligatoria.
                    </div>
                    @enderror
                </div>

                {{-- REMEMBER --}}
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
                    <label style="font-weight:500;">
                        <input type="checkbox" name="remember"> Recordarme
                    </label>
                </div>

                {{-- BOTÓN --}}
                <button type="submit" class="btn-login">
                    Iniciar sesión
                </button>

                <a href="{{ route('register') }}" class="btn-register">
                    ¿No tienes cuenta? Regístrate
                </a>

            </form>

            {{-- 🔑 ACCESO ADMIN (DISCRETO) --}}
            <div style="text-align:center;">
                <a href="{{ route('admin.login') }}" class="admin-link">
                    Acceso para personal administrativo
                </a>
            </div>

            {{-- FOOTER --}}
            <div style="text-align:center;margin-top:32px;">
                <a href="{{ route('tienda') }}" class="footer-link">
                    ← Volver a la tienda
                </a>
            </div>

        </div>
    </div>

</x-guest-layout>
