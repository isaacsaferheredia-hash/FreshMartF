@php
    $correo = session('correo_real');

    if ($correo && str_contains($correo, '@')) {
        [$user, $domain] = explode('@', $correo);

        $visibleStart = substr($user, 0, 3);
        $visibleEnd   = substr($user, -1);

        $maskedUser = $visibleStart
            . str_repeat('*', max(strlen($user) - 4, 3))
            . $visibleEnd;

        $correoMask = $maskedUser . '@' . $domain;
    } else {
        $correoMask = '';
    }
@endphp

<x-guest-layout>

    <style>
        body {
            background: #f3f4f6;
        }

        .register-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
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
            letter-spacing: 0.5px;
        }

        h2 {
            margin-top: 20px;
            margin-bottom: 8px;
            font-size: 26px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 15px;
            margin-bottom: 24px;
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

        /* ===== ERRORES ===== */

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

        .hint-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .btn-primary {
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

        .btn-primary:hover {
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
    </style>

    <div class="register-wrapper">
        <div class="register-card">

            {{-- HEADER --}}
            <div style="text-align:center;margin-bottom:24px;">
                <div class="brand">FreshMart</div>
                <h2>Confirmar correo</h2>
                <p class="subtitle">
                    Para continuar, valida tu correo electrónico
                </p>
            </div>

            {{-- MENSAJE GENERAL --}}
            @if ($errors->any())
                <div class="form-alert">
                    El correo ingresado no coincide con el registrado. Verifica e intenta nuevamente.
                </div>
            @endif

            {{-- PISTA --}}
            <div class="hint-box">
                Confirma el correo asociado a tu cuenta:<br>
                <strong>{{ $correoMask }}</strong>
            </div>

            <form method="POST" action="{{ route('register.confirm.post') }}">
                @csrf

                <input type="hidden" name="cliente_id" value="{{ session('cliente_id') }}">

                {{-- EMAIL --}}
                <div style="margin-bottom:24px;">
                    <label for="email">Correo electrónico</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Ingresa el correo completo"
                        class="input @error('email') input-error @enderror"
                        required
                        autofocus
                    >

                    @error('email')
                    <div class="form-error">
                        Ingresa exactamente el correo asociado a tu cuenta.
                    </div>
                    @enderror
                </div>

                {{-- BOTÓN --}}
                <button type="submit" class="btn-primary">
                    Validar correo
                </button>
            </form>

            {{-- FOOTER --}}
            <div style="text-align:center;margin-top:32px;">
                <a href="{{ route('register') }}" class="footer-link">
                    ← Volver al inicio del registro
                </a>
            </div>

        </div>
    </div>

</x-guest-layout>
