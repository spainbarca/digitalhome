<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Editar Negocio — {{ $negocio->nombre ?? $negocio->empresa?->razon_social ?? 'Negocio' }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        @php $titulo = $negocio->nombre ?? $negocio->empresa?->razon_social ?? 'Negocio'; @endphp

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">Editar — {{ $titulo }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.negocios.index') }}" class="transition-all hover:text-primary-500">Negocios</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">Editar Negocio</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.negocios.update', $negocio) }}">
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
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">
                                <div id="empresaWrapper" class="relative">
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
                                        <input type="hidden" name="empresa_id" id="empresa_id" value="{{ old('empresa_id', $negocio->empresa_id ?? '') }}">
                                        <div id="empresaDropdown"
                                            class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                            <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                                <input type="text" id="empresaBuscar" placeholder="Buscar por razón social o RUC..."
                                                    class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                            </div>
                                            <ul id="empresaOpciones" class="max-h-[240px] overflow-y-auto py-[4px]">
                                                <li class="empresa-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                    data-id="" data-nombre="Sin empresa vinculada" data-buscar="" data-logo="" data-sector="">
                                                    <i class="material-symbols-outlined !text-[18px] text-gray-400">remove_circle_outline</i>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Sin empresa vinculada</span>
                                                </li>
                                                @foreach($empresas as $emp)
                                                <li class="empresa-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                    data-id="{{ $emp->id }}"
                                                    data-nombre="{{ $emp->razon_social }}{{ $emp->ruc ? ' — ' . $emp->ruc : '' }}"
                                                    data-buscar="{{ strtolower($emp->razon_social . ' ' . $emp->ruc) }}"
                                                    data-logo="{{ $emp->logo_url ? asset('storage/' . $emp->logo_url) : '' }}"
                                                    data-sector="{{ $emp->sector?->nombre ?? '' }}">
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

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Sector</label>
                                    <div class="h-[55px] flex items-center rounded-md border border-gray-100 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c] px-[17px]">
                                        <i class="material-symbols-outlined !text-[18px] text-gray-400 mr-[8px]">category</i>
                                        <span id="sectorDisplay" class="{{ $negocio->empresa?->sector ? 'text-black dark:text-white text-sm' : 'text-gray-400 dark:text-gray-500 text-sm italic' }}">
                                            {{ $negocio->empresa?->sector?->nombre ?? 'Se obtiene de la empresa' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 2: Datos del Negocio ──────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">store</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">2. Datos del Negocio</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">
                                <div class="sm:col-span-2">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Nombre del Negocio</label>
                                    <input type="text" name="nombre"
                                        value="{{ old('nombre', $negocio->nombre ?? '') }}"
                                        maxlength="255"
                                        placeholder="Nombre con el que se conoce el negocio"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('nombre') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('nombre')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Código CIIU</label>
                                    <input type="text" name="ciiu"
                                        value="{{ old('ciiu', $negocio->ciiu ?? '') }}"
                                        maxlength="20" placeholder="Ej. 4711"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('ciiu') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 font-mono focus:border-primary-500">
                                    @error('ciiu')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Constitución</label>
                                    <input type="date" name="fecha_constitucion"
                                        value="{{ old('fecha_constitucion', $negocio->fecha_constitucion ? $negocio->fecha_constitucion->format('Y-m-d') : '') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_constitucion') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                    @error('fecha_constitucion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Inicio de Actividades</label>
                                    <input type="date" name="fecha_inicio_actividades"
                                        value="{{ old('fecha_inicio_actividades', $negocio->fecha_inicio_actividades ? $negocio->fecha_inicio_actividades->format('Y-m-d') : '') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_inicio_actividades') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                    @error('fecha_inicio_actividades')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Partida Registral</label>
                                    <input type="text" name="partida_registral"
                                        value="{{ old('partida_registral', $negocio->partida_registral ?? '') }}"
                                        maxlength="100" placeholder="Ej. 12345678"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('partida_registral') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 font-mono focus:border-primary-500">
                                    @error('partida_registral')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Oficina Registral</label>
                                    <input type="text" name="oficina_registral"
                                        value="{{ old('oficina_registral', $negocio->oficina_registral ?? '') }}"
                                        maxlength="100" placeholder="Ej. Lima"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('oficina_registral') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('oficina_registral')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Observaciones</label>
                                    <textarea name="observaciones" rows="3"
                                        placeholder="Observaciones adicionales..."
                                        class="rounded-md text-black dark:text-white border {{ $errors->has('observaciones') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500 resize-none">{{ old('observaciones', $negocio->observaciones ?? '') }}</textarea>
                                    @error('observaciones')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 3: Catálogos ──────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">tune</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">3. Clasificación</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px] md:gap-[25px]">
                                {{-- Régimen Tributario --}}
                                <div id="regimenWrapper" class="relative">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Régimen Tributario</label>
                                    <div id="regimenTrigger"
                                        class="h-[55px] flex items-center rounded-md border {{ $errors->has('regimen_tributario_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <span id="regimenIconEl" class="mr-[8px] flex-shrink-0"><i class="material-symbols-outlined !text-[20px] text-gray-400">receipt_long</i></span>
                                        <span id="regimenLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Sin especificar</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="regimenChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="regimen_tributario_id" id="regimen_tributario_id" value="{{ old('regimen_tributario_id', $negocio->regimen_tributario_id ?? '') }}">
                                    <div id="regimenDropdown" class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <ul class="max-h-[220px] overflow-y-auto py-[4px]">
                                            <li class="regimen-opcion px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors text-sm text-gray-500"
                                                data-id="" data-nombre="Sin especificar" data-codigo="">Sin especificar</li>
                                            @foreach($regimenes as $r)
                                            <li class="regimen-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $r->id }}" data-nombre="{{ $r->nombre }}" data-codigo="{{ $r->codigo ?? '' }}" data-icono="{{ $r->icono ?? 'receipt_long' }}">
                                                <i class="material-symbols-outlined !text-[18px] text-primary-500 flex-shrink-0">{{ $r->icono ?? 'receipt_long' }}</i>
                                                <div class="min-w-0">
                                                    <span class="text-sm text-black dark:text-white block truncate">{{ $r->nombre }}</span>
                                                    @if($r->codigo)<span class="text-xs text-gray-400 font-mono">{{ $r->codigo }}</span>@endif
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @error('regimen_tributario_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                {{-- Tipo de Sociedad --}}
                                <div id="tipoSocWrapper" class="relative">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Tipo de Sociedad</label>
                                    <div id="tipoSocTrigger"
                                        class="h-[55px] flex items-center rounded-md border {{ $errors->has('tipo_sociedad_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <span id="tipoSocIconEl" class="mr-[8px] flex-shrink-0"><i class="material-symbols-outlined !text-[20px] text-gray-400">account_tree</i></span>
                                        <span id="tipoSocLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Sin especificar</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="tipoSocChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="tipo_sociedad_id" id="tipo_sociedad_id" value="{{ old('tipo_sociedad_id', $negocio->tipo_sociedad_id ?? '') }}">
                                    <div id="tipoSocDropdown" class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <ul class="max-h-[220px] overflow-y-auto py-[4px]">
                                            <li class="tipo-soc-opcion px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors text-sm text-gray-500"
                                                data-id="" data-nombre="Sin especificar">Sin especificar</li>
                                            @foreach($tiposSociedad as $ts)
                                            <li class="tipo-soc-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $ts->id }}" data-nombre="{{ $ts->nombre }}" data-icono="{{ $ts->icono ?? 'account_tree' }}">
                                                <i class="material-symbols-outlined !text-[18px] text-primary-500 flex-shrink-0">{{ $ts->icono ?? 'account_tree' }}</i>
                                                <div class="min-w-0">
                                                    <span class="text-sm text-black dark:text-white block truncate">{{ $ts->nombre }}</span>
                                                    @if($ts->sigla)<span class="text-xs text-gray-400 font-mono">{{ $ts->sigla }}</span>@endif
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @error('tipo_sociedad_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                {{-- Estado del Negocio --}}
                                <div id="estadoWrapper" class="relative">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Estado del Negocio</label>
                                    <div id="estadoTrigger"
                                        class="h-[55px] flex items-center rounded-md border {{ $errors->has('estado_negocio_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <span id="estadoColorDot" class="w-[12px] h-[12px] rounded-full bg-gray-300 mr-[6px] flex-shrink-0 block"></span>
                                        <span id="estadoIconEl" class="mr-[6px] flex-shrink-0"><i class="material-symbols-outlined !text-[20px] text-gray-400">store</i></span>
                                        <span id="estadoLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Sin especificar</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="estadoChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="estado_negocio_id" id="estado_negocio_id" value="{{ old('estado_negocio_id', $negocio->estado_negocio_id ?? '') }}">
                                    <div id="estadoDropdown" class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <ul class="max-h-[220px] overflow-y-auto py-[4px]">
                                            <li class="estado-opcion px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors text-sm text-gray-500"
                                                data-id="" data-nombre="Sin especificar" data-color="">Sin especificar</li>
                                            @foreach($estadosNegocio as $en)
                                            <li class="estado-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $en->id }}" data-nombre="{{ $en->nombre }}" data-color="{{ $en->color ?? '#6b7280' }}" data-icono="{{ $en->icono ?? 'store' }}">
                                                <span class="w-[10px] h-[10px] rounded-full flex-shrink-0 block" style="background-color: {{ $en->color ?? '#6b7280' }}"></span>
                                                <i class="material-symbols-outlined !text-[18px] text-primary-500 flex-shrink-0">{{ $en->icono ?? 'store' }}</i>
                                                <span class="text-sm text-black dark:text-white">{{ $en->nombre }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @error('estado_negocio_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 4: Representante Legal ───────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">person_check</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">4. Representante Legal</h6>
                            </div>
                            <div id="miembroWrapper" class="relative">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Persona del Hogar</label>
                                <div id="miembroTrigger"
                                    class="h-[55px] flex items-center rounded-md border {{ $errors->has('miembro_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                    <div id="miembroIconContainer" class="mr-[8px] flex-shrink-0 w-[32px] h-[32px] flex items-center justify-center">
                                        <i class="material-symbols-outlined !text-[24px] text-gray-400">person</i>
                                    </div>
                                    <span id="miembroLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Sin representante legal</span>
                                    <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="miembroChevron">expand_more</i>
                                </div>
                                <input type="hidden" name="miembro_id" id="miembro_id" value="{{ old('miembro_id', $negocio->miembro_id ?? '') }}">
                                <div id="miembroDropdown" class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                    <ul class="max-h-[240px] overflow-y-auto py-[4px]">
                                        <li class="miembro-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                            data-id="" data-nombre="Sin representante legal" data-foto="">
                                            <i class="material-symbols-outlined !text-[20px] text-gray-400">remove_circle_outline</i>
                                            <span class="text-sm text-gray-500">Sin representante legal</span>
                                        </li>
                                        @foreach($personas as $per)
                                        <li class="miembro-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                            data-id="{{ $per->id }}"
                                            data-nombre="{{ $per->nombres }} {{ $per->apellido_paterno }}"
                                            data-foto="{{ $per->foto_url ?? '' }}">
                                            @if($per->foto_url)
                                                <img src="{{ $per->foto_url }}" class="w-[28px] h-[28px] rounded-full object-cover flex-shrink-0" alt="">
                                            @else
                                                <div class="w-[28px] h-[28px] rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                                    <i class="material-symbols-outlined !text-[16px] text-primary-500">person</i>
                                                </div>
                                            @endif
                                            <span class="text-sm text-black dark:text-white truncate">{{ $per->nombres }} {{ $per->apellido_paterno }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @error('miembro_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-[20px] flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.negocios.show', $negocio) }}"
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
            function makeSimpleDropdown(wrapperId, triggerId, dropdownId, chevronId, hiddenId, labelId, itemClass, onSelect) {
                const wrapper  = document.getElementById(wrapperId);
                const trigger  = document.getElementById(triggerId);
                const dropdown = document.getElementById(dropdownId);
                const chevron  = document.getElementById(chevronId);
                const hidden   = document.getElementById(hiddenId);
                const label    = document.getElementById(labelId);
                const opciones = document.querySelectorAll('.' + itemClass);
                function abrir() { dropdown.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; }
                function cerrar() { dropdown.classList.add('hidden'); chevron.style.transform = ''; }
                trigger.addEventListener('click', () => dropdown.classList.contains('hidden') ? abrir() : cerrar());
                opciones.forEach(li => {
                    li.addEventListener('click', () => { hidden.value = li.dataset.id; onSelect(li, label); cerrar(); });
                });
                document.addEventListener('click', e => { if (!wrapper.contains(e.target)) cerrar(); });
                if (hidden.value) {
                    const m = [...opciones].find(li => li.dataset.id === hidden.value);
                    if (m) { onSelect(m, label); }
                }
            }

            // ── Empresa ──────────────────────────────────────────────────
            (function () {
                const wrapper  = document.getElementById('empresaWrapper');
                const trigger  = document.getElementById('empresaTrigger');
                const dropdown = document.getElementById('empresaDropdown');
                const chevron  = document.getElementById('empresaChevron');
                const buscar   = document.getElementById('empresaBuscar');
                const hidden   = document.getElementById('empresa_id');
                const label    = document.getElementById('empresaLabel');
                const opciones = document.querySelectorAll('.empresa-opcion');
                function abrir() { dropdown.classList.remove('hidden'); chevron.style.transform='rotate(180deg)'; buscar.value=''; opciones.forEach(li=>li.style.display=''); setTimeout(()=>buscar.focus(),50); }
                function cerrar() { dropdown.classList.add('hidden'); chevron.style.transform=''; }
                function aplicar(id, nombre, logo, sector) {
                    hidden.value = id;
                    const ic = document.getElementById('empresaIconContainer');
                    const sd = document.getElementById('sectorDisplay');
                    if (id) {
                        label.textContent = nombre; label.className = 'text-black dark:text-white text-sm flex-1 truncate';
                        ic.innerHTML = logo ? `<img src="${logo}" style="width:24px;height:24px;border-radius:4px;object-fit:cover;">` : '<i class="material-symbols-outlined !text-[20px] text-primary-500">business</i>';
                        sd.textContent = sector || '—'; sd.className = 'text-black dark:text-white text-sm';
                    } else {
                        label.textContent = 'Seleccionar empresa...'; label.className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                        ic.innerHTML = '<i class="material-symbols-outlined !text-[20px] text-gray-400">business</i>';
                        sd.textContent = 'Se obtiene de la empresa'; sd.className = 'text-gray-400 dark:text-gray-500 text-sm italic';
                    }
                }
                trigger.addEventListener('click', () => dropdown.classList.contains('hidden') ? abrir() : cerrar());
                buscar.addEventListener('input', () => { const q=buscar.value.toLowerCase(); opciones.forEach(li=>{li.style.display=(li.dataset.buscar||li.dataset.nombre||'').toLowerCase().includes(q)?'':'none';}); });
                opciones.forEach(li => { li.addEventListener('click', () => { aplicar(li.dataset.id,li.dataset.nombre,li.dataset.logo,li.dataset.sector); cerrar(); }); });
                document.addEventListener('click', e => { if (!wrapper.contains(e.target)) cerrar(); });
                const initId = hidden.value;
                if (initId) { const m=[...opciones].find(li=>li.dataset.id===initId); if(m) aplicar(m.dataset.id,m.dataset.nombre,m.dataset.logo,m.dataset.sector); }
            })();

            makeSimpleDropdown('regimenWrapper','regimenTrigger','regimenDropdown','regimenChevron','regimen_tributario_id','regimenLabel','regimen-opcion',
                function(li,label){
                    const icono=li.dataset.icono||'receipt_long';
                    const iconEl=document.getElementById('regimenIconEl');
                    if(li.dataset.id){label.textContent=li.dataset.codigo?`${li.dataset.codigo} — ${li.dataset.nombre}`:li.dataset.nombre;label.className='text-black dark:text-white text-sm flex-1 truncate';iconEl.innerHTML=`<i class="material-symbols-outlined !text-[20px] text-primary-500">${icono}</i>`;}
                    else{label.textContent='Sin especificar';label.className='text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';iconEl.innerHTML=`<i class="material-symbols-outlined !text-[20px] text-gray-400">${icono}</i>`;}
                }
            );
            makeSimpleDropdown('tipoSocWrapper','tipoSocTrigger','tipoSocDropdown','tipoSocChevron','tipo_sociedad_id','tipoSocLabel','tipo-soc-opcion',
                function(li,label){
                    const icono=li.dataset.icono||'account_tree';
                    const iconEl=document.getElementById('tipoSocIconEl');
                    if(li.dataset.id){label.textContent=li.dataset.nombre;label.className='text-black dark:text-white text-sm flex-1 truncate';iconEl.innerHTML=`<i class="material-symbols-outlined !text-[20px] text-primary-500">${icono}</i>`;}
                    else{label.textContent='Sin especificar';label.className='text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';iconEl.innerHTML=`<i class="material-symbols-outlined !text-[20px] text-gray-400">${icono}</i>`;}
                }
            );

            // ── Estado ───────────────────────────────────────────────────
            (function () {
                const wrapper  = document.getElementById('estadoWrapper');
                const trigger  = document.getElementById('estadoTrigger');
                const dropdown = document.getElementById('estadoDropdown');
                const chevron  = document.getElementById('estadoChevron');
                const hidden   = document.getElementById('estado_negocio_id');
                const label    = document.getElementById('estadoLabel');
                const dot      = document.getElementById('estadoColorDot');
                const opciones = document.querySelectorAll('.estado-opcion');
                function abrir() { dropdown.classList.remove('hidden'); chevron.style.transform='rotate(180deg)'; }
                function cerrar() { dropdown.classList.add('hidden'); chevron.style.transform=''; }
                function aplicar(id,nombre,color,icono){hidden.value=id;const iconEl=document.getElementById('estadoIconEl');if(id){label.textContent=nombre;label.className='text-black dark:text-white text-sm flex-1 truncate';dot.style.backgroundColor=color||'#6b7280';iconEl.innerHTML=`<i class="material-symbols-outlined !text-[20px] text-primary-500">${icono||'store'}</i>`;}else{label.textContent='Sin especificar';label.className='text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';dot.style.backgroundColor='#d1d5db';iconEl.innerHTML='<i class="material-symbols-outlined !text-[20px] text-gray-400">store</i>';}}
                trigger.addEventListener('click',()=>dropdown.classList.contains('hidden')?abrir():cerrar());
                opciones.forEach(li=>{li.addEventListener('click',()=>{aplicar(li.dataset.id,li.dataset.nombre,li.dataset.color,li.dataset.icono);cerrar();});});
                document.addEventListener('click',e=>{if(!wrapper.contains(e.target))cerrar();});
                const initId=hidden.value; if(initId){const m=[...opciones].find(li=>li.dataset.id===initId);if(m)aplicar(m.dataset.id,m.dataset.nombre,m.dataset.color,m.dataset.icono);}
            })();

            // ── Representante Legal ──────────────────────────────────────
            (function () {
                const wrapper  = document.getElementById('miembroWrapper');
                const trigger  = document.getElementById('miembroTrigger');
                const dropdown = document.getElementById('miembroDropdown');
                const chevron  = document.getElementById('miembroChevron');
                const hidden   = document.getElementById('miembro_id');
                const label    = document.getElementById('miembroLabel');
                const opciones = document.querySelectorAll('.miembro-opcion');
                function abrir(){dropdown.classList.remove('hidden');chevron.style.transform='rotate(180deg)';}
                function cerrar(){dropdown.classList.add('hidden');chevron.style.transform='';}
                function aplicar(id,nombre,foto){hidden.value=id;const ic=document.getElementById('miembroIconContainer');if(id){label.textContent=nombre;label.className='text-black dark:text-white text-sm flex-1 truncate';ic.innerHTML=foto?`<img src="${foto}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">`:'<div style="width:32px;height:32px;border-radius:50%;background:#e0e7ff;display:flex;align-items:center;justify-content:center;"><i class="material-symbols-outlined" style="font-size:18px;color:#6366f1;">person</i></div>';}else{label.textContent='Sin representante legal';label.className='text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';ic.innerHTML='<i class="material-symbols-outlined !text-[24px] text-gray-400">person</i>';}}
                trigger.addEventListener('click',()=>dropdown.classList.contains('hidden')?abrir():cerrar());
                opciones.forEach(li=>{li.addEventListener('click',()=>{aplicar(li.dataset.id,li.dataset.nombre,li.dataset.foto);cerrar();});});
                document.addEventListener('click',e=>{if(!wrapper.contains(e.target))cerrar();});
                const initId=hidden.value;if(initId){const m=[...opciones].find(li=>li.dataset.id===initId);if(m)aplicar(m.dataset.id,m.dataset.nombre,m.dataset.foto);}
            })();
        </script>
    </body>
</html>
