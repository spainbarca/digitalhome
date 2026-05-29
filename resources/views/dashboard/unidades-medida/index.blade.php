<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('partials.front.styles')

        <title>Unidades de Medida</title>

        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Unidades de Medida</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Unidades de Medida
                    </li>
                </ol>
            </div>

            <!-- Card principal -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                    <div class="trezo-card-title">
                        <div class="relative sm:w-[265px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" id="buscar" oninput="filtrarTabla()"
                                placeholder="Buscar por nombre o símbolo..."
                                class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </div>
                    </div>
                    <div class="trezo-card-subtitle mt-[15px] sm:mt-0">
                        <button type="button" onclick="abrirModalCrear()"
                            class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                                Nueva Unidad
                            </span>
                        </button>
                    </div>
                </div>

                <div class="trezo-card-content">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-[#172036]">
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap w-[60px]">Ícono</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap">Nombre</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap w-[100px]">Símbolo</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap w-[100px]">Estado</th>
                                    <th class="text-right py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap w-[100px]">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaBody">
                                @forelse($unidades as $u)
                                <tr class="border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors"
                                    data-id="{{ $u->id }}"
                                    data-nombre="{{ $u->nombre }}"
                                    data-simbolo="{{ $u->simbolo }}"
                                    data-icono="{{ $u->icono ?? '' }}"
                                    data-activo="{{ $u->activo ? '1' : '0' }}">
                                    <td class="py-[13px] px-[16px]">
                                        <i class="material-symbols-outlined !text-[22px] text-primary-500">{{ $u->icono ?? 'category' }}</i>
                                    </td>
                                    <td class="py-[13px] px-[16px] text-sm text-black dark:text-white font-medium">{{ $u->nombre }}</td>
                                    <td class="py-[13px] px-[16px] text-sm text-gray-600 dark:text-gray-400 font-mono">{{ $u->simbolo }}</td>
                                    <td class="py-[13px] px-[16px]">
                                        @if($u->activo)
                                            <span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-success-600 border border-success-600 bg-success-100 dark:bg-[#0c1427]">Activo</span>
                                        @else
                                            <span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-danger-600 border border-danger-600 bg-danger-100 dark:bg-[#0c1427]">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="py-[13px] px-[16px] text-right">
                                        <button type="button"
                                            onclick="abrirModalEditar(this.closest('tr'))"
                                            class="text-gray-500 dark:text-gray-400 hover:text-primary-500 transition-colors mr-[10px]"
                                            title="Editar">
                                            <i class="material-symbols-outlined !text-[18px]">edit</i>
                                        </button>
                                        <button type="button"
                                            onclick="eliminar('{{ $u->id }}', '{{ addslashes($u->nombre) }}', this.closest('tr'))"
                                            class="text-gray-500 dark:text-gray-400 hover:text-danger-500 transition-colors"
                                            title="Eliminar">
                                            <i class="material-symbols-outlined !text-[18px]">delete</i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="filaVacia">
                                    <td colspan="5" class="text-center py-[50px] text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">straighten</i>
                                        No hay unidades de medida registradas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Contador de resultados filtrados -->
                    <p id="sinResultados" class="hidden text-center py-[30px] text-sm text-gray-500 dark:text-gray-400">
                        No se encontraron coincidencias.
                    </p>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        <!-- ─── Modal Unidad de Medida ─────────────────────────────── -->
        <div id="modalUnidadMedida" class="hidden fixed inset-0 z-[60] flex items-center justify-center" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModal()"></div>
            <div class="relative bg-white dark:bg-[#0c1427] rounded-md w-full max-w-md mx-[16px] shadow-2xl z-10">

                <!-- Header -->
                <div class="flex items-center justify-between px-[25px] py-[18px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-[16px]" id="modalUnidadTitulo">Nueva Unidad de Medida</h5>
                    <button type="button" onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <i class="material-symbols-outlined">close</i>
                    </button>
                </div>

                <!-- Body -->
                <div class="px-[25px] py-[20px] space-y-[16px]">
                    <input type="hidden" id="modalId">

                    <!-- Nombre -->
                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                            Nombre <span class="text-danger-500">*</span>
                        </label>
                        <input type="text" id="modalNombre" maxlength="80" placeholder="Ej: Metro cúbico"
                            class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm">
                        <p id="errNombre" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <!-- Símbolo -->
                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                            Símbolo <span class="text-danger-500">*</span>
                        </label>
                        <input type="text" id="modalSimbolo" maxlength="10" placeholder="Ej: m³"
                            class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm">
                        <p id="errSimbolo" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <!-- Ícono con preview -->
                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                            Ícono <span class="text-xs text-gray-400 font-normal">(nombre de material-symbols-outlined)</span>
                        </label>
                        <div class="flex items-center gap-[10px]">
                            <input type="text" id="modalIcono" maxlength="60" placeholder="Ej: water_drop"
                                oninput="actualizarPreviewIcono()"
                                class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm">
                            <div class="flex-shrink-0 w-[46px] h-[46px] rounded-md border border-gray-200 dark:border-[#172036] flex items-center justify-center bg-primary-50 dark:bg-[#15203c]">
                                <i class="material-symbols-outlined !text-[22px] text-primary-500" id="previewIcono">category</i>
                            </div>
                        </div>
                        <p id="errIcono" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <!-- Activo toggle -->
                    <div class="flex items-center justify-between pt-[4px]">
                        <span class="text-sm font-medium text-black dark:text-white">Activo</span>
                        <label class="relative cursor-pointer">
                            <input type="checkbox" id="modalActivo" class="sr-only peer" checked>
                            <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                        </label>
                    </div>
                </div>

                <!-- Footer -->
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
            const csrfToken    = '{{ csrf_token() }}';
            const storeUrl     = '{{ route('dashboard.unidades-medida.store') }}';
            const baseUrl      = '{{ url('dashboard/unidades-medida') }}';
            let   modoEdicion  = false;
            let   idEditando   = null;

            /* ── Búsqueda en tiempo real ────────────────────────── */
            function filtrarTabla() {
                const q    = document.getElementById('buscar').value.toLowerCase().trim();
                const rows = document.querySelectorAll('#tablaBody tr[data-id]');
                let   vis  = 0;
                rows.forEach(row => {
                    const texto = row.dataset.nombre.toLowerCase() + ' ' + row.dataset.simbolo.toLowerCase();
                    const mostrar = texto.includes(q);
                    row.style.display = mostrar ? '' : 'none';
                    if (mostrar) vis++;
                });
                document.getElementById('sinResultados').classList.toggle('hidden', vis > 0 || q === '');
                const vacia = document.getElementById('filaVacia');
                if (vacia) vacia.style.display = q ? 'none' : '';
            }

            /* ── Modal helpers ──────────────────────────────────── */
            function abrirModalCrear() {
                modoEdicion = false;
                idEditando  = null;
                document.getElementById('modalUnidadTitulo').textContent = 'Nueva Unidad de Medida';
                document.getElementById('modalId').value      = '';
                document.getElementById('modalNombre').value  = '';
                document.getElementById('modalSimbolo').value = '';
                document.getElementById('modalIcono').value   = '';
                document.getElementById('previewIcono').textContent = 'category';
                document.getElementById('modalActivo').checked = true;
                limpiarErrores();
                document.getElementById('modalUnidadMedida').classList.remove('hidden');
                document.getElementById('modalNombre').focus();
            }

            function abrirModalEditar(fila) {
                modoEdicion = true;
                idEditando  = fila.dataset.id;
                document.getElementById('modalUnidadTitulo').textContent = 'Editar Unidad de Medida';
                document.getElementById('modalId').value      = fila.dataset.id;
                document.getElementById('modalNombre').value  = fila.dataset.nombre;
                document.getElementById('modalSimbolo').value = fila.dataset.simbolo;
                document.getElementById('modalIcono').value   = fila.dataset.icono;
                document.getElementById('previewIcono').textContent = fila.dataset.icono || 'category';
                document.getElementById('modalActivo').checked = fila.dataset.activo === '1';
                limpiarErrores();
                document.getElementById('modalUnidadMedida').classList.remove('hidden');
                document.getElementById('modalNombre').focus();
            }

            function cerrarModal() {
                document.getElementById('modalUnidadMedida').classList.add('hidden');
            }

            function actualizarPreviewIcono() {
                const val = document.getElementById('modalIcono').value.trim();
                document.getElementById('previewIcono').textContent = val || 'category';
            }

            /* ── Guardar (store / update) ───────────────────────── */
            async function guardar() {
                const btn = document.getElementById('btnGuardar');
                btn.disabled = true;
                limpiarErrores();

                const payload = {
                    nombre:  document.getElementById('modalNombre').value.trim(),
                    simbolo: document.getElementById('modalSimbolo').value.trim(),
                    icono:   document.getElementById('modalIcono').value.trim() || null,
                    activo:  document.getElementById('modalActivo').checked,
                };

                const url    = modoEdicion ? `${baseUrl}/${idEditando}` : storeUrl;
                const method = modoEdicion ? 'PATCH' : 'POST';

                try {
                    const resp = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type':  'application/json',
                            'Accept':        'application/json',
                            'X-CSRF-TOKEN':  csrfToken,
                        },
                        body: JSON.stringify(payload),
                    });

                    if (resp.status === 422) {
                        const json = await resp.json();
                        mostrarErrores(json.errors ?? {});
                        return;
                    }

                    if (!resp.ok) {
                        alert('Error inesperado. Inténtalo de nuevo.');
                        return;
                    }

                    const { data } = await resp.json();
                    cerrarModal();

                    if (modoEdicion) {
                        actualizarFila(data);
                    } else {
                        prependarFila(data);
                    }
                } catch (e) {
                    alert('Error de conexión.');
                } finally {
                    btn.disabled = false;
                }
            }

            /* ── Eliminar ───────────────────────────────────────── */
            async function eliminar(id, nombre, fila) {
                if (!confirm(`¿Eliminar la unidad «${nombre}»?`)) return;

                try {
                    const resp = await fetch(`${baseUrl}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept':       'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });

                    if (!resp.ok) {
                        alert('No se pudo eliminar. Inténtalo de nuevo.');
                        return;
                    }

                    fila.style.transition = 'opacity 0.2s';
                    fila.style.opacity    = '0';
                    setTimeout(() => {
                        fila.remove();
                        const tbody = document.getElementById('tablaBody');
                        if (!tbody.querySelector('tr[data-id]')) {
                            mostrarFilaVacia();
                        }
                    }, 200);
                } catch (e) {
                    alert('Error de conexión.');
                }
            }

            /* ── DOM helpers ────────────────────────────────────── */
            function badgeActivo(activo) {
                return activo
                    ? '<span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-success-600 border border-success-600 bg-success-100">Activo</span>'
                    : '<span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] text-danger-600 border border-danger-600 bg-danger-100">Inactivo</span>';
            }

            function botonesFila(u) {
                const n = esc(u.nombre);
                return `
                    <button type="button" onclick="abrirModalEditar(this.closest('tr'))"
                        class="text-gray-500 hover:text-primary-500 transition-colors mr-[10px]" title="Editar">
                        <i class="material-symbols-outlined !text-[18px]">edit</i>
                    </button>
                    <button type="button" onclick="eliminar('${u.id}','${n}',this.closest('tr'))"
                        class="text-gray-500 hover:text-danger-500 transition-colors" title="Eliminar">
                        <i class="material-symbols-outlined !text-[18px]">delete</i>
                    </button>`;
            }

            function prependarFila(u) {
                const vacia = document.getElementById('filaVacia');
                if (vacia) vacia.remove();

                const tr = document.createElement('tr');
                tr.className = 'border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors';
                tr.dataset.id     = u.id;
                tr.dataset.nombre = u.nombre;
                tr.dataset.simbolo = u.simbolo;
                tr.dataset.icono  = u.icono ?? '';
                tr.dataset.activo = u.activo ? '1' : '0';
                tr.innerHTML = `
                    <td class="py-[13px] px-[16px]"><i class="material-symbols-outlined !text-[22px] text-primary-500">${esc(u.icono || 'category')}</i></td>
                    <td class="py-[13px] px-[16px] text-sm text-black font-medium">${esc(u.nombre)}</td>
                    <td class="py-[13px] px-[16px] text-sm text-gray-600 font-mono">${esc(u.simbolo)}</td>
                    <td class="py-[13px] px-[16px]">${badgeActivo(u.activo)}</td>
                    <td class="py-[13px] px-[16px] text-right">${botonesFila(u)}</td>`;
                document.getElementById('tablaBody').prepend(tr);
            }

            function actualizarFila(u) {
                const tr = document.querySelector(`#tablaBody tr[data-id="${u.id}"]`);
                if (!tr) return;
                tr.dataset.nombre = u.nombre;
                tr.dataset.simbolo = u.simbolo;
                tr.dataset.icono  = u.icono ?? '';
                tr.dataset.activo = u.activo ? '1' : '0';
                const tds = tr.querySelectorAll('td');
                tds[0].innerHTML = `<i class="material-symbols-outlined !text-[22px] text-primary-500">${esc(u.icono || 'category')}</i>`;
                tds[1].textContent = u.nombre;
                tds[2].textContent = u.simbolo;
                tds[3].innerHTML   = badgeActivo(u.activo);
                tds[4].innerHTML   = `<div class="flex justify-end">${botonesFila(u)}</div>`;
            }

            function mostrarFilaVacia() {
                const tr = document.createElement('tr');
                tr.id = 'filaVacia';
                tr.innerHTML = `<td colspan="5" class="text-center py-[50px] text-gray-500">
                    <i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">straighten</i>
                    No hay unidades de medida registradas.
                </td>`;
                document.getElementById('tablaBody').appendChild(tr);
            }

            /* ── Errores de validación ──────────────────────────── */
            function mostrarErrores(errors) {
                const mapa = { nombre: 'errNombre', simbolo: 'errSimbolo', icono: 'errIcono' };
                Object.entries(mapa).forEach(([campo, id]) => {
                    const el = document.getElementById(id);
                    if (errors[campo]) {
                        el.textContent = errors[campo][0];
                        el.classList.remove('hidden');
                    }
                });
            }

            function limpiarErrores() {
                ['errNombre', 'errSimbolo', 'errIcono'].forEach(id => {
                    const el = document.getElementById(id);
                    el.textContent = '';
                    el.classList.add('hidden');
                });
            }

            /* ── Escape HTML ────────────────────────────────────── */
            function esc(str) {
                const d = document.createElement('div');
                d.appendChild(document.createTextNode(str ?? ''));
                return d.innerHTML;
            }

            /* Cerrar con Escape */
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') cerrarModal();
            });
        </script>
    </body>
</html>
