<x-adminlayout title="Nuevo Usuario">

    <h2 class="fw-bold mb-4">Crear Usuario</h2>

    <form method="POST" action="{{ route('usuarios.store') }}" class="card p-4 shadow-sm border-0">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input name="password" type="password" class="form-control" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Rol Global</label>
            <select name="rol" class="form-select" required>
                <option value="ADMIN">ADMIN</option>
                <option value="JEFE">JEFE</option>
                <option value="AUXILIAR">AUXILIAR</option>
                <option value="OPERATIVO">OPERATIVO</option>
            </select>
        </div>

        <button class="btn btn-success">
            Guardar Usuario
        </button>

        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary ms-2">
            Cancelar
        </a>
    </form>

</x-adminlayout>
