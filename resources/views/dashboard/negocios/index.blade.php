<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Negocios</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Negocios</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Negocios</li>
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
                <div class="flex flex-wrap items-center gap-[12px]">
                    <form method="GET" action="{{ route('dashboard.negocios.index') }}" class="flex gap-[8px]">
                        <div class="relative">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Buscar por nombre o RUC..."
                                class="bg-gray-50 border border-gray-50 h-[40px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </div>
                        <button type="submit"
                            class="h-[40px] px-[14px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            Buscar
                        </button>
                        @if($search)
                        <a href="{{ route('dashboard.negocios.index') }}"
                            class="h-[40px] px-[12px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] flex items-center transition-all">
                            <i class="material-symbols-outlined !text-[18px]">close</i>
                        </a>
                        @endif
                    </form>

                    <div class="flex-1"></div>

                    <a href="{{ route('dashboard.negocios.create') }}"
                        class="mt-[12px] sm:mt-0 inline-block transition-all rounded-md font-medium px-[13px] py-[8px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white whitespace-nowrap">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                            Nuevo Negocio
                        </span>
                    </a>
                </div>
            </div>

            @if($negocios->isEmpty())
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[40px] rounded-md text-center">
                <i class="material-symbols-outlined !text-[56px] text-gray-300 dark:text-gray-600 block mb-[12px]">store</i>
                <p class="text-gray-500 dark:text-gray-400">
                    @if($search)
                        No se encontraron negocios con los filtros aplicados.
                    @else
                        No hay negocios registrados aún.
                    @endif
                </p>
                @if(!$search)
                <a href="{{ route('dashboard.negocios.create') }}"
                    class="mt-[16px] inline-block rounded-md font-medium px-[16px] py-[8px] bg-primary-500 text-white hover:bg-primary-400 transition-all">
                    Registrar primero
                </a>
                @endif
            </div>
            @else
            <div class="trezo-card mb-[25px]">
                <div class="trezo-card-content">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-[25px]">
                        @foreach($negocios as $neg)
                        @php
                            $colores   = ['from-primary-400 to-primary-600','from-orange-400 to-orange-600','from-purple-400 to-purple-600','from-success-400 to-success-600','from-pink-400 to-pink-600'];
                            $gradiente = $colores[abs(crc32($neg->id)) % count($colores)];
                            $nombre    = $neg->nombre ?? $neg->empresa?->razon_social ?? '—';
                            $inicial   = strtoupper(mb_substr($nombre, 0, 1));
                            $logoSrc   = $neg->empresa?->logo_url ? asset('storage/' . $neg->empresa->logo_url) : null;
                            $estado    = $neg->estadoNegocio;
                            $sector    = $neg->empresa?->sector?->nombre;
                            $regimen   = $neg->regimenTributario?->codigo;
                        @endphp
                        <div class="bg-white dark:bg-[#0c1427] p-[10px] rounded-md shadow-sm hover:shadow-md transition-shadow">
                            <div class="rounded-md overflow-hidden h-[100px] relative">
                                <div class="w-full h-full bg-gradient-to-br {{ $gradiente }}"></div>
                                @if($estado)
                                <span class="absolute top-[8px] left-[8px] inline-flex items-center gap-[4px] text-xs font-semibold px-[8px] py-[3px] rounded-full text-white"
                                    style="background-color: {{ $estado->color ?? '#6b7280' }}">
                                    {{ $estado->nombre }}
                                </span>
                                @endif
                            </div>

                            <div class="text-center -mt-[34px]">
                                <div class="relative mb-[12px] inline-block">
                                    @if($logoSrc)
                                        <img src="{{ $logoSrc }}"
                                            class="rounded-full inline-block w-[68px] h-[68px] object-cover ring-4 ring-white dark:ring-[#0c1427]"
                                            alt="{{ $nombre }}">
                                    @else
                                        <div class="rounded-full inline-flex items-center justify-center w-[68px] h-[68px] text-white font-bold text-[26px] ring-4 ring-white dark:ring-[#0c1427] bg-gradient-to-br {{ $gradiente }} select-none">
                                            {{ $inicial }}
                                        </div>
                                    @endif
                                </div>

                                <h3 class="!text-sm !font-semibold !mb-[2px] px-[8px] truncate text-black dark:text-white">
                                    {{ $nombre }}
                                </h3>

                                @if($sector)
                                <span class="block text-xs text-gray-500 dark:text-gray-400 truncate px-[8px] mb-[2px]">
                                    <i class="material-symbols-outlined !text-[12px] align-middle">category</i>
                                    {{ $sector }}
                                </span>
                                @endif

                                @if($regimen)
                                <span class="block text-xs font-mono font-semibold text-primary-500 truncate px-[8px] mb-[4px]">{{ $regimen }}</span>
                                @endif

                                <div class="h-[1px] bg-gray-100 dark:bg-[#172036] my-[10px]"></div>

                                <div class="flex items-center justify-center gap-[8px] pb-[4px]">
                                    <a href="{{ route('dashboard.negocios.show', $neg) }}"
                                        class="inline-block rounded-md py-[5px] px-[12px] text-xs font-medium bg-primary-500 text-white hover:bg-primary-400 transition-all">
                                        Ver detalle
                                    </a>
                                    <a href="{{ route('dashboard.negocios.edit', $neg) }}"
                                        class="inline-block rounded-md py-[5px] px-[12px] text-xs font-medium border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500 transition-all">
                                        Editar
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($negocios->hasPages())
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="sm:flex sm:items-center justify-between">
                    <p class="!mb-0 text-sm text-gray-500 dark:text-gray-400">
                        Mostrando {{ $negocios->firstItem() }}–{{ $negocios->lastItem() }} de {{ $negocios->total() }} negocios
                    </p>
                    <div class="mt-[10px] sm:mt-0">
                        {{ $negocios->links() }}
                    </div>
                </div>
            </div>
            @endif
            @endif

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
