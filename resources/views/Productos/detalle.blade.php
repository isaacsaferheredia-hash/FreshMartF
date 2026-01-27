<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $producto->pro_descripcion }} - FreshMart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-light">

@include('layouts.header')

<div class="container py-5">

    <nav class="mb-4">
        <a href="{{ route('tienda') }}" class="text-decoration-none text-success">
            ← Volver a la tienda
        </a>
    </nav>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-5">

            <div class="row g-5 align-items-center">

                {{-- IMAGEN --}}
                <div class="col-md-5 text-center">
                    <img
                        src="{{ asset('img/products/' . $producto->pro_img) }}"
                        class="img-fluid rounded-4"
                        alt="{{ $producto->pro_descripcion }}">
                </div>

                {{-- INFO --}}
                <div class="col-md-7">

                    <h1 class="fw-bold mb-2">{{ $producto->pro_descripcion }}</h1>

                    <h3 class="text-success fw-bold mb-3">
                        ${{ number_format($producto->pro_precio_venta, 2) }}
                    </h3>

                    {{-- STOCK --}}
                    <p class="mb-3">
                        <strong>Stock disponible:</strong>
                        <span id="stockTexto"
                              class="{{ $producto->pro_saldo_final > 0 ? 'text-success' : 'text-danger' }}">
                            {{ (int) $producto->pro_saldo_final }}
                        </span>
                    </p>

                    <p class="text-muted mb-4">
                        {{ $producto->pro_detalle ?? 'Producto de excelente calidad.' }}
                    </p>

                    {{-- CANTIDAD --}}
                    <div class="mb-3">
                        <span class="fw-semibold d-block mb-2">Cantidad</span>

                        <div class="input-group" style="width:160px;">
                            <button class="btn btn-outline-secondary" id="btnMinus">−</button>

                            <input type="number"
                                   id="cantidad"
                                   class="form-control text-center"
                                   value="1"
                                   readonly>

                            <button class="btn btn-outline-secondary" id="btnPlus">+</button>
                        </div>

                        <small id="stockError" class="text-danger d-none">
                            Stock insuficiente
                        </small>
                    </div>

                    {{-- BOTONES --}}
                    <div class="d-flex gap-3 flex-wrap mt-4">

                        @auth
                            <button type="button"
                                    id="btnAgregar"
                                    class="btn btn-success btn-lg">
                                <i class="fa fa-cart-plus"></i> Agregar al carrito
                            </button>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                                <i class="fa fa-lock"></i> Inicia sesión para comprar
                            </a>
                        @endguest

                        <a href="{{ route('tienda') }}"
                           class="btn btn-outline-secondary btn-lg">
                            Seguir comprando
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')

{{-- TOAST --}}
<div class="toast-container position-fixed bottom-0 end-0 p-4">
    <div id="toastCarrito"
         class="toast align-items-center text-bg-success border-0"
         role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMensaje"></div>
            <button type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- ================== SCRIPT FINAL ================== --}}
<script>
    const stockMax = {{ (int) $producto->pro_saldo_final }};
    const inputCantidad = document.getElementById('cantidad');
    const btnPlus = document.getElementById('btnPlus');
    const btnMinus = document.getElementById('btnMinus');
    const btnAgregar = document.getElementById('btnAgregar');
    const errorStock = document.getElementById('stockError');

    function actualizarUI() {
        const qty = parseInt(inputCantidad.value, 10);

        // 🔒 BLOQUEO EXACTO DEL +
        if (qty >= stockMax) {
            btnPlus.disabled = true;
            errorStock.classList.remove('d-none');
            inputCantidad.classList.add('is-invalid');
        } else {
            btnPlus.disabled = false;
            errorStock.classList.add('d-none');
            inputCantidad.classList.remove('is-invalid');
        }

        // 🚫 SIN STOCK → NO SE PUEDE AGREGAR
        if (stockMax <= 0) {
            btnAgregar.disabled = true;
        }
    }

    btnPlus.addEventListener('click', () => {
        const v = parseInt(inputCantidad.value, 10);
        if (v < stockMax) {
            inputCantidad.value = v + 1;
            actualizarUI();
        }
    });

    btnMinus.addEventListener('click', () => {
        const v = parseInt(inputCantidad.value, 10);
        if (v > 1) {
            inputCantidad.value = v - 1;
            actualizarUI();
        }
    });

    btnAgregar?.addEventListener('click', () => {
        const qty = parseInt(inputCantidad.value, 10);

        if (qty > stockMax || stockMax <= 0) {
            errorStock.classList.remove('d-none');
            return;
        }

        fetch('{{ route('carrito.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                pro_id: '{{ $producto->id_producto }}',
                cantidad: qty
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    document.getElementById('toastMensaje').textContent =
                        'Producto agregado al carrito';

                    new bootstrap.Toast(
                        document.getElementById('toastCarrito')
                    ).show();

                    // 🛒 ACTUALIZA CONTADOR GLOBAL
                    if (typeof window.actualizarContadorCarrito === 'function') {
                        window.actualizarContadorCarrito(data.cartCount);
                    }
                } else {
                    mostrarError(data.msg || 'No se pudo agregar');
                }
            })
            .catch(() => mostrarError('Error al agregar al carrito'));
    });

    function mostrarError(msg) {
        const toast = document.getElementById('toastCarrito');
        document.getElementById('toastMensaje').textContent = msg;
        toast.classList.remove('text-bg-success');
        toast.classList.add('text-bg-danger');
        new bootstrap.Toast(toast).show();
    }

    actualizarUI();
</script>

</body>
</html>
