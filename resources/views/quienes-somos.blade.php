<x-frontlayout>

    <section class="container my-5">

        {{-- Encabezado --}}
        <div class="mb-4">
            <h1 class="fw-bold">Quiénes somos</h1>
            <p class="text-muted">
                Conoce más sobre FreshMart, nuestra misión y el compromiso con la calidad.
            </p>
        </div>

        <div class="row g-4">

            {{-- Contenido principal --}}
            <div class="col-md-8">

                <div class="bg-white rounded-4 shadow p-4 mb-4">
                    <h5 class="fw-bold mb-3">Nuestra historia</h5>
                    <p class="text-muted">
                        FreshMart nace con el objetivo de acercar alimentos frescos y de calidad
                        directamente a los hogares, combinando tecnología y atención personalizada.
                    </p>
                    <p class="text-muted mb-0">
                        Trabajamos con proveedores locales para garantizar productos frescos,
                        precios justos y entregas confiables.
                    </p>
                </div>

                <div class="bg-white rounded-4 shadow p-4 mb-4">
                    <h5 class="fw-bold mb-3">Misión</h5>
                    <p class="text-muted mb-0">
                        Ofrecer una experiencia de compra ágil y segura, brindando alimentos
                        frescos que mejoren la calidad de vida de nuestros clientes.
                    </p>
                </div>

                <div class="bg-white rounded-4 shadow p-4">
                    <h5 class="fw-bold mb-3">Visión</h5>
                    <p class="text-muted mb-0">
                        Ser la tienda online de alimentos más confiable y reconocida,
                        destacando por nuestro servicio, innovación y compromiso social.
                    </p>
                </div>

            </div>

            {{-- Bloque lateral --}}
            <div class="col-md-4">
                <div class="bg-white rounded-4 shadow p-4">
                    <h5 class="fw-bold mb-3">Nuestros valores</h5>
                    <ul class="text-muted mb-3">
                        <li>Calidad</li>
                        <li>Responsabilidad</li>
                        <li>Transparencia</li>
                        <li>Compromiso con el cliente</li>
                    </ul>
                    <a href="{{ route('contacto') }}" class="btn btn-success w-100 rounded-3">
                        Contáctanos
                    </a>
                </div>
            </div>

        </div>

    </section>

</x-frontlayout>
