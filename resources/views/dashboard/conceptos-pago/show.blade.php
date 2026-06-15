<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $conceptoPago->nombre }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $conceptoPago->nombre }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.prestamos.conceptos-pago.index') }}" class="transition-all hover:text-primary-500">Conceptos de Pago</a>
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
                $colores  = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                $bgColor  = $colores[abs(crc32($conceptoPago->id)) % count($colores)];
                $catIcono = $conceptoPago->categoriaConcepto?->icono ?? 'payments';
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Tarjeta principal -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] rounded-md text-center overflow-hidden">

                        @if($conceptoPago->imagen_url)
                            <img src="{{ $conceptoPago->imagen_url }}"
                                class="w-full h-[200px] object-cover object-top block" alt="{{ $conceptoPago->nombre }}">
                        @else
                            <div class="w-full h-[160px] {{ $bgColor }} flex items-center justify-center">
                                <i class="material-symbols-outlined !text-[64px] text-white opacity-20">{{ $catIcono }}</i>
                            </div>
                        @endif

                        <div class="p-[20px] md:p-[25px] -mt-[30px]">
                            <div class="inline-flex items-center justify-center w-[60px] h-[60px] rounded-full bg-white dark:bg-[#0c1427] ring-4 ring-white dark:ring-[#0c1427] shadow-md mb-[12px]">
                                <i class="material-symbols-outlined !text-[30px] text-primary-500">{{ $catIcono }}</i>
                            </div>
                            <h4 class="!mb-[6px] font-semibold">{{ $conceptoPago->nombre }}</h4>

                            @if($conceptoPago->categoriaConcepto)
                            <span class="inline-flex items-center gap-[4px] text-xs font-medium px-[10px] py-[4px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-500 mb-[10px]">
                                <i class="material-symbols-outlined !text-[14px]">{{ $catIcono }}</i>
                                {{ $conceptoPago->categoriaConcepto->nombre }}
                            </span>
                            @else
                            <span class="inline-flex items-center gap-[4px] text-xs px-[10px] py-[4px] rounded-full bg-gray-100 dark:bg-[#15203c] text-gray-500 mb-[10px]">
                                Sin categoría
                            </span>
                            @endif

                            <div class="h-[1px] bg-gray-100 dark:bg-[#172036] my-[16px]"></div>

                            <dl class="text-left space-y-[10px] text-sm">
                                <div class="flex items-center justify-between gap-[8px]">
                                    <dt class="text-gray-500 dark:text-gray-400 flex items-center gap-[4px]">
                                        <i class="material-symbols-outlined !text-[16px]">payments</i>
                                        Precio referencial
                                    </dt>
                                    <dd class="font-semibold text-black dark:text-white">
                                        @if($conceptoPago->precio_referencial)
                                            S/ {{ number_format($conceptoPago->precio_referencial, 2) }}
                                        @else
                                            <span class="text-gray-400 font-normal">No definido</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between gap-[8px]">
                                    <dt class="text-gray-500 dark:text-gray-400 flex items-center gap-[4px]">
                                        <i class="material-symbols-outlined !text-[16px]">circle</i>
                                        Estado
                                    </dt>
                                    <dd>
                                        @if($conceptoPago->activo)
                                            <span class="inline-flex items-center gap-[4px] text-xs font-medium px-[8px] py-[3px] rounded-full bg-success-100 text-success-700">
                                                <span class="w-[6px] h-[6px] rounded-full bg-success-500 inline-block"></span>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-[4px] text-xs font-medium px-[8px] py-[3px] rounded-full bg-danger-100 text-danger-700">
                                                <span class="w-[6px] h-[6px] rounded-full bg-danger-500 inline-block"></span>
                                                Inactivo
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>

                            @if($conceptoPago->descripcion)
                            <div class="mt-[16px] text-left">
                                <p class="text-xs text-gray-400 mb-[4px] font-medium uppercase tracking-wide">Descripción</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $conceptoPago->descripcion }}</p>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="lg:col-span-2 flex flex-col gap-[25px]">

                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="!mb-[16px] font-semibold">Acciones</h6>
                        <div class="flex flex-wrap gap-[12px]">
                            <a href="{{ route('dashboard.prestamos.conceptos-pago.edit', $conceptoPago) }}"
                                class="inline-flex items-center gap-[6px] rounded-md font-medium px-[18px] py-[10px] bg-primary-500 text-white transition-all hover:bg-primary-400">
                                <i class="material-symbols-outlined !text-[18px]">edit</i>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('dashboard.prestamos.conceptos-pago.destroy', $conceptoPago) }}"
                                onsubmit="return confirm('¿Eliminar el concepto «{{ addslashes($conceptoPago->nombre) }}»? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-[6px] rounded-md font-medium px-[18px] py-[10px] bg-danger-500 text-white transition-all hover:bg-danger-400">
                                    <i class="material-symbols-outlined !text-[18px]">delete</i>
                                    Eliminar
                                </button>
                            </form>
                            <a href="{{ route('dashboard.prestamos.conceptos-pago.index') }}"
                                class="inline-flex items-center gap-[6px] rounded-md font-medium px-[18px] py-[10px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-[#15203c]">
                                <i class="material-symbols-outlined !text-[18px]">arrow_back</i>
                                Volver al listado
                            </a>
                        </div>
                    </div>

                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="!mb-[6px] font-semibold">Nota sobre precio referencial</h6>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            El precio referencial (S/ {{ $conceptoPago->precio_referencial ? number_format($conceptoPago->precio_referencial, 2) : '—' }})
                            es un valor orientativo. Al registrar cada movimiento de préstamo podrás ajustarlo al monto real de la transacción.
                        </p>
                    </div>

                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
