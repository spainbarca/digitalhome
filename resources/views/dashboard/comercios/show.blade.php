<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        @php $nombreEfectivo = $comercio->nombre_referencial ?? $comercio->empresa?->razon_social ?? '(Sin nombre)'; @endphp
        <title>{{ $nombreEfectivo }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $nombreEfectivo }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.comercios.index') }}" class="transition-all hover:text-primary-500">Comercios</a>
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

            @php
                $colores   = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                $bgColor   = $colores[abs(crc32($comercio->id)) % count($colores)];
                $tipoIcono = $comercio->tipoComercio?->icono ?? 'store';
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] mb-[25px]">

                <!-- Panel izquierdo: datos del comercio -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] rounded-md text-center overflow-hidden">

                        {{-- Imagen a ancho completo --}}
                        @if($comercio->imagen_path)
                            <img src="{{ asset('storage/' . $comercio->imagen_path) }}"
                                class="w-full h-auto block object-top" alt="Imagen {{ $nombreEfectivo }}">
                        @else
                            <div class="w-full h-[120px] {{ $bgColor }} flex items-center justify-center">
                                <i class="material-symbols-outlined !text-[56px] text-white opacity-20">{{ $tipoIcono }}</i>
                            </div>
                        @endif

                        <div class="px-[25px] pb-[25px]">

                            {{-- Logo efectivo superpuesto --}}
                            <div class="flex justify-center -mt-[40px] mb-[14px]">
                                <div class="w-20 h-20 rounded-full ring-4 ring-white dark:ring-[#0c1427] shadow-lg border-[3px] border-primary-200 dark:border-primary-900 overflow-hidden bg-white dark:bg-[#1a2744] flex items-center justify-center">
                                    @if($comercio->logo_efectivo)
                                        <img src="{{ $comercio->logo_efectivo }}"
                                            class="w-full h-full object-contain"
                                            alt="{{ $nombreEfectivo }}">
                                    @elseif($comercio->empresa)
                                        <span class="text-2xl font-bold text-primary-400 leading-none">
                                            {{ strtoupper(mb_substr($comercio->empresa->razon_social, 0, 1)) }}
                                        </span>
                                    @else
                                        <i class="material-symbols-outlined !text-[38px] text-primary-400">{{ $tipoIcono }}</i>
                                    @endif
                                </div>
                            </div>

                            <h5 class="font-semibold text-black dark:text-white mb-[4px]">{{ $nombreEfectivo }}</h5>

                            @if($comercio->empresa)
                                <a href="{{ route('dashboard.empresas.show', $comercio->empresa) }}"
                                    class="block text-sm text-primary-500 hover:underline mb-[4px]">
                                    {{ $comercio->empresa->razon_social }}
                                </a>
                                @if($comercio->empresa->ruc)
                                    <p class="text-xs text-gray-400 font-mono mb-[12px]">RUC: {{ $comercio->empresa->ruc }}</p>
                                @else
                                    <div class="mb-[12px]"></div>
                                @endif
                            @endif

                            <div class="flex flex-wrap justify-center gap-[6px] mb-[16px]">
                                @if($comercio->tipoComercio)
                                    <span class="inline-flex items-center gap-[5px] text-xs font-medium px-[10px] py-[4px] rounded-md bg-primary-50 dark:bg-[#15203c] text-primary-500">
                                        <i class="material-symbols-outlined !text-[14px]">{{ $tipoIcono }}</i>
                                        {{ $comercio->tipoComercio->nombre }}
                                    </span>
                                @endif
                                @if($comercio->es_online)
                                    <span class="inline-flex items-center gap-[4px] text-xs font-medium px-[8px] py-[3px] rounded-[100px] text-primary-600 border border-primary-600 bg-primary-100 dark:bg-[#0c1427]">
                                        <i class="material-symbols-outlined !text-[12px]">wifi</i> Online
                                    </span>
                                @endif
                                <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] {{ $comercio->activo ? 'text-success-600 border border-success-600 bg-success-100' : 'text-danger-600 border border-danger-600 bg-danger-100' }}">
                                    {{ $comercio->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>

                            {{-- Datos de la empresa --}}
                            @if($comercio->empresa)
                            <div class="text-left mb-[16px] bg-gray-50 dark:bg-[#0a0e19] rounded-md p-[12px]">
                                <h6 class="font-semibold text-black dark:text-white mb-[8px] text-xs flex items-center gap-[6px]">
                                    <i class="material-symbols-outlined !text-[14px] text-primary-500">business</i>
                                    Datos de la Empresa
                                </h6>
                                <div class="grid grid-cols-2 gap-[6px] text-xs">
                                    <div>
                                        <span class="block text-gray-400 mb-[1px]">Razón social</span>
                                        <span class="font-medium text-black dark:text-white">{{ $comercio->empresa->razon_social }}</span>
                                    </div>
                                    @if($comercio->empresa->ruc)
                                    <div>
                                        <span class="block text-gray-400 mb-[1px]">RUC</span>
                                        <span class="font-medium text-black dark:text-white font-mono">{{ $comercio->empresa->ruc }}</span>
                                    </div>
                                    @endif
                                    @if($comercio->empresa->direccion_fiscal)
                                    <div class="col-span-2">
                                        <span class="block text-gray-400 mb-[1px]">Dirección fiscal</span>
                                        <span class="font-medium text-black dark:text-white">{{ $comercio->empresa->direccion_fiscal }}</span>
                                    </div>
                                    @endif
                                    @if($comercio->empresa->telefono)
                                    <div>
                                        <span class="block text-gray-400 mb-[1px]">Teléfono</span>
                                        <span class="font-medium text-black dark:text-white">{{ $comercio->empresa->telefono }}</span>
                                    </div>
                                    @endif
                                    @if($comercio->empresa->email)
                                    <div>
                                        <span class="block text-gray-400 mb-[1px]">Email</span>
                                        <span class="font-medium text-black dark:text-white">{{ $comercio->empresa->email }}</span>
                                    </div>
                                    @endif
                                    @if($comercio->empresa->sitio_web)
                                    <div class="col-span-2">
                                        <span class="block text-gray-400 mb-[1px]">Sitio web</span>
                                        <a href="{{ $comercio->empresa->sitio_web }}" target="_blank" rel="noopener"
                                            class="font-medium text-primary-500 hover:underline break-all">{{ $comercio->empresa->sitio_web }}</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            {{-- Datos del comercio --}}
                            <div class="text-left mb-[16px] bg-gray-50 dark:bg-[#0a0e19] rounded-md p-[12px]">
                                <h6 class="font-semibold text-black dark:text-white mb-[8px] text-xs flex items-center gap-[6px]">
                                    <i class="material-symbols-outlined !text-[14px] text-primary-500">store</i>
                                    Datos del Comercio
                                </h6>
                                <div class="grid grid-cols-2 gap-[6px] text-xs">
                                    @if(!$comercio->es_online && $comercio->distrito_efectivo)
                                    <div class="col-span-2">
                                        <span class="block text-gray-400 mb-[1px]">
                                            Ubicación
                                            @unless($comercio->distrito_inei)
                                                <span class="text-gray-300 dark:text-gray-600 ml-[2px]">(empresa)</span>
                                            @endunless
                                        </span>
                                        <span class="font-medium text-black dark:text-white">
                                            {{ $comercio->distrito_efectivo->distrito }}, {{ $comercio->distrito_efectivo->provincia }}, {{ $comercio->distrito_efectivo->departamento }}
                                        </span>
                                    </div>
                                    @endif
                                    @if($comercio->direccion_efectiva)
                                    <div class="col-span-2">
                                        <span class="block text-gray-400 mb-[1px]">
                                            Dirección
                                            @unless($comercio->direccion)
                                                <span class="text-gray-300 dark:text-gray-600 ml-[2px]">(empresa)</span>
                                            @endunless
                                        </span>
                                        <span class="font-medium text-black dark:text-white">{{ $comercio->direccion_efectiva }}</span>
                                    </div>
                                    @endif
                                    <div>
                                        <span class="block text-gray-400 mb-[1px]">Modalidad</span>
                                        <span class="font-medium text-black dark:text-white">{{ $comercio->es_online ? 'Online' : 'Presencial' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-gray-400 mb-[1px]">Compras registradas</span>
                                        <span class="font-medium text-black dark:text-white">{{ $comercio->compras->count() }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($comercio->notas)
                            <div class="text-left mb-[16px] px-[4px]">
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Notas</span>
                                <p class="text-sm text-black dark:text-white whitespace-pre-line">{{ $comercio->notas }}</p>
                            </div>
                            @endif

                            <div class="mt-[16px] pt-[16px] border-t border-gray-100 dark:border-[#172036] flex flex-wrap gap-[10px] justify-center">
                                <a href="{{ route('dashboard.comercios.edit', $comercio) }}"
                                    class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                    <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                        <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">edit</i>
                                        Editar
                                    </span>
                                </a>
                                <form method="POST" action="{{ route('dashboard.comercios.destroy', $comercio) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('¿Eliminar el comercio «{{ addslashes($nombreEfectivo) }}»? Esta acción no se puede deshacer.')"
                                        class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-danger-500 text-white hover:bg-danger-400 text-sm">
                                        <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                            <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">delete</i>
                                            Eliminar
                                        </span>
                                    </button>
                                </form>
                                <a href="{{ route('dashboard.comercios.index') }}"
                                    class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] text-sm">
                                    Volver
                                </a>
                            </div>

                        </div>{{-- /px-[25px] --}}
                    </div>
                </div>

                <!-- Panel derecho: Compras -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <h5 class="!mb-0">
                                Compras
                                <span class="ml-[6px] text-xs font-normal text-gray-400">({{ $comercio->compras->count() }})</span>
                            </h5>
                            <a href="{{ route('dashboard.comercios.index') }}"
                                class="inline-block transition-all rounded-md font-medium px-[10px] py-[6px] text-xs text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                                <span class="inline-block relative ltr:pl-[18px] rtl:pr-[18px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">shopping_cart</i>
                                    Ver compras
                                </span>
                            </a>
                        </div>
                        <div class="trezo-card-content">
                            @if($comercio->compras->isEmpty())
                            <div class="text-center py-[40px] text-gray-400">
                                <i class="material-symbols-outlined !text-[48px] block mb-[10px] text-gray-300 dark:text-gray-600">shopping_bag</i>
                                No hay compras registradas en este comercio.
                            </div>
                            @else
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr>
                                            <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs ltr:first:rounded-tl-md">Fecha</th>
                                            <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs">Descripción</th>
                                            <th class="font-medium ltr:text-right px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs ltr:last:rounded-tr-md">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-black dark:text-white">
                                        @foreach($comercio->compras->sortByDesc('fecha_compra') as $compra)
                                        <tr>
                                            <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400">
                                                {{ $compra->fecha_compra?->format('d/m/Y') ?? '—' }}
                                            </td>
                                            <td class="ltr:text-left px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm">
                                                {{ Str::limit($compra->concepto ?? $compra->notas ?? '—', 40) }}
                                            </td>
                                            <td class="ltr:text-right whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm font-medium">
                                                {{ $compra->total ? 'S/ ' . number_format($compra->total, 2) : '—' }}
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

        @include('partials.front.scripts')
    </body>
</html>
