<x-adminlayout>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">

                    <div class="mb-4">
                        <h4 class="fw-bold mb-1">Registrar Cliente</h4>
                        <p class="text-muted mb-0">
                            Ingreso de nuevo cliente al sistema
                        </p>
                    </div>

                    {{-- 🔴 Error crítico: RUC / Cédula duplicado --}}
                    @if($errors->has('cli_ruc_ced') && str_contains(strtolower($errors->first('cli_ruc_ced')), 'registr'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            {{ $errors->first('cli_ruc_ced') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- 🟡 Faltan datos obligatorios --}}
                    @if($errors->any() && !($errors->has('cli_ruc_ced') && str_contains(strtolower($errors->first('cli_ruc_ced')), 'registr')))
                        <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            Faltan datos obligatorios.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- 🔴 Error general desde controller --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('clientes.store') }}"
                          novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre</label>
                            <input type="text"
                                   name="cli_nombre"
                                   maxlength="40"
                                   value="{{ old('cli_nombre') }}"
                                   class="form-control @error('cli_nombre') is-invalid @enderror"
                                   required>
                            @error('cli_nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">RUC / Cédula</label>
                            <input type="text"
                                   name="cli_ruc_ced"
                                   class="form-control @error('cli_ruc_ced') is-invalid @enderror"
                                   value="{{ old('cli_ruc_ced') }}"
                                   maxlength="13"
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   placeholder="Ingrese RUC o cédula"
                                   required>
                            @error('cli_ruc_ced')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Correo electrónico</label>
                            <input type="email"
                                   name="cli_mail"
                                   maxlength="60"
                                   value="{{ old('cli_mail') }}"
                                   class="form-control @error('cli_mail') is-invalid @enderror"
                                   required>
                            @error('cli_mail')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input type="text"
                                       name="cli_telefono"
                                       maxlength="10"
                                       pattern="[0-9]{10}"
                                       value="{{ old('cli_telefono') }}"
                                       class="form-control">
                                <div class="form-text">Opcional · 10 dígitos</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Celular</label>
                                <input type="text"
                                       name="cli_celular"
                                       maxlength="10"
                                       pattern="[0-9]{10}"
                                       value="{{ old('cli_celular') }}"
                                       class="form-control">
                                <div class="form-text">Opcional · 10 dígitos</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ciudad</label>
                            <select name="id_ciudad"
                                    required
                                    class="form-select @error('id_ciudad') is-invalid @enderror">
                                <option value="">Seleccione una ciudad</option>
                                @foreach($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id_ciudad }}"
                                        {{ old('id_ciudad') == $ciudad->id_ciudad ? 'selected' : '' }}>
                                        {{ $ciudad->ciu_descripcion }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_ciudad')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Dirección</label>
                            <textarea name="cli_direccion"
                                      rows="3"
                                      required
                                      class="form-control @error('cli_direccion') is-invalid @enderror">{{ old('cli_direccion') }}</textarea>
                            @error('cli_direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('clientes.index') }}"
                               class="btn btn-light px-4">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="btn btn-success px-4">
                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                Guardar
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-adminlayout>
