<x-adminlayout title="Editar Proveedor">

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                {{-- ALERTA GENERAL --}}
                @if($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 mb-3">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        Faltan datos obligatorios. Revise los campos marcados.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-3">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="fw-bold mb-1">Editar Proveedor</h4>
                                <p class="text-muted mb-0">
                                    Actualiza la información del proveedor
                                </p>
                            </div>
                        </div>

                        @isset($proveedor)
                            <form method="POST"
                                  action="{{ route('proveedores.update', $proveedor) }}"
                                  novalidate>
                                @csrf
                                @method('PUT')

                                {{-- NOMBRE --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nombre del Proveedor</label>
                                    <input type="text"
                                           name="prv_nombre"
                                           maxlength="40"
                                           value="{{ old('prv_nombre', $proveedor->prv_nombre) }}"
                                           class="form-control @error('prv_nombre') is-invalid @enderror"
                                           required>
                                    @error('prv_nombre')
                                    <div class="invalid-feedback">
                                        El nombre del proveedor es obligatorio.
                                    </div>
                                    @enderror
                                </div>

                                {{-- RUC / CÉDULA --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">RUC / Cédula</label>
                                    <input type="text"
                                           name="prv_ruc_ced"
                                           maxlength="13"
                                           inputmode="numeric"
                                           value="{{ old('prv_ruc_ced', $proveedor->prv_ruc_ced) }}"
                                           class="form-control @error('prv_ruc_ced') is-invalid @enderror"
                                           oninput="this.value = this.value.replace(/[^0-9]/g,'')"
                                           required>
                                    @error('prv_ruc_ced')
                                    <div class="invalid-feedback">
                                        El RUC o cédula es obligatorio.
                                    </div>
                                    @enderror
                                </div>

                                {{-- CORREO --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Correo electrónico</label>
                                    <input type="email"
                                           name="prv_mail"
                                           maxlength="60"
                                           value="{{ old('prv_mail', $proveedor->prv_mail) }}"
                                           class="form-control @error('prv_mail') is-invalid @enderror">
                                    @error('prv_mail')
                                    <div class="invalid-feedback">
                                        El correo electrónico no es válido.
                                    </div>
                                    @enderror
                                </div>

                                {{-- CIUDAD --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Ciudad</label>
                                    <select name="id_ciudad"
                                            class="form-select @error('id_ciudad') is-invalid @enderror"
                                            required>
                                        <option value="">Seleccione una ciudad</option>
                                        @foreach($ciudades as $ciu)
                                            <option value="{{ $ciu->id_ciudad }}"
                                                {{ old('id_ciudad', $proveedor->id_ciudad) == $ciu->id_ciudad ? 'selected' : '' }}>
                                                {{ $ciu->ciu_descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_ciudad')
                                    <div class="invalid-feedback">
                                        La ciudad es obligatoria.
                                    </div>
                                    @enderror
                                </div>

                                {{-- TELÉFONO / CELULAR --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Teléfono</label>
                                        <input type="text"
                                               name="prv_telefono"
                                               maxlength="10"
                                               inputmode="numeric"
                                               value="{{ old('prv_telefono', $proveedor->prv_telefono) }}"
                                               class="form-control @error('prv_telefono') is-invalid @enderror"
                                               oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                                        <small class="text-muted">Opcional · 10 dígitos</small>
                                        @error('prv_telefono')
                                        <div class="invalid-feedback">
                                            El teléfono debe tener 10 dígitos.
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Celular</label>
                                        <input type="text"
                                               name="prv_celular"
                                               maxlength="10"
                                               inputmode="numeric"
                                               value="{{ old('prv_celular', $proveedor->prv_celular) }}"
                                               class="form-control @error('prv_celular') is-invalid @enderror"
                                               oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                                        <small class="text-muted">Opcional · 10 dígitos</small>
                                        @error('prv_celular')
                                        <div class="invalid-feedback">
                                            El celular debe tener 10 dígitos.
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- DIRECCIÓN --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Dirección</label>
                                    <textarea name="prv_direccion"
                                              rows="3"
                                              maxlength="60"
                                              class="form-control @error('prv_direccion') is-invalid @enderror"
                                              required>{{ old('prv_direccion', $proveedor->prv_direccion) }}</textarea>
                                    @error('prv_direccion')
                                    <div class="invalid-feedback">
                                        La dirección es obligatoria.
                                    </div>
                                    @enderror
                                </div>

                                {{-- BOTONES --}}
                                <div class="d-flex gap-2 mt-4">
                                    <a href="{{ route('proveedores.index') }}"
                                       class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-arrow-left me-1"></i> Cancelar
                                    </a>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-floppy-disk me-1"></i>
                                        Actualizar Proveedor
                                    </button>
                                </div>

                            </form>
                        @else
                            <div class="alert alert-danger">
                                <i class="fa-solid fa-circle-exclamation me-2"></i>
                                Error: No se encontró la información del proveedor.
                            </div>
                            <a href="{{ route('proveedores.index') }}"
                               class="btn btn-secondary">
                                Volver
                            </a>
                        @endisset

                    </div>
                </div>

            </div>
        </div>
    </div>

</x-adminlayout>
