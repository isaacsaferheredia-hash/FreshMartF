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
            max-width: 620px;
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

        /* ===== FORM ===== */

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

        .input, select {
            width: 100%;
            padding: 14px 16px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            font-size: 15px;
            transition: all .2s ease;
        }

        .input:focus, select:focus {
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

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 640px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="register-wrapper">
        <div class="register-card">

            {{-- HEADER --}}
            <div style="text-align:center;margin-bottom:28px;">
                <div class="brand">FreshMart</div>
                <h2>Crear cuenta</h2>
                <p class="subtitle">
                    Completa tus datos para finalizar el registro
                </p>
            </div>

            {{-- ERROR GENERAL --}}
            @if ($errors->any())
                <div class="form-alert">
                    Algunos datos no son válidos. Revisa la información e inténtalo nuevamente.
                </div>
            @endif

            <form method="POST" action="{{ route('register.full.store') }}">
                @csrf

                <input type="hidden" name="cli_ruc_ced" value="{{ session('cli_ruc_ced') }}">

                {{-- NOMBRE --}}
                <div style="margin-bottom:20px;">
                    <div class="field-header">
                        <label>Nombre completo</label>
                        @error('cli_nombre')
                        <span class="inline-error">Obligatorio</span>
                        @enderror
                    </div>
                    <input
                        name="cli_nombre"
                        value="{{ old('cli_nombre') }}"
                        class="input @error('cli_nombre') input-error @enderror"
                        required
                    >
                </div>

                {{-- CORREO --}}
                <div style="margin-bottom:20px;">
                    <div class="field-header">
                        <label>Correo electrónico</label>
                        @error('cli_mail')
                        <span class="inline-error">Correo inválido</span>
                        @enderror
                    </div>
                    <input
                        name="cli_mail"
                        type="email"
                        value="{{ old('cli_mail') }}"
                        class="input @error('cli_mail') input-error @enderror"
                        required
                    >
                </div>

                {{-- TELÉFONOS --}}
                <div class="grid-2">
                    <div>
                        <div class="field-header">
                            <label>Teléfono</label>
                            @error('cli_telefono')
                            <span class="inline-error">10 dígitos</span>
                            @enderror
                        </div>
                        <input
                            name="cli_telefono"
                            value="{{ old('cli_telefono') }}"
                            class="input @error('cli_telefono') input-error @enderror"
                        >
                    </div>

                    <div>
                        <div class="field-header">
                            <label>Celular</label>
                            @error('cli_celular')
                            <span class="inline-error">10 dígitos</span>
                            @enderror
                        </div>
                        <input
                            name="cli_celular"
                            value="{{ old('cli_celular') }}"
                            class="input @error('cli_celular') input-error @enderror"
                        >
                    </div>
                </div>

                {{-- DIRECCIÓN --}}
                <div style="margin-top:20px;">
                    <div class="field-header">
                        <label>Dirección</label>
                        @error('cli_direccion')
                        <span class="inline-error">Obligatoria</span>
                        @enderror
                    </div>
                    <input
                        name="cli_direccion"
                        value="{{ old('cli_direccion') }}"
                        class="input @error('cli_direccion') input-error @enderror"
                        required
                    >
                </div>

                {{-- CIUDAD --}}
                <div style="margin-top:20px;">
                    <div class="field-header">
                        <label>Ciudad</label>
                        @error('id_ciudad')
                        <span class="inline-error">Selecciona una</span>
                        @enderror
                    </div>
                    <select
                        name="id_ciudad"
                        class="@error('id_ciudad') input-error @enderror"
                        required
                    >
                        <option value="">Seleccione una ciudad</option>
                        @foreach ($ciudades as $ciudad)
                            <option
                                value="{{ $ciudad->id_ciudad }}"
                                {{ old('id_ciudad') == $ciudad->id_ciudad ? 'selected' : '' }}
                            >
                                {{ $ciudad->ciu_descripcion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr style="margin:32px 0;">

                {{-- CONTRASEÑA --}}
                <div style="margin-bottom:20px;">
                    <div class="field-header">
                        <label>Contraseña</label>
                        @error('password')
                        <span class="inline-error">Mín. 8 caracteres</span>
                        @enderror
                    </div>
                    <input
                        name="password"
                        type="password"
                        class="input @error('password') input-error @enderror"
                        required
                    >
                </div>

                {{-- CONFIRMAR --}}
                <div style="margin-bottom:28px;">
                    <label>Confirmar contraseña</label>
                    <input
                        name="password_confirmation"
                        type="password"
                        class="input"
                        required
                    >
                </div>

                {{-- BOTÓN --}}
                <button type="submit" class="btn-primary">
                    Registrarse
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
