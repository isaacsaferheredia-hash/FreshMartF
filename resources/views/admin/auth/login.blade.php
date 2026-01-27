<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-sm border-0 p-4" style="width: 360px; border-radius: 16px;">

        {{-- TÍTULO --}}
        <h4 class="fw-bold mb-2 text-center">Acceso Administrativo</h4>
        <p class="text-muted text-center small mb-4">
            Solo personal autorizado
        </p>

        {{-- FORM --}}
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required
                       autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Contraseña</label>
                <input type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required>
            </div>

            {{-- ERROR --}}
            @if($errors->any())
                <div class="alert alert-danger py-2 small">
                    {{ $errors->first() }}
                </div>
            @endif

            <button class="btn btn-success w-100 fw-bold mt-2">
                Ingresar
            </button>
        </form>

        {{-- VOLVER --}}
        <div class="text-center mt-3">
            <a href="{{ route('login') }}"
               class="text-decoration-none text-muted small">
                ← Volver al login general
            </a>
        </div>

    </div>
</div>

</body>
</html>
