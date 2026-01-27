<x-frontlayout>

    <section class="container my-5">

        {{-- Encabezado --}}
        <div class="mb-4">
            <h1 class="fw-bold">Nuestras ubicaciones</h1>
            <p class="text-muted">
                Visítanos o solicita envíos desde nuestras sedes disponibles.
            </p>
        </div>

        <div class="row g-4">

            {{-- CUMBAYÁ --}}
            <div class="col-md-6">
                <div class="bg-white rounded-4 shadow overflow-hidden h-100">

                    <img src="/img/ubicaciones/cumbaya.png"
                         class="w-100"
                         alt="FreshMart Cumbayá">

                    <div class="p-4">
                        <h5 class="fw-bold mb-2">FreshMart Cumbayá</h5>

                        <p class="text-muted mb-3">
                            Av. Interoceánica y Calle Principal, Cumbayá – Quito
                        </p>

                        <ul class="list-unstyled text-muted mb-3">
                            <li class="mb-2">
                                <i class="fa-solid fa-clock text-success me-2"></i>
                                Lunes a Domingo · 08h00 – 20h00
                            </li>
                            <li class="mb-2">
                                <i class="fa-solid fa-truck text-success me-2"></i>
                                Envíos disponibles en el Valle
                            </li>
                        </ul>

                        <a href="https://www.google.com/maps?q=Cumbaya+Quito"
                           target="_blank"
                           class="btn btn-outline-success rounded-3">
                            Ver en el mapa
                        </a>
                    </div>
                </div>
            </div>

            {{-- LA CAROLINA --}}
            <div class="col-md-6">
                <div class="bg-white rounded-4 shadow overflow-hidden h-100">

                    <img src="/img/ubicaciones/carolina.png"
                         class="w-100"
                         alt="FreshMart La Carolina">

                    <div class="p-4">
                        <h5 class="fw-bold mb-2">FreshMart La Carolina</h5>

                        <p class="text-muted mb-3">
                            Av. República del Salvador y Naciones Unidas, Quito
                        </p>

                        <ul class="list-unstyled text-muted mb-3">
                            <li class="mb-2">
                                <i class="fa-solid fa-clock text-success me-2"></i>
                                Lunes a Domingo · 08h00 – 20h00
                            </li>
                            <li class="mb-2">
                                <i class="fa-solid fa-truck text-success me-2"></i>
                                Envíos disponibles zona norte
                            </li>
                        </ul>

                        <a href="https://www.google.com/maps?q=La+Carolina+Quito"
                           target="_blank"
                           class="btn btn-outline-success rounded-3">
                            Ver en el mapa
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </section>

</x-frontlayout>
