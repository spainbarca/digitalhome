<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('partials.front.styles')
        <title>Documento de Servicio</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Detalle del Documento</h5>
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
                        Detalle
                    </li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-success-700 hover:text-success-900">
                    <i class="material-symbols-outlined !text-lg">close</i>
                </button>
            </div>
            @endif

            @php
            // Comprobante de pago
            $isImage  = in_array(strtolower($documento->extension ?? ''), ['jpg','jpeg','png','webp']);
            $isPdf    = strtolower($documento->extension ?? '') === 'pdf';
            $fileUrl  = $documento->archivo_url ? asset('storage/' . $documento->archivo_url) : null;
            $kb       = $documento->tamano_bytes ? round($documento->tamano_bytes / 1024, 1) : 0;
            $size     = $kb > 1024 ? round($kb / 1024, 2) . ' MB' : $kb . ' KB';
            // Documento oficial
            $docIsImage = in_array(strtolower($documento->documento_extension ?? ''), ['jpg','jpeg','png','webp']);
            $docIsPdf   = strtolower($documento->documento_extension ?? '') === 'pdf';
            $docUrl     = $documento->documento_url ? asset('storage/' . $documento->documento_url) : null;
            $docKb      = $documento->documento_tamano_bytes ? round($documento->documento_tamano_bytes / 1024, 1) : 0;
            $docSize    = $docKb > 1024 ? round($docKb / 1024, 2) . ' MB' : $docKb . ' KB';
            // Período y estado
            $meses    = ['01'=>'Ene','02'=>'Feb','03'=>'Mar','04'=>'Abr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Ago','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dic'];
            $inicio   = $documento->periodo_inicio ? ($meses[$documento->periodo_inicio->format('m')] . ' ' . $documento->periodo_inicio->format('Y')) : '—';
            $fin      = $documento->periodo_fin    ? ($meses[$documento->periodo_fin->format('m')]    . ' ' . $documento->periodo_fin->format('Y'))    : '—';
            $nombreEstado = strtolower($documento->estadoPago?->nombre ?? '');
            $esPagado     = str_contains($nombreEstado, 'pagado');
            $estadoColor  = $documento->estadoPago?->color ?? '#6b7280';
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-[25px] mb-[25px]">

                <!-- COLUMNA IZQUIERDA (2/5): Archivos + Info cuenta -->
                <div class="lg:col-span-2 flex flex-col gap-[25px]">

                    <!-- Card A: Comprobante de pago -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">receipt_long</i>
                            Comprobante de Pago
                        </h6>
                        @if($fileUrl)
                            @if($isImage)
                                <img src="{{ $fileUrl }}" alt="Comprobante" class="w-full h-auto max-h-[220px] object-contain rounded-md border border-gray-100 dark:border-[#172036] mb-[10px]">
                                <a href="{{ $fileUrl }}" target="_blank" class="inline-flex items-center gap-[6px] text-sm text-primary-500 hover:underline">
                                    <i class="material-symbols-outlined !text-[16px]">open_in_new</i>Ver en pantalla completa
                                </a>
                            @elseif($isPdf)
                                <div class="flex flex-col items-center justify-center py-[20px] bg-gray-50 dark:bg-[#15203c] rounded-md mb-[10px]">
                                    <i class="material-symbols-outlined !text-[48px] text-danger-500 mb-[6px]">picture_as_pdf</i>
                                    <a href="{{ $fileUrl }}" target="_blank" class="inline-flex items-center gap-[6px] bg-primary-500 text-white py-[6px] px-[14px] rounded-md hover:bg-primary-400 transition-all text-sm">
                                        <i class="material-symbols-outlined !text-[15px]">open_in_new</i>Ver PDF
                                    </a>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-[20px] bg-gray-50 dark:bg-[#15203c] rounded-md mb-[10px]">
                                    <i class="material-symbols-outlined !text-[48px] text-gray-400 mb-[6px]">insert_drive_file</i>
                                    <a href="{{ $fileUrl }}" target="_blank" class="inline-flex items-center gap-[6px] text-sm text-primary-500 hover:underline">
                                        <i class="material-symbols-outlined !text-[16px]">download</i>Descargar
                                    </a>
                                </div>
                            @endif
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-[6px]">
                                <span class="uppercase font-medium">{{ $documento->extension }}</span>@if($documento->tamano_bytes) — {{ $size }}@endif
                            </p>
                        @else
                            <div class="text-center py-[24px] text-gray-400">
                                <i class="material-symbols-outlined !text-[40px]">no_photography</i>
                                <p class="text-sm mt-[6px]">Sin comprobante</p>
                            </div>
                        @endif
                    </div>

                    <!-- Card B: Documento oficial -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">picture_as_pdf</i>
                            Documento Oficial
                        </h6>
                        @if($docUrl)
                            @if($docIsImage)
                                <img src="{{ $docUrl }}" alt="Documento oficial" class="w-full h-auto max-h-[220px] object-contain rounded-md border border-gray-100 dark:border-[#172036] mb-[10px]">
                                <a href="{{ $docUrl }}" target="_blank" class="inline-flex items-center gap-[6px] text-sm text-primary-500 hover:underline">
                                    <i class="material-symbols-outlined !text-[16px]">open_in_new</i>Ver en pantalla completa
                                </a>
                            @elseif($docIsPdf)
                                <div class="flex flex-col items-center justify-center py-[20px] bg-gray-50 dark:bg-[#15203c] rounded-md mb-[10px]">
                                    <i class="material-symbols-outlined !text-[48px] text-danger-500 mb-[6px]">picture_as_pdf</i>
                                    <a href="{{ $docUrl }}" target="_blank" class="inline-flex items-center gap-[6px] bg-primary-500 text-white py-[6px] px-[14px] rounded-md hover:bg-primary-400 transition-all text-sm">
                                        <i class="material-symbols-outlined !text-[15px]">open_in_new</i>Ver PDF
                                    </a>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-[20px] bg-gray-50 dark:bg-[#15203c] rounded-md mb-[10px]">
                                    <i class="material-symbols-outlined !text-[48px] text-gray-400 mb-[6px]">insert_drive_file</i>
                                    <a href="{{ $docUrl }}" target="_blank" class="inline-flex items-center gap-[6px] text-sm text-primary-500 hover:underline">
                                        <i class="material-symbols-outlined !text-[16px]">download</i>Descargar
                                    </a>
                                </div>
                            @endif
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-[6px]">
                                <span class="uppercase font-medium">{{ $documento->documento_extension }}</span>@if($documento->documento_tamano_bytes) — {{ $docSize }}@endif
                            </p>
                        @else
                            <div class="text-center py-[24px] text-gray-400">
                                <i class="material-symbols-outlined !text-[40px]">description</i>
                                <p class="text-sm mt-[6px]">No adjuntado</p>
                            </div>
                        @endif
                    </div>

                    <!-- Info de la cuenta -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">electrical_services</i>
                            Cuenta de Servicio
                        </h6>

                        @php
                        $cuenta  = $documento->cuenta;
                        $empresa = $cuenta?->proveedor?->empresa;
                        $inicial = mb_strtoupper(mb_substr($empresa?->razon_social ?? $cuenta?->proveedor?->nombre_comercial ?? '?', 0, 1));
                        @endphp

                        <div class="flex items-center gap-[12px] mb-[14px]">
                            @if($empresa?->logo_url)
                                <img src="{{ Storage::url($empresa->logo_url) }}" class="w-[44px] h-[44px] rounded-full object-cover flex-shrink-0" alt="">
                            @else
                                <span class="w-[44px] h-[44px] rounded-full flex items-center justify-center text-lg font-bold flex-shrink-0 bg-primary-100 text-primary-700">{{ $inicial }}</span>
                            @endif
                            <div class="min-w-0">
                                <p class="font-semibold text-black dark:text-white truncate">{{ $empresa?->razon_social ?? $cuenta?->proveedor?->nombre_comercial ?? '—' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cuenta?->proveedor?->tipoServicio?->nombre ?? 'Servicio' }}</p>
                            </div>
                        </div>

                        <div class="space-y-[8px] text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 dark:text-gray-400">N.° Cuenta</span>
                                <span class="font-mono font-medium">{{ $cuenta?->numero_cuenta ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Propiedad</span>
                                <span class="flex items-center gap-[4px] text-right">
                                    <i class="material-symbols-outlined !text-[14px] text-primary-500">{{ $cuenta?->propiedad?->tipoInmueble?->icono ?? 'home' }}</i>
                                    {{ $cuenta?->propiedad?->alias ?? '—' }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- COLUMNA DERECHA (3/5): Detalles -->
                <div class="lg:col-span-3 flex flex-col gap-[25px]">

                    <!-- Datos del documento -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">description</i>
                            Datos del Documento
                        </h6>
                        <div class="grid grid-cols-2 gap-y-[14px] text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mb-[2px]">Tipo</p>
                                <span class="inline-block text-xs font-medium py-[2px] px-[8px] border border-primary-300 bg-primary-50 text-primary-700 rounded-xl">
                                    {{ $documento->tipoDocumento?->nombre ?? '—' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mb-[2px]">Período</p>
                                <p class="font-medium text-black dark:text-white">{{ $inicio }} → {{ $fin }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mb-[2px]">Fecha Vencimiento</p>
                                <p class="font-medium text-black dark:text-white">{{ $documento->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mb-[2px]">Fecha Pago</p>
                                <p class="font-medium text-black dark:text-white">{{ $documento->fecha_pago?->format('d/m/Y') ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información de pago -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">payments</i>
                            Información de Pago
                        </h6>
                        <div class="flex items-center justify-between mb-[14px]">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Monto Total</p>
                                <p class="text-2xl font-bold text-black dark:text-white">
                                    {{ $documento->moneda?->simbolo ?? '' }} {{ number_format($documento->monto_total, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-[2px]">{{ $documento->moneda?->nombre ?? '' }}</p>
                            </div>
                            @if($documento->estadoPago)
                            <span class="text-sm font-medium py-[5px] px-[12px] rounded-full text-white"
                                  style="background-color: {{ $estadoColor }}">
                                {{ $documento->estadoPago->nombre }}
                            </span>
                            @endif
                        </div>
                        <div class="text-sm">
                            <p class="text-gray-500 dark:text-gray-400 text-xs mb-[2px]">Método de Pago</p>
                            <p class="font-medium text-black dark:text-white">{{ $documento->metodoPago?->nombre ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">info</i>
                            Información Adicional
                        </h6>
                        <div class="grid grid-cols-2 gap-y-[14px] text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mb-[2px]">Subido por</p>
                                @php
                                $subidor = $documento->subidoPor;
                                $nombreSubidor = trim(($subidor?->persona?->nombres ?? '') . ' ' . ($subidor?->persona?->apellido_paterno ?? '')) ?: $subidor?->name ?? '—';
                                @endphp
                                <p class="font-medium text-black dark:text-white">{{ $nombreSubidor }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mb-[2px]">Visibilidad</p>
                                <p class="font-medium text-black dark:text-white">{{ $documento->visibilidad?->nombre ?? '—' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-500 dark:text-gray-400 text-xs mb-[2px]">Fecha de Registro</p>
                                <p class="font-medium text-black dark:text-white">{{ $documento->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notas -->
                    @if($documento->notas)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">notes</i>
                            Notas
                        </h6>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $documento->notas }}</p>
                    </div>
                    @endif

                    <!-- Etiquetas -->
                    @if($documento->etiquetas->isNotEmpty())
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">label</i>
                            Etiquetas
                        </h6>
                        <div class="flex flex-wrap gap-[8px]">
                            @foreach($documento->etiquetas as $etiqueta)
                            <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[3px] px-[10px] rounded-full text-white"
                                  style="background-color: {{ $etiqueta->color ?? '#6366f1' }}">
                                {{ $etiqueta->nombre }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Historial de versiones -->
                    @if($documento->historialVersiones->isNotEmpty())
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">history</i>
                            Historial de Versiones
                        </h6>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-[#172036]">
                                        <th class="text-left py-[8px] px-[10px] text-xs text-gray-500 font-medium">Fecha</th>
                                        <th class="text-left py-[8px] px-[10px] text-xs text-gray-500 font-medium">Tipo</th>
                                        <th class="text-left py-[8px] px-[10px] text-xs text-gray-500 font-medium">Tamaño</th>
                                        <th class="text-left py-[8px] px-[10px] text-xs text-gray-500 font-medium">Archivo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-[#172036]">
                                    @foreach($documento->historialVersiones->sortByDesc('created_at') as $version)
                                    @php
                                    $vKb   = $version->tamano_bytes ? round($version->tamano_bytes / 1024, 1) : 0;
                                    $vSize = $vKb > 1024 ? round($vKb / 1024, 2) . ' MB' : $vKb . ' KB';
                                    @endphp
                                    <tr>
                                        <td class="py-[8px] px-[10px] text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $version->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="py-[8px] px-[10px]">
                                            <span class="uppercase text-xs font-medium text-gray-700 dark:text-gray-300">{{ $version->extension ?? '—' }}</span>
                                        </td>
                                        <td class="py-[8px] px-[10px] text-gray-600 dark:text-gray-400 text-xs">{{ $vSize }}</td>
                                        <td class="py-[8px] px-[10px]">
                                            @if($version->archivo_url)
                                            <a href="{{ asset('storage/' . $version->archivo_url) }}" target="_blank"
                                               class="inline-flex items-center gap-[4px] text-xs text-primary-500 hover:underline">
                                                <i class="material-symbols-outlined !text-[12px]">open_in_new</i>
                                                Ver
                                            </a>
                                            @else
                                            <span class="text-gray-400 text-xs">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            @if($documento->lecturaConsumo)
            @php
            $unidadSimbolo = $documento->cuenta?->proveedor?->tipoServicio?->unidadMedida?->simbolo ?? '';
            @endphp
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex items-center gap-[8px] mb-[20px]">
                    <i class="material-symbols-outlined !text-[22px] text-primary-500">speed</i>
                    <h6 class="!mb-0">Lectura de Consumo</h6>
                    @if($unidadSimbolo)
                    <span class="text-xs text-gray-400 ml-[2px]">({{ $unidadSimbolo }})</span>
                    @endif
                </div>
                <div class="grid grid-cols-3 gap-[16px] text-center">
                    <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[16px]">
                        <span class="block text-xs text-gray-400 dark:text-gray-500 mb-[6px]">Lectura anterior</span>
                        <span class="block text-2xl font-bold text-black dark:text-white">{{ number_format($documento->lecturaConsumo->lectura_anterior, 2) }}</span>
                        @if($unidadSimbolo)<span class="block text-xs text-gray-400 mt-[4px]">{{ $unidadSimbolo }}</span>@endif
                    </div>
                    <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[16px]">
                        <span class="block text-xs text-gray-400 dark:text-gray-500 mb-[6px]">Lectura actual</span>
                        <span class="block text-2xl font-bold text-black dark:text-white">{{ number_format($documento->lecturaConsumo->lectura_actual, 2) }}</span>
                        @if($unidadSimbolo)<span class="block text-xs text-gray-400 mt-[4px]">{{ $unidadSimbolo }}</span>@endif
                    </div>
                    <div class="bg-primary-50 dark:bg-[#15203c] rounded-md p-[16px] border border-primary-200 dark:border-primary-800">
                        <span class="block text-xs text-primary-500 mb-[6px]">Consumo del período</span>
                        <span class="block text-2xl font-bold text-primary-600">{{ number_format($documento->lecturaConsumo->consumo, 2) }}</span>
                        @if($unidadSimbolo)<span class="block text-xs text-primary-400 mt-[4px]">{{ $unidadSimbolo }}</span>@endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Botones de acción -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex items-center justify-between flex-wrap gap-[12px]">
                    <a href="{{ route('dashboard.documentos-servicio.index', ['propiedad' => $documento->cuenta?->propiedad_id, 'cuenta' => $documento->cuenta_id]) }}"
                       class="inline-flex items-center gap-[6px] text-sm text-gray-500 dark:text-gray-400 hover:text-primary-500 transition-all">
                        <i class="material-symbols-outlined !text-[18px]">arrow_back</i>
                        Volver al listado
                    </a>
                    <div class="flex items-center gap-[10px]">
                        @unless($esPagado)
                        <button type="button"
                            onclick="marcarPagado('{{ route('dashboard.documentos-servicio.marcar-pagado', $documento) }}')"
                            class="inline-flex items-center gap-[6px] bg-success-500 text-white py-[8px] px-[16px] rounded-md hover:bg-success-400 transition-all text-sm">
                            <i class="material-symbols-outlined !text-[16px]">check_circle</i>
                            Marcar como pagado
                        </button>
                        @endunless
                        <a href="{{ route('dashboard.documentos-servicio.edit', $documento) }}"
                           class="inline-flex items-center gap-[6px] bg-primary-500 text-white py-[8px] px-[16px] rounded-md hover:bg-primary-400 transition-all text-sm">
                            <i class="material-symbols-outlined !text-[16px]">edit</i>
                            Editar
                        </a>
                    </div>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script>
            function marcarPagado(url) {
                if (!confirm('¿Marcar este documento como pagado?')) return;
                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'No se pudo actualizar el estado.');
                    }
                })
                .catch(function() { alert('Error al procesar la solicitud.'); });
            }
        </script>
    </body>
</html>
