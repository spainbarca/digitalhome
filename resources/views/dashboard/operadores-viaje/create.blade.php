<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Nuevo Operador de Viaje</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nuevo Operador de Viaje</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.operadores-viaje.index') }}" class="transition-all hover:text-primary-500">Operadores de Viaje</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nuevo</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">Registrar Operador de Viaje</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.operadores-viaje.store') }}" enctype="multipart/form-data">
                        @csrf

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- ── SECCIÓN 1: Empresa vinculada ──────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">business</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">1. Empresa Vinculada</h6>
                                <span class="text-xs text-danger-500">* Requerida</span>
                            </div>
                            <div class="grid grid-cols-12 gap-[20px] md:gap-[25px]">
                                <div class="col-span-12 sm:col-span-9" id="empresaWrapper">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Empresa <span class="text-danger-500">*</span>
                                </label>
                                <div class="relative">
                                    <div id="empresaTrigger"
                                        class="h-[55px] flex items-center rounded-md border {{ $errors->has('empresa_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <div id="empresaIconContainer" class="mr-[8px] flex-shrink-0 w-[24px] h-[24px] flex items-center justify-center">
                                            <i class="material-symbols-outlined !text-[20px] text-gray-400">business</i>
                                        </div>
                                        <span id="empresaLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Seleccionar empresa...</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="empresaChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="empresa_id" id="empresa_id" value="{{ old('empresa_id', '') }}">
                                    <div id="empresaDropdown"
                                        class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <input type="text" id="empresaBuscar" placeholder="Buscar por razón social o RUC..."
                                                class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                        </div>
                                        <ul id="empresaOpciones" class="max-h-[240px] overflow-y-auto py-[4px]">
                                            @foreach($empresas as $emp)
                                            <li class="empresa-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $emp->id }}"
                                                data-nombre="{{ $emp->razon_social }}{{ $emp->ruc ? ' — ' . $emp->ruc : '' }}"
                                                data-buscar="{{ strtolower($emp->razon_social . ' ' . $emp->ruc) }}"
                                                data-logo="{{ $emp->logo_url ? asset('storage/' . $emp->logo_url) : '' }}"
                                                data-sigla="{{ $emp->sigla ?? '' }}">
                                                @if($emp->logo_url)
                                                    <img src="{{ asset('storage/' . $emp->logo_url) }}" class="w-[24px] h-[24px] rounded-[4px] object-cover flex-shrink-0" alt="">
                                                @else
                                                    <i class="material-symbols-outlined !text-[18px] text-primary-500 flex-shrink-0">business</i>
                                                @endif
                                                <div class="min-w-0">
                                                    <span class="text-sm text-black dark:text-white block truncate">{{ $emp->razon_social }}</span>
                                                    @if($emp->ruc)<span class="text-xs text-gray-400 font-mono">RUC: {{ $emp->ruc }}</span>@endif
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @error('empresa_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div class="col-span-12 sm:col-span-3">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Sigla
                                    <span class="text-xs text-gray-400 font-normal ml-[4px]">Opcional</span>
                                </label>
                                <input type="text" name="sigla" id="sigla"
                                    value="{{ old('sigla', '') }}"
                                    maxlength="50"
                                    placeholder="Ej. LAN"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('sigla') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                @error('sigla')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 2: Tipo de Operador ───────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">flight</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">2. Tipo de Operador</h6>
                            </div>
                            <div id="tipoWrapper" class="relative">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Tipo de Operador de Viaje</label>
                                <div id="tipoTrigger"
                                    class="h-[55px] flex items-center rounded-md border {{ $errors->has('tipo_operador_viaje_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                    <div id="tipoIconContainer" class="mr-[8px] flex-shrink-0 w-[24px] h-[24px] flex items-center justify-center">
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400">flight</i>
                                    </div>
                                    <span id="tipoLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Sin tipo especificado</span>
                                    <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="tipoChevron">expand_more</i>
                                </div>
                                <input type="hidden" name="tipo_operador_viaje_id" id="tipo_operador_viaje_id" value="{{ old('tipo_operador_viaje_id', '') }}">
                                <div id="tipoDropdown"
                                    class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                    <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                        <input type="text" id="tipoBuscar" placeholder="Buscar tipo..."
                                            class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                    </div>
                                    <ul id="tipoOpciones" class="max-h-[220px] overflow-y-auto py-[4px]">
                                        <li class="tipo-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                            data-id="" data-nombre="Sin tipo especificado" data-icono="flight">
                                            <i class="material-symbols-outlined !text-[18px] text-gray-400">remove_circle_outline</i>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Sin tipo especificado</span>
                                        </li>
                                        @foreach($tipos as $t)
                                        <li class="tipo-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                            data-id="{{ $t->id }}"
                                            data-nombre="{{ $t->nombre }}"
                                            data-icono="{{ $t->icono ?? 'flight' }}">
                                            <i class="material-symbols-outlined !text-[18px] text-primary-500 flex-shrink-0">{{ $t->icono ?? 'flight' }}</i>
                                            <span class="text-sm text-black dark:text-white">{{ $t->nombre }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @error('tipo_operador_viaje_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- ── SECCIÓN 3: Identificadores ───────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">badge</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">3. Identificadores</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Código IATA</label>
                                    <input type="text" name="codigo_iata" value="{{ old('codigo_iata') }}" maxlength="10"
                                        placeholder="Ej. LA, AA, IB"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('codigo_iata') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 font-mono focus:border-primary-500">
                                    @error('codigo_iata')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIONES 4 & 5: Logo y Banner ──────────────────────── --}}
                        <div class="mb-[25px] grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                            <!-- Logo -->
                            <div>
                                <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                    <i class="material-symbols-outlined !text-[20px] text-primary-500">image</i>
                                    <h6 class="!mb-0 font-semibold text-black dark:text-white">4. Logo</h6>
                                </div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Logo del operador <span class="text-xs text-gray-400 font-normal">(PNG/JPG/WebP, máx. 2 MB)</span>
                                </label>
                                <p class="text-xs text-gray-400 mb-[8px]">Si se deja vacío, se usa el logo de la empresa.</p>
                                <div id="logoPreviewWrap" class="hidden mb-[10px]">
                                    <img id="logoPreview" src="" alt="Preview logo"
                                        class="w-[64px] h-[64px] rounded-full object-contain border border-gray-200 dark:border-[#172036]">
                                </div>
                                <input type="file" name="logo_path" id="logoInput" accept="image/png,image/jpeg,image/webp"
                                    onchange="onLogoFileChange(this)"
                                    class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                @error('logo_path')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Banner -->
                            <div>
                                <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                    <i class="material-symbols-outlined !text-[20px] text-primary-500">panorama</i>
                                    <h6 class="!mb-0 font-semibold text-black dark:text-white">5. Banner</h6>
                                </div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Imagen de banner <span class="text-xs text-gray-400 font-normal">(PNG/JPG/WebP, máx. 2 MB)</span>
                                </label>
                                <p class="text-xs text-gray-400 mb-[8px]">Imagen horizontal para el encabezado del detalle. Recomendado 1200×300 px.</p>
                                <div id="bannerPreviewWrap" class="hidden mb-[10px]">
                                    <img id="bannerPreview" src="" alt="Preview banner"
                                        class="w-full max-w-[280px] h-[70px] rounded-md object-cover">
                                </div>
                                <input type="file" name="banner_path" id="bannerInput" accept="image/png,image/jpeg,image/webp"
                                    onchange="previewFile(this,'bannerPreview','bannerPreviewWrap')"
                                    class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                @error('banner_path')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                        </div>

                        <!-- Botones -->
                        <div class="mt-[20px] flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.operadores-viaje.index') }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Registrar Operador
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
            let logoFileSelected = false;
            let currentEmpresaLogo = '';

            function onLogoFileChange(input) {
                const wrap = document.getElementById('logoPreviewWrap');
                const img  = document.getElementById('logoPreview');
                if (input.files && input.files[0]) {
                    logoFileSelected = true;
                    const reader = new FileReader();
                    reader.onload = e => { img.src = e.target.result; wrap.classList.remove('hidden'); };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    logoFileSelected = false;
                    if (currentEmpresaLogo) {
                        img.src = currentEmpresaLogo;
                        wrap.classList.remove('hidden');
                    } else {
                        wrap.classList.add('hidden');
                    }
                }
            }

            function setLogoPreviewFromEmpresa(empresaLogoUrl) {
                currentEmpresaLogo = empresaLogoUrl || '';
                if (logoFileSelected) return;
                const wrap = document.getElementById('logoPreviewWrap');
                const img  = document.getElementById('logoPreview');
                if (currentEmpresaLogo) {
                    img.src = currentEmpresaLogo;
                    wrap.classList.remove('hidden');
                } else {
                    wrap.classList.add('hidden');
                }
            }

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

            // ── Custom select Empresa ────────────────────────────────────
            (function () {
                const wrapper  = document.getElementById('empresaWrapper');
                const trigger  = document.getElementById('empresaTrigger');
                const dropdown = document.getElementById('empresaDropdown');
                const chevron  = document.getElementById('empresaChevron');
                const buscar   = document.getElementById('empresaBuscar');
                const hidden   = document.getElementById('empresa_id');
                const label    = document.getElementById('empresaLabel');
                const opciones = document.querySelectorAll('.empresa-opcion');

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
                function aplicar(id, nombre, logo) {
                    hidden.value = id;
                    const iconContainer = document.getElementById('empresaIconContainer');
                    if (id) {
                        label.textContent = nombre;
                        label.className = 'text-black dark:text-white text-sm flex-1 truncate';
                        iconContainer.innerHTML = logo
                            ? `<img src="${logo}" style="width:24px;height:24px;border-radius:4px;object-fit:cover;">`
                            : '<i class="material-symbols-outlined !text-[20px] text-primary-500">business</i>';
                    } else {
                        label.textContent = 'Seleccionar empresa...';
                        label.className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                        iconContainer.innerHTML = '<i class="material-symbols-outlined !text-[20px] text-gray-400">business</i>';
                    }
                    setLogoPreviewFromEmpresa(id ? logo : '');
                }

                trigger.addEventListener('click', () =>
                    dropdown.classList.contains('hidden') ? abrir() : cerrar()
                );
                buscar.addEventListener('input', () => {
                    const q = buscar.value.toLowerCase();
                    opciones.forEach(li => {
                        const buscarData = (li.dataset.buscar || li.dataset.nombre || '').toLowerCase();
                        li.style.display = buscarData.includes(q) ? '' : 'none';
                    });
                });
                opciones.forEach(li => {
                    li.addEventListener('click', () => {
                        aplicar(li.dataset.id, li.dataset.nombre, li.dataset.logo);
                        const siglaInput = document.getElementById('sigla');
                        if (siglaInput && !siglaInput.value) siglaInput.value = li.dataset.id ? (li.dataset.sigla || '') : '';
                        cerrar();
                    });
                });
                document.addEventListener('click', e => { if (!wrapper.contains(e.target)) cerrar(); });

                const initId = hidden.value;
                if (initId) {
                    const m = [...opciones].find(li => li.dataset.id === initId);
                    if (m) aplicar(m.dataset.id, m.dataset.nombre, m.dataset.logo);
                }
            })();

            // ── Custom select Tipo Operador ──────────────────────────────
            (function () {
                const wrapper  = document.getElementById('tipoWrapper');
                const trigger  = document.getElementById('tipoTrigger');
                const dropdown = document.getElementById('tipoDropdown');
                const chevron  = document.getElementById('tipoChevron');
                const buscar   = document.getElementById('tipoBuscar');
                const hidden   = document.getElementById('tipo_operador_viaje_id');
                const label    = document.getElementById('tipoLabel');
                const opciones = document.querySelectorAll('.tipo-opcion');

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
                function aplicar(id, nombre, icono) {
                    hidden.value = id;
                    const iconContainer = document.getElementById('tipoIconContainer');
                    if (id) {
                        label.textContent = nombre;
                        label.className = 'text-black dark:text-white text-sm flex-1 truncate';
                        iconContainer.innerHTML = `<i class="material-symbols-outlined !text-[20px] text-primary-500">${icono || 'flight'}</i>`;
                    } else {
                        label.textContent = 'Sin tipo especificado';
                        label.className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                        iconContainer.innerHTML = '<i class="material-symbols-outlined !text-[20px] text-gray-400">flight</i>';
                    }
                }

                trigger.addEventListener('click', () =>
                    dropdown.classList.contains('hidden') ? abrir() : cerrar()
                );
                buscar.addEventListener('input', () => {
                    const q = buscar.value.toLowerCase();
                    opciones.forEach(li => {
                        li.style.display = (li.dataset.nombre || '').toLowerCase().includes(q) ? '' : 'none';
                    });
                });
                opciones.forEach(li => {
                    li.addEventListener('click', () => {
                        aplicar(li.dataset.id, li.dataset.nombre, li.dataset.icono);
                        cerrar();
                    });
                });
                document.addEventListener('click', e => { if (!wrapper.contains(e.target)) cerrar(); });

                const initId = hidden.value;
                if (initId) {
                    const m = [...opciones].find(li => li.dataset.id === initId);
                    if (m) aplicar(m.dataset.id, m.dataset.nombre, m.dataset.icono);
                }
            })();
        </script>
    </body>
</html>
