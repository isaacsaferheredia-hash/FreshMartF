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
        }

        h2 {
            margin-top: 20px;
            margin-bottom: 8px;
            font-size: 26px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 15px;
            margin-bottom: 28px;
        }

        /* ===== INLINE FIELDS ===== */

        .field-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        label {
            font-weight: 600;
            font-size: 14px;
        }

        .inline-error {
            font-size: 13px;
            color: #b91c1c;
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .inline-error::before {
            content: "⚠️";
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
            <div style="text-align:center;margin-bottom:28px;">
                <div class="brand">FreshMart</div>
                <h2>Crear contraseña</h2>
                <p class="subtitle">
                    Define una contraseña para finalizar tu registro
                </p>
            </div>

            {{-- ERROR GENERAL --}}
            @if ($errors->any())
                <div class="form-alert">
                    La contraseña no cumple con los requisitos. Revisa e inténtalo nuevamente.
                </div>
            @endif

            <form method="POST" action="{{ route('register.user.store') }}">
                @csrf

                <input type="hidden" name="cliente_id" value="{{ $cliente_id }}">

                {{-- CONTRASEÑA --}}
                <div style="margin-bottom:24px;">
                    <div class="field-header">
                        <label for="password">Contraseña</label>
                        @error('password')
                        <span class="inline-error">Mín. 8 caracteres</span>
                        @enderror
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="input @error('password') input-error @enderror"
                        placeholder="••••••••"
                        required
                    >
                </div>

                {{-- CONFIRMAR --}}
                <div style="margin-bottom:28px;">
                    <div class="field-header">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        @error('password_confirmation')
                        <span class="inline-error">No coincide</span>
                        @enderror
                    </div>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="input @error('password_confirmation') input-error @enderror"
                        placeholder="••••••••"
                        required
                    >
                </div>

                {{-- BOTÓN --}}
                <button type="submit" class="btn-primary">
                    Crear cuenta
                </button>
            </form>

            {{-- FOOTER --}}
            <div style="text-align:center;margin-top:32px;">
                <a href="{{ route('tienda') }}" class="footer-link">
                    ← Volver a la tienda
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>
