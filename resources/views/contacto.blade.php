<x-frontlayout>

    <section class="container my-5">
        <div class="mb-4">
            <h1 class="fw-bold">Contacto</h1>
            <p class="text-muted">
                ¿Tienes alguna duda? Aquí encontrarás toda nuestra información de contacto.
            </p>
        </div>

        <div class="row g-4">

            {{-- Información --}}
            <div class="col-md-6">
                <div class="bg-white rounded-4 shadow p-4">

                    <h5 class="fw-bold mb-3">Información de la tienda</h5>

                    <ul class="list-unstyled text-muted">
                        <li class="mb-3 d-flex gap-3">
                            <i class="fa-solid fa-location-dot text-success fs-5"></i>
                            <span>Av. Principal 123, Quito - Ecuador</span>
                        </li>

                        <li class="mb-3 d-flex gap-3">
                            <i class="fa-solid fa-phone text-success fs-5"></i>
                            <span>+593 99 123 4567</span>
                        </li>

                        <li class="mb-3 d-flex gap-3">
                            <i class="fa-solid fa-envelope text-success fs-5"></i>
                            <span>contacto@freshmart.com</span>
                        </li>

                        <li class="mb-3 d-flex gap-3">
                            <i class="fa-solid fa-clock text-success fs-5"></i>
                            <span>Lunes a Domingo · 08h00 – 20h00</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Mapa --}}
            <div class="col-md-6">
                <div class="bg-white rounded-4 shadow p-3 h-100">
                    <iframe
                        src="https://www.google.com/maps?q=Quito%20Ecuador&output=embed"
                        class="w-100 h-100 rounded-4 border-0"
                        loading="lazy">
                    </iframe>
                </div>
            </div>

        </div>
    </section>

</x-frontlayout>
