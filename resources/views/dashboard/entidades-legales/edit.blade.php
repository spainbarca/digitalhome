<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <style>
            .select2-container--default .select2-selection--single { height:55px;border-radius:6px;border-color:#e5e7eb;display:flex;align-items:center;padding:0 14px; }
            .select2-container--default .select2-selection--single .select2-selection__rendered { line-height:normal;padding:0;color:inherit;display:flex;align-items:center;gap:8px; }
            .select2-container--default .select2-selection--single .select2-selection__arrow { height:55px;top:0; }
            .select2-dropdown { border-color:#e5e7eb;border-radius:6px; }
            .select2-search--dropdown .select2-search__field { border-radius:4px;border-color:#e5e7eb;outline:none; }
            .select2-results__option { padding:9px 12px; }
            .select2-container--default .select2-results__option--highlighted { background-color:#f0f6ff;color:#4f88e4; }
            .dark .select2-container--default .select2-selection--single { background-color:#0c1427;border-color:#172036;color:#fff; }
            .dark .select2-dropdown { background-color:#0c1427;border-color:#172036; }
            .dark .select2-results__option { color:#fff; }
            .dark .select2-container--default .select2-results__option--highlighted { background-color:#15203c; }
        </style>
        <title>Editar — {{ $entidadLegal->nombre }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Editar Entidad Legal</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.entidades-legales.index') }}" class="transition-all hover:text-primary-500">Entidades</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.entidades-legales.show', $entidadLegal) }}" class="transition-all hover:text-primary-500 truncate">{{ $entidadLegal->nombre }}</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">Editar: {{ $entidadLegal->nombre }}</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.entidades-legales.update', $entidadLegal) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- ── SECCIÓN 1: Tipo e identificación ──────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">gavel</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">1. Tipo e Identificación</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                                <!-- Tipo de entidad — custom dropdown con ícono -->
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Tipo de Entidad <span class="text-danger-500">*</span>
                                    </label>
                                    <div class="relative" id="tipoWrapper">
                                        <div id="tipoTrigger"
                                            class="h-[55px] flex items-center rounded-md border {{ $errors->has('tipo_entidad_legal_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                            <i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400" id="tipoIconPreview">gavel</i>
                                            <span id="tipoLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Seleccionar tipo...</span>
                                            <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="tipoChevron">expand_more</i>
                                        </div>
                                        <input type="hidden" name="tipo_entidad_legal_id" id="tipo_entidad_legal_id"
                                            value="{{ old('tipo_entidad_legal_id', $entidadLegal->tipo_entidad_legal_id) }}">
                                        <div id="tipoDropdown"
                                            class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                            <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                                <input type="text" id="tipoBuscar" placeholder="Buscar tipo..."
                                                    class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                            </div>
                                            <ul id="tipoOpciones" class="max-h-[240px] overflow-y-auto py-[4px]">
                                                @foreach($tipos as $tipo)
                                                <li class="tipo-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                    data-id="{{ $tipo->id }}"
                                                    data-nombre="{{ $tipo->nombre }}"
                                                    data-icono="{{ $tipo->icono ?? 'gavel' }}">
                                                    <i class="material-symbols-outlined !text-[18px] text-primary-500">{{ $tipo->icono ?? 'gavel' }}</i>
                                                    <span class="text-sm text-black dark:text-white">{{ $tipo->nombre }}</span>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    @error('tipo_entidad_legal_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <!-- Nombre -->
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Nombre <span class="text-danger-500">*</span>
                                    </label>
                                    <input type="text" name="nombre" value="{{ old('nombre', $entidadLegal->nombre) }}" maxlength="200"
                                        placeholder="Nombre de la entidad"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('nombre') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('nombre')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <!-- Empresa vinculada + Sigla (9 + 3) -->
                                <div class="sm:col-span-2">
                                <div class="grid grid-cols-12 gap-[20px] md:gap-[25px]">
                                <div class="col-span-12 sm:col-span-9" id="empresaWrapper">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Empresa Vinculada
                                        <span class="text-xs text-gray-400 font-normal ml-[4px]">Opcional</span>
                                    </label>
                                    <div class="relative">
                                        <div id="empresaTrigger"
                                            class="h-[55px] flex items-center rounded-md border {{ $errors->has('empresa_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                            <div id="empresaIconContainer" class="mr-[8px] flex-shrink-0 w-[24px] h-[24px] flex items-center justify-center">
                                                <i class="material-symbols-outlined !text-[20px] text-gray-400">business</i>
                                            </div>
                                            <span id="empresaLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Sin empresa vinculada</span>
                                            <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="empresaChevron">expand_more</i>
                                        </div>
                                        <input type="hidden" name="empresa_id" id="empresa_id" value="{{ old('empresa_id', $entidadLegal->empresa_id ?? '') }}">
                                        <div id="empresaDropdown"
                                            class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                            <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                                <input type="text" id="empresaBuscar" placeholder="Buscar por razón social o RUC..."
                                                    class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                            </div>
                                            <ul id="empresaOpciones" class="max-h-[240px] overflow-y-auto py-[4px]">
                                                <li class="empresa-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                    data-id="" data-nombre="Sin empresa vinculada" data-buscar="" data-logo="">
                                                    <i class="material-symbols-outlined !text-[18px] text-gray-400">remove_circle_outline</i>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Sin empresa vinculada</span>
                                                </li>
                                                @foreach($empresas as $emp)
                                                @php $logoEmp = $emp->logo_url ? (str_starts_with($emp->logo_url, 'http') ? $emp->logo_url : asset('storage/' . $emp->logo_url)) : ''; @endphp
                                                <li class="empresa-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                    data-id="{{ $emp->id }}"
                                                    data-nombre="{{ $emp->razon_social }}"
                                                    data-buscar="{{ strtolower($emp->razon_social . ' ' . $emp->ruc) }}"
                                                    data-logo="{{ $logoEmp }}"
                                                    data-sigla="{{ $emp->sigla ?? '' }}">
                                                    @if($logoEmp)
                                                        <img src="{{ $logoEmp }}" class="w-[24px] h-[24px] rounded-[4px] object-cover flex-shrink-0" alt="">
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
                                            value="{{ old('sigla', $entidadLegal->sigla ?? '') }}"
                                            maxlength="50"
                                            placeholder="Ej. BCP"
                                            class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('sigla') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                        @error('sigla')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                </div>
                                </div>

                                <!-- Descripción -->
                                <div class="sm:col-span-2">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Descripción</label>
                                    <textarea name="descripcion" rows="3" maxlength="1000"
                                        placeholder="Descripción o notas sobre la entidad..."
                                        class="rounded-md text-black dark:text-white border {{ $errors->has('descripcion') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500 resize-none">{{ old('descripcion', $entidadLegal->descripcion) }}</textarea>
                                    @error('descripcion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── SECCIÓN 2: Contacto ────────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">contact_phone</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">2. Contacto</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px] md:gap-[25px]">

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Teléfono</label>
                                    <input type="text" name="telefono" value="{{ old('telefono', $entidadLegal->telefono) }}" maxlength="30"
                                        placeholder="Ej: 01-2345678"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('telefono') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('telefono')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $entidadLegal->email) }}" maxlength="150"
                                        placeholder="contacto@entidad.com"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('email') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('email')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Sitio Web</label>
                                    <input type="url" name="sitio_web" value="{{ old('sitio_web', $entidadLegal->sitio_web) }}" maxlength="200"
                                        placeholder="https://www.entidad.com"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('sitio_web') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('sitio_web')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── SECCIÓN 3: Ubicación ───────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">location_on</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">3. Ubicación</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Dirección</label>
                                    <input type="text" name="direccion" value="{{ old('direccion', $entidadLegal->direccion) }}" maxlength="300"
                                        placeholder="Dirección de la entidad"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('direccion') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('direccion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    @php
                                        $currentDistrito = old('distrito_inei', $entidadLegal->distrito_inei)
                                            ? \App\Models\UbigeoDistrito::find(old('distrito_inei', $entidadLegal->distrito_inei))
                                            : null;
                                    @endphp
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Distrito (Perú)</label>
                                    <select name="distrito_inei" id="distrito_inei"
                                        class="block w-full {{ $errors->has('distrito_inei') ? 'border-danger-500' : '' }}">
                                        @if($currentDistrito)
                                            <option value="{{ $currentDistrito->inei }}" selected>
                                                {{ $currentDistrito->distrito }} — {{ $currentDistrito->provincia }} — {{ $currentDistrito->departamento }}
                                            </option>
                                        @endif
                                    </select>
                                    @error('distrito_inei')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── SECCIÓN 4: Imágenes y Estado ──────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">image</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">4. Imágenes y Estado</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[20px] md:gap-[25px]">

                                <!-- Logo -->
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Logo <span class="text-xs text-gray-400 font-normal">(JPG/PNG, máx. 2 MB)</span>
                                    </label>
                                    @if($entidadLegal->logo_path)
                                    <div class="mb-[8px]">
                                        <img src="{{ Storage::url($entidadLegal->logo_path) }}"
                                            class="w-[64px] h-[64px] rounded-full object-cover border border-gray-200 dark:border-[#172036]"
                                            alt="Logo actual">
                                        <span class="block text-xs text-gray-400 mt-[4px]">Logo actual</span>
                                    </div>
                                    @endif
                                    <div id="logoPreviewWrap" class="hidden mb-[8px]">
                                        <img id="logoPreview" src="" alt="Nuevo logo" class="w-[64px] h-[64px] rounded-full object-cover border border-primary-200">
                                        <span class="block text-xs text-primary-500 mt-[4px]">Nuevo logo</span>
                                    </div>
                                    <input type="file" name="logo" id="logoInput" accept="image/*"
                                        onchange="previewFile(this,'logoPreview','logoPreviewWrap')"
                                        class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                    @error('logo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <!-- Imagen -->
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Imagen <span class="text-xs text-gray-400 font-normal">(JPG/PNG, máx. 2 MB)</span>
                                    </label>
                                    @if($entidadLegal->imagen_path)
                                    <div class="mb-[8px]">
                                        <img src="{{ Storage::url($entidadLegal->imagen_path) }}"
                                            class="w-[80px] h-[50px] rounded-md object-cover"
                                            alt="Imagen actual">
                                        <span class="block text-xs text-gray-400 mt-[4px]">Imagen actual</span>
                                    </div>
                                    @endif
                                    <div id="imagenPreviewWrap" class="hidden mb-[8px]">
                                        <img id="imagenPreview" src="" alt="Nueva imagen" class="w-[80px] h-[50px] rounded-md object-cover border border-primary-200">
                                        <span class="block text-xs text-primary-500 mt-[4px]">Nueva imagen</span>
                                    </div>
                                    <input type="file" name="imagen" id="imagenInput" accept="image/*"
                                        onchange="previewFile(this,'imagenPreview','imagenPreviewWrap')"
                                        class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                    @error('imagen')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <!-- Banner -->
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Banner <span class="text-xs text-gray-400 font-normal">(JPG/PNG, máx. 2 MB)</span>
                                    </label>
                                    @if($entidadLegal->banner_path)
                                    <div class="mb-[8px]">
                                        <img src="{{ Storage::url($entidadLegal->banner_path) }}"
                                            class="w-[180px] h-[48px] rounded-md object-cover"
                                            alt="Banner actual">
                                        <span class="block text-xs text-gray-400 mt-[4px]">Banner actual</span>
                                    </div>
                                    @endif
                                    <div id="bannerPreviewWrap" class="hidden mb-[8px]">
                                        <img id="bannerPreview" src="" alt="Nuevo banner" class="w-[180px] h-[48px] rounded-md object-cover border border-primary-200">
                                        <span class="block text-xs text-primary-500 mt-[4px]">Nuevo banner</span>
                                    </div>
                                    <input type="file" name="banner" id="bannerInput" accept="image/*"
                                        onchange="previewFile(this,'bannerPreview','bannerPreviewWrap')"
                                        class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                    @error('banner')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <!-- Activo -->
                                <div class="flex items-center gap-[12px] pt-[10px] lg:pt-[30px]">
                                    <span class="text-black dark:text-white font-medium">Activo</span>
                                    <label class="relative cursor-pointer">
                                        <input type="checkbox" name="activo" value="1" class="sr-only peer"
                                            {{ old('activo', $entidadLegal->activo) ? 'checked' : '' }}>
                                        <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-[20px] flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.entidades-legales.show', $entidadLegal) }}"
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
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            const buscarDistritosUrl = '{{ route('dashboard.ubigeo.distritos') }}';

            $(document).ready(function () {
                // Select2 AJAX para distrito
                $('#distrito_inei').select2({
                    placeholder: 'Escribe para buscar distrito...',
                    allowClear: true,
                    width: '100%',
                    minimumInputLength: 2,
                    language: {
                        inputTooShort: () => 'Escribe al menos 2 caracteres',
                        noResults:     () => 'Sin resultados',
                        searching:     () => 'Buscando...',
                    },
                    ajax: {
                        url: buscarDistritosUrl,
                        dataType: 'json',
                        delay: 300,
                        data: params => ({ q: params.term }),
                        processResults: data => ({ results: data.results }),
                        cache: true,
                    },
                });
            });

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

            // Custom dropdown Tipo de Entidad
            (function () {
                const wrapper  = document.getElementById('tipoWrapper');
                const trigger  = document.getElementById('tipoTrigger');
                const dropdown = document.getElementById('tipoDropdown');
                const chevron  = document.getElementById('tipoChevron');
                const buscar   = document.getElementById('tipoBuscar');
                const hidden   = document.getElementById('tipo_entidad_legal_id');
                const label    = document.getElementById('tipoLabel');
                const iconPrev = document.getElementById('tipoIconPreview');
                const opciones = document.querySelectorAll('.tipo-opcion');

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
                    if (id) {
                        label.textContent    = nombre;
                        label.className      = 'text-black dark:text-white text-sm flex-1 truncate';
                        iconPrev.textContent = icono;
                        iconPrev.className   = 'material-symbols-outlined !text-[20px] mr-[8px] text-primary-500';
                    } else {
                        label.textContent    = 'Seleccionar tipo...';
                        label.className      = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                        iconPrev.textContent = 'gavel';
                        iconPrev.className   = 'material-symbols-outlined !text-[20px] mr-[8px] text-gray-400';
                    }
                }
                trigger.addEventListener('click', () =>
                    dropdown.classList.contains('hidden') ? abrirDropdown() : cerrarDropdown()
                );
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
                document.addEventListener('click', e => { if (!wrapper.contains(e.target)) cerrarDropdown(); });

                // Pre-seleccionar valor actual
                const initId = hidden.value;
                if (initId) {
                    const m = [...opciones].find(li => li.dataset.id === initId);
                    if (m) aplicarSeleccion(m.dataset.id, m.dataset.nombre, m.dataset.icono);
                }
            })();

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
                        label.textContent = nombre;
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
        </script>
    </body>
</html>
