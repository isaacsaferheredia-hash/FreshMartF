<x-frontlayout>

    {{-- HEADER --}}
    <section class="container my-5">
        <div class="p-4 p-md-5 bg-success text-white rounded-4 shadow-lg">
            <h1 class="fw-bold display-6">
                {{ $categoria->tipo_descripcion }}
            </h1>
            <p class="fs-5 mt-2">
                Productos disponibles en esta categoría
            </p>
        </div>
    </section>

    {{-- PRODUCTOS --}}
    <section class="container my-5">
        <div class="row g-4">

            @forelse ($productos as $p)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card bg-white rounded-4 shadow hover-up h-100 d-flex flex-column">

                        {{-- IMAGEN --}}
                        <a href="{{ route('producto.detalle', $p->id_producto) }}"
                           class="ratio ratio-1x1 bg-light">
                            <img src="{{ asset('img/products/' . $p->pro_img) }}"
                                 class="product-img"
                                 alt="{{ $p->pro_descripcion }}">
                        </a>

                        {{-- INFO --}}
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <h5 class="fw-semibold mb-1">
                                {{ $p->pro_descripcion }}
                            </h5>

                            <p class="text-muted small mb-2">
                                {{ $p->pro_um_venta }}
                            </p>

                            <span class="text-success fw-bold fs-4 mb-3">
                                ${{ number_format($p->pro_precio_venta, 2) }}
                            </span>

                            {{-- BOTONES --}}
                            <div class="mt-auto d-grid gap-2">
                                <a href="{{ route('producto.detalle', $p->id_producto) }}"
                                   class="btn btn-outline-success">
                                    <i class="fa fa-eye"></i> Ver detalle
                                </a>

                                @auth
                                    <button
                                        type="button"
                                        class="btn btn-success rounded-3"
                                        onclick="agregarAlCarrito('{{ $p->id_producto }}')">
                                        <i class="fa fa-cart-plus"></i> Agregar al carrito
                                    </button>
                                @endauth
                                @guest
                                    <a href="{{ route('login') }}"
                                       class="btn btn-success rounded-3"
                                       title="Debes iniciar sesión para comprar">
                                        <i class="fa fa-lock"></i> Inicia sesión para comprar
                                    </a>
                                @endguest

                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center rounded-4">
                        No hay productos en esta categoría.
                    </div>
                </div>
            @endforelse

        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $productos->links('pagination::bootstrap-5') }}
        </div>
    </section>

    {{-- TOAST --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-4">
        <div id="toastCarrito"
             class="toast align-items-center text-bg-success border-0">
            <div class="d-flex">
                <div class="toast-body" id="toastMensaje">
                    Producto agregado al carrito
                </div>
                <button type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script>
        function agregarAlCarrito(productoId) {
            fetch('{{ route('carrito.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    pro_id: productoId,
                    cantidad: 1
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.ok) {
                        mostrarToast(data.msg);
                        actualizarContador(data.cartCount);
                    }
                })
                .catch(() => {
                    mostrarToast('Error al agregar al carrito', true);
                });
        }

        function actualizarContador(total) {
            const badge = document.getElementById('cartCount');
            if (badge) {
                badge.textContent = total;
                badge.classList.add('animate__animated', 'animate__bounce');
                setTimeout(() => {
                    badge.classList.remove('animate__animated', 'animate__bounce');
                }, 600);
            }
        }

        function mostrarToast(mensaje, error = false) {
            const toastEl = document.getElementById('toastCarrito');
            const toastMsg = document.getElementById('toastMensaje');

            toastMsg.textContent = mensaje;

            toastEl.classList.remove('text-bg-success', 'text-bg-danger');
            toastEl.classList.add(error ? 'text-bg-danger' : 'text-bg-success');

            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    </script>

</x-frontlayout>
