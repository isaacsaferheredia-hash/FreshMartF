<x-frontlayout>

    {{-- HERO --}}
    <section class="container my-5">
        <div class="p-4 p-md-5 bg-success text-white rounded-4 shadow-lg">
            <h1 class="fw-bold display-5">Tienda FreshMart</h1>
            <p class="fs-5 mt-2">
                Compra alimentos frescos, saludables y al mejor precio.
            </p>
        </div>
    </section>

    {{-- CONTENIDO --}}
    <section class="container my-5">
        <div class="row">

            {{-- SIDEBAR --}}
            <aside class="col-md-3 mb-4">
                <div class="bg-white shadow-sm rounded-4 p-4">

                    <h5 class="fw-bold mb-3">Categorías</h5>
                    <ul class="list-unstyled">
                        @foreach ($categories as $cat)
                            <li class="mb-1">
                                <a href="{{ route('categoria.show', $cat->id_tipo) }}"
                                   class="filter-link text-decoration-none">
                                    {{ $cat->tipo_descripcion }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </aside>

            {{-- PRODUCTOS --}}
            <div class="col-md-9">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold">Todos los Productos</h3>

                    <form method="GET" action="{{ url()->current() }}">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <select name="orden"
                                class="form-select w-auto shadow-sm"
                                onchange="this.form.submit()">
                            <option value="">Ordenar por</option>

                            <option value="precio_asc"
                                {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>
                                Precio menor
                            </option>

                            <option value="precio_desc"
                                {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>
                                Precio mayor
                            </option>
                        </select>
                    </form>
                </div>

                <div class="row g-4">

                    @forelse ($products as $p)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card bg-white rounded-4 shadow hover-up d-flex flex-column h-100">

                                {{-- IMAGEN (DETALLE) --}}
                                <a href="{{ route('producto.detalle', $p->id_producto) }}"
                                   class="ratio ratio-1x1 bg-light">
                                    <img
                                        src="{{ asset('img/products/' . $p->pro_img) }}"
                                        class="product-img"
                                        alt="{{ $p->pro_descripcion }}"
                                    >
                                </a>

                                {{-- INFO --}}
                                <div class="p-3 d-flex flex-column flex-grow-1">

                                    <h5 class="fw-semibold mb-1 product-title"
                                        title="{{ $p->pro_descripcion }}">
                                        {{ $p->pro_descripcion }}
                                    </h5>

                                    <p class="text-muted small mb-2">
                                        {{ $p->pro_um_venta }}
                                    </p>

                                    <span class="text-success fw-bold fs-4 mt-auto mb-3">
                                        ${{ number_format($p->pro_precio_venta, 2) }}
                                    </span>

                                    {{-- BOTONES --}}
                                    <div class="d-grid gap-2">

                                        <a href="{{ route('producto.detalle', $p->id_producto) }}"
                                           class="btn btn-outline-success rounded-3">
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
                            <div class="alert alert-warning rounded-4 text-center">
                                No hay productos disponibles en este momento.
                            </div>
                        </div>
                    @endforelse

                </div>

                {{-- PAGINACIÓN --}}
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>

            </div>

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
                badge.classList.add('fw-bold');
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
