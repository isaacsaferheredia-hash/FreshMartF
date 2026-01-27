<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Facturas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

@include('layouts.header')

<div class="container py-4">

    <h2 class="mb-4">📄 Mis Compras</h2>

    @if($facturas->isEmpty())
        <div class="alert alert-info">
            No tienes facturas registradas.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered bg-white align-middle">
                <thead class="table-light">
                <tr>
                    <th>N° Factura</th>
                    <th>Fecha</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center" width="120">Acción</th>
                </tr>
                </thead>
                <tbody>

                @foreach($facturas as $f)
                    <tr>
                        <td>{{ trim($f->id_factura) }}</td>

                        <td>
                            {{ $f->fac_fecha_hora
                                ? \Carbon\Carbon::parse($f->fac_fecha_hora)->format('d/m/Y H:i')
                                : '—' }}
                        </td>

                        <td class="text-end">
                            ${{ number_format($f->fac_subtotal + $f->fac_iva, 2) }}
                        </td>

                        <td class="text-center">
                            <span class="badge
                                @if($f->estado_fac === 'ABI')
                                    bg-warning text-dark
                                @elseif($f->estado_fac === 'APR')
                                    bg-success
                                @else
                                    bg-danger
                                @endif
                            ">
                                {{ $f->estado_fac }}
                            </span>
                        </td>

                        <td class="text-center">
                            <a href="{{ route('ClienteFacturas.show', trim($f->id_factura)) }}"
                               class="btn btn-sm btn-outline-primary">
                                Ver
                            </a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    @endif

</div>

</body>
</html>
