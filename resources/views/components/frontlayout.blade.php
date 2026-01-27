<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshMart - Tu tienda de alimentos frescos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/home.css">
</head>

<body class="bg-light">

@include('layouts.header')

<main>
    {{ $slot }}
</main>

@include('layouts.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- ================== TOAST GLOBAL ================== --}}
<div
    id="cartToast"
    class="toast align-items-center text-white bg-success border-0 position-fixed bottom-0 end-0 m-4"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    style="z-index:9999"
>
    <div class="d-flex">
        <div class="toast-body" id="cartToastText"></div>
        <button type="button"
                class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"></button>
    </div>
</div>

{{-- ================== JS GLOBAL ================== --}}
<script>
    /* ===== TOAST GLOBAL ===== */
    window.mostrarToast = function (mensaje, error = false) {
        const toastEl  = document.getElementById('cartToast');
        const toastMsg = document.getElementById('cartToastText');

        if (!toastEl || !toastMsg) return;

        toastMsg.textContent = mensaje;
        toastEl.classList.remove('bg-success', 'bg-danger');
        toastEl.classList.add(error ? 'bg-danger' : 'bg-success');

        bootstrap.Toast.getOrCreateInstance(toastEl).show();
    };

    /* ===== AGREGAR AL CARRITO ===== */
    window.agregarAlCarrito = function (productoId, cantidad = 1) {
        fetch('{{ route('carrito.store') }}', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                pro_id: productoId,
                cantidad: cantidad
            })
        })
            .then(res => res.json())
            .then(data => {
                if (!data.ok) {
                    mostrarToast(data.msg || 'No se pudo agregar al carrito', true);
                    return;
                }

                mostrarToast(data.msg || 'Producto agregado');

                // 🔔 ACTUALIZAR CONTADOR
                if (typeof data.cartCount !== 'undefined') {
                    const cartCountEl = document.getElementById('cartCount');
                    if (cartCountEl) {
                        cartCountEl.textContent = data.cartCount;
                        cartCountEl.classList.add('scale-up');
                        setTimeout(() => cartCountEl.classList.remove('scale-up'), 200);
                    }
                }
            })
            .catch(() => {
                mostrarToast('Error al agregar al carrito', true);
            });
    };
</script>

<style>
    #cartCount.scale-up {
        transform: scale(1.4);
        transition: transform .2s ease;
    }
</style>

</body>
</html>
