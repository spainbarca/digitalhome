<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Subir Documento de Servicio</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Subir Documento de Servicio</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.documentos-servicio.index') }}" class="transition-all hover:text-primary-500">Recibos y Facturas</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nuevo</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <i class="material-symbols-outlined !text-[20px] text-primary-500 mr-[8px]">upload_file</i>
                    <h5 class="!mb-0">Subir Documento de Servicio</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.documentos-servicio.store') }}" enctype="multipart/form-data">
                        @csrf

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- ─── SECCIÓN 1: Cuenta ─────────────────────────────────── --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">electrical_services</i>
                            Cuenta de Servicio
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Propiedad <span class="text-danger-500">*</span></label>
                                <select id="sel_propiedad" name="_propiedad"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500"
                                    {{ $cuentaSeleccionada ? 'disabled' : '' }}>
                                    <option value="">Selecciona una propiedad...</option>
                                    @foreach($propiedades as $prop)
                                        <option value="{{ $prop->id }}" {{ $propiedadSeleccionada?->id === $prop->id ? 'selected' : '' }}>
                                            {{ $prop->alias }}@if($prop->direccion) — {{ Str::limit($prop->direccion, 40) }}@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Cuenta de Servicio <span class="text-danger-500">*</span></label>
                                @if($cuentaSeleccionada)
                                    <input type="hidden" name="cuenta_id" id="cuenta_id" value="{{ $cuentaSeleccionada->id }}">
                                    <div class="h-[55px] flex items-center rounded-md border border-gray-200 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c] px-[14px] text-sm">
                                        <i class="material-symbols-outlined !text-[18px] mr-[8px] text-primary-500">electrical_services</i>
                                        <span class="font-medium text-black dark:text-white">{{ $cuentaSeleccionada->proveedor?->tipoServicio?->nombre ?? 'Servicio' }}</span>
                                        <span class="font-mono ml-[8px] text-gray-500">{{ $cuentaSeleccionada->numero_cuenta }}</span>
                                    </div>
                                @else
                                    <select name="cuenta_id" id="sel_cuenta"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('cuenta_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500"
                                        {{ !$propiedadSeleccionada ? 'disabled' : '' }}>
                                        <option value="">{{ $propiedadSeleccionada ? 'Selecciona una cuenta...' : 'Primero selecciona una propiedad' }}</option>
                                        @foreach($cuentas as $c)
                                            <option value="{{ $c->id }}" {{ old('cuenta_id') == $c->id ? 'selected' : '' }}>
                                                {{ $c->proveedor?->tipoServicio?->nombre ?? 'Servicio' }} — {{ $c->numero_cuenta }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cuenta_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                @endif
                            </div>
                        </div>

                        {{-- ─── SECCIÓN 2: Datos del documento ───────────────────── --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">description</i>
                            Datos del Documento
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">

                            {{-- Tipo de documento — custom select con ícono --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Tipo de Documento <span class="text-danger-500">*</span></label>
                                <div class="relative" id="tipDocWrapper">
                                    <div id="tipDocTrigger" class="h-[55px] flex items-center rounded-md border {{ $errors->has('tipo_documento_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400" id="tipDocIconPreview">description</i>
                                        <span id="tipDocLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Selecciona tipo...</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="tipDocChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="tipo_documento_id" id="tipo_documento_id" value="{{ old('tipo_documento_id', '') }}">
                                    <div id="tipDocDropdown" class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <input type="text" id="tipDocBuscar" placeholder="Buscar tipo..." class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                        </div>
                                        <ul id="tipDocOpciones" class="max-h-[210px] overflow-y-auto py-[4px]">
                                            @foreach($tiposDocumento as $tipo)
                                            <li class="tip-doc-op flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $tipo->id }}" data-nombre="{{ $tipo->nombre }}" data-icono="{{ $tipo->icono ?? 'description' }}">
                                                <i class="material-symbols-outlined !text-[18px] text-primary-500">{{ $tipo->icono ?? 'description' }}</i>
                                                <span class="text-sm text-black dark:text-white">{{ $tipo->nombre }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @error('tipo_documento_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Visibilidad — custom select con ícono --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Visibilidad <span class="text-danger-500">*</span></label>
                                <div class="relative" id="visWrapper">
                                    <div id="visTrigger" class="h-[55px] flex items-center rounded-md border {{ $errors->has('visibilidad_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400" id="visIconPreview">visibility</i>
                                        <span id="visLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Selecciona visibilidad...</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="visChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="visibilidad_id" id="visibilidad_id" value="{{ old('visibilidad_id', '') }}">
                                    <div id="visDropdown" class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <input type="text" id="visBuscar" placeholder="Buscar..." class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                        </div>
                                        <ul class="max-h-[210px] overflow-y-auto py-[4px]">
                                            @foreach($visibilidades as $vis)
                                            <li class="vis-op flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $vis->id }}" data-nombre="{{ $vis->nombre }}" data-icono="{{ $vis->icono ?? 'visibility' }}">
                                                <i class="material-symbols-outlined !text-[18px] text-primary-500">{{ $vis->icono ?? 'visibility' }}</i>
                                                <span class="text-sm text-black dark:text-white">{{ $vis->nombre }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @error('visibilidad_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Período inicio / fin --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Período Inicio <span class="text-danger-500">*</span></label>
                                <input type="date" name="periodo_inicio" value="{{ old('periodo_inicio') }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('periodo_inicio') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('periodo_inicio')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Período Fin <span class="text-danger-500">*</span></label>
                                <input type="date" name="periodo_fin" value="{{ old('periodo_fin') }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('periodo_fin') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('periodo_fin')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Monto + Moneda --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Monto Total <span class="text-danger-500">*</span></label>
                                <div class="flex gap-[8px]">
                                    <select name="moneda_id" class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('moneda_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[10px] outline-0 cursor-pointer transition-all focus:border-primary-500 w-[110px] flex-shrink-0">
                                        <option value="">—</option>
                                        @foreach($monedas as $mon)
                                            <option value="{{ $mon->id }}" {{ old('moneda_id') == $mon->id ? 'selected' : '' }}>{{ $mon->codigo }} {{ $mon->simbolo }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="monto_total" value="{{ old('monto_total') }}" step="0.01" min="0" placeholder="0.00"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('monto_total') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                </div>
                                @error('moneda_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                @error('monto_total')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Fecha vencimiento --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Vencimiento</label>
                                <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_vencimiento') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_vencimiento')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Estado de pago — select estándar + badge live --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Estado de Pago <span class="text-danger-500">*</span></label>
                                <select name="estado_pago_id" id="sel_estado_pago"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('estado_pago_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                    <option value="">Selecciona estado...</option>
                                    @foreach($estadosPago as $est)
                                        <option value="{{ $est->id }}" {{ old('estado_pago_id') == $est->id ? 'selected' : '' }}>{{ $est->nombre }}</option>
                                    @endforeach
                                </select>
                                <div id="estadoPagoBadge" class="mt-[8px] min-h-[24px]"></div>
                                @error('estado_pago_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Método de pago — custom select con logo/ícono --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Método de Pago</label>
                                <div class="relative" id="metPagoWrapper">
                                    <div id="metPagoTrigger" class="h-[55px] flex items-center rounded-md border {{ $errors->has('metodo_pago_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <span id="metPagoIconArea"><i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400">payment</i></span>
                                        <span id="metPagoLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Sin especificar</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="metPagoChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="metodo_pago_id" id="metodo_pago_id" value="{{ old('metodo_pago_id', '') }}">
                                    <div id="metPagoDropdown" class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <input type="text" id="metPagoBuscar" placeholder="Buscar método..." class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                        </div>
                                        <ul class="max-h-[210px] overflow-y-auto py-[4px]">
                                            <li class="met-pago-op flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="" data-nombre="Sin especificar" data-icono="payment" data-logo="">
                                                <i class="material-symbols-outlined !text-[18px] text-gray-400">payment</i>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">Sin especificar</span>
                                            </li>
                                            @foreach($metodosPago as $met)
                                            <li class="met-pago-op flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $met->id }}" data-nombre="{{ $met->nombre }}"
                                                data-icono="{{ $met->icono ?? 'payment' }}"
                                                data-logo="{{ $met->logo ? asset('storage/' . $met->logo) : '' }}">
                                                @if($met->logo)
                                                    <img src="{{ asset('storage/' . $met->logo) }}" class="w-[20px] h-[20px] rounded-full object-cover flex-shrink-0" alt="">
                                                @else
                                                    <i class="material-symbols-outlined !text-[18px] text-primary-500">{{ $met->icono ?? 'payment' }}</i>
                                                @endif
                                                <span class="text-sm text-black dark:text-white">{{ $met->nombre }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @error('metodo_pago_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Fecha de pago --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Pago</label>
                                <input type="date" name="fecha_pago" value="{{ old('fecha_pago') }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_pago') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_pago')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Notas --}}
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Notas</label>
                                <textarea name="notas" rows="3" placeholder="Información adicional..."
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[12px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('notas') }}</textarea>
                                @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- ─── SECCIÓN 3: Archivos ───────────────────────────────── --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">attach_file</i>
                            Archivos
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">

                            {{-- 3A: Comprobante de pago --}}
                            <div>
                                <label class="mb-[4px] text-black dark:text-white font-medium block">
                                    Comprobante de Pago <span class="text-danger-500">*</span>
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-[10px]">Foto del voucher, captura de transferencia, etc.</p>
                                <label for="archivo" class="flex flex-col items-center justify-center w-full h-[130px] border-2 border-dashed rounded-md cursor-pointer transition-all {{ $errors->has('archivo') ? 'border-danger-500 bg-danger-50' : 'border-gray-300 dark:border-[#2a3a5c] bg-gray-50 dark:bg-[#15203c] hover:border-primary-400 hover:bg-primary-50 dark:hover:bg-[#1a2a4c]' }}">
                                    <div id="archDropZone" class="flex flex-col items-center justify-center">
                                        <i class="material-symbols-outlined !text-[36px] text-gray-400 dark:text-gray-500 mb-[6px]">receipt_long</i>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center px-[8px]"><span class="font-medium text-primary-500">Seleccionar</span> o arrastra</p>
                                        <p class="text-xs text-gray-400 mt-[2px]">PDF, JPG, PNG, WEBP — máx. 10 MB</p>
                                    </div>
                                    <div id="archPreviewCont" class="hidden flex-col items-center justify-center w-full px-[12px]">
                                        <img id="archImgPrev" src="#" alt="" class="hidden h-[60px] object-contain rounded mb-[3px]">
                                        <div id="archPdfPrev" class="hidden flex items-center gap-[8px]">
                                            <i class="material-symbols-outlined !text-[32px] text-danger-500">picture_as_pdf</i>
                                            <div class="text-left min-w-0">
                                                <p id="archPdfName" class="font-medium text-xs text-black dark:text-white truncate"></p>
                                                <p id="archPdfSize" class="text-xs text-gray-500"></p>
                                            </div>
                                        </div>
                                        <p id="archImgSize" class="hidden text-xs text-gray-500 mt-[2px]"></p>
                                    </div>
                                    <input id="archivo" name="archivo" type="file" accept=".pdf,.jpg,.jpeg,.png,.webp" class="hidden" onchange="handleArchivoPreview(this)">
                                </label>
                                @error('archivo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- 3B: Documento oficial --}}
                            <div>
                                <label class="mb-[4px] text-black dark:text-white font-medium block">Documento Oficial</label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-[10px]">PDF o imagen de la factura/recibo emitido por la empresa.</p>
                                <label for="documento" class="flex flex-col items-center justify-center w-full h-[130px] border-2 border-dashed rounded-md cursor-pointer transition-all {{ $errors->has('documento') ? 'border-danger-500 bg-danger-50' : 'border-gray-300 dark:border-[#2a3a5c] bg-gray-50 dark:bg-[#15203c] hover:border-primary-400 hover:bg-primary-50 dark:hover:bg-[#1a2a4c]' }}">
                                    <div id="docDropZone" class="flex flex-col items-center justify-center">
                                        <i class="material-symbols-outlined !text-[36px] text-gray-400 dark:text-gray-500 mb-[6px]">picture_as_pdf</i>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center px-[8px]"><span class="font-medium text-primary-500">Seleccionar</span> o arrastra</p>
                                        <p class="text-xs text-gray-400 mt-[2px]">PDF, JPG, PNG, WEBP — máx. 10 MB</p>
                                    </div>
                                    <div id="docPreviewCont" class="hidden flex-col items-center justify-center w-full px-[12px]">
                                        <img id="docImgPrev" src="#" alt="" class="hidden h-[60px] object-contain rounded mb-[3px]">
                                        <div id="docPdfPrev" class="hidden flex items-center gap-[8px]">
                                            <i class="material-symbols-outlined !text-[32px] text-danger-500">picture_as_pdf</i>
                                            <div class="text-left min-w-0">
                                                <p id="docPdfName" class="font-medium text-xs text-black dark:text-white truncate"></p>
                                                <p id="docPdfSize" class="text-xs text-gray-500"></p>
                                            </div>
                                        </div>
                                        <p id="docImgSize" class="hidden text-xs text-gray-500 mt-[2px]"></p>
                                    </div>
                                    <input id="documento" name="documento" type="file" accept=".pdf,.jpg,.jpeg,.png,.webp" class="hidden" onchange="handleDocumentoPreview(this)">
                                </label>
                                @error('documento')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- ─── SECCIÓN 4: Lectura de Consumo (colapsable) ──────── --}}
                        <div class="mt-[8px] mb-[25px] border border-gray-100 dark:border-[#172036] rounded-md overflow-hidden">
                            <div class="flex items-center justify-between px-[16px] py-[12px] cursor-pointer bg-gray-50 dark:bg-[#15203c] select-none"
                                 id="toggleLectura">
                                <div class="flex items-center gap-[8px]">
                                    <i class="material-symbols-outlined !text-[20px] text-primary-500">speed</i>
                                    <h6 class="!mb-0 text-[14px]">Lectura de Consumo
                                        <span class="text-gray-400 text-sm font-normal">(opcional)</span>
                                    </h6>
                                </div>
                                <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="iconToggleLectura">expand_more</i>
                            </div>

                            <div id="seccionLectura" class="hidden px-[16px] py-[16px]">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-[14px] flex items-center gap-[6px]">
                                    <i class="material-symbols-outlined !text-[15px]">straighten</i>
                                    Unidad: <span id="unidadLabel" class="font-medium text-black dark:text-white">—</span>
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px]">
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block text-sm">Lectura anterior</label>
                                        <input type="number" name="lectura_anterior" id="lectura_anterior"
                                               step="0.0001" min="0"
                                               value="{{ old('lectura_anterior', '') }}"
                                               placeholder="Ej: 1500.0000"
                                               class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('lectura_anterior') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                        @error('lectura_anterior')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block text-sm">Lectura actual</label>
                                        <input type="number" name="lectura_actual" id="lectura_actual"
                                               step="0.0001" min="0"
                                               value="{{ old('lectura_actual', '') }}"
                                               placeholder="Ej: 1620.0000"
                                               class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('lectura_actual') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                        @error('lectura_actual')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block text-sm">Consumo <span class="text-gray-400 font-normal text-xs">(calculado)</span></label>
                                        <div class="relative">
                                            <input type="number" name="consumo" id="consumo"
                                                   step="0.0001" min="0"
                                                   value="{{ old('consumo', '') }}"
                                                   placeholder="Auto"
                                                   readonly
                                                   class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c] px-[17px] pr-[52px] block w-full outline-0 cursor-default">
                                            <span class="absolute right-[12px] top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium pointer-events-none"
                                                  id="unidadSufijo"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.documentos-servicio.index', array_filter(['propiedad' => $propiedadSeleccionada?->id, 'cuenta' => $cuentaSeleccionada?->id])) }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Guardar Documento
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
        // ── Recarga al cambiar propiedad ─────────────────────────────────────
        (function () {
            var sel = document.getElementById('sel_propiedad');
            if (sel && !sel.disabled) {
                sel.addEventListener('change', function () {
                    if (this.value) window.location.href = '/dashboard/documentos-servicio/create?propiedad=' + this.value;
                });
            }
        })();

        // ── Custom select genérico (factory) ─────────────────────────────────
        function initCustomSelect(cfg) {
            var wrapper  = document.getElementById(cfg.wrapperId);
            var trigger  = document.getElementById(cfg.triggerId);
            var dropdown = document.getElementById(cfg.dropdownId);
            var chevron  = document.getElementById(cfg.chevronId);
            var buscar   = document.getElementById(cfg.buscarId);
            var hidden   = document.getElementById(cfg.hiddenId);
            var label    = document.getElementById(cfg.labelId);
            var iconPrev = document.getElementById(cfg.iconPrevId);
            var opciones = document.querySelectorAll('.' + cfg.opClass);

            if (!wrapper) return;

            function abrir() {
                dropdown.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
                if (buscar) { buscar.value = ''; opciones.forEach(function(li) { li.style.display = ''; }); setTimeout(function() { buscar.focus(); }, 50); }
            }
            function cerrar() { dropdown.classList.add('hidden'); chevron.style.transform = ''; }

            function aplicar(id, nombre, icono) {
                hidden.value = id;
                if (id) {
                    label.textContent = nombre;
                    label.className = 'text-black dark:text-white text-sm flex-1 truncate';
                    iconPrev.textContent = icono;
                    iconPrev.className = 'material-symbols-outlined !text-[20px] mr-[8px] text-primary-500';
                } else {
                    label.textContent = cfg.placeholder;
                    label.className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                    iconPrev.textContent = cfg.defaultIcon;
                    iconPrev.className = 'material-symbols-outlined !text-[20px] mr-[8px] text-gray-400';
                }
            }

            trigger.addEventListener('click', function() { dropdown.classList.contains('hidden') ? abrir() : cerrar(); });
            if (buscar) {
                buscar.addEventListener('input', function() {
                    var q = this.value.toLowerCase();
                    opciones.forEach(function(li) { li.style.display = li.dataset.nombre.toLowerCase().includes(q) ? '' : 'none'; });
                });
            }
            opciones.forEach(function(li) {
                li.addEventListener('click', function() { aplicar(this.dataset.id, this.dataset.nombre, this.dataset.icono); cerrar(); });
            });
            document.addEventListener('click', function(e) { if (!wrapper.contains(e.target)) cerrar(); });

            var initId = hidden.value;
            if (initId) {
                var found = Array.from(opciones).find(function(li) { return li.dataset.id === initId; });
                if (found) aplicar(found.dataset.id, found.dataset.nombre, found.dataset.icono);
            }
        }

        // Tipo Documento
        initCustomSelect({
            wrapperId: 'tipDocWrapper', triggerId: 'tipDocTrigger', dropdownId: 'tipDocDropdown',
            chevronId: 'tipDocChevron', buscarId: 'tipDocBuscar', hiddenId: 'tipo_documento_id',
            labelId: 'tipDocLabel', iconPrevId: 'tipDocIconPreview',
            opClass: 'tip-doc-op', placeholder: 'Selecciona tipo...', defaultIcon: 'description'
        });

        // Visibilidad
        initCustomSelect({
            wrapperId: 'visWrapper', triggerId: 'visTrigger', dropdownId: 'visDropdown',
            chevronId: 'visChevron', buscarId: 'visBuscar', hiddenId: 'visibilidad_id',
            labelId: 'visLabel', iconPrevId: 'visIconPreview',
            opClass: 'vis-op', placeholder: 'Selecciona visibilidad...', defaultIcon: 'visibility'
        });

        // ── Método de pago (con logo) ─────────────────────────────────────────
        (function () {
            var wrapper  = document.getElementById('metPagoWrapper');
            var trigger  = document.getElementById('metPagoTrigger');
            var dropdown = document.getElementById('metPagoDropdown');
            var chevron  = document.getElementById('metPagoChevron');
            var buscar   = document.getElementById('metPagoBuscar');
            var hidden   = document.getElementById('metodo_pago_id');
            var label    = document.getElementById('metPagoLabel');
            var iconArea = document.getElementById('metPagoIconArea');
            var opciones = document.querySelectorAll('.met-pago-op');

            function abrir() {
                dropdown.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
                buscar.value = '';
                opciones.forEach(function(li) { li.style.display = ''; });
                setTimeout(function() { buscar.focus(); }, 50);
            }
            function cerrar() { dropdown.classList.add('hidden'); chevron.style.transform = ''; }

            function aplicar(id, nombre, icono, logo) {
                hidden.value = id;
                if (id) {
                    label.textContent = nombre;
                    label.className = 'text-black dark:text-white text-sm flex-1 truncate';
                    if (logo) {
                        iconArea.innerHTML = '<img src="' + logo + '" class="w-[20px] h-[20px] rounded-full object-cover mr-[8px] flex-shrink-0" alt="">';
                    } else {
                        iconArea.innerHTML = '<i class="material-symbols-outlined !text-[20px] mr-[8px] text-primary-500">' + icono + '</i>';
                    }
                } else {
                    label.textContent = 'Sin especificar';
                    label.className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                    iconArea.innerHTML = '<i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400">payment</i>';
                }
            }

            trigger.addEventListener('click', function() { dropdown.classList.contains('hidden') ? abrir() : cerrar(); });
            buscar.addEventListener('input', function() {
                var q = this.value.toLowerCase();
                opciones.forEach(function(li) { li.style.display = li.dataset.nombre.toLowerCase().includes(q) ? '' : 'none'; });
            });
            opciones.forEach(function(li) {
                li.addEventListener('click', function() { aplicar(this.dataset.id, this.dataset.nombre, this.dataset.icono, this.dataset.logo); cerrar(); });
            });
            document.addEventListener('click', function(e) { if (!wrapper.contains(e.target)) cerrar(); });

            var initId = hidden.value;
            if (initId) {
                var found = Array.from(opciones).find(function(li) { return li.dataset.id === initId; });
                if (found) aplicar(found.dataset.id, found.dataset.nombre, found.dataset.icono, found.dataset.logo);
            }
        })();

        // ── Estado de pago — badge live ───────────────────────────────────────
        (function () {
            var estadosData = @json($estadosPagoJson);
            var sel         = document.getElementById('sel_estado_pago');
            var badgeCont   = document.getElementById('estadoPagoBadge');

            function actualizar() {
                var id     = sel.value;
                var estado = estadosData.find(function(e) { return e.id === id; });
                if (estado && estado.color) {
                    badgeCont.innerHTML = '<span class="inline-block px-[12px] py-[3px] rounded-full text-white text-xs font-medium" style="background-color:' + estado.color + '">' + estado.nombre + '</span>';
                } else {
                    badgeCont.innerHTML = '';
                }
            }
            sel.addEventListener('change', actualizar);
            actualizar();
        })();

        // ── Preview archivos ──────────────────────────────────────────────────
        function buildPreview(prefix, file) {
            if (!file) return;
            if (file.size > 10 * 1024 * 1024) { alert('El archivo no puede superar los 10 MB.'); return false; }

            var kb   = Math.round(file.size / 1024 * 10) / 10;
            var size = kb > 1024 ? (Math.round(kb / 1024 * 100) / 100) + ' MB' : kb + ' KB';

            document.getElementById(prefix + 'DropZone').classList.add('hidden');
            var cont = document.getElementById(prefix + 'PreviewCont');
            cont.classList.remove('hidden');
            cont.classList.add('flex');

            var imgPrev = document.getElementById(prefix + 'ImgPrev');
            var pdfPrev = document.getElementById(prefix + 'PdfPrev');
            var imgSize = document.getElementById(prefix + 'ImgSize');

            imgPrev.classList.add('hidden');
            pdfPrev.classList.add('hidden');
            imgSize.classList.add('hidden');

            if (file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imgPrev.src = e.target.result;
                    imgPrev.classList.remove('hidden');
                    imgSize.textContent = file.name + ' (' + size + ')';
                    imgSize.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById(prefix + 'PdfName').textContent = file.name;
                document.getElementById(prefix + 'PdfSize').textContent = size;
                pdfPrev.classList.remove('hidden');
                pdfPrev.classList.add('flex');
            }
            return true;
        }

        function handleArchivoPreview(input) {
            if (!buildPreview('arch', input.files[0])) input.value = '';
        }
        function handleDocumentoPreview(input) {
            if (!buildPreview('doc', input.files[0])) input.value = '';
        }

        // ── Lectura de consumo ────────────────────────────────────────────────
        document.getElementById('toggleLectura').addEventListener('click', function () {
            var sec  = document.getElementById('seccionLectura');
            var icon = document.getElementById('iconToggleLectura');
            sec.classList.toggle('hidden');
            icon.style.transform = sec.classList.contains('hidden') ? '' : 'rotate(180deg)';
        });

        function calcularConsumo() {
            var ant = parseFloat(document.getElementById('lectura_anterior').value) || 0;
            var act = parseFloat(document.getElementById('lectura_actual').value) || 0;
            var c   = act - ant;
            document.getElementById('consumo').value = (c >= 0) ? c.toFixed(4) : '';
        }
        document.getElementById('lectura_anterior').addEventListener('input', calcularConsumo);
        document.getElementById('lectura_actual').addEventListener('input', calcularConsumo);

        var cuentasUnidad = @json($cuentasConUnidad);

        function actualizarUnidad(cuentaId) {
            var u      = cuentasUnidad[cuentaId];
            var label  = u && (u.nombre || u.simbolo) ? (u.nombre || u.simbolo) + (u.simbolo ? ' (' + u.simbolo + ')' : '') : '—';
            var sufijo = u ? u.simbolo : '';
            document.getElementById('unidadLabel').textContent  = label;
            document.getElementById('unidadSufijo').textContent = sufijo;
        }

        // Escuchar cambio en cuenta (hidden id="cuenta_id" o select id="sel_cuenta")
        var elCuenta = document.getElementById('cuenta_id') || document.getElementById('sel_cuenta');
        if (elCuenta) {
            elCuenta.addEventListener('change', function () { actualizarUnidad(this.value); });
            if (elCuenta.value) actualizarUnidad(elCuenta.value);
        }

        // Abrir sección si hay valores previos (validación fallida)
        if (document.getElementById('lectura_anterior').value || document.getElementById('lectura_actual').value) {
            document.getElementById('seccionLectura').classList.remove('hidden');
            document.getElementById('iconToggleLectura').style.transform = 'rotate(180deg)';
        }
        </script>
    </body>
</html>
