{{-- BARRA PROMO --}}
<div class="bg-success text-white text-center py-1 small">
    🎉 Envío gratis en compras sobre $50 | Descuentos hasta 50% en productos seleccionados
</div>

<style>
    #cartCount.scale-up {
        transform: scale(1.4);
        transition: transform .2s ease;
    }
</style>

<header class="bg-white shadow-sm sticky-top">

    <nav class="navbar navbar-expand-md navbar-light bg-white">
        <div class="container">

            {{-- LOGO --}}
            <a href="{{ route('home') }}"
               class="px-3 py-2 bg-success text-white rounded-4 fw-bold shadow-sm text-decoration-none">
                <span class="d-none d-md-inline fs-4">FreshMart</span>
                <span class="d-md-none fs-5">FM</span>
            </a>

            {{-- HAMBURGUESA --}}
            <button class="navbar-toggler border-0"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainMenu">
                <i class="fa-solid fa-bars fs-4 text-secondary"></i>
            </button>

            {{-- CONTENIDO --}}
            <div class="collapse navbar-collapse" id="mainMenu">

                {{-- MENÚ --}}
                <ul class="navbar-nav mx-auto gap-2 mt-3 mt-md-0">
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link fw-semibold text-secondary">Inicio</a></li>
                    <li class="nav-item"><a href="/quienes-somos" class="nav-link fw-semibold text-secondary">Quiénes somos</a></li>
                    <li class="nav-item"><a href="{{ route('tienda') }}" class="nav-link fw-semibold text-secondary">Tienda</a></li>
                    <li class="nav-item"><a href="{{ route('blog') }}" class="nav-link fw-semibold text-secondary">Blog</a></li>
                    <li class="nav-item"><a href="/contacto" class="nav-link fw-semibold text-secondary">Contacto</a></li>
                    <li class="nav-item"><a href="/faq" class="nav-link fw-semibold text-secondary">FAQ</a></li>
                </ul>

                {{-- BUSCADOR --}}
                <div class="position-relative d-none d-md-block" style="width:320px;">
                    <input type="text"
                           id="buscadorHeader"
                           class="form-control"
                           placeholder="Buscar productos..."
                           autocomplete="off">

                    <div id="resultadosHeader"
                         class="list-group position-absolute w-100 shadow"
                         style="z-index:2000; display:none;"></div>
                </div>

                {{-- ACCIONES --}}
                <div class="d-flex align-items-center gap-3 mt-3 mt-md-0">

                    @auth
                        <div class="dropdown">
                            <button class="btn d-flex align-items-center gap-2 dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                <i class="fa-regular fa-user fs-5 text-success"></i>
                                <span class="fw-medium">{{ auth()->user()->name }}</span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <a class="dropdown-item" href="{{ route('ClienteFacturas.index') }}">
                                        <i class="fa-solid fa-box me-2"></i> Mis compras
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i>
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="btn fw-medium text-secondary">
                            <i class="fa-regular fa-user"></i> Ingresar
                        </a>
                    @endguest

                    {{-- CARRITO --}}
                    <a href="{{ route('carrito.index') }}"
                       class="btn position-relative bg-success bg-opacity-10 rounded-4 p-2">
                        <i class="fa-solid fa-cart-shopping text-success fs-4"></i>

                        <span id="cartCount"
                              class="position-absolute top-0 start-100 translate-middle
                                     badge rounded-pill bg-warning text-dark">
                            {{ $cartCount }}
                        </span>
                    </a>

                </div>
            </div>
        </div>
    </nav>

    {{-- 🔍 BUSCADOR JS --}}
    <script>
        const inputHeader = document.getElementById('buscadorHeader');
        const resultadosHeader = document.getElementById('resultadosHeader');
        let timer = null;

        if (inputHeader) {
            inputHeader.addEventListener('input', function () {
                clearTimeout(timer);
                const q = this.value.trim();

                if (q.length < 2) {
                    resultadosHeader.style.display = 'none';
                    resultadosHeader.innerHTML = '';
                    return;
                }

                timer = setTimeout(() => {
                    fetch(`{{ route('productos.buscar') }}?q=${encodeURIComponent(q)}`)
                        .then(res => res.json())
                        .then(data => {
                            resultadosHeader.innerHTML = '';

                            if (!data.length) {
                                resultadosHeader.style.display = 'none';
                                return;
                            }

                            data.forEach(p => {
                                resultadosHeader.innerHTML += `
                                    <a href="/tienda/producto/${p.id_producto}"
                                       class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                                        <img src="/img/products/${p.pro_img}"
                                             style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                        <div>
                                            <div class="fw-semibold">${p.pro_descripcion}</div>
                                            <small class="text-success">$${Number(p.pro_precio_venta).toFixed(2)}</small>
                                        </div>
                                    </a>
                                `;
                            });

                            resultadosHeader.style.display = 'block';
                        });
                }, 250);
            });

            document.addEventListener('click', e => {
                if (!inputHeader.contains(e.target)) {
                    resultadosHeader.style.display = 'none';
                }
            });
        }
    </script>

    {{-- 🛒 CONTADOR GLOBAL --}}
    <script>
        window.actualizarContadorCarrito = function (nuevoTotal) {
            const el = document.getElementById('cartCount');
            if (!el) return;

            el.textContent = nuevoTotal;
            el.classList.add('scale-up');
            setTimeout(() => el.classList.remove('scale-up'), 200);
        };
    </script>

</header>
