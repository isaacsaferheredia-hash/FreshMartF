<x-frontlayout>

    <section class="container my-5">

        <div class="mb-4">
            <h1 class="fw-bold">Preguntas Frecuentes</h1>
            <p class="text-muted">
                Encuentra respuestas rápidas a las dudas más comunes sobre FreshMart.
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-8">

                <div class="accordion" id="faqAccordion">

                    <div class="accordion-item rounded-4 shadow-sm mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-semibold" data-bs-toggle="collapse" data-bs-target="#faq1">
                                ¿Cómo realizo una compra en FreshMart?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Solo debes seleccionar los productos, agregarlos al carrito y completar el proceso de pago.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item rounded-4 shadow-sm mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" data-bs-toggle="collapse" data-bs-target="#faq2">
                                ¿Cuáles son los métodos de pago disponibles?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Aceptamos tarjetas de crédito, débito y pagos en línea autorizados.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item rounded-4 shadow-sm mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" data-bs-toggle="collapse" data-bs-target="#faq3">
                                ¿En cuánto tiempo llega mi pedido?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                El tiempo de entrega es de 24 a 48 horas dependiendo de tu ubicación.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item rounded-4 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" data-bs-toggle="collapse" data-bs-target="#faq4">
                                ¿Puedo devolver un producto?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Sí, aceptamos devoluciones según nuestras políticas de garantía y calidad.
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Bloque lateral informativo --}}
            <div class="col-md-4">
                <div class="bg-white rounded-4 shadow p-4">
                    <h5 class="fw-bold mb-3">¿Aún tienes dudas?</h5>
                    <p class="text-muted small">
                        Nuestro equipo está listo para ayudarte.
                    </p>
                    <a href="{{ route('contacto') }}" class="btn btn-success w-100 rounded-3">
                        Contáctanos
                    </a>
                </div>
            </div>

        </div>

    </section>

</x-frontlayout>
