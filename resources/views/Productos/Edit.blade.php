<x-adminlayout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Editar Producto</h2>
            <p class="text-muted mb-0">Actualización de producto</p>
        </div>

        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="fa-solid fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    @if($soloLectura)
        <div class="alert alert-warning shadow-sm border-0 mb-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            Este producto está desactivado. Solo se permite la visualización.
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-warning shadow-sm border-0 mb-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0 mb-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('productos.update', $producto->id_producto) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- ID --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ID</label>
                        <input type="text" class="form-control" value="{{ $producto->id_producto }}" disabled>
                    </div>

                    {{-- Descripción --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Descripción</label>
                        <input type="text"
                               name="pro_descripcion"
                               class="form-control"
                               value="{{ old('pro_descripcion', $producto->pro_descripcion) }}"
                            {{ $soloLectura ? 'disabled' : '' }}>
                    </div>

                    {{-- Tipo --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tipo</label>
                        <select name="id_tipo"
                                class="form-select"
                            {{ $soloLectura ? 'disabled' : '' }}>
                            @foreach($tipos as $t)
                                <option value="{{ trim($t->id_tipo) }}"
                                    @selected(old('id_tipo', $producto->id_tipo) == trim($t->id_tipo))>
                                    {{ $t->id_tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- UM Compra --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">UM Compra</label>
                        <select name="pro_um_compra"
                                class="form-select"
                            {{ $soloLectura ? 'disabled' : '' }}>
                            @foreach($unidades as $u)
                                <option value="{{ trim($u->id_unidad_medida) }}"
                                    @selected(old('pro_um_compra', trim($producto->pro_um_compra)) == trim($u->id_unidad_medida))>
                                    {{ $u->um_descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- UM Venta --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">UM Venta</label>
                        <select name="pro_um_venta"
                                class="form-select"
                            {{ $soloLectura ? 'disabled' : '' }}>
                            @foreach($unidades as $u)
                                <option value="{{ trim($u->id_unidad_medida) }}"
                                    @selected(old('pro_um_venta', trim($producto->pro_um_venta)) == trim($u->id_unidad_medida))>
                                    {{ $u->um_descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Valor Compra --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Valor Compra</label>
                        <input type="number" step="0.01" min="0"
                               name="pro_valor_compra"
                               class="form-control"
                               value="{{ old('pro_valor_compra', $producto->pro_valor_compra) }}"
                            {{ $soloLectura ? 'disabled' : '' }}>
                    </div>

                    {{-- Precio Venta --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Precio Venta</label>
                        <input type="number" step="0.01" min="0"
                               name="pro_precio_venta"
                               class="form-control"
                               value="{{ old('pro_precio_venta', $producto->pro_precio_venta) }}"
                            {{ $soloLectura ? 'disabled' : '' }}>
                    </div>

                    {{-- Saldo Inicial --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Saldo Inicial</label>
                        <input type="number" min="0"
                               name="pro_saldo_inicial"
                               class="form-control"
                               value="{{ old('pro_saldo_inicial', $producto->pro_saldo_inicial) }}"
                            {{ $soloLectura ? 'disabled' : '' }}>
                    </div>

                    {{-- Producto destacado --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Producto destacado</label>
                        <select name="dest"
                                class="form-select"
                            {{ $soloLectura ? 'disabled' : '' }}>
                            <option value="S" @selected(old('dest', $producto->dest) == 'S')>Sí</option>
                            <option value="N" @selected(old('dest', $producto->dest) == 'N')>No</option>
                        </select>
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    @if(!$soloLectura)
                        <button type="submit" class="btn btn-success rounded-3">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Guardar cambios
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-3">
                            Cancelar
                        </a>
                    @else
                        <a href="{{ route('productos.index') }}" class="btn btn-primary rounded-3">
                            OK
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

</x-adminlayout>
