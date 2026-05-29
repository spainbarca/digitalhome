<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('partials.front.styles')

        <title>Cuentas de Servicio — {{ $propiedad->alias }}</title>

        @vite('resources/css/app.css')

    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Cuentas de Servicio</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.propiedades.index') }}" class="transition-all hover:text-primary-500">Propiedades</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.propiedades.show', $propiedad) }}" class="transition-all hover:text-primary-500">{{ $propiedad->alias }}</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Cuentas de Servicio
                    </li>
                </ol>
            </div>

            <!-- Flash message -->
            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-success-700 hover:text-success-900">
                    <i class="material-symbols-outlined !text-lg">close</i>
                </button>
            </div>
            @endif

            <!-- Card header con título y botón -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0">
                            <i class="material-symbols-outlined !text-[20px] align-middle mr-[6px] text-primary-500">electrical_services</i>
                            Servicios de <span class="text-primary-500">{{ $propiedad->alias }}</span>
                        </h5>
                    </div>
                    <a href="{{ route('dashboard.propiedades.cuentas.create', $propiedad) }}"
                       class="inline-block bg-primary-500 text-white py-[8px] px-[16px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">add</i>
                            Agregar cuenta
                        </span>
                    </a>
                </div>

                <div class="trezo-card-content">

                    @php
                    $iconMap = [
                        'electricidad'  => 'bolt',
                        'agua'          => 'water_drop',
                        'gas'           => 'local_fire_department',
                        'internet'      => 'wifi',
                        'telefon'       => 'phone',
                        'móvil'         => 'phone_android',
                        'movil'         => 'phone_android',
                        'cable'         => 'tv',
                        'tv'            => 'tv',
                        'seguridad'     => 'security',
                    ];
                    $colores = [
                        ['bg' => 'bg-primary-50 dark:bg-[#15203c]', 'text' => 'text-primary-500'],
                        ['bg' => 'bg-orange-50 dark:bg-[#15203c]',  'text' => 'text-orange-500'],
                        ['bg' => 'bg-success-50 dark:bg-[#15203c]', 'text' => 'text-success-500'],
                        ['bg' => 'bg-purple-50 dark:bg-[#15203c]',  'text' => 'text-purple-500'],
                    ];
                    @endphp

                    @if($cuentas->isEmpty())
                        <!-- Estado vacío -->
                        <div class="text-center py-[60px]">
                            <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[16px]">electrical_services</i>
                            <p class="text-gray-500 dark:text-gray-400 mb-[20px]">
                                No hay cuentas de servicio registradas para esta propiedad.
                            </p>
                            <a href="{{ route('dashboard.propiedades.cuentas.create', $propiedad) }}"
                               class="inline-block bg-primary-500 text-white py-[10px] px-[20px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                                <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">add</i>
                                    Agregar primera cuenta
                                </span>
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-[20px] sm:gap-[25px]">
                            @foreach($cuentas as $index => $cuenta)
                                @php
                                $color  = $colores[$index % 4];
                                $nombre = strtolower($cuenta->proveedor?->tipoServicio?->nombre ?? '');
                                $icon   = 'home_repair_service';
                                foreach ($iconMap as $key => $val) {
                                    if (str_contains($nombre, $key)) { $icon = $val; break; }
                                }
                                @endphp

                                <div class="{{ $color['bg'] }} rounded-md py-[22px] px-[20px] relative">

                                    <!-- Botones acción -->
                                    <div class="absolute top-[10px] ltr:right-[10px] rtl:left-[10px] flex items-center gap-[3px]">
                                        <a href="{{ route('dashboard.propiedades.cuentas.show', [$propiedad, $cuenta]) }}"
                                           class="w-[24px] h-[24px] rounded flex items-center justify-center bg-white/60 dark:bg-white/10 hover:bg-white/90 transition-all text-gray-500 dark:text-gray-400"
                                           title="Ver detalle">
                                            <i class="material-symbols-outlined !text-[14px]">visibility</i>
                                        </a>
                                        <a href="{{ route('dashboard.propiedades.cuentas.edit', [$propiedad, $cuenta]) }}"
                                           class="w-[24px] h-[24px] rounded flex items-center justify-center bg-white/60 dark:bg-white/10 hover:bg-white/90 transition-all text-gray-500 dark:text-gray-400"
                                           title="Editar">
                                            <i class="material-symbols-outlined !text-[14px]">edit</i>
                                        </a>
                                        <form method="POST" action="{{ route('dashboard.propiedades.cuentas.destroy', [$propiedad, $cuenta]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('¿Eliminar la cuenta «{{ addslashes($cuenta->numero_cuenta) }}»?')"
                                                class="w-[24px] h-[24px] rounded flex items-center justify-center bg-white/60 dark:bg-white/10 hover:bg-danger-100 transition-all text-danger-500"
                                                title="Eliminar">
                                                <i class="material-symbols-outlined !text-[14px]">delete</i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Ícono + tipo + número -->
                                    <div class="flex items-center mt-[4px]">
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

                                    <!-- Proveedor + estado -->
                                    <div class="mt-[15px] sm:mt-[20px] flex items-center justify-between gap-[8px]">
                                        <span class="block text-sm truncate text-gray-700 dark:text-gray-300 max-w-[55%]" title="{{ $cuenta->proveedor?->nombre_comercial ?? '—' }}">
                                            {{ $cuenta->proveedor?->nombre_comercial ?? '—' }}
                                        </span>
                                        @if($cuenta->estado === 'activa')
                                            <span class="inline-block shrink-0 text-xs font-medium py-[2px] px-[8px] border border-success-300 bg-success-100 dark:bg-dark dark:border-dark text-success-700 rounded-xl">
                                                Activa
                                            </span>
                                        @elseif($cuenta->estado === 'suspendida')
                                            <span class="inline-block shrink-0 text-xs font-medium py-[2px] px-[8px] border border-warning-300 bg-warning-100 dark:bg-dark dark:border-dark text-warning-700 rounded-xl">
                                                Suspendida
                                            </span>
                                        @else
                                            <span class="inline-block shrink-0 text-xs font-medium py-[2px] px-[8px] border border-danger-300 bg-danger-100 dark:bg-dark dark:border-dark text-danger-700 rounded-xl">
                                                Cancelada
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            @endforeach
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
