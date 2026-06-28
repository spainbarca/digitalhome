<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Editar Proveedor — {{ $proveedoresNegocio->nombre }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        @php $prov = $proveedoresNegocio; @endphp

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">Editar — {{ $prov->nombre }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.proveedores-negocio.index') }}" class="transition-all hover:text-primary-500">Proveedores</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">Editar Proveedor</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.proveedores-negocio.update', $prov) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                                <span class="text-xs text-gray-400 font-normal ml-[4px]">Opcional</span>
                            </div>
                            <div class="grid grid-cols-12 gap-[20px] md:gap-[25px]">
                                <div class="col-span-12 sm:col-span-9" id="empresaWrapper">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Empresa</label>
                                <div class="relative">
                                    <div id="empresaTrigger"
                                        class="h-[55px] flex items-center rounded-md border {{ $errors->has('empresa_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <div id="empresaIconContainer" class="mr-[8px] flex-shrink-0 w-[24px] h-[24px] flex items-center justify-center">
                                            <i class="material-symbols-outlined !text-[20px] text-gray-400">business</i>
                                        </div>
                                        <span id="empresaLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Seleccionar empresa...</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="empresaChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="empresa_id" id="empresa_id" value="{{ old('empresa_id', $prov->empresa_id ?? '') }}">
                                    <div id="empresaDropdown"
                                        class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <input type="text" id="empresaBuscar" placeholder="Buscar por razón social o RUC..."
                                                class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                        </div>
                                        <ul id="empresaOpciones" class="max-h-[240px] overflow-y-auto py-[4px]">
                                            <li class="empresa-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="" data-nombre="Sin empresa vinculada" data-buscar="" data-logo="" data-sigla="" data-nombre-comercial="">
                                                <i class="material-symbols-outlined !text-[18px] text-gray-400">remove_circle_outline</i>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">Sin empresa vinculada</span>
                                            </li>
                                            @foreach($empresas as $emp)
                                            <li class="empresa-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $emp->id }}"
                                                data-nombre="{{ $emp->razon_social }}{{ $emp->ruc ? ' — ' . $emp->ruc : '' }}"
                                                data-buscar="{{ strtolower($emp->razon_social . ' ' . $emp->ruc) }}"
                                                data-logo="{{ $emp->logo_url ? asset('storage/' . $emp->logo_url) : '' }}"
                                                data-sigla="{{ $emp->sigla ?? '' }}"
                                                data-nombre-comercial="{{ $emp->nombre_comercial ?? $emp->razon_social }}">
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
                                    Sigla <span class="text-xs text-gray-400 font-normal ml-[4px]">Opcional</span>
                                </label>
                                <input type="text" name="sigla" id="sigla"
                                    value="{{ old('sigla', $prov->sigla ?? '') }}"
                                    maxlength="50"
                                    placeholder="Ej. ABC"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('sigla') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                @error('sigla')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 2: Datos del proveedor ───────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">local_shipping</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">2. Datos del Proveedor</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Nombre <span class="text-danger-500">*</span>
                                    </label>
                                    <input type="text" name="nombre" id="nombre"
                                        value="{{ old('nombre', $prov->nombre) }}"
                                        maxlength="255"
                                        placeholder="Nombre del proveedor"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('nombre') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('nombre')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div id="condicionWrapper" class="relative">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Condición de Pago</label>
                                    <div id="condicionTrigger"
                                        class="h-[55px] flex items-center rounded-md border {{ $errors->has('condicion_pago') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 mr-[8px] flex-shrink-0">payments</i>
                                        <span id="condicionLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1">Sin especificar</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="condicionChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="condicion_pago" id="condicion_pago" value="{{ old('condicion_pago', $prov->condicion_pago ?? '') }}">
                                    <div id="condicionDropdown"
                                        class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <ul class="py-[4px]">
                                            <li class="condicion-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="" data-nombre="Sin especificar">
                                                <i class="material-symbols-outlined !text-[18px] text-gray-400">remove_circle_outline</i>
                                                <span class="text-sm text-gray-500">Sin especificar</span>
                                            </li>
                                            <li class="condicion-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="contado" data-nombre="Contado">
                                                <i class="material-symbols-outlined !text-[18px] text-success-500">payments</i>
                                                <span class="text-sm text-black dark:text-white">Contado</span>
                                            </li>
                                            <li class="condicion-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="crédito" data-nombre="Crédito">
                                                <i class="material-symbols-outlined !text-[18px] text-warning-500">credit_card</i>
                                                <span class="text-sm text-black dark:text-white">Crédito</span>
                                            </li>
                                        </ul>
                                    </div>
                                    @error('condicion_pago')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Contacto</label>
                                    <input type="text" name="contacto"
                                        value="{{ old('contacto', $prov->contacto ?? '') }}"
                                        maxlength="255"
                                        placeholder="Persona de contacto"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('contacto') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('contacto')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Teléfono</label>
                                    <input type="text" name="telefono"
                                        value="{{ old('telefono', $prov->telefono ?? '') }}"
                                        maxlength="50"
                                        placeholder="Ej. +51 987 654 321"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('telefono') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('telefono')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 3: Logo y Banner ──────────────────────────────── --}}
                        <div class="mb-[25px] grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">
                            <div>
                                <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                    <i class="material-symbols-outlined !text-[20px] text-primary-500">image</i>
                                    <h6 class="!mb-0 font-semibold text-black dark:text-white">3. Logo</h6>
                                </div>
                                @if($prov->logo_path)
                                <div class="mb-[10px] flex items-center gap-[10px]">
                                    <img src="{{ asset('storage/' . $prov->logo_path) }}" alt="Logo actual"
                                        class="w-[48px] h-[48px] rounded-full object-cover border border-gray-200 dark:border-[#172036]">
                                    <span class="text-xs text-gray-500">Logo actual</span>
                                </div>
                                @endif
                                <div id="logoPreviewWrap" class="hidden mb-[10px]">
                                    <img id="logoPreview" src="" alt="Preview logo"
                                        class="w-[64px] h-[64px] rounded-full object-contain border border-gray-200 dark:border-[#172036]">
                                </div>
                                <input type="file" name="logo_path" accept="image/png,image/jpeg,image/webp"
                                    onchange="previewFile(this,'logoPreview','logoPreviewWrap')"
                                    class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                <p class="text-xs text-gray-400 mt-[5px]">Dejar vacío para mantener el actual.</p>
                                @error('logo_path')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                    <i class="material-symbols-outlined !text-[20px] text-primary-500">panorama</i>
                                    <h6 class="!mb-0 font-semibold text-black dark:text-white">4. Banner</h6>
                                </div>
                                @if($prov->banner_path)
                                <div class="mb-[10px]">
                                    <img src="{{ asset('storage/' . $prov->banner_path) }}" alt="Banner actual"
                                        class="w-full max-w-[280px] h-[70px] rounded-md object-cover">
                                    <span class="text-xs text-gray-500 block mt-[4px]">Banner actual</span>
                                </div>
                                @endif
                                <div id="bannerPreviewWrap" class="hidden mb-[10px]">
                                    <img id="bannerPreview" src="" alt="Preview banner"
                                        class="w-full max-w-[280px] h-[70px] rounded-md object-cover">
                                </div>
                                <input type="file" name="banner_path" accept="image/png,image/jpeg,image/webp"
                                    onchange="previewFile(this,'bannerPreview','bannerPreviewWrap')"
                                    class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                <p class="text-xs text-gray-400 mt-[5px]">Dejar vacío para mantener el actual.</p>
                                @error('banner_path')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- ── SECCIÓN 5: Estado ─────────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">toggle_on</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">5. Estado</h6>
                            </div>
                            <label class="flex items-center gap-[12px] cursor-pointer w-fit">
                                <input type="hidden" name="activo" value="0">
                                <input type="checkbox" name="activo" value="1" id="activo"
                                    {{ old('activo', $prov->activo ? '1' : '0') == '1' ? 'checked' : '' }}
                                    class="w-[18px] h-[18px] rounded border-gray-300 text-primary-500 focus:ring-primary-500">
                                <span class="text-black dark:text-white font-medium">Proveedor activo</span>
                            </label>
                        </div>

                        <div class="mt-[20px] flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.proveedores-negocio.show', $prov) }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Guardar Cambios
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
                } else { wrap.classList.add('hidden'); }
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
                function cerrar() { dropdown.classList.add('hidden'); chevron.style.transform = ''; }
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
                }

                trigger.addEventListener('click', () =>
                    dropdown.classList.contains('hidden') ? abrir() : cerrar()
                );
                buscar.addEventListener('input', () => {
                    const q = buscar.value.toLowerCase();
                    opciones.forEach(li => {
                        const val = (li.dataset.buscar || li.dataset.nombre || '').toLowerCase();
                        li.style.display = val.includes(q) ? '' : 'none';
                    });
                });
                opciones.forEach(li => {
                    li.addEventListener('click', () => { aplicar(li.dataset.id, li.dataset.nombre, li.dataset.logo); cerrar(); });
                });
                document.addEventListener('click', e => { if (!wrapper.contains(e.target)) cerrar(); });

                const initId = hidden.value;
                if (initId) {
                    const m = [...opciones].find(li => li.dataset.id === initId);
                    if (m) aplicar(m.dataset.id, m.dataset.nombre, m.dataset.logo);
                }
            })();

            // ── Custom select Condición de Pago ─────────────────────────
            (function () {
                const wrapper  = document.getElementById('condicionWrapper');
                const trigger  = document.getElementById('condicionTrigger');
                const dropdown = document.getElementById('condicionDropdown');
                const chevron  = document.getElementById('condicionChevron');
                const hidden   = document.getElementById('condicion_pago');
                const label    = document.getElementById('condicionLabel');
                const opciones = document.querySelectorAll('.condicion-opcion');

                function abrir() { dropdown.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; }
                function cerrar() { dropdown.classList.add('hidden'); chevron.style.transform = ''; }
                function aplicar(id, nombre) {
                    hidden.value = id;
                    if (id) {
                        label.textContent = nombre;
                        label.className = 'text-black dark:text-white text-sm flex-1';
                    } else {
                        label.textContent = 'Sin especificar';
                        label.className = 'text-gray-500 dark:text-gray-400 text-sm flex-1';
                    }
                }

                trigger.addEventListener('click', () =>
                    dropdown.classList.contains('hidden') ? abrir() : cerrar()
                );
                opciones.forEach(li => {
                    li.addEventListener('click', () => { aplicar(li.dataset.id, li.dataset.nombre); cerrar(); });
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
