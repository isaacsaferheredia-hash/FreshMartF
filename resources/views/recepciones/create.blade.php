<x-adminlayout>

    <h2 class="fw-bold mb-4">Registrar Recepción</h2>

    @if($errors->any())
        <div class="alert alert-warning">Faltan datos obligatorios</div>
    @endif

    <form method="POST" action="{{ route('recepciones.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Descripción</label>
            <input type="text" name="rec_descripcion" class="form-control" required>
        </div>

        <hr>

        <h5 class="fw-bold">Detalle de productos</h5>

        <div id="detalle"></div>

        <button type="button" class="btn btn-outline-primary mt-2" id="btnAdd">
            <i class="fa fa-plus"></i> Agregar producto
        </button>

        <hr>

        <button class="btn btn-success mt-3">
            <i class="fa fa-save"></i> Guardar
        </button>

        <a href="{{ route('recepciones.index') }}" class="btn btn-secondary mt-3">
            Cancelar
        </a>

    </form>

</x-adminlayout>

<script>
    const productos = @json($productos);
    const cont = document.getElementById('detalle');
    const btn = document.getElementById('btnAdd');

    btn.addEventListener('click', () => {
        const i = cont.children.length;

        const div = document.createElement('div');
        div.className = 'row mb-2';

        div.innerHTML = `
        <div class="col-md-6">
            <select name="items[${i}][id_producto]" class="form-select" required>
                <option value="">Seleccione</option>
                ${productos.map(p => `<option value="${p.id_producto}">
                    ${p.pro_descripcion}
                </option>`).join('')}
            </select>
        </div>

        <div class="col-md-3">
            <input type="number"
                   name="items[${i}][cantidad]"
                   class="form-control"
                   min="1"
                   value="1"
                   required>
        </div>

        <div class="col-md-3 text-end">
            <button type="button" class="btn btn-outline-danger btnDel">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    `;

        cont.appendChild(div);

        div.querySelector('.btnDel').onclick = () => div.remove();
    });
</script>
