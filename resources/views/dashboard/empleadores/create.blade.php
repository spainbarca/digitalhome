<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Nuevo Empleador</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nuevo Empleador</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.empleadores.index') }}" class="transition-all hover:text-primary-500">Empleadores</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nuevo</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">Registrar Empleador</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.empleadores.store') }}" enctype="multipart/form-data">
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
                                <span class="text-xs text-gray-400">Opcional — para empleadores formales con RUC</span>
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
                                        <span id="empresaLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Sin empresa vinculada</span>
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
                                            <li class="empresa-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="" data-nombre="Sin empresa vinculada">
                                                <i class="material-symbols-outlined !text-[18px] text-gray-400">remove_circle_outline</i>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">Sin empresa vinculada</span>
                                            </li>
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
                                    placeholder="Ej. BCP"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('sigla') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                @error('sigla')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 2: Datos generales ────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">corporate_fare</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">2. Datos Generales</h6>
                            </div>
                            <div class="grid grid-cols-1 gap-[20px] md:gap-[25px]">
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Nombre <span class="text-danger-500">*</span>
                                    </label>
                                    <input type="text" name="nombre" value="{{ old('nombre') }}" maxlength="255"
                                        placeholder="Nombre del empleador"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('nombre') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('nombre')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Descripción</label>
                                    <textarea name="descripcion" rows="3"
                                        placeholder="Descripción opcional del empleador..."
                                        class="rounded-md text-black dark:text-white border {{ $errors->has('descripcion') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[12px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500 resize-none">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 3: Ubicación ────────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">location_on</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">3. Ubicación</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">
                                <div class="sm:col-span-2">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Dirección</label>
                                    <textarea name="direccion" rows="2"
                                        placeholder="Dirección del empleador"
                                        class="rounded-md text-black dark:text-white border {{ $errors->has('direccion') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[12px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500 resize-none">{{ old('direccion') }}</textarea>
                                    @error('direccion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Distrito
                                        <span class="text-xs text-gray-400 font-normal ml-[4px]">Buscar por nombre</span>
                                    </label>
                                    <div class="relative" id="distritoWrapper">
                                        <div id="distritoTrigger"
                                            class="h-[55px] flex items-center rounded-md border {{ $errors->has('distrito_inei') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                            <i class="material-symbols-outlined !text-[18px] mr-[8px] text-gray-400 flex-shrink-0">map</i>
                                            <span id="distritoLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Seleccionar distrito...</span>
                                            <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="distritoChevron">expand_more</i>
                                        </div>
                                        <input type="hidden" name="distrito_inei" id="distrito_inei" value="{{ old('distrito_inei', '') }}">
                                        <div id="distritoDropdown"
                                            class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                            <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                                <input type="text" id="distritoBuscar" placeholder="Buscar distrito, provincia o departamento..."
                                                    class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                            </div>
                                            <ul id="distritoOpciones" class="max-h-[220px] overflow-y-auto py-[4px]">
                                                <li class="px-[12px] py-[8px] text-xs text-gray-400">Escribe para buscar...</li>
                                            </ul>
                                        </div>
                                    </div>
                                    @error('distrito_inei')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 4: Contacto ──────────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">contact_phone</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">4. Contacto</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px] md:gap-[25px]">
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Teléfono</label>
                                    <input type="text" name="telefono" value="{{ old('telefono') }}" maxlength="30"
                                        placeholder="Ej: 01-4567890"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('telefono') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('telefono')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" maxlength="150"
                                        placeholder="contacto@empleador.com"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('email') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('email')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Sitio Web</label>
                                    <input type="url" name="sitio_web" value="{{ old('sitio_web') }}" maxlength="255"
                                        placeholder="https://www.empleador.com"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('sitio_web') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('sitio_web')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 5: Imágenes ──────────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">image</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">5. Imágenes</h6>
                                <span class="text-xs text-gray-400">PNG/JPG/WebP, máx. 2 MB cada una</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px] md:gap-[25px]">

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Logo</label>
                                    <div id="logoPreviewWrap" class="hidden mb-[10px]">
                                        <img id="logoPreview" src="" alt="Preview" class="w-[80px] h-[80px] rounded-md object-cover">
                                    </div>
                                    <input type="file" name="logo_path" id="logoInput" accept="image/*"
                                        onchange="previewImg(this,'logoPreview','logoPreviewWrap')"
                                        class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                    @error('logo_path')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Imagen</label>
                                    <div id="imagenPreviewWrap" class="hidden mb-[10px]">
                                        <img id="imagenPreview" src="" alt="Preview" class="w-[80px] h-[80px] rounded-md object-cover">
                                    </div>
                                    <input type="file" name="imagen_path" id="imagenInput" accept="image/*"
                                        onchange="previewImg(this,'imagenPreview','imagenPreviewWrap')"
                                        class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                    @error('imagen_path')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Banner</label>
                                    <div id="bannerPreviewWrap" class="hidden mb-[10px]">
                                        <img id="bannerPreview" src="" alt="Preview" class="w-full h-[80px] rounded-md object-cover">
                                    </div>
                                    <input type="file" name="banner_path" id="bannerInput" accept="image/*"
                                        onchange="previewImg(this,'bannerPreview','bannerPreviewWrap')"
                                        class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                    @error('banner_path')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── SECCIÓN 6: Estado ──────────────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">toggle_on</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">6. Estado</h6>
                            </div>
                            <div class="flex items-center gap-[12px]">
                                <span class="text-black dark:text-white font-medium">Activo</span>
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="activo" value="1" class="sr-only peer" {{ old('activo', '1') ? 'checked' : '' }}>
                                    <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-[20px] flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.empleadores.index') }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Registrar Empleador
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
            const ubigeoUrl = '{{ route('dashboard.ubigeo.distritos') }}';

            // ── Preview imágenes ─────────────────────────────────────────
            function previewImg(input, imgId, wrapId) {
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
                        label.textContent = 'Sin empresa vinculada';
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
                        const buscarData = (li.dataset.buscar || li.dataset.nombre || '').toLowerCase();
                        li.style.display = buscarData.includes(q) ? '' : 'none';
                    });
                });
                opciones.forEach(li => {
                    li.addEventListener('click', () => {
                        aplicar(li.dataset.id, li.dataset.nombre, li.dataset.logo);
                        const siglaInput = document.getElementById('sigla');
                        if (siglaInput) siglaInput.value = li.dataset.id ? (li.dataset.sigla || '') : '';
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

            // ── Autocomplete Distrito ────────────────────────────────────
            (function () {
                const wrapper  = document.getElementById('distritoWrapper');
                const trigger  = document.getElementById('distritoTrigger');
                const dropdown = document.getElementById('distritoDropdown');
                const chevron  = document.getElementById('distritoChevron');
                const buscar   = document.getElementById('distritoBuscar');
                const hidden   = document.getElementById('distrito_inei');
                const label    = document.getElementById('distritoLabel');
                const lista    = document.getElementById('distritoOpciones');
                let   timer    = null;

                function abrir() {
                    dropdown.classList.remove('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                    setTimeout(() => buscar.focus(), 50);
                }
                function cerrar() {
                    dropdown.classList.add('hidden');
                    chevron.style.transform = '';
                }
                function aplicar(inei, texto) {
                    hidden.value = inei;
                    label.textContent = texto;
                    label.className = 'text-black dark:text-white text-sm flex-1 truncate';
                    cerrar();
                }
                function limpiar() {
                    hidden.value = '';
                    label.textContent = 'Seleccionar distrito...';
                    label.className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                }

                trigger.addEventListener('click', () =>
                    dropdown.classList.contains('hidden') ? abrir() : cerrar()
                );
                document.addEventListener('click', e => { if (!wrapper.contains(e.target)) cerrar(); });

                buscar.addEventListener('input', () => {
                    clearTimeout(timer);
                    const q = buscar.value.trim();
                    if (q.length < 2) {
                        lista.innerHTML = '<li class="px-[12px] py-[8px] text-xs text-gray-400">Escribe al menos 2 caracteres...</li>';
                        return;
                    }
                    lista.innerHTML = '<li class="px-[12px] py-[8px] text-xs text-gray-400">Buscando...</li>';
                    timer = setTimeout(async () => {
                        try {
                            const resp = await fetch(`${ubigeoUrl}?q=${encodeURIComponent(q)}`, {
                                headers: { 'Accept': 'application/json' }
                            });
                            const { results } = await resp.json();
                            if (!results || results.length === 0) {
                                lista.innerHTML = '<li class="px-[12px] py-[8px] text-xs text-gray-400">Sin resultados.</li>';
                                return;
                            }
                            lista.innerHTML = '';
                            results.forEach(d => {
                                const li = document.createElement('li');
                                li.className = 'flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors';
                                li.innerHTML = `<i class="material-symbols-outlined !text-[16px] text-primary-500 flex-shrink-0">location_on</i><span class="text-sm text-black dark:text-white">${d.text}</span>`;
                                li.addEventListener('click', () => aplicar(d.id, d.text));
                                lista.appendChild(li);
                            });
                        } catch {
                            lista.innerHTML = '<li class="px-[12px] py-[8px] text-xs text-danger-500">Error al buscar.</li>';
                        }
                    }, 350);
                });

                // Pre-cargar si hay valor (old())
                const initInei = hidden.value;
                if (initInei) {
                    fetch(`${ubigeoUrl}?q=${encodeURIComponent(initInei)}`, { headers: { 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(({ results }) => {
                            const match = (results || []).find(d => d.id === initInei);
                            if (match) {
                                label.textContent = match.text;
                                label.className = 'text-black dark:text-white text-sm flex-1 truncate';
                            }
                        }).catch(() => {});
                }
            })();
        </script>
    </body>
</html>
