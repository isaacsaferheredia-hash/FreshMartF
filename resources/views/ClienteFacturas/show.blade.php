<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $factura->id_factura }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body class="bg-light">

@include('layouts.header')

<div class="container py-4">

    <h3 class="mb-3">🧾 Factura {{ $factura->id_factura }}</h3>

    <p><strong>Fecha:</strong> {{ $factura->fac_fecha_hora }}</p>
    <p><strong>Descripción:</strong> {{ $factura->fac_descripcion }}</p>

    <table class="table table-bordered bg-white mt-3">
        <thead class="table-light">
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @foreach($detalles as $d)
            <tr>
                <td>{{ $d->pro_descripcion }}</td>
                <td>{{ $d->pxf_cantidad }}</td>
                <td>${{ number_format($d->pxf_precio, 2) }}</td>
                <td>${{ number_format($d->pxf_subtotal, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="text-end mt-3">
        <p>Subtotal: <strong>${{ number_format($factura->fac_subtotal, 2) }}</strong></p>
        <p>IVA: <strong>${{ number_format($factura->fac_iva, 2) }}</strong></p>
        <h4>Total: ${{ number_format($factura->fac_subtotal + $factura->fac_iva, 2) }}</h4>
    </div>

    <a href="{{ route('ClienteFacturas.index') }}" class="btn btn-outline-secondary mt-3">
        ← Volver
    </a>

</div>

</body>
