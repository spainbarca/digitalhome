<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $viaje->nombre }} — Viaje</title>
        @vite('resources/css/app.css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
        <style>
            .select2-container--default .select2-selection--single{height:42px;border:1px solid #e5e7eb;border-radius:6px;display:flex;align-items:center;padding:0 10px}
            .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:42px;color:inherit}
            .select2-container--default .select2-selection--single .select2-selection__arrow{height:42px;top:0}
            .dark .select2-container--default .select2-selection--single{background:#0c1427;border-color:#172036;color:#fff}
            .dark .select2-container--default .select2-results__option{background:#0c1427;color:#fff}
            .dark .select2-dropdown{background:#0c1427;border-color:#172036}
            .dark .select2-search--dropdown .select2-search__field{background:#15203c;border-color:#172036;color:#fff}
        </style>
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        @php
            if (!function_exists('colorEstadoViajeShow')) {
                function colorEstadoViajeShow(?string $estado): array {
                    return match($estado) {
                        'planificado' => ['bg' => 'bg-primary-100', 'text' => 'text-primary-600', 'icon' => 'schedule'],
                        'en_curso'    => ['bg' => 'bg-success-100', 'text' => 'text-success-600', 'icon' => 'play_circle'],
                        'completado'  => ['bg' => 'bg-gray-100',    'text' => 'text-gray-500',    'icon' => 'check_circle'],
                        'cancelado'   => ['bg' => 'bg-danger-100',  'text' => 'text-danger-600',  'icon' => 'cancel'],
                        default       => ['bg' => 'bg-gray-100',    'text' => 'text-gray-500',    'icon' => 'flight'],
                    };
                }
            }
            $colEs     = colorEstadoViajeShow($viaje->estado);
            $gradients = ['from-primary-400 to-primary-600','from-orange-400 to-orange-600','from-purple-400 to-purple-600','from-success-400 to-success-600','from-pink-400 to-pink-600'];
            $grad      = $gradients[abs(crc32($viaje->id)) % count($gradients)];
        @endphp

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $viaje->nombre }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.viajes.index') }}" class="transition-all hover:text-primary-500">Viajes</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Detalle</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif
            @if($errors->any())
            <div class="mb-[25px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
            @endif

            {{-- ── Hero ───────────────────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                {{-- Panel izquierdo --}}
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md h-full flex flex-col">

                        <div class="relative rounded-md overflow-hidden mb-[20px] h-[160px] flex items-center justify-center {{ $viaje->portada_url ? '' : 'bg-gradient-to-br ' . $grad }}">
                            @if($viaje->portada_url)
                                <img src="{{ $viaje->portada_url }}" class="w-full h-full object-cover" alt="{{ $viaje->nombre }}">
                            @else
                                <i class="material-symbols-outlined !text-[64px] text-white opacity-70">{{ $viaje->tipoViaje?->icono ?? 'luggage' }}</i>
                            @endif
                        </div>

                        <h6 class="font-semibold text-black dark:text-white !mb-[8px] text-center">{{ $viaje->nombre }}</h6>

                        <div class="flex items-center justify-center flex-wrap gap-[6px] mb-[16px]">
                            @if($viaje->tipoViaje)
                            <span class="inline-flex items-center gap-[4px] text-[11px] font-medium py-[3px] px-[10px] rounded-full bg-primary-50 dark:bg-[#1a2d4d] text-primary-600 dark:text-primary-400">
                                <i class="material-symbols-outlined !text-[12px]">{{ $viaje->tipoViaje->icono ?? 'category' }}</i>
                                {{ $viaje->tipoViaje->nombre }}
                            </span>
                            @endif
                            <span class="inline-flex items-center gap-[4px] text-[11px] font-semibold py-[3px] px-[10px] rounded-full {{ $colEs['bg'] }} {{ $colEs['text'] }}">
                                <i class="material-symbols-outlined !text-[11px]">{{ $colEs['icon'] }}</i>
                                {{ ucfirst(str_replace('_', ' ', $viaje->estado ?? 'planificado')) }}
                            </span>
                        </div>

                        @if($viaje->fecha_inicio || $viaje->fecha_fin)
                        <div class="flex items-center gap-[6px] text-xs text-gray-500 dark:text-gray-400 mb-[12px] justify-center">
                            <i class="material-symbols-outlined !text-[14px]">calendar_month</i>
                            <span>
                                {{ $viaje->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                @if($viaje->fecha_fin) — {{ $viaje->fecha_fin->format('d/m/Y') }} @endif
                            </span>
                        </div>
                        @endif

                        <div class="flex-1"></div>

                        <div class="flex items-center gap-[10px] mt-[16px]">
                            <a href="{{ route('dashboard.viajes.edit', $viaje) }}"
                                class="flex-1 text-center inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <i class="material-symbols-outlined !text-[16px] align-middle mr-[4px]">edit</i>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('dashboard.viajes.destroy', $viaje) }}" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('¿Eliminar el viaje «{{ addslashes($viaje->nombre) }}»?')"
                                    class="w-full transition-all rounded-md font-medium px-[13px] py-[8px] bg-danger-500 text-white hover:bg-danger-400 text-sm">
                                    <i class="material-symbols-outlined !text-[16px] align-middle mr-[4px]">delete</i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Panel derecho --}}
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md h-full">

                        @if($viaje->presupuesto !== null)
                        <div class="mb-[20px] pb-[20px] border-b border-gray-100 dark:border-[#172036]">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-[4px]">Presupuesto total</p>
                            <span class="block text-3xl md:text-4xl font-bold text-black dark:text-white">
                                {{ $viaje->moneda?->simbolo }} {{ number_format((float) $viaje->presupuesto, 2) }}
                                @if($viaje->moneda)<span class="text-base font-normal text-gray-400 ml-[6px]">{{ $viaje->moneda->codigo }}</span>@endif
                            </span>
                        </div>
                        @endif

                        {{-- Resumen gastos por moneda --}}
                        @if(count($resumenGastos))
                        <div class="mb-[20px]">
                            <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[8px] text-sm">
                                <i class="material-symbols-outlined !text-[18px] text-primary-500">payments</i>
                                Gasto total por moneda
                            </h6>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-[#172036]">
                                            <th class="text-left text-xs text-gray-400 py-[6px] pr-[12px]">Moneda</th>
                                            <th class="text-right text-xs text-gray-400 py-[6px] pr-[12px]">Compras</th>
                                            <th class="text-right text-xs text-gray-400 py-[6px] pr-[12px]">Gastos</th>
                                            <th class="text-right text-xs text-gray-400 py-[6px]">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($resumenGastos as $mid => $row)
                                        @php $total = $row['compras'] + $row['gastos']; @endphp
                                        <tr class="border-b border-gray-50 dark:border-[#172036]">
                                            <td class="py-[8px] pr-[12px]">
                                                <span class="font-medium text-black dark:text-white">{{ $row['moneda']?->simbolo ?? '?' }} {{ $row['moneda']?->codigo ?? '—' }}</span>
                                            </td>
                                            <td class="py-[8px] pr-[12px] text-right text-gray-500">{{ number_format($row['compras'], 2) }}</td>
                                            <td class="py-[8px] pr-[12px] text-right text-gray-500">{{ number_format($row['gastos'], 2) }}</td>
                                            <td class="py-[8px] text-right font-semibold text-black dark:text-white">{{ number_format($total, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        {{-- Stats --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-[12px]">
                            <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[14px] text-center">
                                <i class="material-symbols-outlined !text-[24px] text-primary-500 block mb-[4px]">location_on</i>
                                <span class="block text-xl font-bold text-black dark:text-white">{{ $destinos->count() }}</span>
                                <span class="text-xs text-gray-400">Destinos</span>
                            </div>
                            <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[14px] text-center">
                                <i class="material-symbols-outlined !text-[24px] text-orange-500 block mb-[4px]">confirmation_number</i>
                                <span class="block text-xl font-bold text-black dark:text-white">{{ $reservas->count() }}</span>
                                <span class="text-xs text-gray-400">Reservas</span>
                            </div>
                            <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[14px] text-center">
                                <i class="material-symbols-outlined !text-[24px] text-success-500 block mb-[4px]">groups</i>
                                <span class="block text-xl font-bold text-black dark:text-white">{{ $participantes->count() }}</span>
                                <span class="text-xs text-gray-400">Participantes</span>
                            </div>
                            <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[14px] text-center">
                                <i class="material-symbols-outlined !text-[24px] text-purple-500 block mb-[4px]">description</i>
                                <span class="block text-xl font-bold text-black dark:text-white">{{ $documentos->count() }}</span>
                                <span class="text-xs text-gray-400">Documentos</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ── Tabs ───────────────────────────────────────────────────────── --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-tabs" id="trezo-tabs">
                    <ul class="navs mb-[20px] border-b border-gray-100 dark:border-[#172036] flex flex-wrap gap-y-[4px]">
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabResumen" class="nav-link active flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">summarize</i> Resumen
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabDestinos" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">location_on</i> Destinos
                                @if($destinos->count())<span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-primary-500 text-white rounded-full">{{ $destinos->count() }}</span>@endif
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabReservas" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">confirmation_number</i> Reservas
                                @if($reservas->count())<span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-orange-500 text-white rounded-full">{{ $reservas->count() }}</span>@endif
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabGastos" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">payments</i> Gastos
                                @php $totalGastos = $comprasViaje->count() + $gastosViaje->count(); @endphp
                                @if($totalGastos)<span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-success-500 text-white rounded-full">{{ $totalGastos }}</span>@endif
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabParticipantes" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">groups</i> Participantes
                                @if($participantes->count())<span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-success-500 text-white rounded-full">{{ $participantes->count() }}</span>@endif
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabDocumentos" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">description</i> Documentos
                                @if($documentos->count())<span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-purple-500 text-white rounded-full">{{ $documentos->count() }}</span>@endif
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- ─── TAB: Resumen ─────────────────────────────────────────── --}}
                        <div class="tab-pane active" id="tabResumen">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-[20px]">
                                <div>
                                    @if($viaje->descripcion)
                                    <h6 class="font-semibold text-black dark:text-white mb-[10px] flex items-center gap-[6px] text-sm">
                                        <i class="material-symbols-outlined !text-[18px] text-primary-500">info</i> Descripción
                                    </h6>
                                    <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[14px] mb-[16px]">
                                        <p class="text-sm text-black dark:text-white leading-[1.7] whitespace-pre-line">{{ $viaje->descripcion }}</p>
                                    </div>
                                    @endif
                                    @if($viaje->notas)
                                    <h6 class="font-semibold text-black dark:text-white mb-[10px] flex items-center gap-[6px] text-sm">
                                        <i class="material-symbols-outlined !text-[18px] text-primary-500">notes</i> Notas
                                    </h6>
                                    <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[14px]">
                                        <p class="text-sm text-black dark:text-white leading-[1.7] whitespace-pre-line">{{ $viaje->notas }}</p>
                                    </div>
                                    @endif
                                    @if(!$viaje->descripcion && !$viaje->notas)
                                    <p class="text-sm text-gray-400">Sin descripción ni notas.</p>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="font-semibold text-black dark:text-white mb-[10px] flex items-center gap-[6px] text-sm">
                                        <i class="material-symbols-outlined !text-[18px] text-primary-500">info</i> Datos del viaje
                                    </h6>
                                    <div class="space-y-[10px]">
                                        <div class="flex items-center justify-between py-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <span class="text-xs text-gray-400">Tipo de viaje</span>
                                            <span class="text-sm font-medium text-black dark:text-white">{{ $viaje->tipoViaje?->nombre ?? '—' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between py-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <span class="text-xs text-gray-400">Estado</span>
                                            <span class="inline-flex items-center gap-[4px] text-[11px] font-semibold py-[2px] px-[8px] rounded-full {{ $colEs['bg'] }} {{ $colEs['text'] }}">
                                                <i class="material-symbols-outlined !text-[11px]">{{ $colEs['icon'] }}</i>
                                                {{ ucfirst(str_replace('_', ' ', $viaje->estado ?? 'planificado')) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between py-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <span class="text-xs text-gray-400">Fecha inicio</span>
                                            <span class="text-sm font-medium text-black dark:text-white">{{ $viaje->fecha_inicio?->format('d/m/Y') ?? '—' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between py-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <span class="text-xs text-gray-400">Fecha fin</span>
                                            <span class="text-sm font-medium text-black dark:text-white">{{ $viaje->fecha_fin?->format('d/m/Y') ?? '—' }}</span>
                                        </div>
                                        @if($viaje->presupuesto !== null)
                                        <div class="flex items-center justify-between py-[8px]">
                                            <span class="text-xs text-gray-400">Presupuesto</span>
                                            <span class="text-sm font-semibold text-black dark:text-white">{{ $viaje->moneda?->simbolo }} {{ number_format((float)$viaje->presupuesto, 2) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ─── TAB: Destinos ────────────────────────────────────────── --}}
                        <div class="tab-pane hidden" id="tabDestinos">
                            <div class="flex items-center justify-between mb-[20px]">
                                <h6 class="font-semibold text-black dark:text-white flex items-center gap-[8px] !mb-0">
                                    <i class="material-symbols-outlined !text-[18px] text-primary-500">location_on</i>
                                    Destinos ({{ $destinos->count() }})
                                </h6>
                                <button type="button" onclick="abrirModalDestino()"
                                    class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i> Agregar destino
                                </button>
                            </div>

                            @if($destinos->isEmpty())
                            <div class="text-center py-[40px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">location_off</i>
                                <p class="text-sm text-gray-400">Sin destinos registrados.</p>
                            </div>
                            @else
                            <div class="relative pl-[28px]">
                                <div class="absolute left-[10px] top-0 bottom-0 w-[2px] bg-gray-200 dark:bg-[#172036]"></div>
                                @foreach($destinos as $i => $destino)
                                @php
                                    $ubicLabel = '';
                                    if ($destino->distrito_inei && $destino->distrito) {
                                        $dis = $destino->distrito;
                                        $ubicLabel = $dis->distrito . ', ' . $dis->provincia . ' — ' . $dis->departamento;
                                    } elseif ($destino->pais_iso2) {
                                        $ubicLabel = ($destino->ciudad ? $destino->ciudad->nombre . ', ' : '') . ($destino->pais?->nombre ?? $destino->pais_iso2);
                                    }
                                    $editData = [
                                        'id'             => $destino->id,
                                        'nombre'         => $destino->nombre,
                                        'ubicacion_tipo' => $destino->distrito_inei ? 'peru' : ($destino->pais_iso2 ? 'extranjero' : ''),
                                        'distrito_inei'  => $destino->distrito_inei,
                                        'distrito_prov'  => $destino->distrito?->provincia_inei,
                                        'distrito_dep'   => $destino->distrito?->departamento_inei,
                                        'pais_iso2'      => $destino->pais_iso2,
                                        'ciudad_id'      => $destino->ciudad_id,
                                        'fecha_llegada'  => $destino->fecha_llegada?->format('Y-m-d'),
                                        'fecha_salida'   => $destino->fecha_salida?->format('Y-m-d'),
                                        'orden'          => $destino->orden,
                                        'notas'          => $destino->notas,
                                    ];
                                @endphp
                                <div class="relative mb-[20px]">
                                    <div class="absolute -left-[24px] top-[6px] w-[14px] h-[14px] rounded-full bg-primary-500 border-2 border-white dark:border-[#0c1427]"></div>
                                    <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[16px]">
                                        <div class="flex items-start justify-between gap-[10px]">
                                            <div class="flex-1 min-w-0">
                                                <h6 class="!mb-[4px] !text-sm font-semibold text-black dark:text-white">{{ $destino->nombre }}</h6>
                                                @if($ubicLabel)
                                                <div class="flex items-center gap-[4px] text-xs text-gray-400 mb-[4px]">
                                                    <i class="material-symbols-outlined !text-[13px]">place</i>
                                                    {{ $ubicLabel }}
                                                </div>
                                                @endif
                                                @if($destino->fecha_llegada || $destino->fecha_salida)
                                                <div class="flex items-center gap-[4px] text-xs text-gray-400">
                                                    <i class="material-symbols-outlined !text-[13px]">calendar_month</i>
                                                    {{ $destino->fecha_llegada?->format('d/m/Y') ?? '—' }}
                                                    @if($destino->fecha_salida) → {{ $destino->fecha_salida->format('d/m/Y') }} @endif
                                                </div>
                                                @endif
                                                @if($destino->notas)
                                                <p class="text-xs text-gray-400 mt-[6px]">{{ $destino->notas }}</p>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-[6px] flex-shrink-0">
                                                <button type="button"
                                                    onclick="abrirModalEditDestino({{ json_encode($editData) }})"
                                                    class="text-gray-400 hover:text-primary-500 transition-all p-[4px]">
                                                    <i class="material-symbols-outlined !text-[16px]">edit</i>
                                                </button>
                                                <form method="POST" action="{{ route('dashboard.viaje-destino.destroy', $destino) }}" class="inline"
                                                    onsubmit="return confirm('¿Eliminar este destino?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-danger-500 transition-all p-[4px]">
                                                        <i class="material-symbols-outlined !text-[16px]">delete</i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        {{-- ─── TAB: Reservas ────────────────────────────────────────── --}}
                        <div class="tab-pane hidden" id="tabReservas">
                            <div class="flex items-center justify-between mb-[20px]">
                                <h6 class="font-semibold text-black dark:text-white flex items-center gap-[8px] !mb-0">
                                    <i class="material-symbols-outlined !text-[18px] text-orange-500">confirmation_number</i>
                                    Reservas ({{ $reservas->count() }})
                                </h6>
                                <button type="button" onclick="abrirModalReserva()"
                                    class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-md bg-orange-500 text-white text-sm font-medium hover:bg-orange-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i> Agregar reserva
                                </button>
                            </div>

                            @if($reservas->isEmpty())
                            <div class="text-center py-[40px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">confirmation_number</i>
                                <p class="text-sm text-gray-400">Sin reservas registradas.</p>
                            </div>
                            @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-[#172036]">
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">Tipo</th>
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">Operador</th>
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">Título / Código</th>
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">Fechas</th>
                                            <th class="text-right text-xs text-gray-400 py-[10px] pr-[12px]">Monto</th>
                                            <th class="text-center text-xs text-gray-400 py-[10px] pr-[12px]">Estado</th>
                                            <th class="text-center text-xs text-gray-400 py-[10px]">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reservas as $reserva)
                                        <tr class="border-b border-gray-50 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                            <td class="py-[10px] pr-[12px]">
                                                @if($reserva->tipoReserva)
                                                <span class="inline-flex items-center gap-[4px] text-xs">
                                                    <i class="material-symbols-outlined !text-[14px] text-orange-500">{{ $reserva->tipoReserva->icono ?? 'confirmation_number' }}</i>
                                                    {{ $reserva->tipoReserva->nombre }}
                                                </span>
                                                @else —
                                                @endif
                                            </td>
                                            <td class="py-[10px] pr-[12px]">
                                                @php $op = $reserva->operadorViaje; @endphp
                                                @if($op)
                                                <div class="flex items-center gap-[6px]">
                                                    @if($op->logo_resuelto)
                                                        <img src="{{ $op->logo_resuelto }}" class="w-[24px] h-[24px] rounded object-cover" alt="">
                                                    @else
                                                        <span class="w-[24px] h-[24px] rounded bg-gray-200 dark:bg-[#172036] flex items-center justify-center">
                                                            <i class="material-symbols-outlined !text-[12px] text-gray-400">business</i>
                                                        </span>
                                                    @endif
                                                    <span class="text-xs text-black dark:text-white truncate max-w-[100px]">{{ $op->nombre_comercial_resuelto }}</span>
                                                </div>
                                                @else <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="py-[10px] pr-[12px]">
                                                <span class="block font-medium text-black dark:text-white text-xs">{{ $reserva->titulo ?? '—' }}</span>
                                                @if($reserva->codigo_reserva)
                                                <span class="text-[10px] text-gray-400 font-mono">{{ $reserva->codigo_reserva }}</span>
                                                @endif
                                            </td>
                                            <td class="py-[10px] pr-[12px] text-xs text-gray-500">
                                                {{ $reserva->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                                @if($reserva->fecha_fin)<br>{{ $reserva->fecha_fin->format('d/m/Y') }}@endif
                                            </td>
                                            <td class="py-[10px] pr-[12px] text-right">
                                                @if($reserva->monto !== null)
                                                <span class="text-sm font-semibold text-black dark:text-white">
                                                    {{ $reserva->moneda?->simbolo }} {{ number_format((float)$reserva->monto, 2) }}
                                                </span>
                                                @else <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="py-[10px] pr-[12px] text-center">
                                                @if($reserva->estadoReserva)
                                                <span class="inline-flex items-center gap-[3px] text-[10px] font-semibold py-[2px] px-[8px] rounded-full"
                                                    style="background-color:{{ $reserva->estadoReserva->color ?? '#e5e7eb' }}22;color:{{ $reserva->estadoReserva->color ?? '#6b7280' }}">
                                                    {{ $reserva->estadoReserva->nombre }}
                                                </span>
                                                @else <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="py-[10px] text-center">
                                                <div class="flex items-center justify-center gap-[6px]">
                                                    <button type="button"
                                                        onclick="abrirModalEditReserva({{ json_encode(['id'=>$reserva->id,'tipo_reserva_id'=>$reserva->tipo_reserva_id,'operador_viaje_id'=>$reserva->operador_viaje_id,'tipo_transporte_id'=>$reserva->tipo_transporte_id,'estado_reserva_id'=>$reserva->estado_reserva_id,'titulo'=>$reserva->titulo,'codigo_reserva'=>$reserva->codigo_reserva,'fecha_inicio'=>$reserva->fecha_inicio?->format('Y-m-d\TH:i'),'fecha_fin'=>$reserva->fecha_fin?->format('Y-m-d\TH:i'),'monto'=>$reserva->monto,'moneda_id'=>$reserva->moneda_id,'origen'=>$reserva->origen,'destino'=>$reserva->destino,'numero_servicio'=>$reserva->numero_servicio,'asiento'=>$reserva->asiento,'num_noches'=>$reserva->num_noches,'tipo_habitacion'=>$reserva->tipo_habitacion,'direccion'=>$reserva->direccion,'notas'=>$reserva->notas]) }})"
                                                        class="text-gray-400 hover:text-primary-500 p-[4px]">
                                                        <i class="material-symbols-outlined !text-[16px]">edit</i>
                                                    </button>
                                                    <form method="POST" action="{{ route('dashboard.viajes.reservas.destroy', $reserva) }}" class="inline"
                                                        onsubmit="return confirm('¿Eliminar esta reserva?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-gray-400 hover:text-danger-500 p-[4px]">
                                                            <i class="material-symbols-outlined !text-[16px]">delete</i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>

                        {{-- ─── TAB: Gastos ──────────────────────────────────────────── --}}
                        <div class="tab-pane hidden" id="tabGastos">
                            <div class="flex items-center justify-between mb-[20px]">
                                <h6 class="font-semibold text-black dark:text-white flex items-center gap-[8px] !mb-0">
                                    <i class="material-symbols-outlined !text-[18px] text-success-500">payments</i>
                                    Gastos
                                </h6>
                                <button type="button" onclick="abrirModalGasto()"
                                    class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-md bg-success-500 text-white text-sm font-medium hover:bg-success-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i> Agregar gasto
                                </button>
                            </div>

                            {{-- Compras vinculadas --}}
                            @if($comprasViaje->isNotEmpty())
                            <div class="mb-[24px]">
                                <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[6px] text-sm">
                                    <i class="material-symbols-outlined !text-[16px] text-primary-500">shopping_cart</i>
                                    Compras vinculadas al viaje
                                </h6>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-gray-100 dark:border-[#172036]">
                                                <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Fecha</th>
                                                <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Comercio</th>
                                                <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Categoría</th>
                                                <th class="text-right text-xs text-gray-400 py-[8px]">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($comprasViaje as $compra)
                                            <tr class="border-b border-gray-50 dark:border-[#172036]">
                                                <td class="py-[8px] pr-[12px] text-xs text-gray-500">{{ $compra->fecha_compra?->format('d/m/Y') ?? '—' }}</td>
                                                <td class="py-[8px] pr-[12px] text-xs text-black dark:text-white">{{ $compra->comercio?->empresa?->nombre_comercial ?? $compra->descripcion ?? '—' }}</td>
                                                <td class="py-[8px] pr-[12px] text-xs text-gray-500">{{ $compra->categoriaCompra?->nombre ?? '—' }}</td>
                                                <td class="py-[8px] text-right text-sm font-semibold text-black dark:text-white">
                                                    {{ $compra->moneda?->simbolo }} {{ number_format((float)$compra->total, 2) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            {{-- Gastos sueltos --}}
                            @if($gastosViaje->isNotEmpty())
                            <div>
                                <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[6px] text-sm">
                                    <i class="material-symbols-outlined !text-[16px] text-success-500">receipt</i>
                                    Gastos de viaje
                                </h6>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-gray-100 dark:border-[#172036]">
                                                <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Fecha</th>
                                                <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Descripción</th>
                                                <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Categoría</th>
                                                <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Miembro</th>
                                                <th class="text-right text-xs text-gray-400 py-[8px] pr-[12px]">Monto</th>
                                                <th class="text-center text-xs text-gray-400 py-[8px]">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($gastosViaje as $gasto)
                                            @php
                                                $persona = $gasto->hogarMiembro?->user?->persona;
                                                $nombreM = trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '')) ?: ($gasto->hogarMiembro?->apodo ?: '—');
                                            @endphp
                                            <tr class="border-b border-gray-50 dark:border-[#172036]">
                                                <td class="py-[8px] pr-[12px] text-xs text-gray-500">{{ $gasto->fecha?->format('d/m/Y') ?? '—' }}</td>
                                                <td class="py-[8px] pr-[12px] text-xs text-black dark:text-white">{{ $gasto->descripcion }}</td>
                                                <td class="py-[8px] pr-[12px]">
                                                    @if($gasto->categoria)
                                                    <span class="inline-flex items-center gap-[3px] text-[10px] font-semibold py-[2px] px-[6px] rounded-full"
                                                        style="background-color:{{ $gasto->categoria->color ?? '#e5e7eb' }}22;color:{{ $gasto->categoria->color ?? '#6b7280' }}">
                                                        <i class="material-symbols-outlined !text-[10px]">{{ $gasto->categoria->icono ?? 'label' }}</i>
                                                        {{ $gasto->categoria->nombre }}
                                                    </span>
                                                    @else <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="py-[8px] pr-[12px] text-xs text-gray-500">{{ $nombreM }}</td>
                                                <td class="py-[8px] pr-[12px] text-right text-sm font-semibold text-black dark:text-white">
                                                    {{ $gasto->moneda?->simbolo }} {{ number_format((float)$gasto->monto, 2) }}
                                                </td>
                                                <td class="py-[8px] text-center">
                                                    <div class="flex items-center justify-center gap-[4px]">
                                                        <button type="button"
                                                            onclick="abrirModalEditGasto({{ json_encode(['id'=>$gasto->id,'categoria_gasto_viaje_id'=>$gasto->categoria_gasto_viaje_id,'hogar_miembro_id'=>$gasto->hogar_miembro_id,'descripcion'=>$gasto->descripcion,'monto'=>$gasto->monto,'moneda_id'=>$gasto->moneda_id,'fecha'=>$gasto->fecha?->format('Y-m-d'),'notas'=>$gasto->notas]) }})"
                                                            class="text-gray-400 hover:text-primary-500 p-[4px]">
                                                            <i class="material-symbols-outlined !text-[16px]">edit</i>
                                                        </button>
                                                        <form method="POST" action="{{ route('dashboard.gasto-viaje.destroy', $gasto) }}" class="inline"
                                                            onsubmit="return confirm('¿Eliminar este gasto?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-gray-400 hover:text-danger-500 p-[4px]">
                                                                <i class="material-symbols-outlined !text-[16px]">delete</i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            @if($comprasViaje->isEmpty() && $gastosViaje->isEmpty())
                            <div class="text-center py-[40px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">payments</i>
                                <p class="text-sm text-gray-400">Sin gastos registrados.</p>
                            </div>
                            @endif
                        </div>

                        {{-- ─── TAB: Participantes ───────────────────────────────────── --}}
                        <div class="tab-pane hidden" id="tabParticipantes">
                            <div class="flex items-center justify-between mb-[20px]">
                                <h6 class="font-semibold text-black dark:text-white flex items-center gap-[8px] !mb-0">
                                    <i class="material-symbols-outlined !text-[18px] text-success-500">groups</i>
                                    Participantes ({{ $participantes->count() }})
                                </h6>
                                <button type="button" onclick="abrirModalParticipante()"
                                    class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-md bg-success-500 text-white text-sm font-medium hover:bg-success-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i> Agregar participante
                                </button>
                            </div>

                            @if($participantes->isEmpty())
                            <div class="text-center py-[40px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">group_off</i>
                                <p class="text-sm text-gray-400">Sin participantes registrados.</p>
                            </div>
                            @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[12px]">
                                @foreach($participantes as $part)
                                @php
                                    $prs = $part->hogarMiembro?->user?->persona;
                                    $nm  = trim(($prs->nombres ?? '') . ' ' . ($prs->apellido_paterno ?? '')) ?: ($part->hogarMiembro?->apodo ?: '—');
                                    $av  = $prs?->foto_url;
                                    $ini = mb_strtoupper(mb_substr($nm, 0, 1));
                                @endphp
                                <div class="flex items-center gap-[12px] p-[14px] bg-gray-50 dark:bg-[#15203c] rounded-md">
                                    @if($av)
                                        <img src="{{ $av }}" class="w-[40px] h-[40px] rounded-full object-cover flex-shrink-0" alt="">
                                    @else
                                        <span class="w-[40px] h-[40px] rounded-full bg-primary-100 flex items-center justify-center text-sm font-bold text-primary-700 flex-shrink-0">{{ $ini }}</span>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-black dark:text-white truncate">{{ $nm }}</p>
                                        @if($part->rol)
                                        <p class="text-xs text-gray-400">{{ $part->rol }}</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('dashboard.viaje-participante.destroy', $part) }}" class="inline"
                                        onsubmit="return confirm('¿Quitar a este participante?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-300 hover:text-danger-500 transition-all p-[4px]">
                                            <i class="material-symbols-outlined !text-[16px]">person_remove</i>
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        {{-- ─── TAB: Documentos ─────────────────────────────────────── --}}
                        <div class="tab-pane hidden" id="tabDocumentos">
                            <div class="flex items-center justify-between mb-[20px]">
                                <h6 class="font-semibold text-black dark:text-white flex items-center gap-[8px] !mb-0">
                                    <i class="material-symbols-outlined !text-[18px] text-purple-500">description</i>
                                    Documentos ({{ $documentos->count() }})
                                </h6>
                                <button type="button" onclick="abrirModalDocumento()"
                                    class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-md bg-purple-500 text-white text-sm font-medium hover:bg-purple-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i> Agregar documento
                                </button>
                            </div>

                            @if($documentos->isEmpty())
                            <div class="text-center py-[40px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">folder_open</i>
                                <p class="text-sm text-gray-400">Sin documentos registrados.</p>
                            </div>
                            @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-[#172036]">
                                            <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Tipo</th>
                                            <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Nombre</th>
                                            <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Reserva</th>
                                            <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Emisión</th>
                                            <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Vencimiento</th>
                                            <th class="text-center text-xs text-gray-400 py-[8px] pr-[12px]">Archivo</th>
                                            <th class="text-center text-xs text-gray-400 py-[8px]">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($documentos as $doc)
                                        <tr class="border-b border-gray-50 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                            <td class="py-[8px] pr-[12px]">
                                                @if($doc->tipoDocumentoViaje)
                                                <span class="inline-flex items-center gap-[3px] text-xs text-gray-500">
                                                    <i class="material-symbols-outlined !text-[13px]">{{ $doc->tipoDocumentoViaje->icono ?? 'description' }}</i>
                                                    {{ $doc->tipoDocumentoViaje->nombre }}
                                                </span>
                                                @else —
                                                @endif
                                            </td>
                                            <td class="py-[8px] pr-[12px] text-sm font-medium text-black dark:text-white">{{ $doc->nombre }}</td>
                                            <td class="py-[8px] pr-[12px] text-xs text-gray-500">{{ $doc->reserva?->titulo ?? '—' }}</td>
                                            <td class="py-[8px] pr-[12px] text-xs text-gray-500">{{ $doc->fecha_emision?->format('d/m/Y') ?? '—' }}</td>
                                            <td class="py-[8px] pr-[12px] text-xs text-gray-500">{{ $doc->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                                            <td class="py-[8px] pr-[12px] text-center">
                                                @if($doc->archivo_url)
                                                <a href="{{ $doc->archivo_url }}" target="_blank" class="text-primary-500 hover:text-primary-400">
                                                    <i class="material-symbols-outlined !text-[18px]">open_in_new</i>
                                                </a>
                                                @else <span class="text-gray-300">—</span>
                                                @endif
                                            </td>
                                            <td class="py-[8px] text-center">
                                                <div class="flex items-center justify-center gap-[4px]">
                                                    <button type="button"
                                                        onclick="abrirModalEditDocumento({{ json_encode(['id'=>$doc->id,'tipo_documento_viaje_id'=>$doc->tipo_documento_viaje_id,'reserva_id'=>$doc->reserva_id,'nombre'=>$doc->nombre,'fecha_emision'=>$doc->fecha_emision?->format('Y-m-d'),'fecha_vencimiento'=>$doc->fecha_vencimiento?->format('Y-m-d'),'notas'=>$doc->notas]) }})"
                                                        class="text-gray-400 hover:text-primary-500 p-[4px]">
                                                        <i class="material-symbols-outlined !text-[16px]">edit</i>
                                                    </button>
                                                    <form method="POST" action="{{ route('dashboard.documento-viaje.destroy', $doc) }}" class="inline"
                                                        onsubmit="return confirm('¿Eliminar este documento?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-gray-400 hover:text-danger-500 p-[4px]">
                                                            <i class="material-symbols-outlined !text-[16px]">delete</i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════
             MODAL: Destino
        ════════════════════════════════════════════════════════════════════════ --}}
        <div id="modalDestino" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalDestino()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-[16px]">
                <div class="bg-white dark:bg-[#0c1427] rounded-md shadow-xl w-full max-w-[580px] max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                        <h6 class="font-semibold text-black dark:text-white !mb-0" id="modalDestinoTitle">Agregar destino</h6>
                        <button type="button" onclick="cerrarModalDestino()" class="text-gray-400 hover:text-gray-600 transition-all">
                            <i class="material-symbols-outlined">close</i>
                        </button>
                    </div>
                    <form id="formDestino" method="POST" action="{{ route('dashboard.viajes.destinos.store', $viaje) }}">
                        @csrf
                        <span id="formDestinoMethod"></span>
                        <div class="p-[20px] space-y-[16px]">

                            {{-- Nombre --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">
                                    Nombre del destino <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="nombre" id="destinoNombre" required
                                    placeholder="Ej: Cusco, Machu Picchu, París…"
                                    class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                            </div>

                            {{-- Radio Perú / Extranjero — mismo estilo que propiedades --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[8px]">Ubicación</label>
                                <div class="flex items-center gap-[25px]">
                                    <label class="flex items-center gap-[8px] cursor-pointer">
                                        <input type="radio" name="ubicacion_tipo" value="peru" id="destLocPeru" checked
                                            class="cursor-pointer accent-primary-500 w-[16px] h-[16px]">
                                        <span class="text-black dark:text-white text-sm">
                                            <i class="ri-map-pin-line text-primary-500 mr-[2px]"></i>
                                            Perú
                                        </span>
                                    </label>
                                    <label class="flex items-center gap-[8px] cursor-pointer">
                                        <input type="radio" name="ubicacion_tipo" value="extranjero" id="destLocExt"
                                            class="cursor-pointer accent-primary-500 w-[16px] h-[16px]">
                                        <span class="text-black dark:text-white text-sm">
                                            <i class="ri-global-line text-primary-500 mr-[2px]"></i>
                                            Extranjero
                                        </span>
                                    </label>
                                </div>
                            </div>

                            {{-- Sección Perú: Departamento → Provincia → Distrito (Select2) --}}
                            <div id="destSecPeru" class="grid grid-cols-1 sm:grid-cols-3 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Departamento</label>
                                    <select id="destDepto"
                                        class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                        <option value="">Selecciona departamento…</option>
                                        @foreach($departamentos as $dep)
                                        <option value="{{ $dep->inei }}">{{ $dep->departamento }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Provincia</label>
                                    <select id="destProv"
                                        class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                        <option value="">Selecciona provincia…</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Distrito</label>
                                    {{-- Select2 con dropdownParent: '#modalDestino' — ~1800 opciones --}}
                                    <select name="distrito_inei" id="destDistrito">
                                        <option value="">Selecciona distrito…</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Sección Extranjero: País → Ciudad (Select2) — inicialmente oculta --}}
                            <div id="destSecExt" class="grid grid-cols-1 sm:grid-cols-2 gap-[12px]" style="display:none">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">País</label>
                                    <select name="pais_iso2" id="destPais">
                                        <option value="">Selecciona país…</option>
                                        @foreach($paises as $pais)
                                        <option value="{{ $pais->iso2 }}">{{ $pais->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Ciudad</label>
                                    <select name="ciudad_id" id="destCiudad">
                                        <option value="">Selecciona ciudad…</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Fechas --}}
                            <div class="grid grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Llegada</label>
                                    <input type="date" name="fecha_llegada" id="destinoLlegada"
                                        class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Salida</label>
                                    <input type="date" name="fecha_salida" id="destinoSalida"
                                        class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Orden en la línea de tiempo</label>
                                <input type="number" name="orden" id="destinoOrden" min="0"
                                    class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 transition-all focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Notas</label>
                                <textarea name="notas" id="destinoNotas" rows="2"
                                    class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] py-[10px] block w-full outline-0 transition-all focus:border-primary-500 text-sm"></textarea>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-[10px] px-[20px] pb-[20px]">
                            <button type="button" onclick="cerrarModalDestino()"
                                class="px-[16px] py-[8px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-500 hover:border-primary-500 hover:text-primary-500 transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-[16px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════
             MODAL: Reserva
        ════════════════════════════════════════════════════════════════════════ --}}
        <div id="modalReserva" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalReserva()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-[16px]">
                <div class="bg-white dark:bg-[#0c1427] rounded-md shadow-xl w-full max-w-[640px] max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                        <h6 class="font-semibold text-black dark:text-white !mb-0" id="modalReservaTitle">Agregar reserva</h6>
                        <button type="button" onclick="cerrarModalReserva()" class="text-gray-400 hover:text-gray-600 transition-all">
                            <i class="material-symbols-outlined">close</i>
                        </button>
                    </div>
                    <form id="formReserva" method="POST" action="{{ route('dashboard.viajes.reservas.store', $viaje) }}">
                        @csrf
                        <span id="formReservaMethod"></span>
                        <div class="p-[20px] space-y-[16px]">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Tipo de reserva <span class="text-danger-500">*</span></label>
                                    <select name="tipo_reserva_id" id="reservaTipoReserva" class="select2-icono">
                                        <option value="">Seleccionar…</option>
                                        @foreach($tiposReserva as $tr)
                                        <option value="{{ $tr->id }}" data-nombre="{{ strtolower($tr->nombre) }}" data-icono="{{ $tr->icono }}">{{ $tr->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Operador</label>
                                    <select name="operador_viaje_id" id="reservaOperador" class="select2-operador">
                                        <option value="">Sin operador</option>
                                        @foreach($operadores as $op)
                                        <option value="{{ $op->id }}"
                                            data-logo="{{ $op->logo_resuelto }}"
                                            data-icono="{{ $op->tipoOperadorViaje?->icono }}"
                                            data-ruc="{{ $op->empresa?->ruc }}">{{ $op->nombre_comercial_resuelto }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Título</label>
                                    <input type="text" name="titulo" id="reservaTitulo"
                                        class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Código de reserva</label>
                                    <input type="text" name="codigo_reserva" id="reservaCodigo"
                                        class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Fecha inicio</label>
                                    <input type="datetime-local" name="fecha_inicio" id="reservaFechaInicio"
                                        class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Fecha fin</label>
                                    <input type="datetime-local" name="fecha_fin" id="reservaFechaFin"
                                        class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Monto</label>
                                    <input type="number" name="monto" id="reservaMonto" min="0" step="0.01"
                                        class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Moneda</label>
                                    <select name="moneda_id" id="reservaMoneda">
                                        <option value="">Seleccionar…</option>
                                        @foreach($monedas as $m)
                                        <option value="{{ $m->id }}">{{ $m->simbolo }} {{ $m->codigo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Estado</label>
                                <select name="estado_reserva_id" id="reservaEstado" class="select2-icono">
                                    <option value="">Sin estado</option>
                                    @foreach($estadosReserva as $er)
                                    <option value="{{ $er->id }}" data-icono="{{ $er->icono }}" data-color="{{ $er->color }}">{{ $er->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Sección transporte (vuelo/bus/tren) --}}
                            <div id="seccionTransporte" class="hidden border border-primary-200 dark:border-[#1a2d4d] rounded-md p-[14px] space-y-[12px]">
                                <h6 class="text-xs font-semibold text-primary-600 flex items-center gap-[6px] !mb-0">
                                    <i class="material-symbols-outlined !text-[14px]">flight</i> Datos de transporte
                                </h6>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Tipo de transporte</label>
                                    <select name="tipo_transporte_id" id="reservaTipoTransporte" class="select2-icono">
                                        <option value="">Seleccionar…</option>
                                        @foreach($tiposTransporte as $tt)
                                        <option value="{{ $tt->id }}" data-icono="{{ $tt->icono }}">{{ $tt->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-[12px]">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Origen</label>
                                        <input type="text" name="origen" id="reservaOrigen"
                                            class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Destino</label>
                                        <input type="text" name="destino" id="reservaDestino"
                                            class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-[12px]">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Nro. vuelo/servicio</label>
                                        <input type="text" name="numero_servicio" id="reservaNumServicio"
                                            class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Asiento</label>
                                        <input type="text" name="asiento" id="reservaAsiento"
                                            class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                    </div>
                                </div>
                            </div>

                            {{-- Sección hospedaje --}}
                            <div id="seccionHospedaje" class="hidden border border-orange-200 dark:border-[#2d1a0a] rounded-md p-[14px] space-y-[12px]">
                                <h6 class="text-xs font-semibold text-orange-600 flex items-center gap-[6px] !mb-0">
                                    <i class="material-symbols-outlined !text-[14px]">hotel</i> Datos de hospedaje
                                </h6>
                                <div class="grid grid-cols-2 gap-[12px]">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Nro. noches</label>
                                        <input type="number" name="num_noches" id="reservaNumNoches" min="1"
                                            class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Tipo de habitación</label>
                                        <input type="text" name="tipo_habitacion" id="reservaTipoHabitacion"
                                            class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Dirección</label>
                                    <input type="text" name="direccion" id="reservaDireccion"
                                        class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Notas</label>
                                <textarea name="notas" id="reservaNotas" rows="2"
                                    class="w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] py-[10px] text-sm outline-0 focus:border-primary-500"></textarea>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-[10px] px-[20px] pb-[20px]">
                            <button type="button" onclick="cerrarModalReserva()"
                                class="px-[16px] py-[8px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-500 hover:border-primary-500 hover:text-primary-500 transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-[16px] py-[8px] rounded-md bg-orange-500 text-white text-sm font-medium hover:bg-orange-400 transition-all">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════
             MODAL: Gasto
        ════════════════════════════════════════════════════════════════════════ --}}
        <div id="modalGasto" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalGasto()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-[16px]">
                <div class="bg-white dark:bg-[#0c1427] rounded-md shadow-xl w-full max-w-[520px] max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                        <h6 class="font-semibold text-black dark:text-white !mb-0" id="modalGastoTitle">Agregar gasto</h6>
                        <button type="button" onclick="cerrarModalGasto()" class="text-gray-400 hover:text-gray-600 transition-all">
                            <i class="material-symbols-outlined">close</i>
                        </button>
                    </div>
                    <form id="formGasto" method="POST" action="{{ route('dashboard.viajes.gastos.store', $viaje) }}">
                        @csrf
                        <span id="formGastoMethod"></span>
                        <div class="p-[20px] space-y-[16px]">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Descripción <span class="text-danger-500">*</span></label>
                                <input type="text" name="descripcion" id="gastoDescripcion" required
                                    class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                            </div>
                            <div class="grid grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Monto <span class="text-danger-500">*</span></label>
                                    <input type="number" name="monto" id="gastoMonto" min="0.01" step="0.01" required
                                        class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Moneda <span class="text-danger-500">*</span></label>
                                    <select name="moneda_id" id="gastoMoneda" required>
                                        <option value="">Seleccionar…</option>
                                        @foreach($monedas as $m)
                                        <option value="{{ $m->id }}">{{ $m->simbolo }} {{ $m->codigo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Categoría</label>
                                    <select name="categoria_gasto_viaje_id" id="gastoCategoria" class="select2-icono">
                                        <option value="">Sin categoría</option>
                                        @foreach($categoriasGasto as $cat)
                                        <option value="{{ $cat->id }}" data-color="{{ $cat->color }}" data-icono="{{ $cat->icono }}">{{ $cat->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Fecha <span class="text-danger-500">*</span></label>
                                    <input type="date" name="fecha" id="gastoFecha" required
                                        class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Miembro (opcional)</label>
                                <select name="hogar_miembro_id" id="gastoMiembro" class="select2-miembro">
                                    <option value="">Sin asignar</option>
                                    @foreach($miembros as $mb)
                                    @php $prs = $mb->user?->persona; $nm2 = trim(($prs->nombres ?? '') . ' ' . ($prs->apellido_paterno ?? '')) ?: ($mb->apodo ?: 'Miembro'); @endphp
                                    <option value="{{ $mb->id }}" data-foto="{{ $prs?->foto_url }}">{{ $nm2 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Notas</label>
                                <textarea name="notas" id="gastoNotas" rows="2"
                                    class="w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] py-[10px] text-sm outline-0 focus:border-primary-500"></textarea>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-[10px] px-[20px] pb-[20px]">
                            <button type="button" onclick="cerrarModalGasto()"
                                class="px-[16px] py-[8px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-500 hover:border-primary-500 hover:text-primary-500 transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-[16px] py-[8px] rounded-md bg-success-500 text-white text-sm font-medium hover:bg-success-400 transition-all">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════
             MODAL: Participante
        ════════════════════════════════════════════════════════════════════════ --}}
        <div id="modalParticipante" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalParticipante()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-[16px]">
                <div class="bg-white dark:bg-[#0c1427] rounded-md shadow-xl w-full max-w-[440px]">
                    <div class="flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                        <h6 class="font-semibold text-black dark:text-white !mb-0">Agregar participante</h6>
                        <button type="button" onclick="cerrarModalParticipante()" class="text-gray-400 hover:text-gray-600 transition-all">
                            <i class="material-symbols-outlined">close</i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('dashboard.viajes.participantes.store', $viaje) }}">
                        @csrf
                        <div class="p-[20px] space-y-[16px]">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Miembro <span class="text-danger-500">*</span></label>
                                <select name="hogar_miembro_id" id="partMiembro" required class="select2-miembro">
                                    <option value="">Seleccionar miembro…</option>
                                    @foreach($miembros as $mb)
                                    @php $prs = $mb->user?->persona; $nm2 = trim(($prs->nombres ?? '') . ' ' . ($prs->apellido_paterno ?? '')) ?: ($mb->apodo ?: 'Miembro'); @endphp
                                    <option value="{{ $mb->id }}" data-foto="{{ $prs?->foto_url }}">{{ $nm2 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Rol</label>
                                <input type="text" name="rol" id="partRol" placeholder="Ej: Organizador, Acompañante…"
                                    class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-[10px] px-[20px] pb-[20px]">
                            <button type="button" onclick="cerrarModalParticipante()"
                                class="px-[16px] py-[8px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-500 hover:border-primary-500 hover:text-primary-500 transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-[16px] py-[8px] rounded-md bg-success-500 text-white text-sm font-medium hover:bg-success-400 transition-all">
                                Agregar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════
             MODAL: Documento
        ════════════════════════════════════════════════════════════════════════ --}}
        <div id="modalDocumento" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalDocumento()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-[16px]">
                <div class="bg-white dark:bg-[#0c1427] rounded-md shadow-xl w-full max-w-[520px] max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                        <h6 class="font-semibold text-black dark:text-white !mb-0" id="modalDocumentoTitle">Agregar documento</h6>
                        <button type="button" onclick="cerrarModalDocumento()" class="text-gray-400 hover:text-gray-600 transition-all">
                            <i class="material-symbols-outlined">close</i>
                        </button>
                    </div>
                    <form id="formDocumento" method="POST" enctype="multipart/form-data" action="{{ route('dashboard.viajes.documentos.store', $viaje) }}">
                        @csrf
                        <span id="formDocumentoMethod"></span>
                        <div class="p-[20px] space-y-[16px]">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Tipo de documento <span class="text-danger-500">*</span></label>
                                <select name="tipo_documento_viaje_id" id="docTipo" required class="select2-icono">
                                    <option value="">Seleccionar…</option>
                                    @foreach($tiposDocViaje as $td)
                                    <option value="{{ $td->id }}" data-icono="{{ $td->icono }}">{{ $td->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Nombre <span class="text-danger-500">*</span></label>
                                <input type="text" name="nombre" id="docNombre" required
                                    class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Reserva vinculada (opcional)</label>
                                <select name="reserva_id" id="docReserva">
                                    <option value="">Sin reserva</option>
                                    @foreach($reservasSelect as $res)
                                    <option value="{{ $res->id }}">{{ $res->titulo ?? $res->codigo_reserva ?? $res->tipoReserva?->nombre }} ({{ $res->fecha_inicio?->format('d/m/Y') ?? '—' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Fecha emisión</label>
                                    <input type="date" name="fecha_emision" id="docEmision"
                                        class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Fecha vencimiento</label>
                                    <input type="date" name="fecha_vencimiento" id="docVencimiento"
                                        class="h-[42px] w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] text-sm outline-0 focus:border-primary-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Archivo</label>
                                <input type="file" name="archivo" id="docArchivo" accept=".pdf,.jpg,.jpeg,.png,.gif,.webp"
                                    class="w-full text-sm text-gray-500 file:mr-[12px] file:py-[8px] file:px-[14px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Notas</label>
                                <textarea name="notas" id="docNotas" rows="2"
                                    class="w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[14px] py-[10px] text-sm outline-0 focus:border-primary-500"></textarea>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-[10px] px-[20px] pb-[20px]">
                            <button type="button" onclick="cerrarModalDocumento()"
                                class="px-[16px] py-[8px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-500 hover:border-primary-500 hover:text-primary-500 transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-[16px] py-[8px] rounded-md bg-purple-500 text-white text-sm font-medium hover:bg-purple-400 transition-all">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('partials.front.scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        // ── Constantes de URL ──────────────────────────────────────────────────
        const URL_DESTINOS_STORE    = '{{ route('dashboard.viajes.destinos.store', $viaje) }}';
        const URL_DESTINO_UPDATE    = '{{ url('dashboard/viaje-destino') }}';
        const URL_RESERVAS_STORE    = '{{ route('dashboard.viajes.reservas.store', $viaje) }}';
        const URL_RESERVA_UPDATE    = '{{ url('dashboard/viajes-reserva') }}';
        const URL_GASTOS_STORE      = '{{ route('dashboard.viajes.gastos.store', $viaje) }}';
        const URL_GASTO_UPDATE      = '{{ url('dashboard/gasto-viaje') }}';
        const URL_DOCUMENTOS_STORE  = '{{ route('dashboard.viajes.documentos.store', $viaje) }}';
        const URL_DOCUMENTO_UPDATE  = '{{ url('dashboard/documento-viaje') }}';

        // ── Tabs ──────────────────────────────────────────────────────────────
        document.querySelectorAll('#trezo-tabs .nav-link').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#trezo-tabs .nav-link').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('#trezo-tabs .tab-pane').forEach(p => p.classList.add('hidden'));
                btn.classList.add('active');
                const pane = document.getElementById(btn.dataset.tab);
                if (pane) { pane.classList.remove('hidden'); pane.classList.add('active'); }
                initS2InTab(btn.dataset.tab);
            });
        });

        // ── Select2 template functions ────────────────────────────────────────
        function fmtIcono(o) {
            if (!o.id) return o.text;
            const ic = $(o.element).data('icono'), co = $(o.element).data('color');
            const dot = co ? `<span class="inline-block w-[10px] h-[10px] rounded-full mr-1 flex-shrink-0" style="background:${co}"></span>` : '';
            const i   = ic ? `<i class="material-symbols-outlined !text-[18px] mr-1 align-middle flex-shrink-0">${ic}</i>` : '';
            return $(`<span class="flex items-center">${dot}${i}<span>${o.text}</span></span>`);
        }
        function fmtOperador(o) {
            if (!o.id) return o.text;
            const lo = $(o.element).data('logo'), ic = $(o.element).data('icono'), ruc = $(o.element).data('ruc');
            const media = lo ? `<img src="${lo}" class="w-[22px] h-[22px] rounded object-cover mr-2 flex-shrink-0">`
                             : (ic ? `<i class="material-symbols-outlined !text-[18px] mr-2 flex-shrink-0">${ic}</i>` : '');
            const sub = ruc ? `<span class="text-xs text-gray-500 ml-1">${ruc}</span>` : '';
            return $(`<span class="flex items-center">${media}<span>${o.text}</span>${sub}</span>`);
        }
        function fmtMiembro(o) {
            if (!o.id) return o.text;
            const f = $(o.element).data('foto');
            const av = f ? `<img src="${f}" class="w-[22px] h-[22px] rounded-full object-cover mr-2 flex-shrink-0">`
                         : `<i class="material-symbols-outlined !text-[20px] mr-2 flex-shrink-0">person</i>`;
            return $(`<span class="flex items-center">${av}<span>${o.text}</span></span>`);
        }

        function initS2Modal(selector, parent) {
            $(selector).select2({ dropdownParent: $(parent), width: '100%' });
        }

        function initS2InTab(tabId) { /* tabs don't need dropdownParent */ }

        // Inicializar Select2 en modales cuando se abren
        function initReservaS2() {
            if (!$('#reservaTipoReserva').data('select2')) {
                $('#modalReserva .select2-icono').select2({ dropdownParent: $('#modalReserva'), templateResult: fmtIcono, templateSelection: fmtIcono, escapeMarkup: m => m, width: '100%' });
                $('#modalReserva .select2-operador').select2({ dropdownParent: $('#modalReserva'), templateResult: fmtOperador, templateSelection: fmtOperador, escapeMarkup: m => m, width: '100%' });
                $('#reservaMoneda').select2({ dropdownParent: $('#modalReserva'), width: '100%' });
                $('#reservaTipoReserva').on('change', function() {
                    const nombre = $(this).find(':selected').data('nombre') || '';
                    resolverSeccionReserva(nombre);
                });
            }
        }
        function initGastoS2() {
            if (!$('#gastoMoneda').data('select2')) {
                $('#gastoMoneda').select2({ dropdownParent: $('#modalGasto'), width: '100%' });
                $('#modalGasto .select2-icono').select2({ dropdownParent: $('#modalGasto'), templateResult: fmtIcono, templateSelection: fmtIcono, escapeMarkup: m => m, width: '100%' });
                $('#modalGasto .select2-miembro').select2({ dropdownParent: $('#modalGasto'), templateResult: fmtMiembro, templateSelection: fmtMiembro, escapeMarkup: m => m, width: '100%' });
            }
        }
        function initParticipanteS2() {
            if (!$('#partMiembro').data('select2')) {
                $('#modalParticipante .select2-miembro').select2({ dropdownParent: $('#modalParticipante'), templateResult: fmtMiembro, templateSelection: fmtMiembro, escapeMarkup: m => m, width: '100%' });
            }
        }
        function initDocumentoS2() {
            if (!$('#docTipo').data('select2')) {
                $('#modalDocumento .select2-icono').select2({ dropdownParent: $('#modalDocumento'), templateResult: fmtIcono, templateSelection: fmtIcono, escapeMarkup: m => m, width: '100%' });
                $('#docReserva').select2({ dropdownParent: $('#modalDocumento'), width: '100%' });
            }
        }

        // ── Sección dinámica Reserva ──────────────────────────────────────────
        function resolverSeccionReserva(nombre) {
            const n = (nombre || '').toLowerCase();
            const esTransporte = ['vuelo','bus','tren','transporte','ferry','barco','crucero','colectivo','taxi'].some(k => n.includes(k));
            const esHospedaje  = ['hotel','hostal','hospedaje','alojamiento','airbnb','resort','departamento'].some(k => n.includes(k));
            document.getElementById('seccionTransporte').classList.toggle('hidden', !esTransporte);
            document.getElementById('seccionHospedaje').classList.toggle('hidden', !esHospedaje);
        }

        // ── Ubigeo data (arrays pre-construidos en ViajeController::show) ─────
        const allProvinciasViaje = {!! json_encode($provinciasArr) !!};
        const allDistritosViaje  = {!! json_encode($distritosArr) !!};
        const allCiudadesViaje   = {!! json_encode($ciudadesArr) !!};

        function initDestinoS2() {
            if (!$('#destDistrito').data('select2')) {
                $('#destDistrito').select2({ dropdownParent: $('#modalDestino'), width: '100%', placeholder: 'Selecciona distrito…', allowClear: true });
            }
            if (!$('#destPais').data('select2')) {
                $('#destPais').select2({ dropdownParent: $('#modalDestino'), width: '100%', placeholder: 'Selecciona país…', allowClear: true });
            }
            if (!$('#destCiudad').data('select2')) {
                $('#destCiudad').select2({ dropdownParent: $('#modalDestino'), width: '100%', placeholder: 'Selecciona ciudad…', allowClear: true });
            }
        }

        function toggleDestinoUbicacion() {
            const isPeru = document.getElementById('destLocPeru').checked;
            document.getElementById('destSecPeru').style.display = isPeru ? '' : 'none';
            document.getElementById('destSecExt').style.display  = isPeru ? 'none' : '';
        }
        document.getElementById('destLocPeru').addEventListener('change', toggleDestinoUbicacion);
        document.getElementById('destLocExt').addEventListener('change', toggleDestinoUbicacion);

        document.getElementById('destDepto').addEventListener('change', function () {
            const depInei = this.value;
            const provSel = document.getElementById('destProv');
            provSel.innerHTML = '<option value="">Selecciona provincia…</option>';
            if ($('#destDistrito').data('select2')) { $('#destDistrito').select2('destroy'); }
            document.getElementById('destDistrito').innerHTML = '<option value="">Selecciona distrito…</option>';
            $('#destDistrito').select2({ dropdownParent: $('#modalDestino'), width: '100%', placeholder: 'Selecciona distrito…', allowClear: true });
            allProvinciasViaje.filter(p => p.departamento_inei === depInei).forEach(p => {
                const o = document.createElement('option'); o.value = p.inei; o.textContent = p.nombre; provSel.appendChild(o);
            });
        });
        document.getElementById('destProv').addEventListener('change', function () {
            const provInei = this.value;
            const distSel  = document.getElementById('destDistrito');
            if ($('#destDistrito').data('select2')) { $('#destDistrito').select2('destroy'); }
            distSel.innerHTML = '<option value="">Selecciona distrito…</option>';
            allDistritosViaje.filter(d => d.provincia_inei === provInei).forEach(d => {
                const o = document.createElement('option'); o.value = d.inei; o.textContent = d.nombre; distSel.appendChild(o);
            });
            $('#destDistrito').select2({ dropdownParent: $('#modalDestino'), width: '100%', placeholder: 'Selecciona distrito…', allowClear: true });
        });
        document.getElementById('destPais').addEventListener('change', function () {
            const iso2 = this.value;
            const ciudadSel = document.getElementById('destCiudad');
            if ($('#destCiudad').data('select2')) { $('#destCiudad').select2('destroy'); }
            ciudadSel.innerHTML = '<option value="">Selecciona ciudad…</option>';
            allCiudadesViaje.filter(c => c.pais_iso2 === iso2).forEach(c => {
                const o = document.createElement('option'); o.value = c.id; o.textContent = c.nombre; ciudadSel.appendChild(o);
            });
            $('#destCiudad').select2({ dropdownParent: $('#modalDestino'), width: '100%', placeholder: 'Selecciona ciudad…', allowClear: true });
        });

        // ── Modal: Destino ────────────────────────────────────────────────────
        function resetModalDestino() {
            document.getElementById('destinoNombre').value  = '';
            document.getElementById('destinoLlegada').value = '';
            document.getElementById('destinoSalida').value  = '';
            document.getElementById('destinoOrden').value   = '';
            document.getElementById('destinoNotas').value   = '';
            document.getElementById('destDepto').value      = '';
            document.getElementById('destProv').innerHTML   = '<option value="">Selecciona provincia…</option>';
            if ($('#destDistrito').data('select2')) {
                $('#destDistrito').val(null).trigger('change');
            } else {
                document.getElementById('destDistrito').innerHTML = '<option value="">Selecciona distrito…</option>';
            }
            if ($('#destPais').data('select2')) {
                $('#destPais').val(null).trigger('change');
            } else {
                document.getElementById('destPais').value = '';
            }
            if ($('#destCiudad').data('select2')) {
                $('#destCiudad').val(null).trigger('change');
            } else {
                document.getElementById('destCiudad').innerHTML = '<option value="">Selecciona ciudad…</option>';
            }
            document.getElementById('destLocPeru').checked = true;
            toggleDestinoUbicacion();
        }
        function abrirModalDestino() {
            document.getElementById('modalDestinoTitle').textContent = 'Agregar destino';
            document.getElementById('formDestino').action = URL_DESTINOS_STORE;
            document.getElementById('formDestinoMethod').innerHTML = '';
            document.getElementById('modalDestino').classList.remove('hidden');
            initDestinoS2();
            resetModalDestino();
        }
        function cerrarModalDestino() {
            document.getElementById('modalDestino').classList.add('hidden');
        }
        function abrirModalEditDestino(d) {
            document.getElementById('modalDestinoTitle').textContent = 'Editar destino';
            document.getElementById('formDestino').action = URL_DESTINO_UPDATE + '/' + d.id;
            document.getElementById('formDestinoMethod').innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="PUT">';
            document.getElementById('modalDestino').classList.remove('hidden');
            initDestinoS2();
            resetModalDestino();
            document.getElementById('destinoNombre').value  = d.nombre || '';
            document.getElementById('destinoLlegada').value = d.fecha_llegada || '';
            document.getElementById('destinoSalida').value  = d.fecha_salida || '';
            document.getElementById('destinoOrden').value   = d.orden !== null ? d.orden : '';
            document.getElementById('destinoNotas').value   = d.notas || '';

            if (d.ubicacion_tipo === 'peru' && d.distrito_dep) {
                document.getElementById('destLocPeru').checked = true;
                toggleDestinoUbicacion();
                document.getElementById('destDepto').value = d.distrito_dep;
                // llenar provincias
                const provSel = document.getElementById('destProv');
                provSel.innerHTML = '<option value="">Selecciona provincia…</option>';
                allProvinciasViaje.filter(p => p.departamento_inei === d.distrito_dep).forEach(p => {
                    const o = document.createElement('option'); o.value = p.inei; o.textContent = p.nombre;
                    if (p.inei === d.distrito_prov) o.selected = true;
                    provSel.appendChild(o);
                });
                // destruir, poblar y reiniciar Select2 en destDistrito
                if ($('#destDistrito').data('select2')) { $('#destDistrito').select2('destroy'); }
                const distSel = document.getElementById('destDistrito');
                distSel.innerHTML = '<option value="">Selecciona distrito…</option>';
                allDistritosViaje.filter(d2 => d2.provincia_inei === d.distrito_prov).forEach(d2 => {
                    const o = document.createElement('option'); o.value = d2.inei; o.textContent = d2.nombre;
                    distSel.appendChild(o);
                });
                $('#destDistrito').select2({ dropdownParent: $('#modalDestino'), width: '100%', placeholder: 'Selecciona distrito…', allowClear: true });
                $('#destDistrito').val(d.distrito_inei).trigger('change');
            } else if (d.ubicacion_tipo === 'extranjero' && d.pais_iso2) {
                document.getElementById('destLocExt').checked = true;
                toggleDestinoUbicacion();
                $('#destPais').val(d.pais_iso2).trigger('change');
                // destruir, poblar y reiniciar Select2 en destCiudad
                if ($('#destCiudad').data('select2')) { $('#destCiudad').select2('destroy'); }
                const ciudadSel = document.getElementById('destCiudad');
                ciudadSel.innerHTML = '<option value="">Selecciona ciudad…</option>';
                allCiudadesViaje.filter(c => c.pais_iso2 === d.pais_iso2).forEach(c => {
                    const o = document.createElement('option'); o.value = c.id; o.textContent = c.nombre;
                    ciudadSel.appendChild(o);
                });
                $('#destCiudad').select2({ dropdownParent: $('#modalDestino'), width: '100%', placeholder: 'Selecciona ciudad…', allowClear: true });
                $('#destCiudad').val(String(d.ciudad_id)).trigger('change');
            }
        }

        // ── Modal: Reserva ────────────────────────────────────────────────────
        function abrirModalReserva() {
            document.getElementById('modalReservaTitle').textContent = 'Agregar reserva';
            document.getElementById('formReserva').action = URL_RESERVAS_STORE;
            document.getElementById('formReservaMethod').innerHTML = '';
            ['reservaTitulo','reservaCodigo','reservaFechaInicio','reservaFechaFin','reservaMonto',
             'reservaOrigen','reservaDestino','reservaNumServicio','reservaAsiento',
             'reservaNumNoches','reservaTipoHabitacion','reservaDireccion','reservaNotas'].forEach(id => {
                document.getElementById(id).value = '';
            });
            document.getElementById('seccionTransporte').classList.add('hidden');
            document.getElementById('seccionHospedaje').classList.add('hidden');
            document.getElementById('modalReserva').classList.remove('hidden');
            initReservaS2();
            $('#reservaTipoReserva').val(null).trigger('change');
            $('#reservaOperador').val(null).trigger('change');
            $('#reservaMoneda').val(null).trigger('change');
            $('#reservaEstado').val(null).trigger('change');
        }
        function cerrarModalReserva() {
            document.getElementById('modalReserva').classList.add('hidden');
        }
        function abrirModalEditReserva(r) {
            document.getElementById('modalReservaTitle').textContent = 'Editar reserva';
            document.getElementById('formReserva').action = URL_RESERVA_UPDATE + '/' + r.id;
            document.getElementById('formReservaMethod').innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="PUT">';
            document.getElementById('reservaTitulo').value         = r.titulo || '';
            document.getElementById('reservaCodigo').value         = r.codigo_reserva || '';
            document.getElementById('reservaFechaInicio').value    = r.fecha_inicio || '';
            document.getElementById('reservaFechaFin').value       = r.fecha_fin || '';
            document.getElementById('reservaMonto').value          = r.monto || '';
            document.getElementById('reservaOrigen').value         = r.origen || '';
            document.getElementById('reservaDestino').value        = r.destino || '';
            document.getElementById('reservaNumServicio').value    = r.numero_servicio || '';
            document.getElementById('reservaAsiento').value        = r.asiento || '';
            document.getElementById('reservaNumNoches').value      = r.num_noches || '';
            document.getElementById('reservaTipoHabitacion').value = r.tipo_habitacion || '';
            document.getElementById('reservaDireccion').value      = r.direccion || '';
            document.getElementById('reservaNotas').value          = r.notas || '';
            document.getElementById('modalReserva').classList.remove('hidden');
            initReservaS2();
            $('#reservaTipoReserva').val(r.tipo_reserva_id).trigger('change');
            $('#reservaOperador').val(r.operador_viaje_id).trigger('change');
            $('#reservaMoneda').val(r.moneda_id).trigger('change');
            $('#reservaEstado').val(r.estado_reserva_id).trigger('change');
            $('#reservaTipoTransporte').val(r.tipo_transporte_id).trigger('change');
            const nombreTipo = $('#reservaTipoReserva option[value="'+r.tipo_reserva_id+'"]').data('nombre') || '';
            resolverSeccionReserva(nombreTipo);
        }

        // ── Modal: Gasto ──────────────────────────────────────────────────────
        function abrirModalGasto() {
            document.getElementById('modalGastoTitle').textContent = 'Agregar gasto';
            document.getElementById('formGasto').action = URL_GASTOS_STORE;
            document.getElementById('formGastoMethod').innerHTML = '';
            ['gastoDescripcion','gastoMonto','gastoFecha','gastoNotas'].forEach(id => {
                document.getElementById(id).value = '';
            });
            document.getElementById('modalGasto').classList.remove('hidden');
            initGastoS2();
            $('#gastoMoneda').val(null).trigger('change');
            $('#gastoCategoria').val(null).trigger('change');
            $('#gastoMiembro').val(null).trigger('change');
        }
        function cerrarModalGasto() {
            document.getElementById('modalGasto').classList.add('hidden');
        }
        function abrirModalEditGasto(g) {
            document.getElementById('modalGastoTitle').textContent = 'Editar gasto';
            document.getElementById('formGasto').action = URL_GASTO_UPDATE + '/' + g.id;
            document.getElementById('formGastoMethod').innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="PUT">';
            document.getElementById('gastoDescripcion').value = g.descripcion || '';
            document.getElementById('gastoMonto').value       = g.monto || '';
            document.getElementById('gastoFecha').value       = g.fecha || '';
            document.getElementById('gastoNotas').value       = g.notas || '';
            document.getElementById('modalGasto').classList.remove('hidden');
            initGastoS2();
            $('#gastoMoneda').val(g.moneda_id).trigger('change');
            $('#gastoCategoria').val(g.categoria_gasto_viaje_id).trigger('change');
            $('#gastoMiembro').val(g.hogar_miembro_id).trigger('change');
        }

        // ── Modal: Participante ───────────────────────────────────────────────
        function abrirModalParticipante() {
            document.getElementById('partRol').value = '';
            document.getElementById('modalParticipante').classList.remove('hidden');
            initParticipanteS2();
            $('#partMiembro').val(null).trigger('change');
        }
        function cerrarModalParticipante() {
            document.getElementById('modalParticipante').classList.add('hidden');
        }

        // ── Modal: Documento ──────────────────────────────────────────────────
        function abrirModalDocumento() {
            document.getElementById('modalDocumentoTitle').textContent = 'Agregar documento';
            document.getElementById('formDocumento').action = URL_DOCUMENTOS_STORE;
            document.getElementById('formDocumentoMethod').innerHTML = '';
            ['docNombre','docEmision','docVencimiento','docNotas'].forEach(id => {
                document.getElementById(id).value = '';
            });
            document.getElementById('docArchivo').value = '';
            document.getElementById('modalDocumento').classList.remove('hidden');
            initDocumentoS2();
            $('#docTipo').val(null).trigger('change');
            $('#docReserva').val(null).trigger('change');
        }
        function cerrarModalDocumento() {
            document.getElementById('modalDocumento').classList.add('hidden');
        }
        function abrirModalEditDocumento(d) {
            document.getElementById('modalDocumentoTitle').textContent = 'Editar documento';
            document.getElementById('formDocumento').action = URL_DOCUMENTO_UPDATE + '/' + d.id;
            document.getElementById('formDocumentoMethod').innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="PUT">';
            document.getElementById('docNombre').value      = d.nombre || '';
            document.getElementById('docEmision').value     = d.fecha_emision || '';
            document.getElementById('docVencimiento').value = d.fecha_vencimiento || '';
            document.getElementById('docNotas').value       = d.notas || '';
            document.getElementById('modalDocumento').classList.remove('hidden');
            initDocumentoS2();
            $('#docTipo').val(d.tipo_documento_viaje_id).trigger('change');
            $('#docReserva').val(d.reserva_id).trigger('change');
        }

        // ── Auto-activar tab tras redirect ────────────────────────────────────
        @if(session('activeTab'))
        (function() {
            const btn = document.querySelector('[data-tab="{{ session('activeTab') }}"]');
            if (btn) btn.click();
        })();
        @endif

        // ── Re-abrir modal en error de validación ─────────────────────────────
        @if($errors->has('nombre') && session()->previousUrl() && str_contains(session()->previousUrl(), 'destinos'))
        abrirModalDestino();
        @endif
        </script>
    </body>
</html>
