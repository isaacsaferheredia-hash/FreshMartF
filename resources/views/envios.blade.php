<x-frontlayout>

    <section class="container my-5">

        <div class="mb-4">
            <h1 class="fw-bold">Información de Envíos</h1>
            <p class="text-muted">
                Conoce cómo funcionan nuestros envíos, tiempos de entrega y costos.
            </p>
        </div>

        <div class="row g-4">

            {{-- Contenido principal --}}
            <div class="col-md-8">

                <div class="bg-white rounded-4 shadow p-4 mb-4">
                    <h5 class="fw-bold mb-3">Cobertura de envíos</h5>
                    <p class="text-muted">
                        Realizamos envíos dentro de la ciudad y zonas aledañas.
                        Actualmente, FreshMart opera en:
                    </p>
                    <ul class="text-muted">
                        <li>Quito (urbano)</li>
                        <li>Valles cercanos</li>
                        <li>Zonas seleccionadas bajo confirmación</li>
                    </ul>
                </div>

                <div class="bg-white rounded-4 shadow p-4 mb-4">
                    <h5 class="fw-bold mb-3">Tiempos de entrega</h5>
                    <p class="text-muted">
                        Nuestros tiempos estimados de entrega son:
                    </p>
                    <ul class="text-muted">
                        <li><strong>24 horas</strong> para pedidos realizados antes de las 14h00</li>
                        <li><strong>24 a 48 horas</strong> para pedidos realizados después</li>
                    </ul>
                </div>

                <div class="bg-white rounded-4 shadow p-4">
                    <h5 class="fw-bold mb-3">Costos de envío</h5>
                    <ul class="text-muted">
                        <li>Compras mayores a <strong>$50</strong>: <span class="text-success fw-semibold">Envío GRATIS</span></li>
                        <li>Compras menores a $50: costo de envío calculado al finalizar la compra</li>
                    </ul>
                </div>

            </div>

            {{-- Bloque lateral --}}
            <div class="col-md-4">
                <div class="bg-white rounded-4 shadow p-4">
                    <h5 class="fw-bold mb-3">¿Tienes dudas sobre tu envío?</h5>
                    <p class="text-muted small">
                        Nuestro equipo de atención al cliente puede ayudarte con el seguimiento de tu pedido.
                    </p>
                    <a href="{{ route('contacto') }}" class="btn btn-success w-100 rounded-3">
                        Contáctanos
                    </a>
                </div>
            </div>

        </div>

    </section>

</x-frontlayout>
