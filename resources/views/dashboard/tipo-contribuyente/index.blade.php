<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Tipos de Contribuyente</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Tipos de Contribuyente</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Tipos de Contribuyente
                    </li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                    <div class="trezo-card-title">
                        <div class="relative sm:w-[265px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" id="buscar" oninput="filtrarTabla()" placeholder="Buscar por nombre..."
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
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400">Nombre</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[110px]">Empresas</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[100px]">Estado</th>
                                    <th class="text-right py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[90px]">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaBody">
                                @forelse($tipos as $t)
                                <tr class="border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors"
                                    data-id="{{ $t->id }}"
                                    data-nombre="{{ $t->nombre }}"
                                    data-activo="{{ $t->activo ? '1' : '0' }}"
                                    data-count="{{ $t->empresas_count }}">
                                    <td class="py-[13px] px-[16px] text-sm text-black dark:text-white font-medium">{{ $t->nombre }}</td>
                                    <td class="py-[13px] px-[16px]">
                                        <span class="text-xs font-medium px-[8px] py-[2px] rounded-sm bg-primary-50 dark:bg-[#15203c] text-primary-500">
                                            {{ $t->empresas_count }}
                                        </span>
                                    </td>
                                    <td class="py-[13px] px-[16px]">
                                        @if($t->activo)
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
                                        <button type="button" onclick="eliminar('{{ $t->id }}', '{{ addslashes($t->nombre) }}', {{ $t->empresas_count }}, this.closest('tr'))"
                                            class="text-gray-500 dark:text-gray-400 hover:text-danger-500 transition-colors" title="Eliminar">
                                            <i class="material-symbols-outlined !text-[18px]">delete</i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="filaVacia">
                                    <td colspan="4" class="text-center py-[50px] text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">receipt_long</i>
                                        No hay tipos de contribuyente registrados.
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

        <!-- ─── Modal ───────────────────────────────────────────── -->
        <div id="modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModal()"></div>
            <div class="relative bg-white dark:bg-[#0c1427] rounded-md w-full max-w-sm mx-[16px] shadow-2xl z-10">

                <div class="flex items-center justify-between px-[25px] py-[18px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-[16px]" id="modalTitulo">Nuevo Tipo de Contribuyente</h5>
                    <button type="button" onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <i class="material-symbols-outlined">close</i>
                    </button>
                </div>

                <div class="px-[25px] py-[20px] space-y-[16px]">
                    <input type="hidden" id="modalId">

                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                            Nombre <span class="text-danger-500">*</span>
                        </label>
                        <input type="text" id="modalNombre" maxlength="100" placeholder="Ej: Persona Natural"
                            class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm placeholder:text-gray-400">
                        <p id="errNombre" class="hidden text-xs text-danger-500 mt-[5px]"></p>
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
            const CSRF   = '{{ csrf_token() }}';
            const STORE  = '{{ route('dashboard.tipo-contribuyente.store') }}';
            const BASE   = '{{ url('dashboard/tipo-contribuyente') }}';
            let editMode = false;
            let editId   = null;

            function filtrarTabla() {
                const q    = document.getElementById('buscar').value.toLowerCase().trim();
                const rows = document.querySelectorAll('#tablaBody tr[data-id]');
                let vis = 0;
                rows.forEach(tr => {
                    const match = tr.dataset.nombre.toLowerCase().includes(q);
                    tr.style.display = match ? '' : 'none';
                    if (match) vis++;
                });
                document.getElementById('sinResultados').classList.toggle('hidden', vis > 0 || !q);
                const fv = document.getElementById('filaVacia');
                if (fv) fv.style.display = q ? 'none' : '';
            }

            function abrirModalCrear() {
                editMode = false; editId = null;
                document.getElementById('modalTitulo').textContent = 'Nuevo Tipo de Contribuyente';
                document.getElementById('modalNombre').value = '';
                document.getElementById('modalActivo').checked = true;
                limpiarErrores();
                abrirModal();
            }

            function abrirModalEditar(tr) {
                editMode = true; editId = tr.dataset.id;
                document.getElementById('modalTitulo').textContent = 'Editar Tipo de Contribuyente';
                document.getElementById('modalId').value    = tr.dataset.id;
                document.getElementById('modalNombre').value = tr.dataset.nombre;
                document.getElementById('modalActivo').checked = tr.dataset.activo === '1';
                limpiarErrores();
                abrirModal();
            }

            function abrirModal() {
                document.getElementById('modal').classList.remove('hidden');
                document.getElementById('modalNombre').focus();
            }

            function cerrarModal() {
                document.getElementById('modal').classList.add('hidden');
            }

            async function guardar() {
                const btn = document.getElementById('btnGuardar');
                btn.disabled = true;
                limpiarErrores();

                const payload = {
                    nombre: document.getElementById('modalNombre').value.trim(),
                    activo: document.getElementById('modalActivo').checked,
                };
                const url    = editMode ? `${BASE}/${editId}` : STORE;
                const method = editMode ? 'PATCH' : 'POST';

                try {
                    const resp = await fetch(url, {
                        method,
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                        body: JSON.stringify(payload),
                    });

                    if (resp.status === 422) {
                        const json = await resp.json();
                        mostrarErrores(json.errors ?? {});
                        return;
                    }
                    if (!resp.ok) { alert('Error inesperado. Inténtalo de nuevo.'); return; }

                    const { data } = await resp.json();
                    cerrarModal();
                    editMode ? actualizarFila(data) : insertarFila(data);
                } catch (_) {
                    alert('Error de conexión.');
                } finally {
                    btn.disabled = false;
                }
            }

            async function eliminar(id, nombre, count, tr) {
                if (count > 0) {
                    alert(`No se puede eliminar «${nombre}» porque tiene ${count} empresa(s) asociada(s).`);
                    return;
                }
                if (!confirm(`¿Eliminar el tipo «${nombre}»? Esta acción no se puede deshacer.`)) return;

                try {
                    const resp = await fetch(`${BASE}/${id}`, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    });
                    if (resp.status === 422) { const j = await resp.json(); alert(j.message ?? 'No se puede eliminar.'); return; }
                    if (!resp.ok) { alert('No se pudo eliminar.'); return; }

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

            function badgeActivo(a) {
                return a
                    ? `<span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-success-600 border border-success-600 bg-success-100">Activo</span>`
                    : `<span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-danger-600 border border-danger-600 bg-danger-100">Inactivo</span>`;
            }

            function botonesAccion(u) {
                return `
                    <button type="button" onclick="abrirModalEditar(this.closest('tr'))"
                        class="text-gray-500 hover:text-primary-500 transition-colors mr-[10px]" title="Editar">
                        <i class="material-symbols-outlined !text-[18px]">edit</i>
                    </button>
                    <button type="button" onclick="eliminar('${u.id}','${esc(u.nombre)}',${u.empresas_count ?? 0},this.closest('tr'))"
                        class="text-gray-500 hover:text-danger-500 transition-colors" title="Eliminar">
                        <i class="material-symbols-outlined !text-[18px]">delete</i>
                    </button>`;
            }

            function insertarFila(u) {
                document.getElementById('filaVacia')?.remove();
                const tr = document.createElement('tr');
                tr.className = 'border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors';
                tr.dataset.id     = u.id;
                tr.dataset.nombre = u.nombre;
                tr.dataset.activo = u.activo ? '1' : '0';
                tr.dataset.count  = 0;
                tr.innerHTML = `
                    <td class="py-[13px] px-[16px] text-sm text-black dark:text-white font-medium">${esc(u.nombre)}</td>
                    <td class="py-[13px] px-[16px]"><span class="text-xs font-medium px-[8px] py-[2px] rounded-sm bg-primary-50 text-primary-500">0</span></td>
                    <td class="py-[13px] px-[16px]">${badgeActivo(u.activo)}</td>
                    <td class="py-[13px] px-[16px] text-right">${botonesAccion(u)}</td>`;
                document.getElementById('tablaBody').prepend(tr);
            }

            function actualizarFila(u) {
                const tr = document.querySelector(`#tablaBody tr[data-id="${u.id}"]`);
                if (!tr) return;
                tr.dataset.nombre = u.nombre;
                tr.dataset.activo = u.activo ? '1' : '0';
                const tds = tr.querySelectorAll('td');
                tds[0].textContent = u.nombre;
                tds[2].innerHTML   = badgeActivo(u.activo);
                tds[3].innerHTML   = `<div class="flex justify-end">${botonesAccion(u)}</div>`;
            }

            function mostrarFilaVacia() {
                const tr = document.createElement('tr');
                tr.id = 'filaVacia';
                tr.innerHTML = `<td colspan="4" class="text-center py-[50px] text-gray-500 dark:text-gray-400">
                    <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">receipt_long</i>
                    No hay tipos de contribuyente registrados.</td>`;
                document.getElementById('tablaBody').appendChild(tr);
            }

            function mostrarErrores(errs) {
                if (errs.nombre) {
                    const el = document.getElementById('errNombre');
                    el.textContent = errs.nombre[0];
                    el.classList.remove('hidden');
                }
            }

            function limpiarErrores() {
                const el = document.getElementById('errNombre');
                el.textContent = ''; el.classList.add('hidden');
            }

            function esc(s) {
                const d = document.createElement('div');
                d.appendChild(document.createTextNode(s ?? ''));
                return d.innerHTML;
            }

            document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });
        </script>
    </body>
</html>
