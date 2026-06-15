<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Prestatarios</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Prestatarios</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Préstamos Personales</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Prestatarios</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            <!-- Cabecera deuda total -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex items-center gap-[14px]">
                    <div class="w-[54px] h-[54px] rounded-full {{ $deudaTotal > 0 ? 'bg-danger-100' : 'bg-success-100' }} flex items-center justify-center flex-shrink-0">
                        <i class="material-symbols-outlined !text-[26px] {{ $deudaTotal > 0 ? 'text-danger-500' : 'text-success-500' }}">account_balance_wallet</i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 !mb-0 uppercase tracking-wide font-medium">Deuda Total del Hogar</p>
                        <h4 class="!mb-0 font-bold {{ $deudaTotal > 0 ? 'text-danger-500' : 'text-success-500' }}">
                            {{ number_format($deudaTotal, 2) }}
                        </h4>
                        <p class="text-xs text-gray-400 !mb-0">Suma de saldos pendientes de todos los prestatarios</p>
                    </div>
                </div>
            </div>

            <!-- Filtros + botón nuevo -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex flex-wrap items-center gap-[12px] justify-between">
                    <form method="GET" action="{{ route('dashboard.prestamos.prestatarios.index') }}" class="flex flex-wrap items-center gap-[10px]">
                        <div class="relative sm:w-[220px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Buscar por nombre..."
                                class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                            @if(request('filtro') && request('filtro') !== 'todos')
                                <input type="hidden" name="filtro" value="{{ request('filtro') }}">
                            @endif
                        </div>
                        <button type="submit"
                            class="h-[36px] px-[14px] bg-primary-500 text-white rounded-md text-xs hover:bg-primary-400 transition-all">
                            Filtrar
                        </button>
                        @if(request('q') || (request('filtro') && request('filtro') !== 'todos'))
                        <a href="{{ route('dashboard.prestamos.prestatarios.index') }}"
                            class="h-[36px] px-[14px] border border-gray-200 dark:border-[#172036] text-gray-500 dark:text-gray-400 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all flex items-center">
                            Limpiar
                        </a>
                        @endif
                    </form>

                    <a href="{{ route('dashboard.prestamos.prestatarios.create') }}"
                        class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                            Nuevo Prestatario
                        </span>
                    </a>
                </div>

                {{-- Pills de filtro saldo --}}
                <div class="flex flex-wrap items-center gap-[8px] mt-[14px] pt-[14px] border-t border-gray-100 dark:border-[#172036]">
                    @foreach(['todos' => ['Todos', 'people'], 'con-deuda' => ['Con deuda', 'trending_up'], 'saldados' => ['Saldados', 'check_circle']] as $key => [$label, $icon])
                    <a href="{{ route('dashboard.prestamos.prestatarios.index', array_filter(['q' => request('q'), 'filtro' => $key === 'todos' ? null : $key])) }}"
                        class="inline-flex items-center gap-[5px] px-[12px] py-[5px] rounded-full text-xs font-medium transition-all
                            {{ $filtro === $key ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-[#15203c] text-gray-600 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-[#172036]' }}">
                        <i class="material-symbols-outlined !text-[14px]">{{ $icon }}</i>
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Cards grid -->
            <div class="trezo-card mb-[25px]">
                <div class="trezo-card-content">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-[25px]">
                        @forelse($prestatarios as $prestatario)
                        @php
                            $colores   = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                            $bgColor   = $colores[abs(crc32($prestatario->id)) % count($colores)];
                            $saldo     = $prestatario->saldo;
                            $palabras  = explode(' ', $prestatario->nombre_resuelto);
                            $iniciales = mb_strtoupper(mb_substr($palabras[0] ?? '', 0, 1) . mb_substr($palabras[1] ?? '', 0, 1));
                        @endphp
                        <div class="bg-white dark:bg-[#0c1427] p-[10px] rounded-md">
                            <!-- Banner / foto -->
                            <div class="relative h-[160px] rounded-md overflow-hidden">
                                @if($prestatario->foto_resuelta)
                                    <img src="{{ $prestatario->foto_resuelta }}" class="w-full h-full object-cover object-top" alt="{{ $prestatario->nombre_resuelto }}">
                                @else
                                    <div class="w-full h-full {{ $bgColor }} flex items-center justify-center">
                                        <span class="text-white font-bold opacity-40" style="font-size:52px">{{ $iniciales ?: '?' }}</span>
                                    </div>
                                @endif
                            </div>
                            <!-- Info -->
                            <div class="text-center -mt-[26px]">
                                <div class="relative mb-[10px] inline-flex items-center justify-center w-[52px] h-[52px] rounded-full bg-white dark:bg-[#0c1427] ring-2 ring-white dark:ring-[#0c1427] shadow-sm">
                                    <i class="material-symbols-outlined !text-[26px] text-primary-500">person</i>
                                </div>
                                <h6 class="!text-sm !font-semibold !mb-[3px] truncate px-[8px]" title="{{ $prestatario->nombre_resuelto }}">
                                    {{ $prestatario->nombre_resuelto }}
                                </h6>
                                <span class="block text-xs text-gray-400 mb-[6px]">
                                    {{ $prestatario->moneda?->simbolo }} · {{ $prestatario->moneda?->codigo ?? $prestatario->moneda?->nombre }}
                                </span>
                                @if($saldo > 0)
                                    <span class="block text-sm font-bold text-danger-500 mb-[10px]">
                                        {{ $prestatario->moneda?->simbolo }} {{ number_format($saldo, 2) }}
                                    </span>
                                @elseif($saldo < 0)
                                    <span class="block text-sm font-bold text-success-500 mb-[10px]">
                                        A favor: {{ $prestatario->moneda?->simbolo }} {{ number_format(abs($saldo), 2) }}
                                    </span>
                                @else
                                    <span class="block text-sm font-semibold text-gray-400 dark:text-gray-500 mb-[10px]">Sin deuda</span>
                                @endif

                                <div class="h-[1px] bg-primary-50 dark:bg-[#172036] mb-[12px]"></div>

                                <div class="flex items-center justify-center px-[4px]">
                                    <a href="{{ route('dashboard.prestamos.prestatarios.show', $prestatario) }}"
                                        class="inline-block rounded-[7px] py-[5px] px-[13px] font-medium text-sm bg-primary-500 text-white transition-all hover:bg-primary-400">
                                        Ver detalle
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-1 sm:col-span-2 md:col-span-3 xl:col-span-4 text-center py-[50px] text-gray-400">
                            <i class="material-symbols-outlined !text-[56px] mb-[10px] block text-gray-300 dark:text-gray-600">people</i>
                            @if(request('q') || $filtro !== 'todos')
                                No se encontraron prestatarios con ese criterio.
                            @else
                                No hay prestatarios registrados aún.
                            @endif
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            @if($prestatarios->hasPages())
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="sm:flex sm:items-center justify-between">
                    <p class="!mb-0 text-sm">
                        Mostrando {{ $prestatarios->firstItem() }}–{{ $prestatarios->lastItem() }} de {{ $prestatarios->total() }} resultados
                    </p>
                    <ol class="mt-[10px] sm:mt-0">
                        <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0">
                            @if($prestatarios->onFirstPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                </span>
                            @else
                                <a href="{{ $prestatarios->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                </a>
                            @endif
                        </li>
                        @foreach($prestatarios->getUrlRange(max(1,$prestatarios->currentPage()-2), min($prestatarios->lastPage(),$prestatarios->currentPage()+2)) as $page => $url)
                        <li class="inline-block mx-[1px]">
                            @if($page == $prestatarios->currentPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                            @endif
                        </li>
                        @endforeach
                        <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0">
                            @if($prestatarios->hasMorePages())
                                <a href="{{ $prestatarios->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
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
