<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Métodos de Pago</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Métodos de Pago</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Métodos de Pago</li>
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
                                Nuevo Método
                            </span>
                        </button>
                    </div>
                </div>

                <div class="trezo-card-content">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-[#172036]">
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[60px]">Logo</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[60px]">Ícono</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400">Nombre</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[100px]">Estado</th>
                                    <th class="text-right py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[100px]">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaBody">
                                @forelse($metodos as $m)
                                <tr class="border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors"
                                    data-id="{{ $m->id }}"
                                    data-nombre="{{ $m->nombre }}"
                                    data-icono="{{ $m->icono ?? '' }}"
                                    data-logo="{{ $m->logo ? asset('storage/' . $m->logo) : '' }}"
                                    data-activo="{{ $m->activo ? '1' : '0' }}">
                                    <td class="py-[13px] px-[16px]">
                                        @if($m->logo)
                                            <img src="{{ asset('storage/' . $m->logo) }}" alt="{{ $m->nombre }}"
                                                class="w-[36px] h-[36px] rounded-full object-cover border border-gray-100 dark:border-[#172036]">
                                        @else
                                            <span class="w-[36px] h-[36px] rounded-full bg-gray-100 dark:bg-[#172036] flex items-center justify-center text-[11px] font-bold text-gray-500 dark:text-gray-400">
                                                {{ mb_strtoupper(mb_substr($m->nombre, 0, 1)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-[13px] px-[16px]">
                                        <i class="material-symbols-outlined !text-[22px] text-primary-500">{{ $m->icono ?? 'payment' }}</i>
                                    </td>
                                    <td class="py-[13px] px-[16px] text-sm text-black dark:text-white font-medium">{{ $m->nombre }}</td>
                                    <td class="py-[13px] px-[16px]">
                                        @if($m->activo)
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
                                        <button type="button" onclick="eliminar('{{ $m->id }}', '{{ addslashes($m->nombre) }}', this.closest('tr'))"
                                            class="text-gray-500 dark:text-gray-400 hover:text-danger-500 transition-colors" title="Eliminar">
                                            <i class="material-symbols-outlined !text-[18px]">delete</i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="filaVacia">
                                    <td colspan="5" class="text-center py-[50px] text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">payments</i>
                                        No hay métodos de pago registrados.
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

        <!-- Modal Crear / Editar -->
        <div id="modalMetodo" class="hidden fixed inset-0 z-[60] flex items-center justify-center" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModal()"></div>
            <div class="relative bg-white dark:bg-[#0c1427] rounded-md w-full max-w-md mx-[16px] shadow-2xl z-10 max-h-[90vh] overflow-y-auto">

                <div class="flex items-center justify-between px-[25px] py-[18px] border-b border-gray-100 dark:border-[#172036] sticky top-0 bg-white dark:bg-[#0c1427] z-10">
                    <h5 class="!mb-0 text-[16px]" id="modalTitulo">Nuevo Método de Pago</h5>
                    <button type="button" onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <i class="material-symbols-outlined">close</i>
                    </button>
                </div>

                <div class="px-[25px] py-[20px] space-y-[16px]">
                    <input type="hidden" id="modalId">

                    <!-- Nombre -->
                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Nombre <span class="text-danger-500">*</span></label>
                        <input type="text" id="modalNombre" maxlength="255" placeholder="Ej: Tarjeta de crédito"
                            class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm">
                        <p id="errNombre" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <!-- Ícono con preview -->
                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                            Ícono <span class="text-xs text-gray-400 font-normal">(nombre material-symbols-outlined)</span>
                        </label>
                        <div class="flex items-center gap-[10px]">
                            <input type="text" id="modalIcono" maxlength="255" placeholder="Ej: payment"
                                oninput="actualizarPreviewIcono()"
                                class="h-[46px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500 text-sm">
                            <div class="flex-shrink-0 w-[46px] h-[46px] rounded-md border border-gray-200 dark:border-[#172036] flex items-center justify-center bg-primary-50 dark:bg-[#15203c]">
                                <i class="material-symbols-outlined !text-[22px] text-primary-500" id="previewIcono">payment</i>
                            </div>
                        </div>
                        <p id="errIcono" class="hidden text-xs text-danger-500 mt-[5px]"></p>
                    </div>

                    <!-- Logo -->
                    <div>
                        <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Logo</label>
                        <div class="flex items-center gap-[12px]">
                            <div class="flex-shrink-0">
                                <img id="previewLogo" src="" alt=""
                                    class="hidden w-[48px] h-[48px] rounded-full object-cover border border-gray-200 dark:border-[#172036]">
                                <span id="placeholderLogo"
                                    class="w-[48px] h-[48px] rounded-full bg-gray-100 dark:bg-[#172036] flex items-center justify-center">
                                    <i class="material-symbols-outlined !text-[22px] text-gray-400">image</i>
                                </span>
                            </div>
                            <input type="file" id="modalLogo" accept="image/jpg,image/jpeg,image/png,image/webp,image/svg+xml"
                                onchange="previewLogoChange()"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-[10px] file:py-[6px] file:px-[12px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100 dark:file:bg-[#15203c] dark:file:text-primary-400 cursor-pointer">
                        </div>
                        <p id="errLogo" class="hidden text-xs text-danger-500 mt-[5px]"></p>
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
            const csrfToken  = '{{ csrf_token() }}';
            const storeUrl   = '{{ route('dashboard.metodos-pago.store') }}';
            const baseUrl    = '{{ url('dashboard/metodos-pago') }}';
            const iconDefault = 'payment';
            let   modoEdicion = false;
            let   idEditando  = null;

            function filtrarTabla() {
                const q    = document.getElementById('buscar').value.toLowerCase().trim();
                const rows = document.querySelectorAll('#tablaBody tr[data-id]');
                let   vis  = 0;
                rows.forEach(row => {
                    const match = row.dataset.nombre.toLowerCase().includes(q);
                    row.style.display = match ? '' : 'none';
                    if (match) vis++;
                });
                document.getElementById('sinResultados').classList.toggle('hidden', vis > 0 || q === '');
                const vacia = document.getElementById('filaVacia');
                if (vacia) vacia.style.display = q ? 'none' : '';
            }

            function abrirModalCrear() {
                modoEdicion = false; idEditando = null;
                document.getElementById('modalTitulo').textContent  = 'Nuevo Método de Pago';
                document.getElementById('modalId').value            = '';
                document.getElementById('modalNombre').value        = '';
                document.getElementById('modalIcono').value         = '';
                document.getElementById('previewIcono').textContent = iconDefault;
                document.getElementById('modalActivo').checked      = true;
                resetLogoPreview('');
                limpiarErrores();
                document.getElementById('modalMetodo').classList.remove('hidden');
                document.getElementById('modalNombre').focus();
            }

            function abrirModalEditar(fila) {
                modoEdicion = true; idEditando = fila.dataset.id;
                document.getElementById('modalTitulo').textContent  = 'Editar Método de Pago';
                document.getElementById('modalId').value            = fila.dataset.id;
                document.getElementById('modalNombre').value        = fila.dataset.nombre;
                document.getElementById('modalIcono').value         = fila.dataset.icono;
                document.getElementById('previewIcono').textContent = fila.dataset.icono || iconDefault;
                document.getElementById('modalActivo').checked      = fila.dataset.activo === '1';
                resetLogoPreview(fila.dataset.logo);
                document.getElementById('modalLogo').value          = '';
                limpiarErrores();
                document.getElementById('modalMetodo').classList.remove('hidden');
                document.getElementById('modalNombre').focus();
            }

            function cerrarModal() {
                document.getElementById('modalMetodo').classList.add('hidden');
            }

            function actualizarPreviewIcono() {
                const val = document.getElementById('modalIcono').value.trim();
                document.getElementById('previewIcono').textContent = val || iconDefault;
            }

            function previewLogoChange() {
                const file = document.getElementById('modalLogo').files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => mostrarLogoPreview(e.target.result);
                reader.readAsDataURL(file);
            }

            function mostrarLogoPreview(src) {
                document.getElementById('previewLogo').src = src;
                document.getElementById('previewLogo').classList.remove('hidden');
                document.getElementById('placeholderLogo').classList.add('hidden');
            }

            function resetLogoPreview(src) {
                if (src) {
                    mostrarLogoPreview(src);
                } else {
                    document.getElementById('previewLogo').classList.add('hidden');
                    document.getElementById('placeholderLogo').classList.remove('hidden');
                }
            }

            async function guardar() {
                const btn = document.getElementById('btnGuardar');
                btn.disabled = true;
                limpiarErrores();

                const formData = new FormData();
                formData.append('nombre', document.getElementById('modalNombre').value.trim());
                formData.append('icono',  document.getElementById('modalIcono').value.trim() || '');
                formData.append('activo', document.getElementById('modalActivo').checked ? '1' : '0');

                const logoFile = document.getElementById('modalLogo').files[0];
                if (logoFile) formData.append('logo', logoFile);

                const url    = modoEdicion ? `${baseUrl}/${idEditando}` : storeUrl;
                const method = modoEdicion ? 'POST' : 'POST';
                if (modoEdicion) formData.append('_method', 'PATCH');

                try {
                    const resp = await fetch(url, {
                        method,
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: formData,
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
                if (!confirm(`¿Eliminar el método «${nombre}»?`)) return;
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

            function logoHtml(logo, nombre) {
                if (logo) return `<img src="${esc(logo)}" alt="${esc(nombre)}" class="w-[36px] h-[36px] rounded-full object-cover border border-gray-100">`;
                return `<span class="w-[36px] h-[36px] rounded-full bg-gray-100 flex items-center justify-center text-[11px] font-bold text-gray-500">${esc(nombre.charAt(0).toUpperCase())}</span>`;
            }

            function botonesHtml(t) {
                return `
                    <button type="button" onclick="abrirModalEditar(this.closest('tr'))"
                        class="text-gray-500 hover:text-primary-500 transition-colors mr-[10px]" title="Editar">
                        <i class="material-symbols-outlined !text-[18px]">edit</i>
                    </button>
                    <button type="button" onclick="eliminar('${t.id}','${esc(t.nombre)}',this.closest('tr'))"
                        class="text-gray-500 hover:text-danger-500 transition-colors" title="Eliminar">
                        <i class="material-symbols-outlined !text-[18px]">delete</i>
                    </button>`;
            }

            function prependarFila(t) {
                const vacia = document.getElementById('filaVacia');
                if (vacia) vacia.remove();
                const tr = document.createElement('tr');
                tr.className = 'border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors';
                tr.dataset.id     = t.id;
                tr.dataset.nombre = t.nombre;
                tr.dataset.icono  = t.icono ?? '';
                tr.dataset.logo   = t.logo ?? '';
                tr.dataset.activo = t.activo ? '1' : '0';
                tr.innerHTML = `
                    <td class="py-[13px] px-[16px]">${logoHtml(t.logo, t.nombre)}</td>
                    <td class="py-[13px] px-[16px]"><i class="material-symbols-outlined !text-[22px] text-primary-500">${esc(t.icono || iconDefault)}</i></td>
                    <td class="py-[13px] px-[16px] text-sm text-black font-medium">${esc(t.nombre)}</td>
                    <td class="py-[13px] px-[16px]">${badgeActivo(t.activo)}</td>
                    <td class="py-[13px] px-[16px] text-right">${botonesHtml(t)}</td>`;
                document.getElementById('tablaBody').prepend(tr);
            }

            function actualizarFila(t) {
                const tr = document.querySelector(`#tablaBody tr[data-id="${t.id}"]`);
                if (!tr) return;
                tr.dataset.nombre = t.nombre;
                tr.dataset.icono  = t.icono ?? '';
                tr.dataset.logo   = t.logo ?? '';
                tr.dataset.activo = t.activo ? '1' : '0';
                const tds = tr.querySelectorAll('td');
                tds[0].innerHTML = logoHtml(t.logo, t.nombre);
                tds[1].innerHTML = `<i class="material-symbols-outlined !text-[22px] text-primary-500">${esc(t.icono || iconDefault)}</i>`;
                tds[2].textContent = t.nombre;
                tds[3].innerHTML   = badgeActivo(t.activo);
                tds[4].innerHTML   = `<div class="flex justify-end">${botonesHtml(t)}</div>`;
            }

            function mostrarFilaVacia() {
                const tr = document.createElement('tr');
                tr.id = 'filaVacia';
                tr.innerHTML = `<td colspan="5" class="text-center py-[50px] text-gray-500">
                    <i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">payments</i>
                    No hay métodos de pago registrados.</td>`;
                document.getElementById('tablaBody').appendChild(tr);
            }

            function mostrarErrores(errors) {
                const mapa = { nombre: 'errNombre', icono: 'errIcono', logo: 'errLogo' };
                Object.entries(mapa).forEach(([campo, id]) => {
                    if (errors[campo]) {
                        const el = document.getElementById(id);
                        el.textContent = errors[campo][0];
                        el.classList.remove('hidden');
                    }
                });
            }

            function limpiarErrores() {
                ['errNombre', 'errIcono', 'errLogo'].forEach(id => {
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
