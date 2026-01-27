<x-frontlayout>

    <section class="container my-5">

        <div class="mb-4">
            <h1 class="fw-bold">Política de Devoluciones</h1>
            <p class="text-muted">
                En FreshMart queremos que estés satisfecho con tu compra.
                Aquí te explicamos cómo funcionan nuestras devoluciones.
            </p>
        </div>

        <div class="row g-4">

            {{-- Contenido principal --}}
            <div class="col-md-8">
                <div class="bg-white rounded-4 shadow p-4 mb-4">
                    <h5 class="fw-bold mb-3">¿Cuándo puedo solicitar una devolución?</h5>
                    <p class="text-muted">
                        Puedes solicitar una devolución dentro de las primeras
                        <strong>24 horas</strong> después de recibir tu pedido, siempre que el producto:
                    </p>
                    <ul class="text-muted">
                        <li>Esté en mal estado</li>
                        <li>No corresponda a lo solicitado</li>
                        <li>Presente daños visibles</li>
                    </ul>
                </div>

                <div class="bg-white rounded-4 shadow p-4 mb-4">
                    <h5 class="fw-bold mb-3">Productos no elegibles</h5>
                    <p class="text-muted">
                        No se aceptan devoluciones de productos:
                    </p>
                    <ul class="text-muted">
                        <li>Consumidos parcialmente</li>
                        <li>Fuera del tiempo establecido</li>
                        <li>Sin comprobante de compra</li>
                    </ul>
                </div>

                <div class="bg-white rounded-4 shadow p-4">
                    <h5 class="fw-bold mb-3">Proceso de devolución</h5>
                    <ol class="text-muted">
                        <li>Comunícate con nuestro equipo de atención al cliente</li>
                        <li>Indica tu número de pedido</li>
                        <li>Adjunta evidencia del producto</li>
                        <li>Recibirás una respuesta en un plazo máximo de 48 horas</li>
                    </ol>
                </div>
            </div>

            {{-- Bloque lateral --}}
            <div class="col-md-4">
                <div class="bg-white rounded-4 shadow p-4">
                    <h5 class="fw-bold mb-3">¿Necesitas ayuda?</h5>
                    <p class="text-muted small">
                        Si tienes dudas sobre una devolución, nuestro equipo está listo para ayudarte.
                    </p>
                    <a href="{{ route('contacto') }}" class="btn btn-success w-100 rounded-3">
                        Contáctanos
                    </a>
                </div>
            </div>

        </div>

    </section>

</x-frontlayout>
