<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Tipos de Documento</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Tipos de Documento</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Tipos de Documento</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                    <div class="trezo-card-title">
                        <div class="relative sm:w-[265px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" id="buscar" oninput="filtrarTabla()" placeholder="Buscar por código o nombre..."
                                class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </div>
                    </div>
                    <div class="trezo-card-subtitle mt-[15px] sm:mt-0">
                        <button type="button" onclick="abrirModalCrear()"
                            class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                                Nuevo Tipo
                            </span>
                        </button>
                    </div>
                </div>

                <div class="trezo-card-content">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-[#172036]">
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[90px]">Código</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400">Nombre</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[90px]">Longitud</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[110px]">Es numérico</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[100px]">Estado</th>
                                    <th class="text-right py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[90px]">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaBody">
                                @forelse($tipos as $t)
                                <tr class="border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors"
                                    data-id="{{ $t->id }}">
                                    <td class="py-[13px] px-[16px]">
                                        <span class="text-xs font-mono font-semibold px-[8px] py-[3px] rounded bg-primary-50 dark:bg-[#15203c] text-primary-600">{{ $t->codigo }}</span>
                                    </td>
                                    <td class="py-[13px] px-[16px] text-sm text-black dark:text-white font-medium">{{ $t->nombre }}</td>
                                    <td class="py-[13px] px-[16px] text-sm text-gray-600 dark:text-gray-400">
                                        {{ $t->longitud ? $t->longitud . ' dígitos' : '—' }}
                                    </td>
                                    <td class="py-[13px] px-[16px]">
                                        @if($t->es_numerico)
                                            <span class="inline-flex items-center gap-[4px] text-xs font-medium text-success-600">
                                                <i class="material-symbols-outlined !text-[16px]">check_circle</i> Sí
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-[4px] text-xs font-medium text-gray-400">
                                                <i class="material-symbols-outlined !text-[16px]">cancel</i> No
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-[13px] px-[16px]">
                                        @if($t->estado)
                                            <span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-success-600 border border-success-600 bg-success-100 dark:bg-[#0c1427]">Activo</span>
                                        @else
                                            <span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-danger-600 border border-danger-600 bg-danger-100 dark:bg-[#0c1427]">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="py-[13px] px-[16px] text-right">
                                        <button type="button" onclick="abrirModalEditar({{ $t->id }})"
                                            class="text-gray-500 dark:text-gray-400 hover:text-primary-500 transition-colors mr-[10px]" title="Editar">
                                            <i class="material-symbols-outlined !text-[18px]">edit</i>
                                        </button>
                                        <button type="button" onclick="eliminar({{ $t->id }}, this.closest('tr'))"
                                            class="text-gray-500 dark:text-gray-400 hover:text-danger-500 transition-colors" title="Eliminar">
                                            <i class="material-symbols-outlined !text-[18px]">delete</i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="filaVacia">
                                    <td colspan="6" class="text-center py-[50px] text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">badge</i>
                                        No hay tipos de documento registrados.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p id="sinResultados" class="hidden text-center py-[30px] text-sm text-gray-500 dark:text-gray-400">
                        No se encontraron coincidencias.
                    </p>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        <!-- ─── Modal ───────────────────────────────────────────────── -->
        <div id="modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModal()"></div>
            <div class="relative bg-white dark:bg-[#0c1427] rounded-md w-full max-w-md mx-[16px] shadow-2xl z-10">

                <div class="flex items-center justify-between px-[25px] py-[18px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-[16px]" id="modalTitulo">Nuevo Tipo de Documento</h5>
                    <button type="button" onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <i class="material-symbols-outlined">close</i>
                    </button>
                </div>

                <div class="px-[25px] py-[20px] space-y-[14px]">
                    <input type="hidden" id="modalId">

                    <div class="grid grid-cols-2 gap-[14px]">
                        <!-- Código -->
                        <div>
                            <label class="mb-[7px] text-black dark:text-white font-medium block text-sm">
                                Código <span class="text-danger-500">*</span>
                            </label>
                            <input type="text" id="modalCodigo" maxlength="10" placeholder="Ej: DNI"
                                class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm font-mono uppercase placeholder:normal-case placeholder:text-gray-400">
                            <p id="errCodigo" class="hidden text-xs text-danger-500 mt-[4px]"></p>
                        </div>
                        <!-- Longitud -->
                        <div>
                            <label class="mb-[7px] text-black dark:text-white font-medium block text-sm">
                                Longitud <span class="text-gray-400 font-normal text-xs">(dígitos)</span>
                            </label>
                            <input type="number" id="modalLongitud" min="1" max="50" placeholder="Ej: 8"
                                class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm placeholder:text-gray-400">
                            <p id="errLongitud" class="hidden text-xs text-danger-500 mt-[4px]"></p>
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label class="mb-[7px] text-black dark:text-white font-medium block text-sm">
                            Nombre <span class="text-danger-500">*</span>
                        </label>
                        <input type="text" id="modalNombre" maxlength="100" placeholder="Ej: Documento Nacional de Identidad"
                            class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm placeholder:text-gray-400">
                        <p id="errNombre" class="hidden text-xs text-danger-500 mt-[4px]"></p>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="mb-[7px] text-black dark:text-white font-medium block text-sm">Descripción</label>
                        <textarea id="modalDescripcion" maxlength="300" rows="2" placeholder="Descripción opcional..."
                            class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] py-[10px] block w-full outline-0 transition-all focus:border-primary-500 text-sm resize-none placeholder:text-gray-400"></textarea>
                    </div>

                    <!-- Toggles -->
                    <div class="flex items-center justify-between gap-[16px] pt-[4px]">
                        <div class="flex items-center gap-[10px]">
                            <label class="relative cursor-pointer">
                                <input type="checkbox" id="modalEsNumerico" class="sr-only peer">
                                <div class="w-[38px] h-[22px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[18px] after:w-[18px] after:transition-all peer-checked:bg-primary-500"></div>
                            </label>
                            <span class="text-sm text-black dark:text-white">Es numérico</span>
                        </div>
                        <div class="flex items-center gap-[10px]">
                            <span class="text-sm text-black dark:text-white">Activo</span>
                            <label class="relative cursor-pointer">
                                <input type="checkbox" id="modalEstado" class="sr-only peer" checked>
                                <div class="w-[38px] h-[22px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[18px] after:w-[18px] after:transition-all peer-checked:bg-primary-500"></div>
                            </label>
                        </div>
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
            const CSRF  = '{{ csrf_token() }}';
            const STORE = '{{ route('dashboard.tipo-documento.store') }}';
            const BASE  = '{{ url('dashboard/tipo-documento') }}';

            // Datos completos indexados por ID para poblar el modal sin perder datos multi-línea
            const DATOS = @json($tipos->keyBy('id'));

            let editMode = false;
            let editId   = null;

            /* ── Búsqueda ──────────────────────────────────────────── */
            function filtrarTabla() {
                const q    = document.getElementById('buscar').value.toLowerCase().trim();
                const rows = document.querySelectorAll('#tablaBody tr[data-id]');
                let vis = 0;
                rows.forEach(tr => {
                    const id = tr.dataset.id;
                    const d  = DATOS[id];
                    const texto = ((d?.codigo ?? '') + ' ' + (d?.nombre ?? '')).toLowerCase();
                    const match = texto.includes(q);
                    tr.style.display = match ? '' : 'none';
                    if (match) vis++;
                });
                document.getElementById('sinResultados').classList.toggle('hidden', vis > 0 || !q);
                const fv = document.getElementById('filaVacia');
                if (fv) fv.style.display = q ? 'none' : '';
            }

            /* ── Modal ─────────────────────────────────────────────── */
            function abrirModalCrear() {
                editMode = false; editId = null;
                document.getElementById('modalTitulo').textContent  = 'Nuevo Tipo de Documento';
                document.getElementById('modalCodigo').value        = '';
                document.getElementById('modalNombre').value        = '';
                document.getElementById('modalDescripcion').value   = '';
                document.getElementById('modalLongitud').value      = '';
                document.getElementById('modalEsNumerico').checked  = false;
                document.getElementById('modalEstado').checked      = true;
                limpiarErrores();
                abrirModal();
            }

            function abrirModalEditar(id) {
                const d = DATOS[id];
                if (!d) return;
                editMode = true; editId = id;
                document.getElementById('modalTitulo').textContent  = 'Editar Tipo de Documento';
                document.getElementById('modalId').value            = id;
                document.getElementById('modalCodigo').value        = d.codigo ?? '';
                document.getElementById('modalNombre').value        = d.nombre ?? '';
                document.getElementById('modalDescripcion').value   = d.descripcion ?? '';
                document.getElementById('modalLongitud').value      = d.longitud ?? '';
                document.getElementById('modalEsNumerico').checked  = !!d.es_numerico;
                document.getElementById('modalEstado').checked      = !!d.estado;
                limpiarErrores();
                abrirModal();
            }

            function abrirModal() {
                document.getElementById('modal').classList.remove('hidden');
                document.getElementById('modalCodigo').focus();
            }

            function cerrarModal() {
                document.getElementById('modal').classList.add('hidden');
            }

            /* ── Guardar ───────────────────────────────────────────── */
            async function guardar() {
                const btn = document.getElementById('btnGuardar');
                btn.disabled = true;
                limpiarErrores();

                const longVal = document.getElementById('modalLongitud').value.trim();
                const payload = {
                    codigo:       document.getElementById('modalCodigo').value.trim().toUpperCase(),
                    nombre:       document.getElementById('modalNombre').value.trim(),
                    descripcion:  document.getElementById('modalDescripcion').value.trim() || null,
                    longitud:     longVal ? parseInt(longVal) : null,
                    es_numerico:  document.getElementById('modalEsNumerico').checked,
                    estado:       document.getElementById('modalEstado').checked,
                };
                const url    = editMode ? `${BASE}/${editId}` : STORE;
                const method = editMode ? 'PATCH' : 'POST';

                try {
                    const resp = await fetch(url, {
                        method,
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                        body: JSON.stringify(payload),
                    });

                    if (resp.status === 422) { const j = await resp.json(); mostrarErrores(j.errors ?? {}); return; }
                    if (!resp.ok) { alert('Error inesperado. Inténtalo de nuevo.'); return; }

                    const { data } = await resp.json();
                    DATOS[data.id] = data;
                    cerrarModal();
                    editMode ? actualizarFila(data) : insertarFila(data);
                } catch (_) {
                    alert('Error de conexión.');
                } finally {
                    btn.disabled = false;
                }
            }

            /* ── Eliminar ──────────────────────────────────────────── */
            async function eliminar(id, tr) {
                const d = DATOS[id];
                if (!confirm(`¿Eliminar el tipo «${d?.codigo ?? id}»? Esta acción no se puede deshacer.`)) return;

                try {
                    const resp = await fetch(`${BASE}/${id}`, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    });
                    if (resp.status === 422) { const j = await resp.json(); alert(j.message ?? 'No se puede eliminar.'); return; }
                    if (!resp.ok) { alert('No se pudo eliminar.'); return; }

                    delete DATOS[id];
                    tr.style.transition = 'opacity 0.2s';
                    tr.style.opacity    = '0';
                    setTimeout(() => {
                        tr.remove();
                        if (!document.querySelector('#tablaBody tr[data-id]')) mostrarFilaVacia();
                    }, 200);
                } catch (_) {
                    alert('Error de conexión.');
                }
            }

            /* ── DOM helpers ───────────────────────────────────────── */
            function badgeEstado(v) {
                return v
                    ? `<span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-success-600 border border-success-600 bg-success-100">Activo</span>`
                    : `<span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-danger-600 border border-danger-600 bg-danger-100">Inactivo</span>`;
            }

            function badgeNumerico(v) {
                return v
                    ? `<span class="inline-flex items-center gap-[4px] text-xs font-medium text-success-600"><i class="material-symbols-outlined !text-[16px]">check_circle</i> Sí</span>`
                    : `<span class="inline-flex items-center gap-[4px] text-xs font-medium text-gray-400"><i class="material-symbols-outlined !text-[16px]">cancel</i> No</span>`;
            }

            function botonesAccion(id) {
                return `
                    <button type="button" onclick="abrirModalEditar(${id})"
                        class="text-gray-500 hover:text-primary-500 transition-colors mr-[10px]" title="Editar">
                        <i class="material-symbols-outlined !text-[18px]">edit</i>
                    </button>
                    <button type="button" onclick="eliminar(${id},this.closest('tr'))"
                        class="text-gray-500 hover:text-danger-500 transition-colors" title="Eliminar">
                        <i class="material-symbols-outlined !text-[18px]">delete</i>
                    </button>`;
            }

            function insertarFila(u) {
                document.getElementById('filaVacia')?.remove();
                const tr = document.createElement('tr');
                tr.className = 'border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors';
                tr.dataset.id = u.id;
                tr.innerHTML = `
                    <td class="py-[13px] px-[16px]"><span class="text-xs font-mono font-semibold px-[8px] py-[3px] rounded bg-primary-50 text-primary-600">${esc(u.codigo)}</span></td>
                    <td class="py-[13px] px-[16px] text-sm text-black dark:text-white font-medium">${esc(u.nombre)}</td>
                    <td class="py-[13px] px-[16px] text-sm text-gray-600 dark:text-gray-400">${u.longitud ? u.longitud + ' dígitos' : '—'}</td>
                    <td class="py-[13px] px-[16px]">${badgeNumerico(u.es_numerico)}</td>
                    <td class="py-[13px] px-[16px]">${badgeEstado(u.estado)}</td>
                    <td class="py-[13px] px-[16px] text-right">${botonesAccion(u.id)}</td>`;
                document.getElementById('tablaBody').prepend(tr);
            }

            function actualizarFila(u) {
                const tr = document.querySelector(`#tablaBody tr[data-id="${u.id}"]`);
                if (!tr) return;
                const tds = tr.querySelectorAll('td');
                tds[0].innerHTML = `<span class="text-xs font-mono font-semibold px-[8px] py-[3px] rounded bg-primary-50 text-primary-600">${esc(u.codigo)}</span>`;
                tds[1].textContent = u.nombre;
                tds[2].textContent = u.longitud ? u.longitud + ' dígitos' : '—';
                tds[3].innerHTML   = badgeNumerico(u.es_numerico);
                tds[4].innerHTML   = badgeEstado(u.estado);
            }

            function mostrarFilaVacia() {
                const tr = document.createElement('tr');
                tr.id = 'filaVacia';
                tr.innerHTML = `<td colspan="6" class="text-center py-[50px] text-gray-500 dark:text-gray-400">
                    <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">badge</i>
                    No hay tipos de documento registrados.</td>`;
                document.getElementById('tablaBody').appendChild(tr);
            }

            /* ── Errores ───────────────────────────────────────────── */
            function mostrarErrores(errs) {
                const map = { codigo: 'errCodigo', nombre: 'errNombre', longitud: 'errLongitud' };
                Object.entries(map).forEach(([k, id]) => {
                    if (errs[k]) { const el = document.getElementById(id); el.textContent = errs[k][0]; el.classList.remove('hidden'); }
                });
            }

            function limpiarErrores() {
                ['errCodigo', 'errNombre', 'errLongitud'].forEach(id => {
                    const el = document.getElementById(id); el.textContent = ''; el.classList.add('hidden');
                });
            }

            function esc(s) {
                const d = document.createElement('div');
                d.appendChild(document.createTextNode(s ?? ''));
                return d.innerHTML;
            }

            // Forzar mayúsculas en el campo Código
            document.getElementById('modalCodigo').addEventListener('input', function () {
                const pos = this.selectionStart;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(pos, pos);
            });

            document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });
        </script>
    </body>
</html>
