<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Cuentas de Servicio</title>
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
                        Cuentas de Servicio
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

            @if(session('error'))
            <div class="mb-[25px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-danger-700 hover:text-danger-900">
                    <i class="material-symbols-outlined !text-lg">close</i>
                </button>
            </div>
            @endif

            <!-- Selector de propiedad -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-[12px] uppercase tracking-wide">Filtrar por propiedad</p>
                <div class="flex flex-wrap gap-[8px]">
                    <a href="{{ route('dashboard.cuentas-servicio.index') }}"
                       class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-[8px] border text-sm font-medium transition-all
                           {{ !$propiedadSeleccionada
                               ? 'bg-primary-500 border-primary-500 text-white'
                               : 'border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500' }}">
                        <i class="material-symbols-outlined !text-[16px]">apps</i>
                        Todas
                    </a>
                    @foreach($propiedades as $prop)
                    <a href="{{ route('dashboard.cuentas-servicio.index', ['propiedad' => $prop->id]) }}"
                       class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-[8px] border text-sm font-medium transition-all
                           {{ $propiedadSeleccionada?->id === $prop->id
                               ? 'bg-primary-500 border-primary-500 text-white'
                               : 'border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500' }}">
                        <i class="material-symbols-outlined !text-[16px]">{{ $prop->tipoInmueble?->icono ?? 'home' }}</i>
                        <span class="max-w-[160px] truncate">{{ $prop->alias }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Card de cuentas -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                    <h5 class="!mb-0">
                        <i class="material-symbols-outlined !text-[20px] align-middle mr-[6px] text-primary-500">electrical_services</i>
                        @if($propiedadSeleccionada)
                            Servicios de <span class="text-primary-500">{{ $propiedadSeleccionada->alias }}</span>
                        @else
                            Todos los servicios del hogar
                        @endif
                    </h5>
                    @if($propiedadSeleccionada)
                    <a href="{{ route('dashboard.cuentas-servicio.create', ['propiedad' => $propiedadSeleccionada->id]) }}"
                       class="inline-block bg-primary-500 text-white py-[7px] px-[14px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                        <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                            <i class="material-symbols-outlined !text-[15px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">add</i>
                            Agregar cuenta
                        </span>
                    </a>
                    @endif
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
                $colores = [
                    ['bg' => 'bg-primary-50 dark:bg-[#15203c]', 'text' => 'text-primary-500', 'inicial' => 'bg-primary-100 text-primary-700'],
                    ['bg' => 'bg-orange-50 dark:bg-[#15203c]',  'text' => 'text-orange-500',  'inicial' => 'bg-orange-100 text-orange-700'],
                    ['bg' => 'bg-success-50 dark:bg-[#15203c]', 'text' => 'text-success-500', 'inicial' => 'bg-success-100 text-success-700'],
                    ['bg' => 'bg-purple-50 dark:bg-[#15203c]',  'text' => 'text-purple-500',  'inicial' => 'bg-purple-100 text-purple-700'],
                ];
                @endphp

                @if($cuentas->isEmpty())
                    <div class="text-center py-[60px]">
                        <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[16px]">electrical_services</i>
                        <p class="text-gray-500 dark:text-gray-400 mb-[20px]">
                            @if($propiedadSeleccionada)
                                No hay cuentas de servicio registradas para <strong>{{ $propiedadSeleccionada->alias }}</strong>.
                            @else
                                No hay cuentas de servicio registradas en el hogar.
                            @endif
                        </p>
                        @if($propiedadSeleccionada)
                        <a href="{{ route('dashboard.cuentas-servicio.create', ['propiedad' => $propiedadSeleccionada->id]) }}"
                           class="inline-block bg-primary-500 text-white py-[10px] px-[20px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">add</i>
                                Agregar primera cuenta
                            </span>
                        </a>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-[20px] sm:gap-[25px]">
                        @foreach($cuentas as $index => $cuenta)
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

                            <div class="{{ $color['bg'] }} rounded-md py-[22px] px-[20px] relative">

                                <!-- Acciones -->
                                <div class="absolute top-[10px] ltr:right-[10px] rtl:left-[10px] flex items-center gap-[3px]">
                                    <a href="{{ route('dashboard.cuentas-servicio.show', $cuenta) }}"
                                       class="w-[24px] h-[24px] rounded flex items-center justify-center bg-white/60 dark:bg-white/10 hover:bg-white/90 transition-all text-gray-500 dark:text-gray-400"
                                       title="Ver detalle">
                                        <i class="material-symbols-outlined !text-[14px]">visibility</i>
                                    </a>
                                    <a href="{{ route('dashboard.cuentas-servicio.edit', $cuenta) }}"
                                       class="w-[24px] h-[24px] rounded flex items-center justify-center bg-white/60 dark:bg-white/10 hover:bg-white/90 transition-all text-gray-500 dark:text-gray-400"
                                       title="Editar">
                                        <i class="material-symbols-outlined !text-[14px]">edit</i>
                                    </a>
                                    <form method="POST" action="{{ route('dashboard.cuentas-servicio.destroy', $cuenta) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('¿Eliminar la cuenta «{{ addslashes($cuenta->numero_cuenta) }}»? Esta acción no se puede deshacer.')"
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

                                <!-- Logo empresa + proveedor + estado -->
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

                                @unless($propiedadSeleccionada)
                                <!-- Propiedad (visible en vista "Todas") -->
                                <div class="mt-[10px] pt-[8px] border-t border-white/30 dark:border-white/5">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-[4px] truncate">
                                        <i class="material-symbols-outlined !text-[13px] flex-shrink-0">{{ $cuenta->propiedad?->tipoInmueble?->icono ?? 'home' }}</i>
                                        {{ $cuenta->propiedad?->alias ?? '—' }}
                                    </span>
                                </div>
                                @endunless

                            </div>
                        @endforeach
                    </div>

                    @if($cuentas->hasPages())
                    <div class="sm:flex sm:items-center justify-between mt-[25px] pt-[14px] border-t border-gray-100 dark:border-[#172036]">
                        <p class="!mb-0 text-sm">
                            Mostrando {{ $cuentas->firstItem() }}–{{ $cuentas->lastItem() }} de {{ $cuentas->total() }} resultados
                        </p>
                        <ol class="mt-[10px] sm:mt-0">
                            <li class="inline-block mx-[1px]">
                                @if($cuentas->onFirstPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </span>
                                @else
                                    <a href="{{ $cuentas->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </a>
                                @endif
                            </li>
                            @foreach($cuentas->getUrlRange(max(1, $cuentas->currentPage()-2), min($cuentas->lastPage(), $cuentas->currentPage()+2)) as $page => $url)
                            <li class="inline-block mx-[1px]">
                                @if($page == $cuentas->currentPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                                @endif
                            </li>
                            @endforeach
                            <li class="inline-block mx-[1px]">
                                @if($cuentas->hasMorePages())
                                    <a href="{{ $cuentas->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
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
                @endif
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
