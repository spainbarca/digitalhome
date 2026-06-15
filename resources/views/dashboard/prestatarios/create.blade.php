<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Nuevo Prestatario</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nuevo Prestatario</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.prestamos.prestatarios.index') }}" class="transition-all hover:text-primary-500">Prestatarios</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nuevo</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">Registrar Prestatario</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.prestamos.prestatarios.store') }}" enctype="multipart/form-data">
                        @csrf

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- ── SECCIÓN 1: Identidad ─────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">person</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">1. Identidad del Prestatario</h6>
                            </div>

                            {{-- Toggle es_miembro_hogar --}}
                            <div class="max-w-lg mb-[20px]">
                                <div class="flex items-center gap-[14px] p-[16px] rounded-md border border-gray-200 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c]">
                                    <label class="relative cursor-pointer flex-shrink-0">
                                        <input type="checkbox" name="es_miembro_hogar" id="esMiembroToggle" value="1"
                                            class="sr-only peer" {{ old('es_miembro_hogar') ? 'checked' : '' }}>
                                        <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                    </label>
                                    <div>
                                        <p class="text-sm font-medium text-black dark:text-white !mb-0">¿Es miembro del hogar?</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 !mb-0">Activa si el prestatario ya está registrado como miembro del hogar.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Bloque miembro (visible solo cuando el toggle está ON) --}}
                            <div id="bloqueHogarMiembro" class="{{ old('es_miembro_hogar') ? '' : 'hidden' }} max-w-lg mb-[20px]">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Seleccionar miembro
                                    <span class="text-danger-500">*</span>
                                </label>
                                <div class="relative" id="miembroWrapper">
                                    <div id="miembroTrigger"
                                        class="h-[55px] flex items-center rounded-md border {{ $errors->has('hogar_miembro_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <div id="miembroFotoWrap" class="w-[32px] h-[32px] rounded-full bg-gray-100 dark:bg-[#172036] flex items-center justify-center mr-[10px] flex-shrink-0 overflow-hidden">
                                            <i class="material-symbols-outlined !text-[18px] text-gray-400" id="miembroFotoIcon">person</i>
                                        </div>
                                        <span id="miembroLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Selecciona un miembro...</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="miembroChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="hogar_miembro_id" id="hogar_miembro_id" value="{{ old('hogar_miembro_id', '') }}">
                                    <div id="miembroDropdown"
                                        class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <input type="text" id="miembroBuscar" placeholder="Buscar miembro..."
                                                class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                        </div>
                                        <ul id="miembroOpciones" class="max-h-[240px] overflow-y-auto py-[4px]">
                                            @forelse($miembros as $m)
                                            @php
                                                $mNombre = $m->apodo ?? $m->user?->name ?? '—';
                                                $mFoto   = $m->user?->persona?->foto_url;
                                            @endphp
                                            <li class="miembro-opcion flex items-center gap-[10px] px-[12px] py-[8px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $m->id }}"
                                                data-nombre="{{ $mNombre }}"
                                                data-foto="{{ $mFoto ?? '' }}">
                                                <div class="w-[32px] h-[32px] rounded-full flex-shrink-0 overflow-hidden {{ $mFoto ? '' : 'bg-primary-100 flex items-center justify-center' }}">
                                                    @if($mFoto)
                                                        <img src="{{ $mFoto }}" class="w-full h-full object-cover" alt="{{ $mNombre }}">
                                                    @else
                                                        <i class="material-symbols-outlined !text-[16px] text-primary-500">person</i>
                                                    @endif
                                                </div>
                                                <span class="text-sm text-black dark:text-white">{{ $mNombre }}</span>
                                            </li>
                                            @empty
                                            <li class="px-[12px] py-[8px] text-sm text-gray-400">No hay miembros activos en el hogar.</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                                @error('hogar_miembro_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Nombre (siempre visible; auto-fill cuando se vincula miembro) --}}
                            <div class="max-w-lg">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Nombre <span class="text-danger-500">*</span>
                                    <span id="nombreHint" class="text-xs text-gray-400 font-normal ml-[4px]">Se auto-completa al seleccionar un miembro, pero puedes editarlo.</span>
                                </label>
                                <input type="text" name="nombre" id="nombreInput" value="{{ old('nombre') }}" maxlength="255"
                                    placeholder="Ej: Juan Pérez"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('nombre') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                @error('nombre')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- ── SECCIÓN 2: Moneda ────────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">payments</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">2. Moneda</h6>
                            </div>
                            <div class="max-w-lg">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Moneda <span class="text-danger-500">*</span>
                                </label>
                                <div class="relative" id="monedaWrapper">
                                    <div id="monedaTrigger"
                                        class="h-[55px] flex items-center rounded-md border {{ $errors->has('moneda_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400" id="monedaIconPreview">monetization_on</i>
                                        <span id="monedaLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Selecciona la moneda...</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="monedaChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="moneda_id" id="moneda_id" value="{{ old('moneda_id', '') }}">
                                    <div id="monedaDropdown"
                                        class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <input type="text" id="monedaBuscar" placeholder="Buscar moneda..."
                                                class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                        </div>
                                        <ul id="monedaOpciones" class="max-h-[240px] overflow-y-auto py-[4px]">
                                            @foreach($monedas as $mon)
                                            <li class="moneda-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $mon->id }}"
                                                data-nombre="{{ $mon->simbolo }} — {{ $mon->nombre }}">
                                                <span class="inline-flex items-center justify-center w-[28px] h-[28px] rounded-full bg-primary-50 dark:bg-[#172036] text-primary-500 text-xs font-bold flex-shrink-0">{{ $mon->simbolo }}</span>
                                                <div>
                                                    <span class="text-sm text-black dark:text-white">{{ $mon->nombre }}</span>
                                                    <span class="text-xs text-gray-400 ml-[4px]">{{ $mon->codigo }}</span>
                                                </div>
                                                @if($mon->moneda_local)
                                                <span class="ml-auto text-xs text-success-500 font-medium">Local</span>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @error('moneda_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- ── SECCIÓN 3: Datos de contacto y foto ──────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">contact_page</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">3. Contacto y Foto</h6>
                                <span class="text-xs text-gray-400">Opcional</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px] md:gap-[25px]">

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Teléfono</label>
                                    <input type="text" name="telefono" value="{{ old('telefono') }}" maxlength="30"
                                        placeholder="Ej: 987 654 321"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('telefono') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('telefono')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div id="fotoBlock">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Foto <span class="text-xs text-gray-400 font-normal">(JPG/PNG/WebP, máx. 2 MB)</span>
                                    </label>
                                    <p class="text-xs text-gray-400 mb-[8px]">
                                        <i class="material-symbols-outlined !text-[13px] align-middle">info</i>
                                        Si el prestatario es miembro del hogar, su foto se toma del perfil del miembro.
                                    </p>
                                    <div id="fotoPreviewWrap" class="hidden mb-[10px]">
                                        <img id="fotoPreview" src="" alt="Preview"
                                            class="w-full max-w-[160px] h-[160px] rounded-full object-cover border border-gray-200 dark:border-[#172036]">
                                    </div>
                                    <input type="file" name="foto" id="fotoInput" accept="image/*"
                                        onchange="previewFile(this,'fotoPreview','fotoPreviewWrap')"
                                        class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                    @error('foto')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                            </div>

                            <div class="mt-[20px]">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Notas</label>
                                <textarea name="notas" rows="3"
                                    placeholder="Observaciones adicionales sobre el prestatario..."
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500 resize-none">{{ old('notas') }}</textarea>
                                @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-[20px] flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.prestamos.prestatarios.index') }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Registrar Prestatario
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script>
            function previewFile(input, imgId, wrapId) {
                const wrap = document.getElementById(wrapId);
                const img  = document.getElementById(imgId);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => { img.src = e.target.result; wrap.classList.remove('hidden'); };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    wrap.classList.add('hidden');
                }
            }

            // ── Toggle es_miembro_hogar ────────────────────────────────────────
            (function () {
                const toggle   = document.getElementById('esMiembroToggle');
                const bloque   = document.getElementById('bloqueHogarMiembro');
                const nombreHint = document.getElementById('nombreHint');

                function aplicarEstado() {
                    if (toggle.checked) {
                        bloque.classList.remove('hidden');
                        nombreHint.classList.remove('hidden');
                    } else {
                        bloque.classList.add('hidden');
                        nombreHint.classList.add('hidden');
                        document.getElementById('hogar_miembro_id').value = '';
                    }
                }
                toggle.addEventListener('change', aplicarEstado);
                aplicarEstado();
            })();

            // ── Dropdown de miembros del hogar ────────────────────────────────
            (function () {
                const wrapper   = document.getElementById('miembroWrapper');
                const trigger   = document.getElementById('miembroTrigger');
                const dropdown  = document.getElementById('miembroDropdown');
                const chevron   = document.getElementById('miembroChevron');
                const buscar    = document.getElementById('miembroBuscar');
                const hidden    = document.getElementById('hogar_miembro_id');
                const label     = document.getElementById('miembroLabel');
                const fotoWrap  = document.getElementById('miembroFotoWrap');
                const fotoIcon  = document.getElementById('miembroFotoIcon');
                const opciones  = document.querySelectorAll('.miembro-opcion');
                const nombreInp = document.getElementById('nombreInput');

                if (!wrapper) return;

                function abrir() {
                    dropdown.classList.remove('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                    buscar.value = '';
                    opciones.forEach(li => li.style.display = '');
                    setTimeout(() => buscar.focus(), 50);
                }
                function cerrar() {
                    dropdown.classList.add('hidden');
                    chevron.style.transform = '';
                }
                function aplicar(id, nombre, foto) {
                    hidden.value = id;
                    label.textContent = nombre;
                    label.className   = 'text-black dark:text-white text-sm flex-1 truncate';
                    if (foto) {
                        fotoWrap.innerHTML = `<img src="${foto}" class="w-full h-full object-cover" alt="${nombre}">`;
                    } else {
                        fotoWrap.innerHTML = `<i class="material-symbols-outlined !text-[18px] text-primary-500">person</i>`;
                    }
                    if (nombreInp && !nombreInp.dataset.manualEdit) {
                        nombreInp.value = nombre;
                    }
                }

                trigger.addEventListener('click', () =>
                    dropdown.classList.contains('hidden') ? abrir() : cerrar()
                );
                buscar.addEventListener('input', () => {
                    const q = buscar.value.toLowerCase();
                    opciones.forEach(li => {
                        li.style.display = li.dataset.nombre.toLowerCase().includes(q) ? '' : 'none';
                    });
                });
                opciones.forEach(li => {
                    li.addEventListener('click', () => {
                        aplicar(li.dataset.id, li.dataset.nombre, li.dataset.foto);
                        cerrar();
                    });
                });
                document.addEventListener('click', e => { if (!wrapper.contains(e.target)) cerrar(); });

                // Marcar edición manual del nombre para no sobreescribir
                if (nombreInp) {
                    nombreInp.addEventListener('input', () => { nombreInp.dataset.manualEdit = '1'; });
                }

                const initId = hidden.value;
                if (initId) {
                    const m = [...opciones].find(li => li.dataset.id === initId);
                    if (m) aplicar(m.dataset.id, m.dataset.nombre, m.dataset.foto);
                }
            })();

            // ── Dropdown de moneda ────────────────────────────────────────────
            (function () {
                const wrapper  = document.getElementById('monedaWrapper');
                const trigger  = document.getElementById('monedaTrigger');
                const dropdown = document.getElementById('monedaDropdown');
                const chevron  = document.getElementById('monedaChevron');
                const buscar   = document.getElementById('monedaBuscar');
                const hidden   = document.getElementById('moneda_id');
                const label    = document.getElementById('monedaLabel');
                const opciones = document.querySelectorAll('.moneda-opcion');

                function abrir() {
                    dropdown.classList.remove('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                    buscar.value = '';
                    opciones.forEach(li => li.style.display = '');
                    setTimeout(() => buscar.focus(), 50);
                }
                function cerrar() {
                    dropdown.classList.add('hidden');
                    chevron.style.transform = '';
                }
                function aplicar(id, nombre) {
                    hidden.value = id;
                    label.textContent = nombre;
                    label.className   = 'text-black dark:text-white text-sm flex-1 truncate';
                }

                trigger.addEventListener('click', () =>
                    dropdown.classList.contains('hidden') ? abrir() : cerrar()
                );
                buscar.addEventListener('input', () => {
                    const q = buscar.value.toLowerCase();
                    opciones.forEach(li => {
                        li.style.display = li.dataset.nombre.toLowerCase().includes(q) ? '' : 'none';
                    });
                });
                opciones.forEach(li => {
                    li.addEventListener('click', () => {
                        aplicar(li.dataset.id, li.dataset.nombre);
                        cerrar();
                    });
                });
                document.addEventListener('click', e => { if (!wrapper.contains(e.target)) cerrar(); });

                const initId = hidden.value;
                if (initId) {
                    const m = [...opciones].find(li => li.dataset.id === initId);
                    if (m) aplicar(m.dataset.id, m.dataset.nombre);
                }
            })();
        </script>
    </body>
</html>
