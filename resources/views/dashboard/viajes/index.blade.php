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

        @php
            function colorEstadoViaje(?string $estado): array {
                return match($estado) {
                    'planificado' => ['bg' => 'bg-primary-100', 'text' => 'text-primary-600', 'icon' => 'schedule'],
                    'en_curso'    => ['bg' => 'bg-success-100', 'text' => 'text-success-600', 'icon' => 'play_circle'],
                    'completado'  => ['bg' => 'bg-gray-100',    'text' => 'text-gray-500',    'icon' => 'check_circle'],
                    'cancelado'   => ['bg' => 'bg-danger-100',  'text' => 'text-danger-600',  'icon' => 'cancel'],
                    default       => ['bg' => 'bg-gray-100',    'text' => 'text-gray-500',    'icon' => 'flight'],
                };
            }
        @endphp

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
                        $colEs = colorEstadoViaje($viaje->estado);
                        $gradients = ['from-primary-400 to-primary-600','from-orange-400 to-orange-600','from-purple-400 to-purple-600','from-success-400 to-success-600','from-pink-400 to-pink-600'];
                        $grad = $gradients[abs(crc32($viaje->id)) % count($gradients)];
                    @endphp
                    <div class="bg-white dark:bg-[#0c1427] rounded-md overflow-hidden shadow-sm border border-gray-100 dark:border-[#172036]">
                        <!-- Portada -->
                        <div class="relative h-[140px] overflow-hidden flex items-center justify-center {{ $viaje->portada_url ? '' : 'bg-gradient-to-br ' . $grad }}">
                            @if($viaje->portada_url)
                                <img src="{{ $viaje->portada_url }}" class="w-full h-full object-cover" alt="{{ $viaje->nombre }}">
                            @else
                                <i class="material-symbols-outlined !text-[56px] text-white opacity-70">{{ $viaje->tipoViaje?->icono ?? 'luggage' }}</i>
                            @endif
                            <div class="absolute top-[10px] ltr:right-[10px] rtl:left-[10px]">
                                <span class="inline-flex items-center gap-[4px] px-[8px] py-[3px] rounded-full text-[11px] font-semibold {{ $colEs['bg'] }} {{ $colEs['text'] }}">
                                    <i class="material-symbols-outlined !text-[11px]">{{ $colEs['icon'] }}</i>
                                    {{ ucfirst(str_replace('_', ' ', $viaje->estado ?? 'planificado')) }}
                                </span>
                            </div>
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
                        $colEs = colorEstadoViaje($viaje->estado);
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
