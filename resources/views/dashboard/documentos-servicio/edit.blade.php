<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Editar Documento de Servicio</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Editar Documento</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.documentos-servicio.index', ['propiedad' => $documento->cuenta?->propiedad_id, 'cuenta' => $documento->cuenta_id]) }}" class="transition-all hover:text-primary-500">Recibos y Facturas</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.documentos-servicio.show', $documento) }}" class="transition-all hover:text-primary-500">Detalle</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <i class="material-symbols-outlined !text-[20px] text-primary-500 mr-[8px]">edit_document</i>
                    <h5 class="!mb-0">Editar Documento de Servicio</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.documentos-servicio.update', $documento) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- ─── SECCIÓN 1: Cuenta (fija) ─────────────────────────── --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">electrical_services</i>
                            Cuenta de Servicio
                        </h6>

                        @php
                        $cuenta  = $documento->cuenta;
                        $empresa = $cuenta?->proveedor?->empresa;
                        $inicial = mb_strtoupper(mb_substr($empresa?->razon_social ?? $cuenta?->proveedor?->nombre_comercial ?? '?', 0, 1));
                        @endphp

                        <div class="mb-[25px] p-[16px] rounded-md bg-gray-50 dark:bg-[#15203c] border border-gray-200 dark:border-[#172036]">
                            <div class="flex items-center gap-[12px]">
                                @if($empresa?->logo_url)
                                    <img src="{{ Storage::url($empresa->logo_url) }}" class="w-[40px] h-[40px] rounded-full object-cover flex-shrink-0" alt="">
                                @else
                                    <span class="w-[40px] h-[40px] rounded-full flex items-center justify-center text-base font-bold flex-shrink-0 bg-primary-100 text-primary-700">{{ $inicial }}</span>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-semibold text-black dark:text-white text-sm">
                                        {{ $empresa?->razon_social ?? $cuenta?->proveedor?->nombre_comercial ?? '—' }}
                                        <span class="font-normal text-gray-500">· {{ $cuenta?->proveedor?->tipoServicio?->nombre ?? 'Servicio' }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500 font-mono mt-[2px]">{{ $cuenta?->numero_cuenta }}</p>
                                </div>
                                <span class="ml-auto text-xs text-gray-400 flex items-center gap-[4px]">
                                    <i class="material-symbols-outlined !text-[14px]">{{ $cuenta?->propiedad?->tipoInmueble?->icono ?? 'home' }}</i>
                                    {{ $cuenta?->propiedad?->alias ?? '—' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-[10px] flex items-center gap-[4px]">
                                <i class="material-symbols-outlined !text-[12px]">lock</i>
                                La cuenta no se puede cambiar en este formulario.
                            </p>
                        </div>

                        {{-- ─── SECCIÓN 2: Datos del documento ───────────────────── --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">description</i>
                            Datos del Documento
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">

                            {{-- Tipo de documento — custom select --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Tipo de Documento <span class="text-danger-500">*</span></label>
                                <div class="relative" id="tipDocWrapper">
                                    <div id="tipDocTrigger" class="h-[55px] flex items-center rounded-md border {{ $errors->has('tipo_documento_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400" id="tipDocIconPreview">description</i>
                                        <span id="tipDocLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Selecciona tipo...</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="tipDocChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="tipo_documento_id" id="tipo_documento_id" value="{{ old('tipo_documento_id', $documento->tipo_documento_id) }}">
                                    <div id="tipDocDropdown" class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <input type="text" id="tipDocBuscar" placeholder="Buscar tipo..." class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                        </div>
                                        <ul class="max-h-[210px] overflow-y-auto py-[4px]">
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

                            {{-- Visibilidad — custom select --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Visibilidad <span class="text-danger-500">*</span></label>
                                <div class="relative" id="visWrapper">
                                    <div id="visTrigger" class="h-[55px] flex items-center rounded-md border {{ $errors->has('visibilidad_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400" id="visIconPreview">visibility</i>
                                        <span id="visLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Selecciona visibilidad...</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="visChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="visibilidad_id" id="visibilidad_id" value="{{ old('visibilidad_id', $documento->visibilidad_id) }}">
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
                                <input type="date" name="periodo_inicio" value="{{ old('periodo_inicio', $documento->periodo_inicio?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('periodo_inicio') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('periodo_inicio')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Período Fin <span class="text-danger-500">*</span></label>
                                <input type="date" name="periodo_fin" value="{{ old('periodo_fin', $documento->periodo_fin?->format('Y-m-d')) }}"
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
                                            <option value="{{ $mon->id }}" {{ old('moneda_id', $documento->moneda_id) == $mon->id ? 'selected' : '' }}>{{ $mon->codigo }} {{ $mon->simbolo }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="monto_total" value="{{ old('monto_total', $documento->monto_total) }}" step="0.01" min="0" placeholder="0.00"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('monto_total') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                </div>
                                @error('moneda_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                @error('monto_total')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Fecha vencimiento --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Vencimiento</label>
                                <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', $documento->fecha_vencimiento?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_vencimiento') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_vencimiento')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Estado de pago --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Estado de Pago <span class="text-danger-500">*</span></label>
                                <select name="estado_pago_id" id="sel_estado_pago"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('estado_pago_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                    <option value="">Selecciona estado...</option>
                                    @foreach($estadosPago as $est)
                                        <option value="{{ $est->id }}" {{ old('estado_pago_id', $documento->estado_pago_id) == $est->id ? 'selected' : '' }}>{{ $est->nombre }}</option>
                                    @endforeach
                                </select>
                                <div id="estadoPagoBadge" class="mt-[8px] min-h-[24px]"></div>
                                @error('estado_pago_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Método de pago — custom select con logo --}}
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Método de Pago</label>
                                <div class="relative" id="metPagoWrapper">
                                    <div id="metPagoTrigger" class="h-[55px] flex items-center rounded-md border {{ $errors->has('metodo_pago_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <span id="metPagoIconArea"><i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400">payment</i></span>
                                        <span id="metPagoLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Sin especificar</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="metPagoChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="metodo_pago_id" id="metodo_pago_id" value="{{ old('metodo_pago_id', $documento->metodo_pago_id ?? '') }}">
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
                                <input type="date" name="fecha_pago" value="{{ old('fecha_pago', $documento->fecha_pago?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_pago') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_pago')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            {{-- Notas --}}
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Notas</label>
                                <textarea name="notas" rows="3" placeholder="Información adicional..."
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[12px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('notas', $documento->notas) }}</textarea>
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
                                <label class="mb-[4px] text-black dark:text-white font-medium block">Comprobante de Pago</label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-[10px]">Foto del voucher, captura de transferencia, etc.</p>

                                @if($documento->archivo_url)
                                @php $archKb = round(($documento->tamano_bytes ?? 0) / 1024, 1); $archSize = $archKb > 1024 ? round($archKb/1024,2).' MB' : $archKb.' KB'; $archUrl = asset('storage/'.$documento->archivo_url); $archImg = in_array(strtolower($documento->extension ?? ''), ['jpg','jpeg','png','webp']); @endphp
                                <div class="mb-[8px] p-[12px] rounded-md bg-gray-50 dark:bg-[#15203c] border border-gray-200 dark:border-[#172036] flex items-center gap-[10px]">
                                    @if($archImg)<img src="{{ $archUrl }}" class="h-[40px] w-[40px] object-cover rounded flex-shrink-0" alt="">
                                    @else<i class="material-symbols-outlined !text-[36px] text-danger-500 flex-shrink-0">picture_as_pdf</i>@endif
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">{{ $documento->extension }}</p>
                                        <p class="text-xs text-gray-500">{{ $archSize }}</p>
                                    </div>
                                    <a href="{{ $archUrl }}" target="_blank" class="shrink-0 text-xs text-primary-500 hover:underline flex items-center gap-[3px]"><i class="material-symbols-outlined !text-[13px]">open_in_new</i>Ver</a>
                                </div>
                                <p class="text-xs text-warning-600 bg-warning-50 border border-warning-200 px-[10px] py-[6px] rounded-md mb-[8px] flex items-center gap-[4px]">
                                    <i class="material-symbols-outlined !text-[13px]">info</i>
                                    Subir un nuevo archivo reemplazará el actual y guardará el historial.
                                </p>
                                @endif

                                <label for="archivo" class="flex flex-col items-center justify-center w-full h-[110px] border-2 border-dashed rounded-md cursor-pointer transition-all {{ $errors->has('archivo') ? 'border-danger-500 bg-danger-50' : 'border-gray-300 dark:border-[#2a3a5c] bg-gray-50 dark:bg-[#15203c] hover:border-primary-400 hover:bg-primary-50 dark:hover:bg-[#1a2a4c]' }}">
                                    <div id="archDropZone" class="flex flex-col items-center justify-center">
                                        <i class="material-symbols-outlined !text-[30px] text-gray-400 mb-[4px]">cloud_upload</i>
                                        <p class="text-xs text-gray-500 text-center px-[8px]"><span class="font-medium text-primary-500">{{ $documento->archivo_url ? 'Reemplazar archivo' : 'Seleccionar archivo' }}</span></p>
                                        <p class="text-xs text-gray-400 mt-[2px]">PDF, JPG, PNG, WEBP — máx. 10 MB</p>
                                    </div>
                                    <div id="archPreviewCont" class="hidden flex-col items-center justify-center w-full px-[12px]">
                                        <img id="archImgPrev" src="#" alt="" class="hidden h-[50px] object-contain rounded mb-[3px]">
                                        <div id="archPdfPrev" class="hidden flex items-center gap-[8px]">
                                            <i class="material-symbols-outlined !text-[28px] text-danger-500">picture_as_pdf</i>
                                            <div class="text-left min-w-0"><p id="archPdfName" class="font-medium text-xs text-black dark:text-white truncate"></p><p id="archPdfSize" class="text-xs text-gray-500"></p></div>
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

                                @if($documento->documento_url)
                                @php $docKb = round(($documento->documento_tamano_bytes ?? 0) / 1024, 1); $docSize = $docKb > 1024 ? round($docKb/1024,2).' MB' : $docKb.' KB'; $docUrl = asset('storage/'.$documento->documento_url); $docImg = in_array(strtolower($documento->documento_extension ?? ''), ['jpg','jpeg','png','webp']); @endphp
                                <div class="mb-[8px] p-[12px] rounded-md bg-gray-50 dark:bg-[#15203c] border border-gray-200 dark:border-[#172036] flex items-center gap-[10px]">
                                    @if($docImg)<img src="{{ $docUrl }}" class="h-[40px] w-[40px] object-cover rounded flex-shrink-0" alt="">
                                    @else<i class="material-symbols-outlined !text-[36px] text-danger-500 flex-shrink-0">picture_as_pdf</i>@endif
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">{{ $documento->documento_extension }}</p>
                                        <p class="text-xs text-gray-500">{{ $docSize }}</p>
                                    </div>
                                    <a href="{{ $docUrl }}" target="_blank" class="shrink-0 text-xs text-primary-500 hover:underline flex items-center gap-[3px]"><i class="material-symbols-outlined !text-[13px]">open_in_new</i>Ver</a>
                                </div>
                                <p class="text-xs text-warning-600 bg-warning-50 border border-warning-200 px-[10px] py-[6px] rounded-md mb-[8px] flex items-center gap-[4px]">
                                    <i class="material-symbols-outlined !text-[13px]">info</i>
                                    Subir un nuevo archivo reemplazará el actual y guardará el historial.
                                </p>
                                @endif

                                <label for="documento" class="flex flex-col items-center justify-center w-full h-[110px] border-2 border-dashed rounded-md cursor-pointer transition-all {{ $errors->has('documento') ? 'border-danger-500 bg-danger-50' : 'border-gray-300 dark:border-[#2a3a5c] bg-gray-50 dark:bg-[#15203c] hover:border-primary-400 hover:bg-primary-50 dark:hover:bg-[#1a2a4c]' }}">
                                    <div id="docDropZone" class="flex flex-col items-center justify-center">
                                        <i class="material-symbols-outlined !text-[30px] text-gray-400 mb-[4px]">picture_as_pdf</i>
                                        <p class="text-xs text-gray-500 text-center px-[8px]"><span class="font-medium text-primary-500">{{ $documento->documento_url ? 'Reemplazar documento' : 'Seleccionar documento' }}</span></p>
                                        <p class="text-xs text-gray-400 mt-[2px]">PDF, JPG, PNG, WEBP — máx. 10 MB</p>
                                    </div>
                                    <div id="docPreviewCont" class="hidden flex-col items-center justify-center w-full px-[12px]">
                                        <img id="docImgPrev" src="#" alt="" class="hidden h-[50px] object-contain rounded mb-[3px]">
                                        <div id="docPdfPrev" class="hidden flex items-center gap-[8px]">
                                            <i class="material-symbols-outlined !text-[28px] text-danger-500">picture_as_pdf</i>
                                            <div class="text-left min-w-0"><p id="docPdfName" class="font-medium text-xs text-black dark:text-white truncate"></p><p id="docPdfSize" class="text-xs text-gray-500"></p></div>
                                        </div>
                                        <p id="docImgSize" class="hidden text-xs text-gray-500 mt-[2px]"></p>
                                    </div>
                                    <input id="documento" name="documento" type="file" accept=".pdf,.jpg,.jpeg,.png,.webp" class="hidden" onchange="handleDocumentoPreview(this)">
                                </label>
                                @error('documento')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.documentos-servicio.show', $documento) }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
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
            function abrir() { dropdown.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; if (buscar) { buscar.value = ''; opciones.forEach(function(li) { li.style.display = ''; }); setTimeout(function() { buscar.focus(); }, 50); } }
            function cerrar() { dropdown.classList.add('hidden'); chevron.style.transform = ''; }
            function aplicar(id, nombre, icono) {
                hidden.value = id;
                if (id) { label.textContent = nombre; label.className = 'text-black dark:text-white text-sm flex-1 truncate'; iconPrev.textContent = icono; iconPrev.className = 'material-symbols-outlined !text-[20px] mr-[8px] text-primary-500'; }
                else { label.textContent = cfg.placeholder; label.className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate'; iconPrev.textContent = cfg.defaultIcon; iconPrev.className = 'material-symbols-outlined !text-[20px] mr-[8px] text-gray-400'; }
            }
            trigger.addEventListener('click', function() { dropdown.classList.contains('hidden') ? abrir() : cerrar(); });
            if (buscar) { buscar.addEventListener('input', function() { var q = this.value.toLowerCase(); opciones.forEach(function(li) { li.style.display = li.dataset.nombre.toLowerCase().includes(q) ? '' : 'none'; }); }); }
            opciones.forEach(function(li) { li.addEventListener('click', function() { aplicar(this.dataset.id, this.dataset.nombre, this.dataset.icono); cerrar(); }); });
            document.addEventListener('click', function(e) { if (!wrapper.contains(e.target)) cerrar(); });
            var initId = hidden.value;
            if (initId) { var found = Array.from(opciones).find(function(li) { return li.dataset.id === initId; }); if (found) aplicar(found.dataset.id, found.dataset.nombre, found.dataset.icono); }
        }

        initCustomSelect({ wrapperId:'tipDocWrapper', triggerId:'tipDocTrigger', dropdownId:'tipDocDropdown', chevronId:'tipDocChevron', buscarId:'tipDocBuscar', hiddenId:'tipo_documento_id', labelId:'tipDocLabel', iconPrevId:'tipDocIconPreview', opClass:'tip-doc-op', placeholder:'Selecciona tipo...', defaultIcon:'description' });
        initCustomSelect({ wrapperId:'visWrapper', triggerId:'visTrigger', dropdownId:'visDropdown', chevronId:'visChevron', buscarId:'visBuscar', hiddenId:'visibilidad_id', labelId:'visLabel', iconPrevId:'visIconPreview', opClass:'vis-op', placeholder:'Selecciona visibilidad...', defaultIcon:'visibility' });

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
            function abrir() { dropdown.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; buscar.value = ''; opciones.forEach(function(li) { li.style.display = ''; }); setTimeout(function() { buscar.focus(); }, 50); }
            function cerrar() { dropdown.classList.add('hidden'); chevron.style.transform = ''; }
            function aplicar(id, nombre, icono, logo) {
                hidden.value = id;
                if (id) { label.textContent = nombre; label.className = 'text-black dark:text-white text-sm flex-1 truncate'; iconArea.innerHTML = logo ? '<img src="'+logo+'" class="w-[20px] h-[20px] rounded-full object-cover mr-[8px] flex-shrink-0" alt="">' : '<i class="material-symbols-outlined !text-[20px] mr-[8px] text-primary-500">'+icono+'</i>'; }
                else { label.textContent = 'Sin especificar'; label.className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate'; iconArea.innerHTML = '<i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400">payment</i>'; }
            }
            trigger.addEventListener('click', function() { dropdown.classList.contains('hidden') ? abrir() : cerrar(); });
            buscar.addEventListener('input', function() { var q = this.value.toLowerCase(); opciones.forEach(function(li) { li.style.display = li.dataset.nombre.toLowerCase().includes(q) ? '' : 'none'; }); });
            opciones.forEach(function(li) { li.addEventListener('click', function() { aplicar(this.dataset.id, this.dataset.nombre, this.dataset.icono, this.dataset.logo); cerrar(); }); });
            document.addEventListener('click', function(e) { if (!wrapper.contains(e.target)) cerrar(); });
            var initId = hidden.value;
            if (initId) { var found = Array.from(opciones).find(function(li) { return li.dataset.id === initId; }); if (found) aplicar(found.dataset.id, found.dataset.nombre, found.dataset.icono, found.dataset.logo); }
        })();

        (function () {
            var estadosData = @json($estadosPagoJson);
            var sel = document.getElementById('sel_estado_pago');
            var badgeCont = document.getElementById('estadoPagoBadge');
            function actualizar() { var id = sel.value; var estado = estadosData.find(function(e) { return e.id === id; }); badgeCont.innerHTML = (estado && estado.color) ? '<span class="inline-block px-[12px] py-[3px] rounded-full text-white text-xs font-medium" style="background-color:'+estado.color+'">'+estado.nombre+'</span>' : ''; }
            sel.addEventListener('change', actualizar);
            actualizar();
        })();

        function buildPreview(prefix, file) {
            if (!file) return;
            if (file.size > 10 * 1024 * 1024) { alert('El archivo no puede superar los 10 MB.'); return false; }
            var kb = Math.round(file.size / 1024 * 10) / 10;
            var size = kb > 1024 ? (Math.round(kb / 1024 * 100) / 100) + ' MB' : kb + ' KB';
            document.getElementById(prefix + 'DropZone').classList.add('hidden');
            var cont = document.getElementById(prefix + 'PreviewCont');
            cont.classList.remove('hidden'); cont.classList.add('flex');
            var imgPrev = document.getElementById(prefix + 'ImgPrev');
            var pdfPrev = document.getElementById(prefix + 'PdfPrev');
            var imgSize = document.getElementById(prefix + 'ImgSize');
            imgPrev.classList.add('hidden'); pdfPrev.classList.add('hidden'); imgSize.classList.add('hidden');
            if (file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function(e) { imgPrev.src = e.target.result; imgPrev.classList.remove('hidden'); imgSize.textContent = file.name + ' (' + size + ')'; imgSize.classList.remove('hidden'); };
                reader.readAsDataURL(file);
            } else {
                document.getElementById(prefix + 'PdfName').textContent = file.name;
                document.getElementById(prefix + 'PdfSize').textContent = size;
                pdfPrev.classList.remove('hidden'); pdfPrev.classList.add('flex');
            }
            return true;
        }
        function handleArchivoPreview(input) { if (!buildPreview('arch', input.files[0])) input.value = ''; }
        function handleDocumentoPreview(input) { if (!buildPreview('doc', input.files[0])) input.value = ''; }
        </script>
    </body>
</html>
