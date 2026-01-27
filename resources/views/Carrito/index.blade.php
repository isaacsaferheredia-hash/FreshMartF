<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - FreshMart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/home.css">
</head>

<body class="bg-light">

@include('layouts.header')

<div class="container py-4">

    <h1 class="mb-4">🛒 Carrito de Compras</h1>

    {{-- NO EXISTE CARRITO --}}
    @if(!$carrito)
        <div class="alert alert-info">
            No existe un carrito activo.
        </div>

        {{-- CARRITO VACÍO --}}
    @elseif($detalles->isEmpty())
        <div class="alert alert-warning">
            El carrito está vacío.
        </div>

        {{-- CARRITO CON PRODUCTOS --}}
    @else

        {{-- BUSCADOR --}}
        <div class="row mb-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Buscar en el carrito</label>
                <input type="text" id="buscarCarrito" class="form-control"
                       placeholder="Ej: arroz, leche...">
            </div>

            <div class="col-md-2">
                <button type="button" id="btnBuscarCarrito" class="btn btn-primary w-100">
                    <i class="fa fa-search"></i> Buscar
                </button>
            </div>

            <div class="col-md-2">
                <button type="button" id="btnLimpiarCarrito" class="btn btn-outline-secondary w-100">
                    Limpiar
                </button>
            </div>
        </div>

        {{-- TABLA --}}
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-light">
            <tr>
                <th>Producto</th>
                <th width="180">Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
                <th width="100">Acción</th>
            </tr>
            </thead>
            <tbody>

            @foreach($detalles as $d)
                <tr data-row-id="{{ $d->car_det_id }}">
                    <td class="producto-nombre">{{ $d->pro_descripcion }}</td>

                    {{-- CANTIDAD + / - --}}
                    <td>
                        <div class="input-group input-group-sm cantidad-group"
                             data-id="{{ $d->car_det_id }}"
                             data-stock="{{ $d->pro_saldo_final }}">


                        <button class="btn btn-outline-secondary btn-minus" type="button">
                                <i class="fa fa-minus"></i>
                            </button>

                            <input
                                type="text"
                                class="form-control text-center cantidad-input"
                                value="{{ (int) $d->cantidad }}"
                                readonly
                            >

                            <button class="btn btn-outline-secondary btn-plus" type="button">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>

                        <small class="text-danger d-none stock-error">
                            Stock insuficiente
                        </small>

                    </td>

                    <td>${{ number_format($d->precio_unit, 2) }}</td>

                    {{-- SUBTOTAL FILA --}}
                    <td class="subtotal-fila">
                        ${{ number_format($d->subtotal, 2) }}
                    </td>

                    <td>
                        <form method="POST"
                              action="{{ route('carrito.destroy', $d->car_det_id) }}"
                              onsubmit="return confirm('¿Eliminar este producto?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

        {{-- TOTALES --}}
        <div class="text-end mt-3">
            <p>Subtotal:
                <strong id="cartSubtotal">${{ number_format($carrito->car_subtotal, 2) }}</strong>
            </p>
            <p>IVA:
                <strong id="cartIva">${{ number_format($carrito->car_iva, 2) }}</strong>
            </p>
            <h4>Total:
                <span id="cartTotal">${{ number_format($carrito->car_total, 2) }}</span>
            </h4>
        </div>

        {{-- ACCIONES --}}
        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <a href="{{ route('tienda') }}" class="btn btn-outline-primary">
                <i class="fa fa-arrow-left"></i> Seguir comprando
            </a>

            <a href="{{ route('checkout.index') }}" class="btn btn-success">
                Ir a pagar
            </a>
        </div>

    @endif
</div>

@include('layouts.footer')

{{-- ================== SCRIPT CANTIDAD SIN RECARGA ================== --}}
<script>
    document.querySelectorAll('.cantidad-group').forEach(group => {

        const btnMinus = group.querySelector('.btn-minus');
        const btnPlus  = group.querySelector('.btn-plus');
        const input    = group.querySelector('.cantidad-input');
        const fila     = group.closest('tr');
        const subtotalTd = fila.querySelector('.subtotal-fila');
        const carDetId = group.dataset.id;

        function actualizarCantidad(nuevaCantidad) {
            if (nuevaCantidad < 1) return;

            btnMinus.disabled = true;
            btnPlus.disabled = true;

            fetch(`/carrito/${carDetId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ cantidad: nuevaCantidad })
            })
                .then(res => res.json())
                .then(data => {
                    input.value = data.cantidad;

                    subtotalTd.textContent =
                        `$${Number(data.subtotal_producto).toFixed(2)}`;

                    document.getElementById('cartSubtotal').textContent =
                        `$${Number(data.car_subtotal).toFixed(2)}`;

                    document.getElementById('cartIva').textContent =
                        `$${Number(data.car_iva).toFixed(2)}`;

                    document.getElementById('cartTotal').textContent =
                        `$${Number(data.car_total).toFixed(2)}`;
                })
                .catch(() => {
                    alert('Error al actualizar el carrito');
                })
                .finally(() => {
                    btnMinus.disabled = false;
                    btnPlus.disabled = false;
                });
        }

        btnPlus.addEventListener('click', () => {
            actualizarCantidad(parseInt(input.value, 10) + 1);
        });

        btnMinus.addEventListener('click', () => {
            actualizarCantidad(parseInt(input.value, 10) - 1);
        });
    });
</script>

{{-- ================== BUSCADOR ================== --}}
<script>
    const inputBuscar = document.getElementById('buscarCarrito');
    const btnBuscar = document.getElementById('btnBuscarCarrito');
    const btnLimpiar = document.getElementById('btnLimpiarCarrito');

    function filtrarCarrito() {
        const texto = inputBuscar.value.trim().toLowerCase();
        document.querySelectorAll('tbody tr').forEach(fila => {
            const nombre = fila.querySelector('.producto-nombre').textContent.toLowerCase();
            fila.style.display = nombre.includes(texto) ? '' : 'none';
        });
    }

    btnBuscar?.addEventListener('click', filtrarCarrito);

    btnLimpiar?.addEventListener('click', () => {
        inputBuscar.value = '';
        document.querySelectorAll('tbody tr').forEach(fila => fila.style.display = '');
    });
</script>


{{-- ================== SCRIPT CANTIDAD SIN RECARGA + STOCK (BLOQUEO +) ================== --}}
<script>
    document.querySelectorAll('.cantidad-group').forEach(group => {

        const btnMinus = group.querySelector('.btn-minus');
        const btnPlus  = group.querySelector('.btn-plus');
        const input    = group.querySelector('.cantidad-input');
        const fila     = group.closest('tr');
        const subtotalTd = fila.querySelector('.subtotal-fila');
        const carDetId = group.dataset.id;
        const stockMax = parseInt(group.dataset.stock, 10);
        const errorMsg = fila.querySelector('.stock-error');

        function marcarErrorStock() {
            input.classList.add('is-invalid');
            errorMsg.classList.remove('d-none');
            btnPlus.disabled = true; // 🔒 BLOQUEA +
        }

        function limpiarErrorStock() {
            input.classList.remove('is-invalid');
            errorMsg.classList.add('d-none');
            btnPlus.disabled = false; // 🔓 HABILITA +
        }

        function validarLimite() {
            const actual = parseInt(input.value, 10);

            if (actual >= stockMax) {
                marcarErrorStock();
            } else {
                limpiarErrorStock();
            }
        }

        function actualizarCantidad(nuevaCantidad) {
            if (nuevaCantidad < 1) return;

            btnMinus.disabled = true;
            btnPlus.disabled = true;

            fetch(`/carrito/${carDetId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ cantidad: nuevaCantidad })
            })
                .then(res => res.json())
                .then(data => {
                    input.value = data.cantidad;

                    subtotalTd.textContent =
                        `$${Number(data.subtotal_producto).toFixed(2)}`;

                    document.getElementById('cartSubtotal').textContent =
                        `$${Number(data.car_subtotal).toFixed(2)}`;

                    document.getElementById('cartIva').textContent =
                        `$${Number(data.car_iva).toFixed(2)}`;

                    document.getElementById('cartTotal').textContent =
                        `$${Number(data.car_total).toFixed(2)}`;

                    validarLimite();
                })
                .catch(() => {
                    alert('Error al actualizar el carrito');
                })
                .finally(() => {
                    btnMinus.disabled = false;
                });
        }

        btnPlus.addEventListener('click', () => {
            const actual = parseInt(input.value, 10);
            const nueva  = actual + 1;

            if (nueva > stockMax) {
                marcarErrorStock();
                return;
            }

            actualizarCantidad(nueva);
        });

        btnMinus.addEventListener('click', () => {
            const actual = parseInt(input.value, 10);
            const nueva  = actual - 1;

            if (nueva >= 1) {
                actualizarCantidad(nueva);
            }
        });

        // 🔍 Validación inicial al cargar la página
        validarLimite();
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
