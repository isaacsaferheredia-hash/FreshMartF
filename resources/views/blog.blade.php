<x-frontlayout>

    <section class="container my-5">

        {{-- Encabezado --}}
        <div class="mb-4">
            <h1 class="fw-bold">Blog FreshMart</h1>
            <p class="text-muted">
                Consejos, recetas y novedades para una alimentación saludable.
            </p>
        </div>

        <div class="row g-4">

            {{-- Post 1 --}}
            <div class="col-md-4">
                <div class="bg-white rounded-4 shadow hover-up overflow-hidden h-100">

                    <img src="/img/blog/blog-1.webp
                        " class="w-100" alt="Recetas saludables">

                    <div class="p-4">
                        <span class="badge bg-success mb-2">Recetas</span>
                        <h5 class="fw-bold mb-2">
                            Ideas rápidas para comidas saludables
                        </h5>
                        <p class="text-muted small">
                            Descubre recetas fáciles y nutritivas usando productos frescos.
                        </p>
                        <a href="{{ route('blog.recetas') }}" class="btn btn-link text-success fw-semibold p-0">
                            Leer más <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>

                </div>
            </div>

            {{-- Post 2 --}}
            <div class="col-md-4">
                <div class="bg-white rounded-4 shadow hover-up overflow-hidden h-100">

                    <img src="/img/blog/blog-2.jpg" class="w-100" alt="Frutas frescas">

                    <div class="p-4">
                        <span class="badge bg-warning text-dark mb-2">Consejos</span>
                        <h5 class="fw-bold mb-2">
                            ¿Cómo elegir frutas y verduras frescas?
                        </h5>
                        <p class="text-muted small">
                            Aprende a identificar productos de calidad al momento de comprar.
                        </p>
                        <a href="{{ route('blog.frutas') }}" class="btn btn-link text-success fw-semibold p-0">
                            Leer más <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>

                </div>
            </div>

            {{-- Post 3 --}}
            <div class="col-md-4">
                <div class="bg-white rounded-4 shadow hover-up overflow-hidden h-100">

                    <img src="/img/blog/blog-3.jpg" class="w-100" alt="Novedades FreshMart">

                    <div class="p-4">
                        <span class="badge bg-info mb-2">Novedades</span>
                        <h5 class="fw-bold mb-2">
                            Nuevos productos llegan a FreshMart
                        </h5>
                        <p class="text-muted small">
                            Conoce las últimas incorporaciones a nuestro catálogo.
                        </p>
                        <a href="{{ route('blog.novedades') }}" class="btn btn-link text-success fw-semibold p-0">
                            Leer más <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </section>

</x-frontlayout>
