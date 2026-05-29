<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('partials.front.styles')

        <title>Cuenta de Servicio — {{ $propiedad->alias }}</title>

        @vite('resources/css/app.css')

    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Detalle de Cuenta de Servicio</h5>
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
                        <a href="{{ route('dashboard.propiedades.cuentas.index', $propiedad) }}" class="transition-all hover:text-primary-500">Cuentas de Servicio</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Detalle
                    </li>
                </ol>
            </div>

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
            $nombreTipo = strtolower($cuenta->proveedor?->tipoServicio?->nombre ?? '');
            $icon = 'home_repair_service';
            foreach ($iconMap as $key => $val) {
                if (str_contains($nombreTipo, $key)) { $icon = $val; break; }
            }
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Tarjeta visual -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">

                        <div class="bg-primary-50 dark:bg-[#15203c] rounded-md py-[30px] px-[25px] text-center mb-[20px]">
                            <i class="material-symbols-outlined !text-[72px] text-primary-500 block mb-[10px]">{{ $icon }}</i>
                            <h4 class="!text-[18px] !mb-[4px]">{{ $cuenta->proveedor?->tipoServicio?->nombre ?? 'Servicio' }}</h4>
                            <p class="text-gray-500 dark:text-gray-400 font-mono text-sm">{{ $cuenta->numero_cuenta }}</p>
                            <div class="mt-[12px]">
                                @if($cuenta->estado === 'activa')
                                    <span class="inline-block text-xs font-medium py-[3px] px-[10px] border border-success-300 bg-success-100 dark:bg-dark dark:border-dark text-success-700 rounded-xl">
                                        Activa
                                    </span>
                                @elseif($cuenta->estado === 'suspendida')
                                    <span class="inline-block text-xs font-medium py-[3px] px-[10px] border border-warning-300 bg-warning-100 dark:bg-dark dark:border-dark text-warning-700 rounded-xl">
                                        Suspendida
                                    </span>
                                @else
                                    <span class="inline-block text-xs font-medium py-[3px] px-[10px] border border-danger-300 bg-danger-100 dark:bg-dark dark:border-dark text-danger-700 rounded-xl">
                                        Cancelada
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-[10px]">
                            <a href="{{ route('dashboard.propiedades.cuentas.edit', [$propiedad, $cuenta]) }}"
                                class="flex-1 text-center inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-primary-500 text-white hover:bg-primary-400">
                                <i class="material-symbols-outlined !text-[18px] align-middle mr-[4px]">edit</i>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('dashboard.propiedades.cuentas.destroy', [$propiedad, $cuenta]) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('¿Eliminar esta cuenta de servicio?')"
                                    class="w-full inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-danger-500 text-white hover:bg-danger-400">
                                    <i class="material-symbols-outlined !text-[18px] align-middle mr-[4px]">delete</i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h4 class="!text-lg !mb-[16px]">Información de la Cuenta</h4>

                        <ul class="mb-[20px] md:mb-[25px] grid grid-cols-1 sm:grid-cols-2">
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Tipo de Servicio</span>
                                <span class="block text-black dark:text-white font-medium">
                                    {{ $cuenta->proveedor?->tipoServicio?->nombre ?? '—' }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Proveedor</span>
                                <span class="block text-black dark:text-white font-medium">
                                    {{ $cuenta->proveedor?->nombre_comercial ?? '—' }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Número de Cuenta</span>
                                <span class="block text-black dark:text-white font-mono">
                                    {{ $cuenta->numero_cuenta }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Estado</span>
                                <span class="block font-medium
                                    @if($cuenta->estado === 'activa') text-success-600
                                    @elseif($cuenta->estado === 'suspendida') text-warning-600
                                    @else text-danger-600 @endif">
                                    {{ ucfirst($cuenta->estado) }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Titular</span>
                                <span class="block text-black dark:text-white">
                                    @if($cuenta->titular)
                                        {{ trim(($cuenta->titular->persona?->nombres ?? '') . ' ' . ($cuenta->titular->persona?->apellido_paterno ?? '')) ?: $cuenta->titular->name }}
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Fecha de Inicio</span>
                                <span class="block text-black dark:text-white">
                                    {{ $cuenta->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Propiedad</span>
                                <span class="block text-black dark:text-white">
                                    {{ $propiedad->alias }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Registrado</span>
                                <span class="block text-black dark:text-white">
                                    {{ $cuenta->created_at?->format('d M, Y') }}
                                </span>
                            </li>
                            @if($cuenta->notas)
                            <li class="sm:col-span-2 py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 mb-[4px]">Notas</span>
                                <p class="text-black dark:text-white text-sm">{{ $cuenta->notas }}</p>
                            </li>
                            @endif
                        </ul>

                        <div class="mt-[16px]">
                            <a href="{{ route('dashboard.propiedades.cuentas.index', $propiedad) }}"
                               class="inline-flex items-center gap-[6px] text-primary-500 hover:text-primary-400 text-sm font-medium transition-all">
                                <i class="material-symbols-outlined !text-[16px]">arrow_back</i>
                                Volver a Cuentas de Servicio
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
