<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago exitoso - FreshMart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .loader-overlay {
            position: fixed;
            inset: 0;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loader-box {
            text-align: center;
        }

        .success-card {
            max-width: 620px;
            margin: auto;
            border-radius: 20px;
        }

        .success-icon {
            font-size: 70px;
            color: #16a34a;
        }
    </style>
</head>

<body class="bg-light">

{{-- LOADER --}}
<div class="loader-overlay" id="loader">
    <div class="loader-box">
        <div class="spinner-border text-success mb-3" role="status"></div>
        <p class="fw-semibold text-muted">Procesando pago…</p>
    </div>
</div>

@include('layouts.header')

{{-- CONTENIDO --}}
<div class="container py-5 text-center d-none" id="successContent">
    <div class="card shadow border-0 p-5 success-card">

        <div class="success-icon mb-3">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <h2 class="fw-bold mb-2 text-success">¡Pago confirmado!</h2>

        <p class="text-muted mb-4">
            Tu pedido ha sido procesado correctamente.<br>
            En breve recibirás un correo con los detalles de tu compra.
        </p>

        <div class="d-grid gap-3 col-md-8 mx-auto">

            {{-- MIS COMPRAS --}}
            <a href="{{ route('ClienteFacturas.index') }}"
               class="btn btn-outline-success btn-lg">
                <i class="fa-solid fa-box me-2"></i>
                Mis compras
            </a>

            {{-- INICIO --}}
            <a href="{{ route('home') }}"
               class="btn btn-link text-decoration-none text-muted">
                Volver al inicio
            </a>

        </div>

        <hr class="my-4">

        <p class="small text-muted">
            ¿Necesitas ayuda?
            <a href="/contacto" class="text-success fw-semibold text-decoration-none">
                Contáctanos
            </a>
        </p>
    </div>
</div>

<script>
    // Simula procesamiento del pago
    setTimeout(() => {
        document.getElementById('loader').classList.add('d-none');
        document.getElementById('successContent').classList.remove('d-none');
    }, 1800); // 1.8 segundos
</script>

</body>
</html>
