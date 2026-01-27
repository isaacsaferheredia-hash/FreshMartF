<footer class="bg-dark text-white pt-5 mt-5">
    <div class="container pb-4">

        <div class="row g-4">

            {{-- COMPRAR (categorías reales) --}}
            <div class="col-6 col-md-3">
                <h5 class="fw-bold mb-3">Comprar</h5>
                <ul class="list-unstyled">
                    @foreach ($categories as $cat)
                        <li class="mb-2">
                            <a href="{{ route('categoria.show', $cat->id_tipo) }}"
                               class="text-white-50 text-decoration-none d-flex align-items-center gap-2 footer-hover">
                                <i class="fa-solid fa-chevron-right small opacity-0 arrow-animate"></i>
                                {{ $cat->tipo_descripcion }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- AYUDA --}}
            <div class="col-6 col-md-3">
                <h5 class="fw-bold mb-3">Ayuda</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('contacto') }}"
                           class="text-white-50 text-decoration-none d-flex align-items-center gap-2 footer-hover">
                            <i class="fa-solid fa-chevron-right small opacity-0 arrow-animate"></i>
                            Contacto
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('envios') }}"
                           class="text-white-50 text-decoration-none d-flex align-items-center gap-2 footer-hover">
                            <i class="fa-solid fa-chevron-right small opacity-0 arrow-animate"></i>
                            Envíos
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('devoluciones') }}"
                           class="text-white-50 text-decoration-none d-flex align-items-center gap-2 footer-hover">
                            <i class="fa-solid fa-chevron-right small opacity-0 arrow-animate"></i>
                            Devoluciones
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('faq') }}"
                           class="text-white-50 text-decoration-none d-flex align-items-center gap-2 footer-hover">
                            <i class="fa-solid fa-chevron-right small opacity-0 arrow-animate"></i>
                            FAQ
                        </a>
                    </li>
                </ul>
            </div>

            {{-- NOSOTROS --}}
            <div class="col-6 col-md-3">
                <h5 class="fw-bold mb-3">Nosotros</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('quienes.somos') }}"
                           class="text-white-50 text-decoration-none d-flex align-items-center gap-2 footer-hover">
                            <i class="fa-solid fa-chevron-right small opacity-0 arrow-animate"></i>
                            Quiénes somos
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('ubicaciones') }}"
                           class="text-white-50 text-decoration-none d-flex align-items-center gap-2 footer-hover">
                            <i class="fa-solid fa-chevron-right small opacity-0 arrow-animate"></i>
                            Ubicaciones
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('blog') }}"
                           class="text-white-50 text-decoration-none d-flex align-items-center gap-2 footer-hover">
                            <i class="fa-solid fa-chevron-right small opacity-0 arrow-animate"></i>
                            Blog
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <hr class="border-secondary mt-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3">

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('home') }}"
                   class="px-3 py-2 bg-success text-white rounded-4 fw-bold shadow-sm text-decoration-none">
                    FreshMart
                </a>
                <span class="text-white-50 small">
                    Tu tienda de alimentos frescos
                </span>
            </div>

            <p class="text-white-50 small mt-3 mt-md-0">
                © 2025 FreshMart. Todos los derechos reservados.
            </p>

        </div>
    </div>
</footer>
