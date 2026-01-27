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
            <div style="text-align:center;margin-bottom:36px;">
                <div class="brand">FreshMart</div>
                <h2>Crear cuenta</h2>
                <p class="subtitle">
                    Ingresa tu cédula o RUC para continuar con el registro
                </p>
            </div>

            {{-- ERROR GENERAL --}}
            @if ($errors->any())
                <div class="form-alert">
                    No pudimos validar tu identificación. Verifica los datos e inténtalo nuevamente.
                </div>
            @endif

            <form method="POST" action="{{ route('register.check') }}">
                @csrf

                {{-- CÉDULA / RUC --}}
                <div style="margin-bottom:24px;">
                    <label for="cli_ruc_ced">Cédula o RUC</label>
                    <input
                        type="text"
                        name="cli_ruc_ced"
                        id="cli_ruc_ced"
                        value="{{ old('cli_ruc_ced') }}"
                        placeholder="Ej: 0102030405 o 0102030405001"
                        maxlength="13"
                        inputmode="numeric"
                        class="input @error('cli_ruc_ced') input-error @enderror"
                        required
                        autofocus
                    >

                    @error('cli_ruc_ced')
                    <div class="form-error">
                        Ingresa una cédula (10 dígitos) o un RUC válido (13 dígitos).
                    </div>
                    @enderror
                </div>

                {{-- BOTÓN --}}
                <button type="submit" class="btn-primary">
                    Continuar
                </button>
            </form>

            {{-- FOOTER --}}
            <div style="text-align:center;margin-top:32px;">
                <a href="{{ route('login') }}" class="footer-link">
                    ¿Ya tienes cuenta? Inicia sesión
                </a>
                <br>
                <a href="{{ route('home') }}" class="footer-link">
                    ← Volver a la tienda
                </a>
            </div>

        </div>
    </div>

</x-guest-layout>
