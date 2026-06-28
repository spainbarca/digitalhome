<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Regímenes Tributarios</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Regímenes Tributarios</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Negocios</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Regímenes Tributarios</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                    <div class="trezo-card-title">
                        <div class="relative sm:w-[265px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" id="buscar" oninput="filtrarTabla()" placeholder="Buscar por nombre o código..."
                                class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </div>
                    </div>
                    <div class="trezo-card-subtitle mt-[15px] sm:mt-0">
                        <button type="button" onclick="abrirModalCrear()"
                            class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                                Nuevo Régimen
                            </span>
                        </button>
                    </div>
                </div>

                <div class="trezo-card-content">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-[#172036]">
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[60px]">Ícono</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[100px]">Código</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400">Nombre</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400">Descripción</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[60px]">Orden</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[100px]">Estado</th>
                                    <th class="text-right py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[100px]">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaBody">
                                @forelse($registros as $r)
                                <tr class="border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors"
                                    data-id="{{ $r->id }}"
                                    data-codigo="{{ $r->codigo }}"
                                    data-nombre="{{ $r->nombre }}"
                                    data-descripcion="{{ $r->descripcion ?? '' }}"
                                    data-icono="{{ $r->icono ?? '' }}"
                                    data-orden="{{ $r->orden ?? 0 }}"
                                    data-activo="{{ $r->activo ? '1' : '0' }}">
                                    <td class="py-[13px] px-[16px]">
                                        <i class="material-symbols-outlined !text-[22px] text-primary-500">{{ $r->icono ?? 'receipt_long' }}</i>
                                    </td>
                                    <td class="py-[13px] px-[16px] text-sm font-mono font-medium text-black dark:text-white">{{ $r->codigo }}</td>
                                    <td class="py-[13px] px-[16px] text-sm text-black dark:text-white font-medium">{{ $r->nombre }}</td>
                                    <td class="py-[13px] px-[16px] text-sm text-gray-600 dark:text-gray-400">{{ $r->descripcion ?? '—' }}</td>
                                    <td class="py-[13px] px-[16px] text-sm text-gray-600 dark:text-gray-400">{{ $r->orden }}</td>
                                    <td class="py-[13px] px-[16px]">
                                        @if($r->activo)
                                            <span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-success-600 border border-success-600 bg-success-100 dark:bg-[#0c1427]">Activo</span>
                                        @else
                                            <span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-danger-600 border border-danger-600 bg-danger-100 dark:bg-[#0c1427]">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="py-[13px] px-[16px] text-right">
                                        <button type="button" onclick="abrirModalEditar(this.closest('tr'))"
                                            class="text-gray-500 dark:text-gray-400 hover:text-primary-500 transition-colors mr-[10px]" title="Editar">
                                            <i class="material-symbols-outlined !text-[18px]">edit</i>
                                        </button>
                                        <button type="button" onclick="eliminar('{{ $r->id }}', '{{ addslashes($r->nombre) }}', this.closest('tr'))"
                                            class="text-gray-500 dark:text-gray-400 hover:text-danger-500 transition-colors" title="Eliminar">
                                            <i class="material-symbols-outlined !text-[18px]">delete</i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="filaVacia">
                                    <td colspan="7" class="text-center py-[50px] text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">receipt_long</i>
                                        No hay regímenes tributarios registrados.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p id="sinResultados" class="hidden text-center py-[30px] text-sm text-gray-500 dark:text-gray-400">No se encontraron coincidencias.</p>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        <!-- Modal -->
        <div id="modalRegistro" class="hidden fixed inset-0 z-[60] flex items-center justify-center" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModal()"></div>
            <div class="relative bg-white dark:bg-[#0c1427] rounded-md w-full max-w-md mx-[16px] shadow-2xl z-10 max-h-[90vh] overflow-y-auto">

                <div class="flex items-center justify-between px-[25px] py-[18px] border-b border-gray-100 dark:border-[#172036] sticky top-0 bg-white dark:bg-[#0c1427] z-10">
                    <h5 class="!mb-0 text-[16px]" id="modalTitulo">Nuevo Régimen Tributario</h5>
                    <button type="button" onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <i class="material-symbols-outlined">close</i>
                    </button>
                </div>

                <div class="px-[25px] py-[20px] space-y-[16px]">
                    <input type="hidden" id="modalId">

                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Código <span class="text-danger-500">*</span></label>
                        <input type="text" id="modalCodigo" maxlength="20" placeholder="Ej: NRUS, RER, RMT, RG"
                            class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm font-mono">
                        <p id="errCodigo" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Nombre <span class="text-danger-500">*</span></label>
                        <input type="text" id="modalNombre" maxlength="255" placeholder="Ej: Régimen General"
                            class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm">
                        <p id="errNombre" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Descripción</label>
                        <textarea id="modalDescripcion" rows="2" placeholder="Descripción opcional..."
                            class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] py-[10px] block w-full outline-0 transition-all focus:border-primary-500 text-sm resize-none"></textarea>
                        <p id="errDescripcion" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                            Ícono <span class="text-xs text-gray-400 font-normal">(nombre material-symbols-outlined)</span>
                        </label>
                        <div class="flex items-center gap-[10px]">
                            <input type="text" id="modalIcono" maxlength="100" placeholder="Ej: receipt_long"
                                oninput="actualizarPreviewIcono()"
                                class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm">
                            <div class="flex-shrink-0 w-[46px] h-[46px] rounded-md border border-gray-200 dark:border-[#172036] flex items-center justify-center bg-primary-50 dark:bg-[#15203c]">
                                <i class="material-symbols-outlined !text-[22px] text-primary-500" id="previewIcono">receipt_long</i>
                            </div>
                        </div>
                        <p id="errIcono" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Orden</label>
                        <input type="number" id="modalOrden" min="0" placeholder="0"
                            class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm">
                        <p id="errOrden" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <div class="flex items-center justify-between pt-[4px]">
                        <span class="text-sm font-medium text-black dark:text-white">Activo</span>
                        <label class="relative cursor-pointer">
                            <input type="checkbox" id="modalActivo" class="sr-only peer" checked>
                            <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-[10px] px-[25px] py-[16px] border-t border-gray-100 dark:border-[#172036]">
                    <button type="button" onclick="cerrarModal()"
                        class="px-[16px] py-[8px] rounded-md border border-gray-200 dark:border-[#172036] text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all">
                        Cancelar
                    </button>
                    <button type="button" id="btnGuardar" onclick="guardar()"
                        class="px-[16px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                        Guardar
                    </button>
                </div>
            </div>
        </div>

        @include('partials.front.scripts')

        <script>
            const csrfToken   = '{{ csrf_token() }}';
            const storeUrl    = '{{ route('dashboard.regimen-tributario.store') }}';
            const baseUrl     = '{{ url('dashboard/regimen-tributario') }}';
            const iconDefault = 'receipt_long';
            let   modoEdicion = false;
            let   idEditando  = null;

            function filtrarTabla() {
                const q    = document.getElementById('buscar').value.toLowerCase().trim();
                const rows = document.querySelectorAll('#tablaBody tr[data-id]');
                let   vis  = 0;
                rows.forEach(row => {
                    const match = row.dataset.nombre.toLowerCase().includes(q) || row.dataset.codigo.toLowerCase().includes(q);
                    row.style.display = match ? '' : 'none';
                    if (match) vis++;
                });
                document.getElementById('sinResultados').classList.toggle('hidden', vis > 0 || q === '');
                const vacia = document.getElementById('filaVacia');
                if (vacia) vacia.style.display = q ? 'none' : '';
            }

            function abrirModalCrear() {
                modoEdicion = false; idEditando = null;
                document.getElementById('modalTitulo').textContent  = 'Nuevo Régimen Tributario';
                document.getElementById('modalId').value            = '';
                document.getElementById('modalCodigo').value        = '';
                document.getElementById('modalNombre').value        = '';
                document.getElementById('modalDescripcion').value   = '';
                document.getElementById('modalIcono').value         = '';
                document.getElementById('previewIcono').textContent = iconDefault;
                document.getElementById('modalOrden').value         = '0';
                document.getElementById('modalActivo').checked      = true;
                limpiarErrores();
                document.getElementById('modalRegistro').classList.remove('hidden');
                document.getElementById('modalCodigo').focus();
            }

            function abrirModalEditar(fila) {
                modoEdicion = true; idEditando = fila.dataset.id;
                document.getElementById('modalTitulo').textContent  = 'Editar Régimen Tributario';
                document.getElementById('modalId').value            = fila.dataset.id;
                document.getElementById('modalCodigo').value        = fila.dataset.codigo;
                document.getElementById('modalNombre').value        = fila.dataset.nombre;
                document.getElementById('modalDescripcion').value   = fila.dataset.descripcion;
                document.getElementById('modalIcono').value         = fila.dataset.icono;
                document.getElementById('previewIcono').textContent = fila.dataset.icono || iconDefault;
                document.getElementById('modalOrden').value         = fila.dataset.orden;
                document.getElementById('modalActivo').checked      = fila.dataset.activo === '1';
                limpiarErrores();
                document.getElementById('modalRegistro').classList.remove('hidden');
                document.getElementById('modalCodigo').focus();
            }

            function cerrarModal() { document.getElementById('modalRegistro').classList.add('hidden'); }

            function actualizarPreviewIcono() {
                const val = document.getElementById('modalIcono').value.trim();
                document.getElementById('previewIcono').textContent = val || iconDefault;
            }

            async function guardar() {
                const btn = document.getElementById('btnGuardar');
                btn.disabled = true;
                limpiarErrores();
                const ordenVal = document.getElementById('modalOrden').value.trim();
                const payload = {
                    codigo:      document.getElementById('modalCodigo').value.trim(),
                    nombre:      document.getElementById('modalNombre').value.trim(),
                    descripcion: document.getElementById('modalDescripcion').value.trim() || null,
                    icono:       document.getElementById('modalIcono').value.trim() || null,
                    orden:       ordenVal !== '' ? parseInt(ordenVal) : null,
                    activo:      document.getElementById('modalActivo').checked,
                };
                const url    = modoEdicion ? `${baseUrl}/${idEditando}` : storeUrl;
                const method = modoEdicion ? 'PATCH' : 'POST';
                try {
                    const resp = await fetch(url, {
                        method,
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify(payload),
                    });
                    if (resp.status === 422) { mostrarErrores((await resp.json()).errors ?? {}); return; }
                    if (!resp.ok) { alert('Error inesperado. Inténtalo de nuevo.'); return; }
                    const { data } = await resp.json();
                    cerrarModal();
                    modoEdicion ? actualizarFila(data) : prependarFila(data);
                } catch { alert('Error de conexión.'); }
                finally { btn.disabled = false; }
            }

            async function eliminar(id, nombre, fila) {
                if (!confirm(`¿Eliminar el régimen «${nombre}»?`)) return;
                try {
                    const resp = await fetch(`${baseUrl}/${id}`, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    });
                    if (!resp.ok) { alert('No se pudo eliminar.'); return; }
                    fila.style.transition = 'opacity 0.2s';
                    fila.style.opacity    = '0';
                    setTimeout(() => {
                        fila.remove();
                        if (!document.querySelector('#tablaBody tr[data-id]')) mostrarFilaVacia();
                    }, 200);
                } catch { alert('Error de conexión.'); }
            }

            function badgeActivo(activo) {
                return activo
                    ? '<span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-success-600 border border-success-600 bg-success-100">Activo</span>'
                    : '<span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-danger-600 border border-danger-600 bg-danger-100">Inactivo</span>';
            }

            function botonesHtml(r) {
                return `
                    <button type="button" onclick="abrirModalEditar(this.closest('tr'))"
                        class="text-gray-500 hover:text-primary-500 transition-colors mr-[10px]" title="Editar">
                        <i class="material-symbols-outlined !text-[18px]">edit</i>
                    </button>
                    <button type="button" onclick="eliminar('${r.id}','${esc(r.nombre)}',this.closest('tr'))"
                        class="text-gray-500 hover:text-danger-500 transition-colors" title="Eliminar">
                        <i class="material-symbols-outlined !text-[18px]">delete</i>
                    </button>`;
            }

            function prependarFila(r) {
                const vacia = document.getElementById('filaVacia');
                if (vacia) vacia.remove();
                const tr = document.createElement('tr');
                tr.className = 'border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors';
                tr.dataset.id          = r.id;
                tr.dataset.codigo      = r.codigo;
                tr.dataset.nombre      = r.nombre;
                tr.dataset.descripcion = r.descripcion ?? '';
                tr.dataset.icono       = r.icono ?? '';
                tr.dataset.orden       = r.orden ?? 0;
                tr.dataset.activo      = r.activo ? '1' : '0';
                tr.innerHTML = `
                    <td class="py-[13px] px-[16px]"><i class="material-symbols-outlined !text-[22px] text-primary-500">${esc(r.icono || iconDefault)}</i></td>
                    <td class="py-[13px] px-[16px] text-sm font-mono font-medium text-black">${esc(r.codigo)}</td>
                    <td class="py-[13px] px-[16px] text-sm text-black font-medium">${esc(r.nombre)}</td>
                    <td class="py-[13px] px-[16px] text-sm text-gray-600">${esc(r.descripcion) || '—'}</td>
                    <td class="py-[13px] px-[16px] text-sm text-gray-600">${r.orden ?? 0}</td>
                    <td class="py-[13px] px-[16px]">${badgeActivo(r.activo)}</td>
                    <td class="py-[13px] px-[16px] text-right">${botonesHtml(r)}</td>`;
                document.getElementById('tablaBody').prepend(tr);
            }

            function actualizarFila(r) {
                const tr = document.querySelector(`#tablaBody tr[data-id="${r.id}"]`);
                if (!tr) return;
                tr.dataset.codigo      = r.codigo;
                tr.dataset.nombre      = r.nombre;
                tr.dataset.descripcion = r.descripcion ?? '';
                tr.dataset.icono       = r.icono ?? '';
                tr.dataset.orden       = r.orden ?? 0;
                tr.dataset.activo      = r.activo ? '1' : '0';
                const tds = tr.querySelectorAll('td');
                tds[0].innerHTML   = `<i class="material-symbols-outlined !text-[22px] text-primary-500">${esc(r.icono || iconDefault)}</i>`;
                tds[1].textContent = r.codigo;
                tds[2].textContent = r.nombre;
                tds[3].textContent = r.descripcion || '—';
                tds[4].textContent = r.orden ?? 0;
                tds[5].innerHTML   = badgeActivo(r.activo);
                tds[6].innerHTML   = `<div class="flex justify-end">${botonesHtml(r)}</div>`;
            }

            function mostrarFilaVacia() {
                const tr = document.createElement('tr');
                tr.id = 'filaVacia';
                tr.innerHTML = `<td colspan="7" class="text-center py-[50px] text-gray-500">
                    <i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">receipt_long</i>
                    No hay regímenes tributarios registrados.</td>`;
                document.getElementById('tablaBody').appendChild(tr);
            }

            function mostrarErrores(errors) {
                const mapa = { codigo: 'errCodigo', nombre: 'errNombre', descripcion: 'errDescripcion', icono: 'errIcono', orden: 'errOrden' };
                Object.entries(mapa).forEach(([campo, id]) => {
                    if (errors[campo]) {
                        const el = document.getElementById(id);
                        el.textContent = errors[campo][0];
                        el.classList.remove('hidden');
                    }
                });
            }

            function limpiarErrores() {
                ['errCodigo', 'errNombre', 'errDescripcion', 'errIcono', 'errOrden'].forEach(id => {
                    const el = document.getElementById(id);
                    el.textContent = '';
                    el.classList.add('hidden');
                });
            }

            function esc(str) {
                const d = document.createElement('div');
                d.appendChild(document.createTextNode(str ?? ''));
                return d.innerHTML;
            }

            document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });
        </script>
    </body>
</html>
