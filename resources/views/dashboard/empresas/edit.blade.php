<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Editar — {{ $empresa->razon_social }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Editar Empresa</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.empresas.index') }}" class="transition-all hover:text-primary-500">Empresas</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.empresas.show', $empresa) }}" class="transition-all hover:text-primary-500 truncate">{{ $empresa->razon_social }}</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[25px] flex items-center justify-between -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">Editar: {{ $empresa->razon_social }}</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.empresas.update', $empresa) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- ── SECCIÓN 1: Re-consulta SUNAT ────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">manage_search</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">1. Re-consultar SUNAT</h6>
                                @if($empresa->sunat_sincronizado_en)
                                    <span class="text-xs text-gray-400 ml-auto">Última sync: {{ $empresa->sunat_sincronizado_en->format('d/m/Y H:i') }}</span>
                                @endif
                            </div>
                            <div class="flex gap-[10px] items-start">
                                <div class="flex-1">
                                    <input type="text" id="rucInput" name="ruc" value="{{ old('ruc', $empresa->ruc) }}"
                                        maxlength="11" pattern="\d{11}"
                                        placeholder="RUC (11 dígitos)"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('ruc') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('ruc')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <button type="button" id="btnBuscarRuc"
                                    class="h-[55px] px-[18px] bg-primary-500 text-white rounded-md hover:bg-primary-400 transition-all font-medium text-sm whitespace-nowrap flex items-center gap-[8px] disabled:opacity-60 disabled:cursor-not-allowed">
                                    <i class="material-symbols-outlined !text-[18px]" id="iconBuscar">search</i>
                                    <span id="lblBuscar">Actualizar desde SUNAT</span>
                                </button>
                            </div>
                            <div id="sunatStatus" class="mt-[10px] hidden"></div>
                            <input type="hidden" name="sunat_sincronizado_en" id="sunat_sincronizado_en"
                                value="{{ $empresa->sunat_sincronizado_en?->toISOString() }}">
                        </div>

                        {{-- ── SECCIÓN 2: Datos generales ────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">business</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">2. Datos Generales</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                                <!-- Sector — custom select con íconos Remixicon -->
                                <div class="sm:col-span-2">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Sector</label>
                                    <div class="relative" id="sectorWrapper">
                                        <div id="sectorTrigger"
                                            class="h-[55px] flex items-center rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                            <i class="ri-building-line text-[18px] mr-[8px] text-gray-400" id="sectorIconPreview"></i>
                                            <span id="sectorLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Seleccionar sector...</span>
                                            <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="sectorChevron">expand_more</i>
                                        </div>
                                        <input type="hidden" name="sector_id" id="sector_id"
                                            value="{{ old('sector_id', $empresa->sector_id ?? '') }}">
                                        <div id="sectorDropdown"
                                            class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                            <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                                <input type="text" id="sectorBuscar" placeholder="Buscar sector..."
                                                    class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                            </div>
                                            <ul id="sectorOpciones" class="max-h-[240px] overflow-y-auto py-[4px]">
                                                <li class="sector-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                    data-id="" data-nombre="Sin sector" data-icono="ri-building-line">
                                                    <i class="ri-building-line text-[18px] text-gray-400"></i>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Sin sector</span>
                                                </li>
                                                @foreach($sectores as $sec)
                                                <li class="sector-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                    data-id="{{ $sec->id }}"
                                                    data-nombre="{{ $sec->nombre }}"
                                                    data-icono="{{ $sec->icono ?? 'ri-building-line' }}">
                                                    <i class="{{ $sec->icono ?? 'ri-building-line' }} text-[18px] text-primary-500"></i>
                                                    <span class="text-sm text-black dark:text-white">{{ $sec->nombre }}</span>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    @error('sector_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Razón Social <span class="text-danger-500">*</span>
                                    </label>
                                    <input type="text" id="razon_social" name="razon_social" value="{{ old('razon_social', $empresa->razon_social) }}" maxlength="200"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('razon_social') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('razon_social')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Nombre Comercial</label>
                                    <input type="text" id="nombre_comercial" name="nombre_comercial" value="{{ old('nombre_comercial', $empresa->nombre_comercial) }}" maxlength="200"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Tipo de Contribuyente</label>
                                    <select name="tipo_contribuyente_id"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('tipo_contribuyente_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                        <option value="">Seleccionar tipo...</option>
                                        @foreach($tiposContribuyente as $tipo)
                                            <option value="{{ $tipo->id }}" {{ old('tipo_contribuyente_id', $empresa->tipo_contribuyente_id ?? '') == $tipo->id ? 'selected' : '' }}>
                                                {{ $tipo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tipo_contribuyente_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Estado SUNAT</label>
                                    <input type="text" id="estado_sunat" name="estado_sunat" value="{{ old('estado_sunat', $empresa->estado_sunat) }}" maxlength="50"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Condición SUNAT</label>
                                    <input type="text" id="condicion_sunat" name="condicion_sunat" value="{{ old('condicion_sunat', $empresa->condicion_sunat) }}" maxlength="50"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Inicio de Actividades</label>
                                    <input type="date" id="fecha_inicio_actividades" name="fecha_inicio_actividades"
                                        value="{{ old('fecha_inicio_actividades', $empresa->fecha_inicio_actividades?->format('Y-m-d')) }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_inicio_actividades') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                    @error('fecha_inicio_actividades')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── SECCIÓN 3: Ubicación ───────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">location_on</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">3. Ubicación</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                                <div class="sm:col-span-2">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Dirección Fiscal</label>
                                    <textarea id="direccion_fiscal" name="direccion_fiscal" rows="2"
                                        class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] py-[12px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500 resize-none">{{ old('direccion_fiscal', $empresa->direccion_fiscal) }}</textarea>
                                    <p id="infoUbigeo" class="hidden text-xs text-primary-500 mt-[5px] flex items-center gap-[4px]">
                                        <i class="material-symbols-outlined !text-[14px]">location_on</i>
                                        <span id="infoUbigeoText"></span>
                                    </p>
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Código INEI del Distrito
                                        <span class="text-xs text-gray-400 font-normal ml-[4px]">(6 dígitos)</span>
                                    </label>
                                    <input type="text" id="distrito_inei" name="distrito_inei"
                                        value="{{ old('distrito_inei', $empresa->distrito_inei) }}" maxlength="6"
                                        placeholder="Ej: 150101"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('distrito_inei') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @if($empresa->distrito)
                                        <p class="text-xs text-gray-400 mt-[4px]">
                                            Actual: {{ $empresa->distrito->distrito }}, {{ $empresa->distrito->provincia }}, {{ $empresa->distrito->departamento }}
                                        </p>
                                    @endif
                                    @error('distrito_inei')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── SECCIÓN 4: Contacto ────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">contact_phone</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">4. Contacto</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px] md:gap-[25px]">

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Teléfono</label>
                                    <input type="text" name="telefono" value="{{ old('telefono', $empresa->telefono) }}" maxlength="20"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('telefono') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('telefono')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $empresa->email) }}" maxlength="150"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('email') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('email')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Sitio Web</label>
                                    <input type="url" name="sitio_web" value="{{ old('sitio_web', $empresa->sitio_web) }}" maxlength="200"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('sitio_web') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('sitio_web')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── SECCIÓN 5: Logo y estado ───────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">image</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">5. Logo y Estado</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Logo <span class="text-xs text-gray-400 font-normal">(PNG/JPG, máx. 2 MB)</span>
                                    </label>
                                    @if($empresa->logo_url)
                                    <div class="mb-[10px] flex items-center gap-[10px]">
                                        <img src="{{ Storage::url($empresa->logo_url) }}" id="logoPreview"
                                            class="w-[80px] h-[80px] rounded-md object-cover" alt="Logo actual">
                                        <span class="text-xs text-gray-400">Logo actual. Sube uno nuevo para reemplazarlo.</span>
                                    </div>
                                    @else
                                    <div id="logoPreviewWrap" class="hidden mb-[10px]">
                                        <img id="logoPreview" src="" alt="Preview" class="w-[80px] h-[80px] rounded-md object-cover">
                                    </div>
                                    @endif
                                    <input type="file" name="logo" id="logoInput" accept="image/*"
                                        onchange="previewLogo(this)"
                                        class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                    @error('logo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div class="flex items-center gap-[12px] pt-[10px] sm:pt-[30px]">
                                    <span class="text-black dark:text-white font-medium">Activo</span>
                                    <label class="relative cursor-pointer">
                                        <input type="checkbox" name="activo" value="1" id="activo"
                                            class="sr-only peer" {{ old('activo', $empresa->activo) ? 'checked' : '' }}>
                                        <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-[20px] ltr:text-right rtl:text-left flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.empresas.show', $empresa) }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Actualizar Empresa
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
            // ── Custom select de Sector ──────────────────────────────
            (function () {
                const wrapper  = document.getElementById('sectorWrapper');
                const trigger  = document.getElementById('sectorTrigger');
                const dropdown = document.getElementById('sectorDropdown');
                const chevron  = document.getElementById('sectorChevron');
                const buscar   = document.getElementById('sectorBuscar');
                const hidden   = document.getElementById('sector_id');
                const label    = document.getElementById('sectorLabel');
                const iconPrev = document.getElementById('sectorIconPreview');
                const opciones = document.querySelectorAll('.sector-opcion');

                function abrirDropdown() {
                    dropdown.classList.remove('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                    buscar.value = '';
                    opciones.forEach(li => li.style.display = '');
                    setTimeout(() => buscar.focus(), 50);
                }

                function cerrarDropdown() {
                    dropdown.classList.add('hidden');
                    chevron.style.transform = '';
                }

                function aplicarSeleccion(id, nombre, icono) {
                    hidden.value = id;
                    const ic = icono || 'ri-building-line';
                    if (id) {
                        label.textContent  = nombre;
                        label.className    = 'text-black dark:text-white text-sm flex-1 truncate';
                        iconPrev.className = `${ic} text-[18px] mr-[8px] text-primary-500`;
                    } else {
                        label.textContent  = 'Seleccionar sector...';
                        label.className    = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                        iconPrev.className = 'ri-building-line text-[18px] mr-[8px] text-gray-400';
                    }
                }

                trigger.addEventListener('click', () => {
                    dropdown.classList.contains('hidden') ? abrirDropdown() : cerrarDropdown();
                });

                buscar.addEventListener('input', () => {
                    const q = buscar.value.toLowerCase();
                    opciones.forEach(li => {
                        li.style.display = li.dataset.nombre.toLowerCase().includes(q) ? '' : 'none';
                    });
                });

                opciones.forEach(li => {
                    li.addEventListener('click', () => {
                        aplicarSeleccion(li.dataset.id, li.dataset.nombre, li.dataset.icono);
                        cerrarDropdown();
                    });
                });

                document.addEventListener('click', e => {
                    if (!wrapper.contains(e.target)) cerrarDropdown();
                });

                // Pre-seleccionar valor actual de la empresa
                const initId = hidden.value;
                if (initId) {
                    const m = [...opciones].find(li => li.dataset.id === initId);
                    if (m) aplicarSeleccion(m.dataset.id, m.dataset.nombre, m.dataset.icono);
                }
            })();

            const buscarRucUrl = '{{ route('dashboard.empresas.buscar-ruc') }}';

            // ── Preview logo ─────────────────────────────────────────
            function previewLogo(input) {
                const preview = document.getElementById('logoPreview');
                const wrap    = document.getElementById('logoPreviewWrap');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        if (preview) preview.src = e.target.result;
                        if (wrap)    wrap.classList.remove('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // ── Búsqueda SUNAT ───────────────────────────────────────
            document.getElementById('btnBuscarRuc').addEventListener('click', async function () {
                const ruc = document.getElementById('rucInput').value.trim();
                const btn = this;

                if (!/^\d{11}$/.test(ruc)) {
                    mostrarEstado('error', 'El RUC debe tener exactamente 11 dígitos numéricos.');
                    return;
                }

                btn.disabled = true;
                document.getElementById('iconBuscar').textContent = 'sync';
                document.getElementById('iconBuscar').classList.add('animate-spin');
                document.getElementById('lblBuscar').textContent = 'Consultando...';
                ocultarEstado();

                try {
                    const resp = await fetch(`${buscarRucUrl}?ruc=${encodeURIComponent(ruc)}`, {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await resp.json();

                    if (!resp.ok) {
                        mostrarEstado('error', data.error || 'RUC no encontrado o servicio no disponible.');
                        return;
                    }

                    // Campos ya mapeados por el controller
                    setField('razon_social',             data.razon_social             || '');
                    setField('nombre_comercial',         data.nombre_comercial         || '');
                    setField('tipo_contribuyente',       data.tipo_contribuyente       || '');
                    setField('estado_sunat',             data.estado_sunat             || '');
                    setField('condicion_sunat',          data.condicion_sunat          || '');
                    setField('direccion_fiscal',         data.direccion_fiscal         || '');
                    setField('distrito_inei',            data.distrito_inei            || '');
                    setField('fecha_inicio_actividades', data.fecha_inicio_actividades || '');

                    document.getElementById('sunat_sincronizado_en').value = new Date().toISOString();

                    // Ubicación geográfica
                    const ubicacion = [data.distrito, data.provincia, data.departamento].filter(Boolean).join(' › ');
                    const infoEl    = document.getElementById('infoUbigeo');
                    const infoText  = document.getElementById('infoUbigeoText');
                    if (infoEl && infoText && ubicacion) {
                        infoText.textContent = ubicacion;
                        infoEl.classList.remove('hidden');
                    }

                    mostrarEstado('success', '✓ Datos actualizados desde PeruAPI correctamente.', ubicacion);

                } catch (e) {
                    mostrarEstado('error', 'Error de conexión. Inténtalo de nuevo.');
                } finally {
                    btn.disabled = false;
                    document.getElementById('iconBuscar').textContent = 'search';
                    document.getElementById('iconBuscar').classList.remove('animate-spin');
                    document.getElementById('lblBuscar').textContent = 'Actualizar desde SUNAT';
                }
            });

            function setField(id, value) {
                const el = document.getElementById(id);
                if (!el) return;
                el.value = value;
            }

            function mostrarEstado(tipo, msg, ubicacion) {
                const el  = document.getElementById('sunatStatus');
                const cls = tipo === 'success'
                    ? 'bg-success-100 border border-success-400 text-success-700'
                    : 'bg-danger-100 border border-danger-400 text-danger-700';
                el.className = `${cls} px-[14px] py-[10px] rounded-md text-sm`;
                el.innerHTML  = msg;
                if (ubicacion) {
                    el.innerHTML += `<span class="block text-xs opacity-75 mt-[3px]">📍 ${ubicacion}</span>`;
                }
                el.classList.remove('hidden');
            }

            function ocultarEstado() {
                document.getElementById('sunatStatus').classList.add('hidden');
                const infoEl = document.getElementById('infoUbigeo');
                if (infoEl) infoEl.classList.add('hidden');
            }
        </script>
    </body>
</html>
