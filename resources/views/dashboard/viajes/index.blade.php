<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Viajes</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Mis Viajes</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Viajes</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            <!-- Botón Nuevo viaje -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[22px] text-primary-500">luggage</i>
                        <h6 class="!mb-0 font-semibold text-black dark:text-white">Gestiona tus viajes</h6>
                    </div>
                    <a href="{{ route('dashboard.viajes.create') }}"
                        class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                            Nuevo Viaje
                        </span>
                    </a>
                </div>
            </div>

            {{-- ── Panel documentos de viajero ─────────────────────────────────── --}}
            @if($docVencidos > 0 || $docPorVencer > 0)
            <a href="{{ route('dashboard.viajes.documentos-viajero') }}"
               class="flex items-center justify-between gap-[14px] mb-[25px] px-[20px] py-[14px] rounded-md border
                      {{ $docVencidos > 0 ? 'bg-danger-50 dark:bg-[#2a1a1a] border-danger-300 dark:border-danger-700' : 'bg-warning-50 dark:bg-[#2a2200] border-warning-300 dark:border-warning-700' }}
                      hover:opacity-90 transition-opacity group">
                <div class="flex items-center gap-[12px]">
                    <i class="material-symbols-outlined !text-[24px] {{ $docVencidos > 0 ? 'text-danger-600' : 'text-warning-600' }}">
                        {{ $docVencidos > 0 ? 'cancel' : 'schedule' }}
                    </i>
                    <div>
                        <span class="block text-sm font-semibold {{ $docVencidos > 0 ? 'text-danger-700 dark:text-danger-400' : 'text-warning-700 dark:text-warning-400' }}">
                            @if($docVencidos > 0)
                                {{ $docVencidos }} documento{{ $docVencidos > 1 ? 's' : '' }} de viajero vencido{{ $docVencidos > 1 ? 's' : '' }}
                                @if($docPorVencer > 0) y {{ $docPorVencer }} por vencer @endif
                            @else
                                {{ $docPorVencer }} documento{{ $docPorVencer > 1 ? 's' : '' }} de viajero por vencer (próx. 90 días)
                            @endif
                        </span>
                        <span class="text-xs {{ $docVencidos > 0 ? 'text-danger-600' : 'text-warning-600' }}">
                            Pasaporte, visa, permiso de menor, etc. — Haz clic para ver el detalle.
                        </span>
                    </div>
                </div>
                <i class="material-symbols-outlined !text-[20px] {{ $docVencidos > 0 ? 'text-danger-400' : 'text-warning-400' }} group-hover:translate-x-1 transition-transform">
                    chevron_right
                </i>
            </a>
            @else
            <a href="{{ route('dashboard.viajes.documentos-viajero') }}"
               class="flex items-center justify-between gap-[14px] mb-[25px] px-[20px] py-[12px] rounded-md border border-success-200 dark:border-success-800 bg-success-50 dark:bg-[#0a2a15] hover:opacity-90 transition-opacity group">
                <div class="flex items-center gap-[10px]">
                    <i class="material-symbols-outlined !text-[20px] text-success-600">verified</i>
                    <span class="text-sm font-medium text-success-700 dark:text-success-400">Documentos de viajero — todos vigentes</span>
                </div>
                <i class="material-symbols-outlined !text-[18px] text-success-400 group-hover:translate-x-1 transition-transform">chevron_right</i>
            </a>
            @endif

            {{-- ── Próximos viajes ──────────────────────────────────────────────── --}}
            <div class="mb-[25px]">
                <div class="flex items-center gap-[8px] mb-[16px]">
                    <i class="material-symbols-outlined !text-[20px] text-primary-500">flight_takeoff</i>
                    <h6 class="!mb-0 font-semibold text-black dark:text-white">Próximos viajes</h6>
                    <span class="inline-flex items-center justify-center min-w-[20px] h-[20px] px-[5px] text-[10px] font-bold bg-primary-500 text-white rounded-full">{{ $proximos->count() }}</span>
                </div>

                @if($proximos->isEmpty())
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[40px] rounded-md text-center">
                    <i class="material-symbols-outlined !text-[56px] text-gray-300 dark:text-gray-600 block mb-[12px]">flight_takeoff</i>
                    <p class="text-gray-400 text-sm mb-[16px]">No hay viajes próximos.</p>
                    <a href="{{ route('dashboard.viajes.create') }}"
                        class="inline-flex items-center gap-[6px] px-[16px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                        <i class="material-symbols-outlined !text-[16px]">add</i>
                        Planificar viaje
                    </a>
                </div>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[25px]">
                    @foreach($proximos as $viaje)
                    @php
                        $gradients = ['from-primary-400 to-primary-600','from-orange-400 to-orange-600','from-purple-400 to-purple-600','from-success-400 to-success-600','from-pink-400 to-pink-600'];
                        $grad = $gradients[abs(crc32($viaje->id)) % count($gradients)];
                        $estadoColor = $viaje->estadoViaje?->color ?? null;
                    @endphp
                    <div class="bg-white dark:bg-[#0c1427] rounded-md overflow-hidden shadow-sm border border-gray-100 dark:border-[#172036]">
                        <!-- Portada -->
                        <div class="relative h-[140px] overflow-hidden flex items-center justify-center {{ $viaje->portada_url ? '' : 'bg-gradient-to-br ' . $grad }}">
                            @if($viaje->portada_url)
                                <img src="{{ $viaje->portada_url }}" class="w-full h-full object-cover" alt="{{ $viaje->nombre }}">
                            @else
                                <i class="material-symbols-outlined !text-[56px] text-white opacity-70">{{ $viaje->tipoViaje?->icono ?? 'luggage' }}</i>
                            @endif
                            @if($viaje->estadoViaje)
                            <div class="absolute top-[10px] ltr:right-[10px] rtl:left-[10px]">
                                <span class="inline-flex items-center gap-[4px] px-[8px] py-[3px] rounded-full text-[11px] font-semibold"
                                      style="background-color: {{ $estadoColor }}22; color: {{ $estadoColor }}; border: 1px solid {{ $estadoColor }}55; backdrop-filter: blur(4px)">
                                    <i class="material-symbols-outlined !text-[11px]">{{ $viaje->estadoViaje->icono ?? 'flag' }}</i>
                                    {{ $viaje->estadoViaje->nombre }}
                                </span>
                            </div>
                            @endif
                        </div>
                        <!-- Info -->
                        <div class="p-[16px]">
                            <h6 class="!text-sm !font-semibold !mb-[4px] truncate" title="{{ $viaje->nombre }}">{{ $viaje->nombre }}</h6>
                            @if($viaje->tipoViaje)
                            <span class="inline-flex items-center gap-[4px] text-[11px] text-gray-400 mb-[6px]">
                                <i class="material-symbols-outlined !text-[13px]">{{ $viaje->tipoViaje->icono ?? 'category' }}</i>
                                {{ $viaje->tipoViaje->nombre }}
                            </span>
                            @endif
                            <div class="flex items-center gap-[4px] text-xs text-gray-500 dark:text-gray-400 mb-[10px]">
                                <i class="material-symbols-outlined !text-[14px]">calendar_month</i>
                                <span>
                                    {{ $viaje->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                    @if($viaje->fecha_fin)
                                        — {{ $viaje->fecha_fin->format('d/m/Y') }}
                                    @endif
                                </span>
                            </div>
                            @if($viaje->presupuesto !== null)
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-[10px]">
                                <span class="font-medium text-black dark:text-white">{{ $viaje->moneda?->simbolo }} {{ number_format((float) $viaje->presupuesto, 2) }}</span>
                                <span class="text-gray-400 ml-[4px]">presupuesto</span>
                            </div>
                            @endif
                            <div class="h-[1px] bg-gray-100 dark:bg-[#172036] mb-[12px]"></div>
                            <a href="{{ route('dashboard.viajes.show', $viaje) }}"
                                class="block text-center rounded-md py-[6px] px-[13px] text-sm font-medium bg-primary-500 text-white transition-all hover:bg-primary-400">
                                Ver detalle
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- ── Viajes pasados ───────────────────────────────────────────────── --}}
            @if($pasados->isNotEmpty())
            <div class="mb-[25px]">
                <div class="flex items-center gap-[8px] mb-[16px]">
                    <i class="material-symbols-outlined !text-[20px] text-gray-400">flight_land</i>
                    <h6 class="!mb-0 font-semibold text-black dark:text-white">Viajes pasados</h6>
                    <span class="inline-flex items-center justify-center min-w-[20px] h-[20px] px-[5px] text-[10px] font-bold bg-gray-400 text-white rounded-full">{{ $pasados->count() }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[25px]">
                    @foreach($pasados as $viaje)
                    @php
                        $gradients = ['from-primary-400 to-primary-600','from-orange-400 to-orange-600','from-purple-400 to-purple-600','from-success-400 to-success-600','from-pink-400 to-pink-600'];
                        $grad = $gradients[abs(crc32($viaje->id)) % count($gradients)];
                    @endphp
                    <div class="bg-white dark:bg-[#0c1427] rounded-md overflow-hidden shadow-sm border border-gray-100 dark:border-[#172036] opacity-80 hover:opacity-100 transition-opacity">
                        <div class="relative h-[120px] overflow-hidden flex items-center justify-center {{ $viaje->portada_url ? '' : 'bg-gradient-to-br ' . $grad }}">
                            @if($viaje->portada_url)
                                <img src="{{ $viaje->portada_url }}" class="w-full h-full object-cover grayscale" alt="{{ $viaje->nombre }}">
                            @else
                                <i class="material-symbols-outlined !text-[48px] text-white opacity-50">{{ $viaje->tipoViaje?->icono ?? 'luggage' }}</i>
                            @endif
                        </div>
                        <div class="p-[14px]">
                            <h6 class="!text-sm !font-semibold !mb-[4px] truncate" title="{{ $viaje->nombre }}">{{ $viaje->nombre }}</h6>
                            <div class="flex items-center gap-[4px] text-xs text-gray-400 mb-[8px]">
                                <i class="material-symbols-outlined !text-[13px]">calendar_month</i>
                                <span>
                                    {{ $viaje->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                    @if($viaje->fecha_fin) — {{ $viaje->fecha_fin->format('d/m/Y') }} @endif
                                </span>
                            </div>
                            <a href="{{ route('dashboard.viajes.show', $viaje) }}"
                                class="block text-center rounded-md py-[5px] px-[10px] text-xs font-medium border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 transition-all hover:border-primary-500 hover:text-primary-500">
                                Ver detalle
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
