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
            .select2-results__option { display:flex;align-items:center;gap:8px;padding:9px 12px; }
            .select2-container--default .select2-results__option--highlighted { background-color:#f0f6ff;color:#4f88e4; }
            .dark .select2-container--default .select2-selection--single { background-color:#0c1427;border-color:#172036; }
            .dark .select2-dropdown { background-color:#0c1427;border-color:#172036; }
            .dark .select2-results__option { color:#fff; }
            .dark .select2-container--default .select2-results__option--highlighted { background-color:#15203c; }
        </style>
        <title>Nuevo Producto Financiero</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nuevo Producto Financiero</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.productos-financieros.index') }}" class="transition-all hover:text-primary-500">Productos Financieros</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nuevo</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">Registrar Producto Financiero</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.productos-financieros.store') }}" id="productoForm">
                        @csrf

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- ── SECCIÓN 1: Entidad y Tipo ───────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">account_balance</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">1. Entidad y Tipo</h6>
                                <span class="text-xs text-danger-500">* Requerido</span>
                            </div>
                            <div class="grid grid-cols-12 gap-[20px] md:gap-[25px]">

                                <!-- Entidad Financiera (custom dropdown) -->
                                <div class="col-span-12 sm:col-span-9" id="entidadWrapper">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Entidad Financiera <span class="text-danger-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div id="entidadTrigger"
                                            class="h-[55px] flex items-center rounded-md border {{ $errors->has('entidad_financiera_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                            <div id="entidadIconContainer" class="mr-[8px] flex-shrink-0 w-[24px] h-[24px] flex items-center justify-center">
                                                <i class="material-symbols-outlined !text-[20px] text-gray-400">account_balance</i>
                                            </div>
                                            <span id="entidadLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Seleccionar entidad financiera...</span>
                                            <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="entidadChevron">expand_more</i>
                                        </div>
                                        <input type="hidden" name="entidad_financiera_id" id="entidad_financiera_id" value="{{ old('entidad_financiera_id', '') }}">
                                        <div id="entidadDropdown"
                                            class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                            <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                                <input type="text" id="entidadBuscar" placeholder="Buscar por nombre o RUC..."
                                                    class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                            </div>
                                            <ul id="entidadOpciones" class="max-h-[240px] overflow-y-auto py-[4px]">
                                                @foreach($entidades as $ent)
                                                @php
                                                    $nombreEnt = $ent->nombre_comercial_resuelto ?? $ent->empresa?->razon_social ?? '—';
                                                    $rucEnt    = $ent->empresa?->ruc;
                                                @endphp
                                                <li class="entidad-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                    data-id="{{ $ent->id }}"
                                                    data-nombre="{{ $nombreEnt }}"
                                                    data-buscar="{{ strtolower($nombreEnt . ' ' . $rucEnt) }}"
                                                    data-logo="{{ $ent->logo_resuelto }}">
                                                    @if($ent->logo_resuelto)
                                                        <img src="{{ $ent->logo_resuelto }}" class="w-[24px] h-[24px] rounded-[4px] object-cover flex-shrink-0" alt="">
                                                    @else
                                                        <i class="material-symbols-outlined !text-[18px] text-primary-500 flex-shrink-0">account_balance</i>
                                                    @endif
                                                    <div class="min-w-0">
                                                        <span class="text-sm text-black dark:text-white block truncate">{{ $nombreEnt }}</span>
                                                        @if($rucEnt)<span class="text-xs text-gray-400 font-mono">RUC: {{ $rucEnt }}</span>@endif
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    @error('entidad_financiera_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <!-- Tipo de Producto (custom dropdown, carries data-naturaleza) -->
                                <div class="col-span-12 sm:col-span-3" id="tipoWrapper">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Tipo de Producto <span class="text-danger-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div id="tipoTrigger"
                                            class="h-[55px] flex items-center rounded-md border {{ $errors->has('tipo_producto_financiero_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                            <div id="tipoIconContainer" class="mr-[8px] flex-shrink-0 w-[24px] h-[24px] flex items-center justify-center">
                                                <i class="material-symbols-outlined !text-[20px] text-gray-400">category</i>
                                            </div>
                                            <span id="tipoLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Seleccionar tipo...</span>
                                            <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200 flex-shrink-0" id="tipoChevron">expand_more</i>
                                        </div>
                                        <input type="hidden" name="tipo_producto_financiero_id" id="tipo_producto_financiero_id" value="{{ old('tipo_producto_financiero_id', '') }}">
                                        <div id="tipoDropdown"
                                            class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                            <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                                <input type="text" id="tipoBuscar" placeholder="Buscar tipo..."
                                                    class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                            </div>
                                            <ul id="tipoOpciones" class="max-h-[240px] overflow-y-auto py-[4px]">
                                                @foreach($tipos as $t)
                                                <li class="tipo-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                    data-id="{{ $t->id }}"
                                                    data-nombre="{{ $t->nombre }}"
                                                    data-naturaleza="{{ $t->naturaleza }}"
                                                    data-icono="{{ $t->icono ?? 'category' }}">
                                                    <i class="material-symbols-outlined !text-[18px] text-primary-500 flex-shrink-0">{{ $t->icono ?? 'category' }}</i>
                                                    <span class="text-sm text-black dark:text-white">{{ $t->nombre }}</span>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    @error('tipo_producto_financiero_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── SECCIÓN 2: Identificación ───────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">badge</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">2. Identificación</h6>
                            </div>
                            <div class="grid grid-cols-12 gap-[20px] md:gap-[25px] mb-[20px] md:mb-[25px]">
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Alias <span class="text-danger-500">*</span>
                                        <span class="text-xs text-gray-400 font-normal ml-[4px]">(nombre referencial)</span>
                                    </label>
                                    <input type="text" name="alias" value="{{ old('alias') }}" maxlength="255"
                                        placeholder="Ej: Cuenta sueldo BCP"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('alias') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('alias')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div class="col-span-12 sm:col-span-3">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Moneda <span class="text-danger-500">*</span></label>
                                    <select name="moneda_id"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('moneda_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                        <option value="">Seleccionar...</option>
                                        @foreach($monedas as $m)
                                        <option value="{{ $m->id }}" {{ old('moneda_id') == $m->id ? 'selected' : '' }}>
                                            {{ $m->simbolo }} {{ $m->codigo }} — {{ $m->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('moneda_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div class="col-span-12 sm:col-span-3">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Estado <span class="text-danger-500">*</span></label>
                                    <select name="estado_producto_id"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('estado_producto_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                        <option value="">Seleccionar...</option>
                                        @foreach($estados as $es)
                                        <option value="{{ $es->id }}" {{ old('estado_producto_id') == $es->id ? 'selected' : '' }}>{{ $es->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('estado_producto_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-[20px] md:gap-[25px]">
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Titular <span class="text-danger-500">*</span></label>
                                    <select name="miembro_id" id="miembro_id" class="w-full">
                                        <option value="">Seleccionar titular...</option>
                                        @foreach($miembros as $mi)
                                        @php
                                            $persona  = $mi->user?->persona;
                                            $nombreMi = trim(collect([
                                                $persona?->nombres,
                                                $persona?->apellido_paterno,
                                                $persona?->apellido_materno,
                                            ])->filter()->implode(' ')) ?: '—';
                                        @endphp
                                        <option value="{{ $mi->id }}"
                                            data-avatar="{{ $mi->user?->persona?->foto_url ?? '' }}"
                                            {{ old('miembro_id') == $mi->id ? 'selected' : '' }}>{{ $nombreMi }}</option>
                                        @endforeach
                                    </select>
                                    @error('miembro_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div class="col-span-12 sm:col-span-6 flex items-center gap-[12px] pt-[28px]">
                                    <span class="text-black dark:text-white font-medium">¿Cuenta mancomunada?</span>
                                    <label class="relative cursor-pointer">
                                        <input type="hidden" name="es_mancomunada" value="0">
                                        <input type="checkbox" name="es_mancomunada" value="1" class="sr-only peer" {{ old('es_mancomunada') ? 'checked' : '' }}>
                                        <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 3: Detalle del Producto (bloque dinámico) ──── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">tune</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">3. Detalle del Producto</h6>
                            </div>

                            <p id="sinTipoMsg" class="text-sm text-gray-400 italic">Selecciona un tipo de producto para ver sus campos específicos.</p>

                            <!-- CUENTA -->
                            <div id="bloqueCuenta" class="dynamic-block hidden grid grid-cols-12 gap-[20px] md:gap-[25px]">
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Número de Cuenta</label>
                                    <input type="text" name="numero_cuenta" value="{{ old('numero_cuenta') }}" maxlength="255"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">CCI</label>
                                    <input type="text" name="cci" value="{{ old('cci') }}" maxlength="255"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500 font-mono">
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Saldo Actual</label>
                                    <input type="number" step="0.01" name="saldo_actual" value="{{ old('saldo_actual') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                            </div>

                            <!-- TARJETA -->
                            <div id="bloqueTarjeta" class="dynamic-block hidden grid grid-cols-12 gap-[20px] md:gap-[25px]">
                                <div class="col-span-12 sm:col-span-3">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Últimos 4 Dígitos</label>
                                    <input type="text" name="ultimos_digitos" value="{{ old('ultimos_digitos') }}" maxlength="4" inputmode="numeric"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500 font-mono">
                                </div>
                                <div class="col-span-12 sm:col-span-3">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Línea de Crédito</label>
                                    <input type="number" step="0.01" name="linea_credito" value="{{ old('linea_credito') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12 sm:col-span-3">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Día de Corte</label>
                                    <input type="number" min="1" max="31" name="dia_corte" value="{{ old('dia_corte') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12 sm:col-span-3">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Día de Pago</label>
                                    <input type="number" min="1" max="31" name="dia_pago" value="{{ old('dia_pago') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Producto Padre
                                        <span class="text-xs text-gray-400 font-normal ml-[4px]">(si es adicional/débito de otra cuenta o tarjeta)</span>
                                    </label>
                                    <select name="producto_padre_id"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                        <option value="">Ninguno</option>
                                        @foreach($productosPadre as $pp)
                                        <option value="{{ $pp->id }}" {{ old('producto_padre_id') == $pp->id ? 'selected' : '' }}>
                                            {{ $pp->alias }} — {{ $pp->entidadFinanciera?->nombre_comercial_resuelto }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- PRÉSTAMO -->
                            <div id="bloquePrestamo" class="dynamic-block hidden grid grid-cols-12 gap-[20px] md:gap-[25px]">
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Saldo Actual (capital pendiente)</label>
                                    <input type="number" step="0.01" name="saldo_actual" value="{{ old('saldo_actual') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Cuota</label>
                                    <input type="number" step="0.01" name="cuota" value="{{ old('cuota') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12 sm:col-span-4">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Plazo (meses)</label>
                                    <input type="number" min="0" name="plazo_meses" value="{{ old('plazo_meses') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12 sm:col-span-4">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">TCEA (%)</label>
                                    <input type="number" step="0.001" name="tasa_tcea" value="{{ old('tasa_tcea') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12 sm:col-span-4">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Apertura</label>
                                    <input type="date" name="fecha_apertura" value="{{ old('fecha_apertura') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                            </div>

                            <!-- PLAZO FIJO -->
                            <div id="bloquePlazoFijo" class="dynamic-block hidden grid grid-cols-12 gap-[20px] md:gap-[25px]">
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Monto</label>
                                    <input type="number" step="0.01" name="saldo_actual" value="{{ old('saldo_actual') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">TEA (%)</label>
                                    <input type="number" step="0.001" name="tasa_tea" value="{{ old('tasa_tea') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Apertura</label>
                                    <input type="date" name="fecha_apertura" value="{{ old('fecha_apertura') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Vencimiento</label>
                                    <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                            </div>

                            <!-- CTS / FONDO AFP -->
                            <div id="bloqueCtsAfp" class="dynamic-block hidden grid grid-cols-12 gap-[20px] md:gap-[25px]">
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Saldo Actual</label>
                                    <input type="number" step="0.01" name="saldo_actual" value="{{ old('saldo_actual') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Apertura</label>
                                    <input type="date" name="fecha_apertura" value="{{ old('fecha_apertura') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                            </div>
                        </div>

                        {{-- ── SECCIÓN 4: Notas ────────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">notes</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">4. Notas</h6>
                            </div>
                            <div class="max-w-2xl">
                                <textarea name="notas" rows="3"
                                    placeholder="Información adicional del producto..."
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500 resize-none">{{ old('notas') }}</textarea>
                                @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-[20px] flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.productos-financieros.index') }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Registrar Producto
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
        function fmtTitular(opt) {
            if (!opt.id) return opt.text;
            var av  = $(opt.element).data('avatar');
            var ini = opt.text.charAt(0).toUpperCase();
            if (av) {
                return $('<span style="display:flex;align-items:center;gap:8px"><img src="' + av + '" style="width:26px;height:26px;border-radius:50%;object-fit:cover;">' + opt.text + '</span>');
            }
            return $('<span style="display:flex;align-items:center;gap:8px"><span style="width:26px;height:26px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700">' + ini + '</span>' + opt.text + '</span>');
        }
        $(function() {
            $('#miembro_id').select2({
                width: '100%',
                placeholder: 'Seleccionar titular...',
                templateResult: fmtTitular,
                templateSelection: fmtTitular,
            });
        });
        </script>

        <script>
            // ── Mapeo naturaleza/nombre de tipo -> bloque dinámico ───────
            function resolverBloque(naturaleza, nombre) {
                const n = (nombre || '').toLowerCase();
                if (n.includes('tarjeta')) return 'bloqueTarjeta';
                if (n.includes('préstamo') || n.includes('prestamo')) return 'bloquePrestamo';
                if (n.includes('plazo fijo')) return 'bloquePlazoFijo';
                if (n.includes('cts') || n.includes('afp')) return 'bloqueCtsAfp';
                if (n.includes('cuenta')) return 'bloqueCuenta';
                return null;
            }

            function mostrarBloqueDinamico(bloqueId) {
                const bloques  = document.querySelectorAll('.dynamic-block');
                const sinTipo  = document.getElementById('sinTipoMsg');

                bloques.forEach(b => {
                    const esActivo = b.id === bloqueId;
                    b.classList.toggle('hidden', !esActivo);
                    b.querySelectorAll('input, select').forEach(el => { el.disabled = !esActivo; });
                });

                sinTipo.classList.toggle('hidden', !!bloqueId);
            }

            // ── Custom select Entidad Financiera ─────────────────────────
            (function () {
                const wrapper  = document.getElementById('entidadWrapper');
                const trigger  = document.getElementById('entidadTrigger');
                const dropdown = document.getElementById('entidadDropdown');
                const chevron  = document.getElementById('entidadChevron');
                const buscar   = document.getElementById('entidadBuscar');
                const hidden   = document.getElementById('entidad_financiera_id');
                const label    = document.getElementById('entidadLabel');
                const opciones = document.querySelectorAll('.entidad-opcion');

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
                    const iconContainer = document.getElementById('entidadIconContainer');
                    if (id) {
                        label.textContent = nombre;
                        label.className = 'text-black dark:text-white text-sm flex-1 truncate';
                        iconContainer.innerHTML = logo
                            ? `<img src="${logo}" style="width:24px;height:24px;border-radius:4px;object-fit:cover;">`
                            : '<i class="material-symbols-outlined !text-[20px] text-primary-500">account_balance</i>';
                    } else {
                        label.textContent = 'Seleccionar entidad financiera...';
                        label.className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                        iconContainer.innerHTML = '<i class="material-symbols-outlined !text-[20px] text-gray-400">account_balance</i>';
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

            // ── Custom select Tipo de Producto ───────────────────────────
            (function () {
                const wrapper  = document.getElementById('tipoWrapper');
                const trigger  = document.getElementById('tipoTrigger');
                const dropdown = document.getElementById('tipoDropdown');
                const chevron  = document.getElementById('tipoChevron');
                const buscar   = document.getElementById('tipoBuscar');
                const hidden   = document.getElementById('tipo_producto_financiero_id');
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
                function aplicar(id, nombre, icono, naturaleza) {
                    hidden.value = id;
                    const iconContainer = document.getElementById('tipoIconContainer');
                    if (id) {
                        label.textContent = nombre;
                        label.className = 'text-black dark:text-white text-sm flex-1 truncate';
                        iconContainer.innerHTML = `<i class="material-symbols-outlined !text-[20px] text-primary-500">${icono || 'category'}</i>`;
                    } else {
                        label.textContent = 'Seleccionar tipo...';
                        label.className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                        iconContainer.innerHTML = '<i class="material-symbols-outlined !text-[20px] text-gray-400">category</i>';
                    }
                    mostrarBloqueDinamico(id ? resolverBloque(naturaleza, nombre) : null);
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
                        aplicar(li.dataset.id, li.dataset.nombre, li.dataset.icono, li.dataset.naturaleza);
                        cerrar();
                    });
                });
                document.addEventListener('click', e => { if (!wrapper.contains(e.target)) cerrar(); });

                const initId = hidden.value;
                if (initId) {
                    const m = [...opciones].find(li => li.dataset.id === initId);
                    if (m) aplicar(m.dataset.id, m.dataset.nombre, m.dataset.icono, m.dataset.naturaleza);
                } else {
                    mostrarBloqueDinamico(null);
                }
            })();
        </script>
    </body>
</html>
