<x-adminlayout>
    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Mostrar errores --}}
            @if ($errors->any())
                <div style="background:#fee; padding:10px; border:1px solid red; margin-bottom:15px;">
                    <pre>{{ print_r($errors->all(), true) }}</pre>
                </div>
            @endif

            <form method="POST" action="{{ route('productos.store') }}">
                @csrf

                <div class="row g-3">

                    {{-- Descripción --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Descripción</label>
                        <input type="text"
                               name="pro_descripcion"
                               class="form-control"
                               value="{{ old('pro_descripcion') }}"
                               required>
                    </div>

                    {{-- Tipo --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tipo</label>
                        <select name="id_tipo" class="form-select" required>
                            <option value="" selected disabled>-- Seleccione --</option>
                            @foreach($tipos as $t)
                                <option value="{{ trim($t->id_tipo) }}"
                                    {{ old('id_tipo') == trim($t->id_tipo) ? 'selected' : '' }}>
                                    {{ $t->id_tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- UM Compra --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">UM Compra</label>
                        <select name="pro_um_compra" class="form-select" required>
                            <option value="" selected disabled>-- Seleccione --</option>
                            @foreach($unidades as $u)
                                <option value="{{ trim($u->id_unidad_medida) }}"
                                    {{ old('pro_um_compra') == trim($u->id_unidad_medida) ? 'selected' : '' }}>
                                    {{ $u->um_descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- UM Venta --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">UM Venta</label>
                        <select name="pro_um_venta" class="form-select" required>
                            <option value="" selected disabled>-- Seleccione --</option>
                            @foreach($unidades as $u)
                                <option value="{{ trim($u->id_unidad_medida) }}"
                                    {{ old('pro_um_venta') == trim($u->id_unidad_medida) ? 'selected' : '' }}>
                                    {{ $u->um_descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Valor Compra --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Valor Compra</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="pro_valor_compra"
                               class="form-control"
                               value="{{ old('pro_valor_compra') }}"
                               required>
                    </div>

                    {{-- Precio Venta --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Precio Venta</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="pro_precio_venta"
                               class="form-control"
                               value="{{ old('pro_precio_venta') }}"
                               required>
                    </div>

                    {{-- Saldo Inicial --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Saldo Inicial</label>
                        <input type="number"
                               min="0"
                               name="pro_saldo_inicial"
                               class="form-control"
                               value="{{ old('pro_saldo_inicial', 0) }}">
                    </div>

                    {{-- Destacado --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Producto destacado</label>
                        <select name="dest" class="form-select" required>
                            <option value="" selected disabled>-- Seleccione --</option>
                            <option value="S" {{ old('dest') == 'S' ? 'selected' : '' }}>Sí</option>
                            <option value="N" {{ old('dest') == 'N' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        Guardar
                    </button>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-adminlayout>
