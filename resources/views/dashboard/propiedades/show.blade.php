<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('partials.front.styles')

        <title>{{ $propiedad->alias }} - Propiedad</title>

        @vite('resources/css/app.css')

    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">
                    Detalle de Propiedad
                </h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">
                                home
                            </i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.propiedades.index') }}" class="transition-all hover:text-primary-500">
                            Propiedades
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        {{ $propiedad->alias }}
                    </li>
                </ol>
            </div>

            <!-- Detalle -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Imagen -->
                <div class="xl:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] rounded-md overflow-hidden">
                        <div class="relative">
                            @if($propiedad->avatar_url)
                                <img src="{{ $propiedad->avatar_url }}" class="w-full object-cover rounded-md" style="max-height:380px;" alt="{{ $propiedad->alias }}">
                            @else
                                <div class="w-full bg-gray-100 dark:bg-[#15203c] rounded-md flex items-center justify-center" style="height:280px;">
                                    <i class="material-symbols-outlined text-gray-300 dark:text-gray-600" style="font-size:96px">home_work</i>
                                </div>
                            @endif
                            <span class="inline-block absolute top-[13px] ltr:right-[13px] rtl:left-[13px] z-[1]
                                {{ $propiedad->activo
                                    ? 'bg-success-100 dark:bg-[#0c1427] text-success-700 border border-success-600'
                                    : 'bg-danger-100 dark:bg-[#0c1427] text-danger-700 border border-danger-600' }}
                                text-xs font-medium py-[2px] px-[8px] rounded-[4px]">
                                {{ $propiedad->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <div class="p-[20px] md:p-[25px]">
                            <div class="flex items-center gap-[10px] mt-[15px]">
                                <a href="{{ route('dashboard.propiedades.edit', $propiedad) }}"
                                    class="flex-1 text-center inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-primary-500 text-white hover:bg-primary-400">
                                    <i class="material-symbols-outlined !text-[18px] align-middle mr-[4px]">edit</i>
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('dashboard.propiedades.destroy', $propiedad) }}" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('¿Eliminar la propiedad «{{ addslashes($propiedad->alias) }}»?')"
                                        class="w-full inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-danger-500 text-white hover:bg-danger-400">
                                        <i class="material-symbols-outlined !text-[18px] align-middle mr-[4px]">delete</i>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="xl:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md lg:h-full">
                        <div class="trezo-card-content">

                            <div class="sm:flex items-start justify-between mb-[20px] md:mb-[25px]">
                                <div>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">
                                        {{ $propiedad->tipoInmueble?->nombre ?? '—' }}
                                    </span>
                                    <h3 class="!text-[20px] md:!text-xl !mb-[8px]">
                                        {{ $propiedad->alias }}
                                    </h3>
                                    <span class="block ltr:pl-[23px] rtl:pr-[23px] font-medium relative text-sm">
                                        <i class="ri-map-pin-line absolute ltr:left-0 rtl:right-0 text-primary-500 top-1/2 -translate-y-1/2 text-[17px] font-normal mt-px"></i>
                                        {{ $propiedad->direccion }}
                                    </span>
                                </div>
                                <div class="mt-[10px] sm:mt-0">
                                    <span class="inline-block text-xs font-medium px-[10px] py-[4px] rounded-[100px]
                                        {{ $propiedad->activo
                                            ? 'text-success-600 border border-success-600 bg-success-100 dark:bg-[#15203c] dark:border-success-600'
                                            : 'text-danger-600 border border-danger-600 bg-danger-100 dark:bg-[#15203c] dark:border-danger-600' }}">
                                        {{ $propiedad->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>

                            <h4 class="!text-lg !mb-[13px]">
                                Detalles de la Propiedad
                            </h4>

                            <ul class="mb-[20px] md:mb-[25px] grid grid-cols-1 sm:grid-cols-2">
                                <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                    <span class="block text-gray-500 dark:text-gray-400">Tipo</span>
                                    <span class="block text-black dark:text-white font-medium">
                                        {{ $propiedad->tipoInmueble?->nombre ?? '—' }}
                                    </span>
                                </li>
                                <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                    <span class="block text-gray-500 dark:text-gray-400">Estado</span>
                                    <span class="block font-medium {{ $propiedad->activo ? 'text-success-600' : 'text-danger-600' }}">
                                        {{ $propiedad->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </li>
                                <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                    <span class="block text-gray-500 dark:text-gray-400">Dirección</span>
                                    <span class="block text-black dark:text-white text-right max-w-[60%]">
                                        {{ $propiedad->direccion }}
                                    </span>
                                </li>
                                <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                    <span class="block text-gray-500 dark:text-gray-400">Creado</span>
                                    <span class="block text-black dark:text-white">
                                        {{ $propiedad->created_at?->format('d M, Y') }}
                                    </span>
                                </li>
                                @if($propiedad->referencia)
                                <li class="sm:col-span-2 flex items-start justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                    <span class="block text-gray-500 dark:text-gray-400">Referencia</span>
                                    <span class="block text-black dark:text-white text-right max-w-[65%]">
                                        {{ $propiedad->referencia }}
                                    </span>
                                </li>
                                @endif
                            </ul>

                            <h4 class="!text-lg !mb-[13px]">
                                Ubicación
                            </h4>

                            <ul class="mb-[20px] md:mb-[25px] grid grid-cols-1 sm:grid-cols-2">
                                @if($propiedad->distrito_inei && $propiedad->distrito)
                                    <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                        <span class="block text-gray-500 dark:text-gray-400">País</span>
                                        <span class="block text-black dark:text-white font-medium">Perú</span>
                                    </li>
                                    <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                        <span class="block text-gray-500 dark:text-gray-400">Departamento</span>
                                        <span class="block text-black dark:text-white">{{ $propiedad->distrito->departamento ?? '—' }}</span>
                                    </li>
                                    <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                        <span class="block text-gray-500 dark:text-gray-400">Provincia</span>
                                        <span class="block text-black dark:text-white">{{ $propiedad->distrito->provincia ?? '—' }}</span>
                                    </li>
                                    <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                        <span class="block text-gray-500 dark:text-gray-400">Distrito</span>
                                        <span class="block text-black dark:text-white">{{ $propiedad->distrito->distrito ?? '—' }}</span>
                                    </li>
                                    <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                        <span class="block text-gray-500 dark:text-gray-400">Código INEI</span>
                                        <span class="block text-black dark:text-white font-mono">{{ $propiedad->distrito_inei }}</span>
                                    </li>
                                @elseif($propiedad->pais_iso2 && $propiedad->pais)
                                    <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                        <span class="block text-gray-500 dark:text-gray-400">País</span>
                                        <span class="block text-black dark:text-white font-medium">{{ $propiedad->pais->nombre }}</span>
                                    </li>
                                    @if($propiedad->ciudad)
                                    <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                        <span class="block text-gray-500 dark:text-gray-400">Ciudad</span>
                                        <span class="block text-black dark:text-white">{{ $propiedad->ciudad->nombre }}</span>
                                    </li>
                                    @endif
                                @else
                                    <li class="sm:col-span-2 py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c] text-gray-500 dark:text-gray-400">
                                        Sin información de ubicación registrada.
                                    </li>
                                @endif
                            </ul>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Cuentas de Servicio (solo lectura) -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                    <h5 class="!mb-0">
                        <i class="material-symbols-outlined !text-[20px] align-middle mr-[6px] text-primary-500">electrical_services</i>
                        Cuentas de Servicio
                    </h5>
                </div>

                <div class="trezo-card-content">
                    @php
                    $iconMap = [
                        'electricidad' => 'bolt',
                        'agua'         => 'water_drop',
                        'gas'          => 'local_fire_department',
                        'internet'     => 'wifi',
                        'telefon'      => 'phone',
                        'móvil'        => 'phone_android',
                        'movil'        => 'phone_android',
                        'cable'        => 'tv',
                        'tv'           => 'tv',
                        'seguridad'    => 'security',
                    ];
                    $colores = [
                        ['bg' => 'bg-primary-50 dark:bg-[#15203c]', 'text' => 'text-primary-500', 'inicial' => 'bg-primary-100 text-primary-700'],
                        ['bg' => 'bg-orange-50 dark:bg-[#15203c]',  'text' => 'text-orange-500',  'inicial' => 'bg-orange-100 text-orange-700'],
                        ['bg' => 'bg-success-50 dark:bg-[#15203c]', 'text' => 'text-success-500', 'inicial' => 'bg-success-100 text-success-700'],
                        ['bg' => 'bg-purple-50 dark:bg-[#15203c]',  'text' => 'text-purple-500',  'inicial' => 'bg-purple-100 text-purple-700'],
                    ];
                    @endphp

                    @if($propiedad->cuentasServicio->isEmpty())
                        <div class="text-center py-[40px]">
                            <i class="material-symbols-outlined !text-[52px] text-gray-300 dark:text-gray-600 block mb-[12px]">electrical_services</i>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No hay cuentas de servicio registradas.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-[20px] sm:gap-[25px]">
                            @foreach($propiedad->cuentasServicio as $index => $cuenta)
                                @php
                                $color   = $colores[$index % 4];
                                $nombre  = strtolower($cuenta->proveedor?->tipoServicio?->nombre ?? '');
                                $icon    = 'home_repair_service';
                                foreach ($iconMap as $key => $val) {
                                    if (str_contains($nombre, $key)) { $icon = $val; break; }
                                }
                                $empresa = $cuenta->proveedor?->empresa;
                                $inicial = mb_strtoupper(mb_substr($empresa?->razon_social ?? $cuenta->proveedor?->nombre_comercial ?? '?', 0, 1));
                                @endphp

                                <div class="{{ $color['bg'] }} rounded-md py-[22px] px-[20px]">
                                    <div class="flex items-center">
                                        <div class="{{ $color['text'] }} leading-none ltr:mr-[12px] rtl:ml-[12px]">
                                            <i class="material-symbols-outlined !text-5xl">{{ $icon }}</i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="block text-sm text-gray-600 dark:text-gray-400 truncate">
                                                {{ $cuenta->proveedor?->tipoServicio?->nombre ?? 'Servicio' }}
                                            </span>
                                            <h5 class="!mb-0 !text-[15px] mt-[2px] font-mono tracking-wide truncate">
                                                {{ $cuenta->numero_cuenta }}
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="mt-[15px] sm:mt-[20px] flex items-center justify-between gap-[8px]">
                                        <div class="flex items-center gap-[6px] min-w-0">
                                            @if($empresa?->logo_url)
                                                <img src="{{ Storage::url($empresa->logo_url) }}" class="w-[28px] h-[28px] object-cover rounded-full flex-shrink-0" alt="">
                                            @else
                                                <span class="w-[28px] h-[28px] rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 {{ $color['inicial'] }}">{{ $inicial }}</span>
                                            @endif
                                            <span class="block text-sm truncate text-gray-700 dark:text-gray-300" title="{{ $cuenta->proveedor?->nombre_comercial ?? '—' }}">
                                                {{ $cuenta->proveedor?->nombre_comercial ?? '—' }}
                                            </span>
                                        </div>
                                        @if($cuenta->estado === 'activa')
                                            <span class="inline-block shrink-0 text-xs font-medium py-[2px] px-[8px] border border-success-300 bg-success-100 dark:bg-dark dark:border-dark text-success-700 rounded-xl">Activa</span>
                                        @elseif($cuenta->estado === 'suspendida')
                                            <span class="inline-block shrink-0 text-xs font-medium py-[2px] px-[8px] border border-warning-300 bg-warning-100 dark:bg-dark dark:border-dark text-warning-700 rounded-xl">Suspendida</span>
                                        @else
                                            <span class="inline-block shrink-0 text-xs font-medium py-[2px] px-[8px] border border-danger-300 bg-danger-100 dark:bg-dark dark:border-dark text-danger-700 rounded-xl">Cancelada</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-[20px] text-right">
                            <a href="{{ route('dashboard.cuentas-servicio.index', ['propiedad' => $propiedad->id]) }}"
                               class="inline-flex items-center gap-[6px] text-primary-500 hover:text-primary-400 text-sm font-medium transition-all">
                                Ver todas las cuentas
                                <i class="material-symbols-outlined !text-[16px]">arrow_forward</i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
