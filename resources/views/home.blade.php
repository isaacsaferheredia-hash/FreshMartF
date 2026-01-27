<x-frontlayout>

    {{-- HERO --}}
    <section class="container my-5">
        <div class="hero-banner rounded-4 overflow-hidden shadow-lg position-relative">
            <div class="row g-0 align-items-center p-4 p-md-5">

                <div class="col-md-6 text-white">
                    <div class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3 rounded-3">
                        <i class="fa-solid fa-tag"></i> Ofertas de la Semana
                    </div>

                    <h1 class="display-4 fw-bold mb-3">
                        Hasta <span class="text-warning">50% OFF</span>
                    </h1>

                    <p class="fs-4 opacity-75">
                        En frutas y verduras frescas de la temporada
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-3 mt-4">
                        <a href="/tienda"
                           class="btn btn-light text-success fw-semibold px-4 py-3 rounded-3 shadow-sm">
                            Ver ofertas <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 mt-4 mt-md-0">
                    <img src="/img/foods/hero-grocery.jpg"
                         class="img-fluid rounded-4 shadow-lg hero-image"
                         alt="Imagen de compras de alimentos">
                </div>

            </div>
        </div>
    </section>

    {{-- BENEFICIOS --}}
    <section class="container my-5">
        <div class="row g-4">

            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow feature-box d-flex align-items-center gap-3">
                    <div class="icon-circle bg-success bg-opacity-25 text-success">
                        <i class="fa-solid fa-truck-fast fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Envío Gratis</h5>
                        <p class="text-muted small mb-0">En compras sobre $50</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow feature-box d-flex align-items-center gap-3">
                    <div class="icon-circle bg-success bg-opacity-25 text-success">
                        <i class="fa-solid fa-shield-halved fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Compra Segura</h5>
                        <p class="text-muted small mb-0">Garantía 100%</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow feature-box d-flex align-items-center gap-3">
                    <div class="icon-circle bg-success bg-opacity-25 text-success">
                        <i class="fa-solid fa-clock fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Entrega Rápida</h5>
                        <p class="text-muted small mb-0">En 24-48 horas</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- CATEGORÍAS --}}
    <section class="container my-5">
        <div class="row g-4">
            @foreach ($categories as $cat)
                <div class="col-6 col-md-3">
                    <a href="{{ route('categoria.show', $cat->id_tipo) }}"
                       class="text-decoration-none text-dark">
                        <div class="category-box shadow rounded-4 p-4 bg-white text-center hover-up">
                            <img src="/img/categories/{{ $cat->img }}"
                                 class="category-img mb-3"
                                 alt="{{ $cat->tipo_descripcion }}">
                            <h5 class="fw-semibold">
                                {{ $cat->tipo_descripcion }}
                            </h5>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- DESTACADOS --}}
    <section class="container my-5">
        <div class="row g-4">

            @forelse ($destacados as $p)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card bg-white rounded-4 shadow hover-up overflow-hidden">

                        <div class="ratio ratio-1x1 bg-light">
                            <img src="/img/products/{{ $p->pro_img }}"
                                 class="product-img"
                                 alt="{{ $p->pro_descripcion }}">
                        </div>

                        <div class="p-3">
                            <h5 class="fw-semibold mb-1 product-title">
                                {{ $p->pro_descripcion }}
                            </h5>

                            <p class="text-muted small mb-2">
                                {{ $p->pro_um_venta }}
                            </p>

                            <span class="text-success fw-bold fs-4">
                                ${{ number_format($p->pro_precio_venta, 2) }}
                            </span>

                            {{-- BOTONES ORDENADOS --}}
                            <div class="d-grid gap-2 mt-3">

                                <a href="{{ route('producto.detalle', $p->id_producto) }}"
                                   class="btn btn-outline-success w-100">
                                    <i class="fa fa-eye"></i> Ver producto
                                </a>

                                @auth
                                    <button
                                        type="button"
                                        class="btn btn-success w-100"
                                        onclick="agregarAlCarrito('{{ $p->id_producto }}')">
                                        <i class="fa fa-cart-plus"></i> Agregar al carrito
                                    </button>
                                @endauth

                                @guest
                                    <a href="{{ route('login') }}"
                                       class="btn btn-success w-100"
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
                        No hay productos destacados en este momento.
                    </div>
                </div>
            @endforelse

        </div>
    </section>

    {{-- TOAST --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-4">
        <div id="toastCarrito"
             class="toast align-items-center text-bg-success border-0"
             role="alert">
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

    <script>
        function agregarAlCarrito(productoId, cantidad = 1) {

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
                .then(r => r.json())
                .then(data => {

                    if (!data.ok) {
                        mostrarToast(data.msg || 'Error', true);
                        return;
                    }

                    mostrarToast(data.msg);

                    // 🔥 ACTUALIZAR HEADER (aquí está la clave)
                    if (typeof window.actualizarContadorCarrito === 'function') {
                        window.actualizarContadorCarrito(data.cartCount);
                    } else {
                        console.warn('Función actualizarContadorCarrito no encontrada');
                    }
                })
                .catch(() => {
                    mostrarToast('Error al agregar al carrito', true);
                });
        }
    </script>

</x-frontlayout>
