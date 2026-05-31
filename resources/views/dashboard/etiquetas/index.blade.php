<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Etiquetas</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Etiquetas</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Etiquetas
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
                                placeholder="Buscar por nombre..."
                                class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </div>
                    </div>
                    <div class="trezo-card-subtitle mt-[15px] sm:mt-0">
                        <button type="button" onclick="abrirModalCrear()"
                            class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                                Nueva Etiqueta
                            </span>
                        </button>
                    </div>
                </div>

                <div class="trezo-card-content">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-[#172036]">
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap w-[60px]">Color</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap">Nombre</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap">Documentos vinculados</th>
                                    <th class="text-right py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap w-[100px]">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaBody">
                                @forelse($etiquetas as $e)
                                <tr class="border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors"
                                    data-id="{{ $e->id }}"
                                    data-nombre="{{ $e->nombre }}"
                                    data-color="{{ $e->color }}"
                                    data-icono="{{ $e->icono ?? '' }}">
                                    <td class="py-[13px] px-[16px]">
                                        <span class="w-7 h-7 rounded-full inline-flex items-center justify-center" style="background-color: {{ $e->color }}">
                                            @if($e->icono)
                                                <i class="material-symbols-outlined text-white !text-[14px]">{{ $e->icono }}</i>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="py-[13px] px-[16px] text-sm text-black dark:text-white font-medium">{{ $e->nombre }}</td>
                                    <td class="py-[13px] px-[16px] text-sm text-gray-600 dark:text-gray-400">
                                        {{ $e->documentosServicio()->count() }}
                                    </td>
                                    <td class="py-[13px] px-[16px] text-right">
                                        <button type="button"
                                            onclick="abrirModalEditar(this.closest('tr'))"
                                            class="text-gray-500 dark:text-gray-400 hover:text-primary-500 transition-colors mr-[10px]"
                                            title="Editar">
                                            <i class="material-symbols-outlined !text-[18px]">edit</i>
                                        </button>
                                        <button type="button"
                                            onclick="eliminar('{{ $e->id }}', '{{ addslashes($e->nombre) }}', this.closest('tr'))"
                                            class="text-gray-500 dark:text-gray-400 hover:text-danger-500 transition-colors"
                                            title="Eliminar">
                                            <i class="material-symbols-outlined !text-[18px]">delete</i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="filaVacia">
                                    <td colspan="4" class="text-center py-[50px] text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">label</i>
                                        No hay etiquetas registradas.
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

        <!-- ─── Modal Etiqueta ─────────────────────────────── -->
        <div id="modalEtiqueta" class="hidden fixed inset-0 z-[60] flex items-center justify-center" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModal()"></div>
            <div class="relative bg-white dark:bg-[#0c1427] rounded-md w-full max-w-md mx-[16px] shadow-2xl z-10">

                <!-- Header -->
                <div class="flex items-center justify-between px-[25px] py-[18px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-[16px]" id="modalTitulo">Nueva Etiqueta</h5>
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
                        <input type="text" id="modalNombre" maxlength="60" placeholder="Ej: Urgente"
                            oninput="actualizarPreview()"
                            class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm">
                        <p id="errNombre" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <!-- Color -->
                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                            Color <span class="text-danger-500">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="color" id="colorPicker" value="#6366f1"
                                   class="w-10 h-10 rounded cursor-pointer border-0 p-0"
                                   oninput="sincronizarDesdeColor()">
                            <input type="text" id="colorHex" value="#6366f1"
                                   class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block flex-1 outline-0 transition-all focus:border-primary-500 text-sm"
                                   placeholder="#6366f1" maxlength="7"
                                   oninput="sincronizarDesdeHex()">
                        </div>
                        <p id="errColor" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <!-- Ícono -->
                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                            Ícono <span class="text-gray-400 text-xs font-normal">(opcional — Material Symbols)</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined !text-[22px] text-primary-500" id="previewIconoEtiqueta">label</i>
                            </div>
                            <input type="text" id="iconoEtiqueta" maxlength="80"
                                   placeholder="Ej: label, star, bookmark, folder..."
                                   oninput="actualizarPreviewIcono()"
                                   class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block flex-1 outline-0 transition-all focus:border-primary-500 text-sm">
                        </div>
                        <p class="text-xs text-gray-400 mt-[5px]">
                            Busca íconos en
                            <a href="https://fonts.google.com/icons" target="_blank" class="text-primary-500 hover:underline">fonts.google.com/icons</a>
                        </p>
                        <p id="errIcono" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <!-- Preview -->
                    <div class="flex items-center gap-2 py-[10px] px-[14px] bg-gray-50 dark:bg-[#15203c] rounded-md">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0" id="previewColor" style="background-color:#6366f1">
                            <i class="material-symbols-outlined text-white !text-[13px]" id="previewIconoMini">label</i>
                        </span>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="previewNombre">Nombre etiqueta</span>
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
            const csrfToken   = '{{ csrf_token() }}';
            const storeUrl    = '{{ route('dashboard.etiquetas.store') }}';
            const baseUrl     = '{{ url('dashboard/etiquetas') }}';
            let   modoEdicion = false;
            let   idEditando  = null;

            /* ── Búsqueda en tiempo real ────────────────────────── */
            function filtrarTabla() {
                const q    = document.getElementById('buscar').value.toLowerCase().trim();
                const rows = document.querySelectorAll('#tablaBody tr[data-id]');
                let   vis  = 0;
                rows.forEach(function(row) {
                    const texto   = row.dataset.nombre.toLowerCase();
                    const mostrar = texto.includes(q);
                    row.style.display = mostrar ? '' : 'none';
                    if (mostrar) vis++;
                });
                document.getElementById('sinResultados').classList.toggle('hidden', vis > 0 || q === '');
                const vacia = document.getElementById('filaVacia');
                if (vacia) vacia.style.display = q ? 'none' : '';
            }

            /* ── Color picker sync ──────────────────────────────── */
            function sincronizarDesdeColor() {
                const val = document.getElementById('colorPicker').value;
                document.getElementById('colorHex').value = val;
                actualizarPreview();
            }

            function sincronizarDesdeHex() {
                const val = document.getElementById('colorHex').value.trim();
                if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
                    document.getElementById('colorPicker').value = val;
                }
                actualizarPreview();
            }

            function actualizarPreviewIcono() {
                const val = document.getElementById('iconoEtiqueta').value.trim() || 'label';
                document.getElementById('previewIconoEtiqueta').textContent = val;
                document.getElementById('previewIconoMini').textContent     = val;
            }

            function actualizarPreview() {
                const nombre = document.getElementById('modalNombre').value.trim();
                const color  = document.getElementById('colorHex').value.trim();
                document.getElementById('previewNombre').textContent = nombre || 'Nombre etiqueta';
                if (/^#[0-9A-Fa-f]{6}$/.test(color)) {
                    document.getElementById('previewColor').style.backgroundColor = color;
                }
            }

            /* ── Modal helpers ──────────────────────────────────── */
            function abrirModalCrear() {
                modoEdicion = false;
                idEditando  = null;
                document.getElementById('modalTitulo').textContent              = 'Nueva Etiqueta';
                document.getElementById('modalId').value                        = '';
                document.getElementById('modalNombre').value                    = '';
                document.getElementById('colorPicker').value                    = '#6366f1';
                document.getElementById('colorHex').value                       = '#6366f1';
                document.getElementById('iconoEtiqueta').value                  = '';
                document.getElementById('previewColor').style.backgroundColor   = '#6366f1';
                document.getElementById('previewNombre').textContent            = 'Nombre etiqueta';
                document.getElementById('previewIconoEtiqueta').textContent     = 'label';
                document.getElementById('previewIconoMini').textContent         = 'label';
                limpiarErrores();
                document.getElementById('modalEtiqueta').classList.remove('hidden');
                document.getElementById('modalNombre').focus();
            }

            function abrirModalEditar(fila) {
                modoEdicion = true;
                idEditando  = fila.dataset.id;
                const color = fila.dataset.color;
                const icono = fila.dataset.icono || '';
                document.getElementById('modalTitulo').textContent              = 'Editar Etiqueta';
                document.getElementById('modalId').value                        = fila.dataset.id;
                document.getElementById('modalNombre').value                    = fila.dataset.nombre;
                document.getElementById('colorPicker').value                    = color;
                document.getElementById('colorHex').value                       = color;
                document.getElementById('iconoEtiqueta').value                  = icono;
                document.getElementById('previewColor').style.backgroundColor   = color;
                document.getElementById('previewNombre').textContent            = fila.dataset.nombre;
                document.getElementById('previewIconoEtiqueta').textContent     = icono || 'label';
                document.getElementById('previewIconoMini').textContent         = icono || 'label';
                limpiarErrores();
                document.getElementById('modalEtiqueta').classList.remove('hidden');
                document.getElementById('modalNombre').focus();
            }

            function cerrarModal() {
                document.getElementById('modalEtiqueta').classList.add('hidden');
            }

            /* ── Guardar (store / update) ───────────────────────── */
            async function guardar() {
                const btn = document.getElementById('btnGuardar');
                btn.disabled = true;
                limpiarErrores();

                const payload = {
                    nombre: document.getElementById('modalNombre').value.trim(),
                    color:  document.getElementById('colorHex').value.trim(),
                    icono:  document.getElementById('iconoEtiqueta').value.trim() || null,
                };

                const url    = modoEdicion ? (baseUrl + '/' + idEditando) : storeUrl;
                const method = modoEdicion ? 'PATCH' : 'POST';

                try {
                    const resp = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept':       'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(payload),
                    });

                    if (resp.status === 422) {
                        const json = await resp.json();
                        mostrarErrores(json.errors || {});
                        return;
                    }

                    if (!resp.ok) {
                        const json = await resp.json().catch(function() { return {}; });
                        alert(json.message || 'Error inesperado. Inténtalo de nuevo.');
                        return;
                    }

                    const json = await resp.json();
                    cerrarModal();

                    if (modoEdicion) {
                        actualizarFila(json.data);
                    } else {
                        prependarFila(json.data);
                    }
                } catch (e) {
                    alert('Error de conexión.');
                } finally {
                    btn.disabled = false;
                }
            }

            /* ── Eliminar ───────────────────────────────────────── */
            async function eliminar(id, nombre, fila) {
                if (!confirm('¿Eliminar la etiqueta «' + nombre + '»?')) return;

                try {
                    const resp = await fetch(baseUrl + '/' + id, {
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
                    setTimeout(function() {
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
            function circuloColor(color, icono) {
                var inner = icono
                    ? '<i class="material-symbols-outlined text-white !text-[14px]">' + esc(icono) + '</i>'
                    : '';
                return '<span class="w-7 h-7 rounded-full inline-flex items-center justify-center" style="background-color:' + esc(color) + '">' + inner + '</span>';
            }

            function botonesFila(e) {
                const n = esc(e.nombre);
                return '<button type="button" onclick="abrirModalEditar(this.closest(\'tr\'))"'
                    + ' class="text-gray-500 hover:text-primary-500 transition-colors mr-[10px]" title="Editar">'
                    + '<i class="material-symbols-outlined !text-[18px]">edit</i></button>'
                    + '<button type="button" onclick="eliminar(\'' + e.id + '\',\'' + n + '\',this.closest(\'tr\'))"'
                    + ' class="text-gray-500 hover:text-danger-500 transition-colors" title="Eliminar">'
                    + '<i class="material-symbols-outlined !text-[18px]">delete</i></button>';
            }

            function prependarFila(e) {
                const vacia = document.getElementById('filaVacia');
                if (vacia) vacia.remove();

                const tr = document.createElement('tr');
                tr.className = 'border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors';
                tr.dataset.id     = e.id;
                tr.dataset.nombre = e.nombre;
                tr.dataset.color  = e.color;
                tr.dataset.icono  = e.icono || '';
                tr.innerHTML = '<td class="py-[13px] px-[16px]">' + circuloColor(e.color, e.icono) + '</td>'
                    + '<td class="py-[13px] px-[16px] text-sm text-black font-medium">' + esc(e.nombre) + '</td>'
                    + '<td class="py-[13px] px-[16px] text-sm text-gray-600">0</td>'
                    + '<td class="py-[13px] px-[16px] text-right">' + botonesFila(e) + '</td>';
                document.getElementById('tablaBody').prepend(tr);
            }

            function actualizarFila(e) {
                const tr = document.querySelector('#tablaBody tr[data-id="' + e.id + '"]');
                if (!tr) return;
                tr.dataset.nombre = e.nombre;
                tr.dataset.color  = e.color;
                tr.dataset.icono  = e.icono || '';
                const tds = tr.querySelectorAll('td');
                tds[0].innerHTML   = circuloColor(e.color, e.icono);
                tds[1].textContent = e.nombre;
                tds[3].innerHTML   = botonesFila(e);
            }

            function mostrarFilaVacia() {
                const tr = document.createElement('tr');
                tr.id = 'filaVacia';
                tr.innerHTML = '<td colspan="4" class="text-center py-[50px] text-gray-500">'
                    + '<i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">label</i>'
                    + 'No hay etiquetas registradas.</td>';
                document.getElementById('tablaBody').appendChild(tr);
            }

            /* ── Errores de validación ──────────────────────────── */
            function mostrarErrores(errors) {
                var mapa = { nombre: 'errNombre', color: 'errColor', icono: 'errIcono' };
                Object.keys(mapa).forEach(function(campo) {
                    var el = document.getElementById(mapa[campo]);
                    if (errors[campo]) {
                        el.textContent = errors[campo][0];
                        el.classList.remove('hidden');
                    }
                });
            }

            function limpiarErrores() {
                ['errNombre', 'errColor', 'errIcono'].forEach(function(id) {
                    var el = document.getElementById(id);
                    el.textContent = '';
                    el.classList.add('hidden');
                });
            }

            /* ── Escape HTML ────────────────────────────────────── */
            function esc(str) {
                var d = document.createElement('div');
                d.appendChild(document.createTextNode(str != null ? str : ''));
                return d.innerHTML;
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') cerrarModal();
            });
        </script>
    </body>
</html>
