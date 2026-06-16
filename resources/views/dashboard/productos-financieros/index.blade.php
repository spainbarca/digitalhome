<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Productos Financieros</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        @php
            function colorEstadoProductoClases(?string $color): array {
                return match($color) {
                    'green'  => ['bg' => 'bg-success-100', 'text' => 'text-success-600'],
                    'red'    => ['bg' => 'bg-danger-100',  'text' => 'text-danger-600'],
                    'orange' => ['bg' => 'bg-orange-100',  'text' => 'text-orange-600'],
                    'amber'  => ['bg' => 'bg-warning-100', 'text' => 'text-warning-600'],
                    'gray'   => ['bg' => 'bg-gray-100',    'text' => 'text-gray-500'],
                    default  => ['bg' => 'bg-gray-100',    'text' => 'text-gray-500'],
                };
            }
        @endphp

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Productos Financieros</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Finanzas</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Productos Financieros</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            <!-- Filtros + botón nuevo -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex flex-wrap items-center gap-[12px] justify-between">
                    <form method="GET" action="{{ route('dashboard.productos-financieros.index') }}" class="flex flex-wrap items-center gap-[10px]">
                        <div class="relative sm:w-[220px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Buscar por alias..."
                                class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                            @if($tipoId)<input type="hidden" name="tipo_id" value="{{ $tipoId }}">@endif
                            @if($estadoId)<input type="hidden" name="estado_id" value="{{ $estadoId }}">@endif
                        </div>
                        <button type="submit"
                            class="h-[36px] px-[14px] bg-primary-500 text-white rounded-md text-xs hover:bg-primary-400 transition-all">
                            Filtrar
                        </button>
                        @if($search || $tipoId || $estadoId)
                        <a href="{{ route('dashboard.productos-financieros.index') }}"
                            class="h-[36px] px-[14px] border border-gray-200 dark:border-[#172036] text-gray-500 dark:text-gray-400 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all flex items-center">
                            Limpiar
                        </a>
                        @endif
                    </form>

                    <a href="{{ route('dashboard.productos-financieros.create') }}"
                        class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                            Nuevo Producto
                        </span>
                    </a>
                </div>

                {{-- Pills de filtro: tipo --}}
                <div class="flex flex-wrap items-center gap-[8px] mt-[14px] pt-[14px] border-t border-gray-100 dark:border-[#172036]">
                    <a href="{{ route('dashboard.productos-financieros.index', array_filter(['search' => $search, 'estado_id' => $estadoId])) }}"
                        class="inline-flex items-center gap-[5px] px-[12px] py-[5px] rounded-full text-xs font-medium transition-all
                            {{ !$tipoId ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-[#15203c] text-gray-600 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-[#172036]' }}">
                        <i class="material-symbols-outlined !text-[14px]">apps</i>
                        Todos los tipos
                    </a>
                    @foreach($tipos as $t)
                    <a href="{{ route('dashboard.productos-financieros.index', array_filter(['search' => $search, 'estado_id' => $estadoId, 'tipo_id' => $t->id])) }}"
                        class="inline-flex items-center gap-[5px] px-[12px] py-[5px] rounded-full text-xs font-medium transition-all
                            {{ $tipoId == $t->id ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-[#15203c] text-gray-600 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-[#172036]' }}">
                        <i class="material-symbols-outlined !text-[14px]">{{ $t->icono ?? 'category' }}</i>
                        {{ $t->nombre }}
                    </a>
                    @endforeach
                </div>

                {{-- Pills de filtro: estado --}}
                <div class="flex flex-wrap items-center gap-[8px] mt-[10px]">
                    <a href="{{ route('dashboard.productos-financieros.index', array_filter(['search' => $search, 'tipo_id' => $tipoId])) }}"
                        class="inline-flex items-center gap-[5px] px-[12px] py-[5px] rounded-full text-xs font-medium transition-all
                            {{ !$estadoId ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-[#15203c] text-gray-600 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-[#172036]' }}">
                        Todos los estados
                    </a>
                    @foreach($estados as $es)
                    @php $colEs = colorEstadoProductoClases($es->color); @endphp
                    <a href="{{ route('dashboard.productos-financieros.index', array_filter(['search' => $search, 'tipo_id' => $tipoId, 'estado_id' => $es->id])) }}"
                        class="inline-flex items-center gap-[5px] px-[12px] py-[5px] rounded-full text-xs font-medium transition-all
                            {{ $estadoId == $es->id ? $colEs['bg'].' '.$colEs['text'] : 'bg-gray-100 dark:bg-[#15203c] text-gray-600 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-[#172036]' }}">
                        <i class="material-symbols-outlined !text-[14px]">{{ $es->icono ?? 'circle' }}</i>
                        {{ $es->nombre }}
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Cards grid -->
            <div class="trezo-card mb-[25px]">
                <div class="trezo-card-content">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-[25px]">
                        @forelse($productos as $producto)
                        @php
                            $colores   = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                            $bgColor   = $colores[abs(crc32($producto->id)) % count($colores)];
                            $entidad   = $producto->entidadFinanciera;
                            $nombreEnt = $entidad?->nombre_comercial_resuelto ?? '—';
                            $colEs     = colorEstadoProductoClases($producto->estadoProducto?->color);
                        @endphp
                        <div class="bg-white dark:bg-[#0c1427] p-[10px] rounded-md">
                            <!-- Banner / logo entidad -->
                            <div class="relative h-[120px] rounded-md overflow-hidden flex items-center justify-center {{ $entidad?->logo_resuelto ? 'bg-gray-50 dark:bg-[#15203c]' : $bgColor }}">
                                @if($entidad?->logo_resuelto)
                                    <img src="{{ $entidad->logo_resuelto }}" class="max-h-[64px] max-w-[64px] object-contain" alt="{{ $nombreEnt }}">
                                @else
                                    <i class="material-symbols-outlined !text-[48px] text-white opacity-70">account_balance</i>
                                @endif
                            </div>
                            <!-- Info -->
                            <div class="text-center -mt-[26px]">
                                <div class="relative mb-[10px] inline-flex items-center justify-center w-[52px] h-[52px] rounded-full bg-white dark:bg-[#0c1427] ring-2 ring-white dark:ring-[#0c1427] shadow-sm">
                                    <i class="material-symbols-outlined !text-[26px] text-primary-500">{{ $producto->tipoProductoFinanciero?->icono ?? 'savings' }}</i>
                                </div>
                                <h6 class="!text-sm !font-semibold !mb-[3px] truncate px-[8px]" title="{{ $producto->alias }}">
                                    {{ $producto->alias }}
                                </h6>
                                <span class="block text-xs text-gray-400 mb-[6px] truncate px-[8px]">
                                    {{ $producto->tipoProductoFinanciero?->nombre ?? '—' }} · {{ $nombreEnt }}
                                </span>

                                <span class="inline-flex items-center gap-[4px] px-[10px] py-[3px] rounded-full text-xs font-medium {{ $colEs['bg'] }} {{ $colEs['text'] }} mb-[8px]">
                                    <i class="material-symbols-outlined !text-[13px]">{{ $producto->estadoProducto?->icono ?? 'circle' }}</i>
                                    {{ $producto->estadoProducto?->nombre ?? '—' }}
                                </span>

                                <span class="block text-sm font-bold text-black dark:text-white mb-[10px]">
                                    {{ $producto->moneda?->simbolo }} {{ number_format((float) $producto->saldo_actual, 2) }}
                                </span>

                                <div class="h-[1px] bg-primary-50 dark:bg-[#172036] mb-[12px]"></div>

                                <div class="flex items-center justify-center px-[4px]">
                                    <a href="{{ route('dashboard.productos-financieros.show', $producto) }}"
                                        class="inline-block rounded-[7px] py-[5px] px-[13px] font-medium text-sm bg-primary-500 text-white transition-all hover:bg-primary-400">
                                        Ver detalle
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-1 sm:col-span-2 md:col-span-3 xl:col-span-4 text-center py-[50px] text-gray-400">
                            <i class="material-symbols-outlined !text-[56px] mb-[10px] block text-gray-300 dark:text-gray-600">savings</i>
                            @if($search || $tipoId || $estadoId)
                                No se encontraron productos financieros con ese criterio.
                            @else
                                No hay productos financieros registrados aún.
                            @endif
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            @if($productos->hasPages())
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="sm:flex sm:items-center justify-between">
                    <p class="!mb-0 text-sm">
                        Mostrando {{ $productos->firstItem() }}–{{ $productos->lastItem() }} de {{ $productos->total() }} resultados
                    </p>
                    <ol class="mt-[10px] sm:mt-0">
                        <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0">
                            @if($productos->onFirstPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                </span>
                            @else
                                <a href="{{ $productos->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                </a>
                            @endif
                        </li>
                        @foreach($productos->getUrlRange(max(1,$productos->currentPage()-2), min($productos->lastPage(),$productos->currentPage()+2)) as $page => $url)
                        <li class="inline-block mx-[1px]">
                            @if($page == $productos->currentPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                            @endif
                        </li>
                        @endforeach
                        <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0">
                            @if($productos->hasMorePages())
                                <a href="{{ $productos->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
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
            </div>
            @endif

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
