<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('partials.front.styles')
        <title>Recibos y Facturas</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Recibos y Facturas</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Recibos y Facturas
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

            <!-- PASO 1: Selector de propiedad -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-[12px] uppercase tracking-wide">
                    <i class="material-symbols-outlined !text-[14px] align-middle">home</i>
                    Paso 1 — Selecciona una propiedad
                </p>
                <div class="flex flex-wrap gap-[8px]">
                    @forelse($propiedades as $prop)
                    <a href="{{ route('dashboard.documentos-servicio.index', ['propiedad' => $prop->id]) }}"
                       class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-[8px] border text-sm font-medium transition-all
                           {{ $propiedadSeleccionada?->id === $prop->id
                               ? 'bg-primary-500 border-primary-500 text-white'
                               : 'border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500' }}">
                        <i class="material-symbols-outlined !text-[16px]">{{ $prop->tipoInmueble?->icono ?? 'home' }}</i>
                        <span class="max-w-[160px] truncate">{{ $prop->alias }}</span>
                    </a>
                    @empty
                    <p class="text-sm text-gray-400 dark:text-gray-500">No tienes propiedades registradas.</p>
                    @endforelse
                </div>
            </div>

            @if($propiedadSeleccionada)

            <!-- PASO 2: Selector de cuenta -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-[16px] uppercase tracking-wide flex items-center gap-[6px]">
                    <i class="material-symbols-outlined !text-[14px]">electrical_services</i>
                    Paso 2 — Selecciona una cuenta de servicio
                </p>
                @if($cuentas->isEmpty())
                    <p class="text-sm text-gray-400 dark:text-gray-500">
                        <strong>{{ $propiedadSeleccionada->alias }}</strong> no tiene cuentas de servicio registradas.
                        <a href="{{ route('dashboard.cuentas-servicio.create', ['propiedad' => $propiedadSeleccionada->id]) }}" class="text-primary-500 hover:underline">Agregar una cuenta</a>
                    </p>
                @else
                    @php
                    $iconMap = ['electricidad'=>'bolt','agua'=>'water_drop','gas'=>'local_fire_department','internet'=>'wifi','telefon'=>'phone','móvil'=>'phone_android','movil'=>'phone_android','cable'=>'tv','tv'=>'tv','seguridad'=>'security'];
                    $paletas = [
                        ['text'=>'text-primary-600',  'iconBg'=>'bg-primary-100',  'ring'=>'ring-primary-200',  'inicial'=>'bg-primary-100 text-primary-700'],
                        ['text'=>'text-orange-600',   'iconBg'=>'bg-orange-100',   'ring'=>'ring-orange-200',   'inicial'=>'bg-orange-100 text-orange-700'],
                        ['text'=>'text-success-600',  'iconBg'=>'bg-success-100',  'ring'=>'ring-success-200',  'inicial'=>'bg-success-100 text-success-700'],
                        ['text'=>'text-purple-600',   'iconBg'=>'bg-purple-100',   'ring'=>'ring-purple-200',   'inicial'=>'bg-purple-100 text-purple-700'],
                    ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-[12px]">
                        @foreach($cuentas as $cIdx => $cuenta)
                        @php
                        $paleta         = $paletas[$cIdx % 4];
                        $empresa        = $cuenta->proveedor?->empresa;
                        $nombreServicio = strtolower($cuenta->proveedor?->tipoServicio?->nombre ?? '');
                        $iconCuenta     = 'home_repair_service';
                        foreach ($iconMap as $key => $val) { if (str_contains($nombreServicio, $key)) { $iconCuenta = $val; break; } }
                        $inicial        = mb_strtoupper(mb_substr($empresa?->razon_social ?? $cuenta->proveedor?->nombre_comercial ?? '?', 0, 1));
                        $seleccionada   = $cuentaSeleccionada?->id === $cuenta->id;
                        @endphp

                        <a href="{{ route('dashboard.documentos-servicio.index', ['propiedad' => $propiedadSeleccionada->id, 'cuenta' => $cuenta->id]) }}"
                           class="flex items-center gap-[10px] rounded-[10px] py-[10px] px-[14px] transition-all duration-150 cursor-pointer border-2
                               {{ $seleccionada
                                   ? 'border-primary-500 bg-primary-50 dark:bg-[#1a2a4c] shadow-sm'
                                   : 'border-transparent bg-gray-50 dark:bg-[#0f1b30] hover:bg-white dark:hover:bg-[#15203c] hover:border-gray-200 dark:hover:border-[#2a3a5c] hover:shadow-sm' }}">

                            {{-- Ícono tipo de servicio --}}
                            <div class="w-9 h-9 rounded-md {{ $paleta['iconBg'] }} flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined !text-[20px] {{ $paleta['text'] }}">{{ $iconCuenta }}</i>
                            </div>

                            {{-- Textos compactos --}}
                            <div class="flex-1 min-w-0">
                                <span class="block font-semibold text-black dark:text-white text-[13px] leading-tight truncate">
                                    {{ $cuenta->proveedor?->tipoServicio?->nombre ?? 'Servicio' }}
                                </span>
                                <span class="block text-[11px] text-gray-400 dark:text-gray-500 leading-tight truncate">
                                    {{ $cuenta->numero_cuenta }} · {{ $cuenta->proveedor?->nombre_comercial ?? '—' }}
                                </span>
                            </div>

                            {{-- Logo / inicial empresa --}}
                            <div class="flex-shrink-0">
                                @if($empresa?->logo_url)
                                    <img src="{{ asset('storage/' . $empresa->logo_url) }}"
                                         class="w-7 h-7 rounded-full object-cover border border-white dark:border-[#0c1427] shadow-sm" alt="">
                                @else
                                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold {{ $paleta['inicial'] }}">
                                        {{ $inicial }}
                                    </span>
                                @endif
                            </div>

                        </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- PASO 3: Listado de documentos -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">

                @if($cuentaSeleccionada)

                <!-- Encabezado -->
                <div class="trezo-card-header mb-[20px] flex items-center justify-between flex-wrap gap-[12px]">
                    <div>
                        <h5 class="!mb-0">
                            <i class="material-symbols-outlined !text-[20px] align-middle mr-[6px] text-primary-500">receipt_long</i>
                            {{ $cuentaSeleccionada->proveedor?->tipoServicio?->nombre ?? 'Servicio' }}
                            <span class="font-mono text-gray-500 text-sm font-normal">· {{ $cuentaSeleccionada->numero_cuenta }}</span>
                        </h5>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-[4px]">
                            {{ $cuentaSeleccionada->proveedor?->empresa?->razon_social ?? $cuentaSeleccionada->proveedor?->nombre_comercial ?? '—' }}
                        </p>
                    </div>
                    <a href="{{ route('dashboard.documentos-servicio.create', ['cuenta' => $cuentaSeleccionada->id]) }}"
                       class="inline-block bg-primary-500 text-white py-[7px] px-[14px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                        <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                            <i class="material-symbols-outlined !text-[15px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">upload_file</i>
                            Subir documento
                        </span>
                    </a>
                </div>

                @if($documentos->isEmpty())
                <div class="text-center py-[60px]">
                    <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[16px]">receipt_long</i>
                    <p class="text-gray-500 dark:text-gray-400 mb-[20px]">No hay documentos subidos para esta cuenta.</p>
                    <a href="{{ route('dashboard.documentos-servicio.create', ['cuenta' => $cuentaSeleccionada->id]) }}"
                       class="inline-block bg-primary-500 text-white py-[10px] px-[20px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">upload_file</i>
                            Subir primer documento
                        </span>
                    </a>
                </div>
                @else

                @php
                $meses = ['01'=>'Ene','02'=>'Feb','03'=>'Mar','04'=>'Abr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Ago','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dic'];
                $hoy   = now()->startOfDay();
                @endphp

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-[#172036]">
                                <th class="text-left py-[10px] px-[12px] text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wide whitespace-nowrap">Tipo</th>
                                <th class="text-left py-[10px] px-[12px] text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wide whitespace-nowrap">Período</th>
                                <th class="text-right py-[10px] px-[12px] text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wide whitespace-nowrap">Monto</th>
                                <th class="text-left py-[10px] px-[12px] text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wide whitespace-nowrap">Vencimiento</th>
                                <th class="text-left py-[10px] px-[12px] text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wide whitespace-nowrap">Estado</th>
                                <th class="text-left py-[10px] px-[12px] text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wide whitespace-nowrap">Archivos</th>
                                <th class="text-right py-[10px] px-[12px] text-gray-500 dark:text-gray-400 font-medium text-xs uppercase tracking-wide whitespace-nowrap">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-[#172036]">
                            @foreach($documentos as $doc)
                            @php
                            $nombreEstado = strtolower($doc->estadoPago?->nombre ?? '');
                            $esPagado     = str_contains($nombreEstado, 'pagado');
                            $estadoColor  = $doc->estadoPago?->color ?? '#6b7280';

                            $vencBadge = null;
                            if ($doc->fecha_vencimiento && !$esPagado) {
                                if ($doc->fecha_vencimiento->lt($hoy)) {
                                    $vencBadge = 'danger';
                                } elseif ($hoy->diffInDays($doc->fecha_vencimiento) <= 7) {
                                    $vencBadge = 'warning';
                                }
                            }

                            $inicio      = $doc->periodo_inicio ? ($meses[$doc->periodo_inicio->format('m')] . ' ' . $doc->periodo_inicio->format('Y')) : '—';
                            $fin         = $doc->periodo_fin    ? ($meses[$doc->periodo_fin->format('m')]    . ' ' . $doc->periodo_fin->format('Y'))    : '—';
                            $esImagen    = in_array(strtolower($doc->extension ?? ''), ['jpg','jpeg','png','webp']);
                            $docEsImagen = in_array(strtolower($doc->documento_extension ?? ''), ['jpg','jpeg','png','webp']);
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                <!-- Tipo documento -->
                                <td class="py-[12px] px-[12px]">
                                    <span class="inline-block text-xs font-medium py-[2px] px-[8px] border border-primary-300 bg-primary-50 text-primary-700 rounded-xl whitespace-nowrap">
                                        {{ $doc->tipoDocumento?->nombre ?? '—' }}
                                    </span>
                                </td>
                                <!-- Período -->
                                <td class="py-[12px] px-[12px] whitespace-nowrap text-gray-700 dark:text-gray-300">
                                    {{ $inicio }} → {{ $fin }}
                                </td>
                                <!-- Monto -->
                                <td class="py-[12px] px-[12px] text-right font-mono font-semibold whitespace-nowrap">
                                    {{ $doc->moneda?->simbolo ?? '' }} {{ number_format($doc->monto_total, 2) }}
                                </td>
                                <!-- Fecha vencimiento -->
                                <td class="py-[12px] px-[12px] whitespace-nowrap">
                                    @if($doc->fecha_vencimiento)
                                        @if($vencBadge === 'danger')
                                            <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[2px] px-[8px] border border-danger-300 bg-danger-100 text-danger-700 rounded-xl">
                                                <i class="material-symbols-outlined !text-[12px]">warning</i>
                                                {{ $doc->fecha_vencimiento->format('d/m/Y') }}
                                            </span>
                                        @elseif($vencBadge === 'warning')
                                            <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[2px] px-[8px] border border-warning-300 bg-warning-100 text-warning-700 rounded-xl">
                                                <i class="material-symbols-outlined !text-[12px]">schedule</i>
                                                {{ $doc->fecha_vencimiento->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-gray-600 dark:text-gray-400 text-xs">{{ $doc->fecha_vencimiento->format('d/m/Y') }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <!-- Estado pago -->
                                <td class="py-[12px] px-[12px]">
                                    @if($doc->estadoPago)
                                    <span class="inline-block text-xs font-medium py-[2px] px-[8px] rounded-full text-white whitespace-nowrap"
                                          style="background-color: {{ $estadoColor }}">
                                        {{ $doc->estadoPago->nombre }}
                                    </span>
                                    @else
                                    <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <!-- Archivos -->
                                <td class="py-[12px] px-[12px]">
                                    <div class="flex items-center gap-[6px]">
                                        {{-- Comprobante de pago --}}
                                        @if($doc->archivo_url)
                                            <a href="{{ asset('storage/' . $doc->archivo_url) }}" target="_blank"
                                               title="Ver comprobante de pago"
                                               class="w-8 h-8 rounded-md flex items-center justify-center bg-orange-50 dark:bg-orange-900/20 text-orange-500 hover:bg-orange-100 transition-colors">
                                                <i class="material-symbols-outlined !text-[18px]">{{ $esImagen ? 'receipt' : 'picture_as_pdf' }}</i>
                                            </a>
                                        @else
                                            <div class="w-8 h-8 rounded-md flex items-center justify-center bg-gray-50 dark:bg-[#15203c] text-gray-300 dark:text-gray-600"
                                                 title="Sin comprobante">
                                                <i class="material-symbols-outlined !text-[18px]">receipt</i>
                                            </div>
                                        @endif
                                        {{-- Documento oficial --}}
                                        @if($doc->documento_url)
                                            <a href="{{ asset('storage/' . $doc->documento_url) }}" target="_blank"
                                               title="Ver documento oficial"
                                               class="w-8 h-8 rounded-md flex items-center justify-center bg-primary-50 dark:bg-primary-900/20 text-primary-500 hover:bg-primary-100 transition-colors">
                                                <i class="material-symbols-outlined !text-[18px]">{{ $docEsImagen ? 'description' : 'picture_as_pdf' }}</i>
                                            </a>
                                        @else
                                            <div class="w-8 h-8 rounded-md flex items-center justify-center bg-gray-50 dark:bg-[#15203c] text-gray-300 dark:text-gray-600"
                                                 title="Sin documento oficial">
                                                <i class="material-symbols-outlined !text-[18px]">description</i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <!-- Acciones -->
                                <td class="py-[12px] px-[12px]">
                                    <div class="flex items-center justify-end gap-[4px]">
                                        <a href="{{ route('dashboard.documentos-servicio.show', $doc) }}"
                                           class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-primary-100 transition-all text-gray-500 hover:text-primary-600"
                                           title="Ver detalle">
                                            <i class="material-symbols-outlined !text-[15px]">visibility</i>
                                        </a>
                                        <a href="{{ route('dashboard.documentos-servicio.edit', $doc) }}"
                                           class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-warning-100 transition-all text-gray-500 hover:text-warning-600"
                                           title="Editar">
                                            <i class="material-symbols-outlined !text-[15px]">edit</i>
                                        </a>
                                        @unless($esPagado)
                                        <button type="button"
                                            onclick="marcarPagado('{{ route('dashboard.documentos-servicio.marcar-pagado', $doc) }}')"
                                            class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-success-100 transition-all text-gray-500 hover:text-success-600"
                                            title="Marcar como pagado">
                                            <i class="material-symbols-outlined !text-[15px]">check_circle</i>
                                        </button>
                                        @endunless
                                        <form method="POST" action="{{ route('dashboard.documentos-servicio.destroy', $doc) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('¿Eliminar este documento? Esta acción no se puede deshacer.')"
                                                class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-danger-100 transition-all text-gray-500 hover:text-danger-600"
                                                title="Eliminar">
                                                <i class="material-symbols-outlined !text-[15px]">delete</i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($documentos->hasPages())
                <div class="sm:flex sm:items-center justify-between mt-[25px] pt-[14px] border-t border-gray-100 dark:border-[#172036]">
                    <p class="!mb-0 text-sm">
                        Mostrando {{ $documentos->firstItem() }}–{{ $documentos->lastItem() }} de {{ $documentos->total() }} resultados
                    </p>
                    <ol class="mt-[10px] sm:mt-0">
                        <li class="inline-block mx-[1px]">
                            @if($documentos->onFirstPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                </span>
                            @else
                                <a href="{{ $documentos->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                </a>
                            @endif
                        </li>
                        @foreach($documentos->getUrlRange(max(1, $documentos->currentPage()-2), min($documentos->lastPage(), $documentos->currentPage()+2)) as $page => $url)
                        <li class="inline-block mx-[1px]">
                            @if($page == $documentos->currentPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                            @endif
                        </li>
                        @endforeach
                        <li class="inline-block mx-[1px]">
                            @if($documentos->hasMorePages())
                                <a href="{{ $documentos->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i>
                                </a>
                            @else
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i>
                                </span>
                            @endif
                        </li>
                    </ol>
                </div>
                @endif

                @endif {{-- $documentos->isEmpty() --}}

                @else {{-- no hay cuentaSeleccionada --}}
                <div class="text-center py-[60px]">
                    <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[16px]">receipt_long</i>
                    <p class="text-gray-500 dark:text-gray-400">Selecciona una cuenta para ver sus documentos.</p>
                </div>
                @endif {{-- $cuentaSeleccionada --}}

            </div>

            @else {{-- no hay propiedadSeleccionada --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="text-center py-[60px]">
                    <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[16px]">home</i>
                    <p class="text-gray-500 dark:text-gray-400">Selecciona una propiedad para comenzar.</p>
                </div>
            </div>
            @endif {{-- $propiedadSeleccionada --}}

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
