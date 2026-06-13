<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Empleadores</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Empleadores</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Laboral</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Empleadores</li>
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
                <div class="sm:flex items-center gap-[15px]">
                    <form method="GET" action="{{ route('dashboard.empleadores.index') }}" class="flex-1 flex gap-[10px]">
                        <div class="relative flex-1 max-w-sm">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre o empresa..."
                                class="bg-gray-50 border border-gray-50 h-[40px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </div>
                        <button type="submit"
                            class="h-[40px] px-[14px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            Buscar
                        </button>
                        @if($search)
                        <a href="{{ route('dashboard.empleadores.index') }}"
                            class="h-[40px] px-[12px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] flex items-center transition-all">
                            <i class="material-symbols-outlined !text-[18px]">close</i>
                        </a>
                        @endif
                    </form>
                    <a href="{{ route('dashboard.empleadores.create') }}"
                        class="mt-[12px] sm:mt-0 inline-block transition-all rounded-md font-medium px-[13px] py-[8px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white whitespace-nowrap">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                            Nuevo Empleador
                        </span>
                    </a>
                </div>
            </div>

            <!-- Grid de cards -->
            @if($empleadores->isEmpty())
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[40px] rounded-md text-center">
                <i class="material-symbols-outlined !text-[56px] text-gray-300 dark:text-gray-600 block mb-[12px]">corporate_fare</i>
                <p class="text-gray-500 dark:text-gray-400">
                    @if($search)
                        No se encontraron empleadores para "{{ $search }}".
                    @else
                        No hay empleadores registrados aún.
                    @endif
                </p>
                @if(!$search)
                <a href="{{ route('dashboard.empleadores.create') }}"
                    class="mt-[16px] inline-block rounded-md font-medium px-[16px] py-[8px] bg-primary-500 text-white hover:bg-primary-400 transition-all">
                    Registrar primero
                </a>
                @endif
            </div>
            @else
            <div class="trezo-card mb-[25px]">
                <div class="trezo-card-content">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-[25px]">
                        @foreach($empleadores as $e)
                        @php
                            $colores = ['from-primary-400 to-primary-600','from-orange-400 to-orange-600','from-purple-400 to-purple-600','from-success-400 to-success-600','from-pink-400 to-pink-600'];
                            $gradiente = $colores[abs(crc32($e->id)) % count($colores)];
                            $inicial = strtoupper(mb_substr($e->nombre, 0, 1));
                        @endphp
                        <div class="bg-white dark:bg-[#0c1427] p-[10px] rounded-md shadow-sm hover:shadow-md transition-shadow">
                            <!-- Banner -->
                            <div class="rounded-md overflow-hidden h-[100px] relative">
                                @if($e->banner_path)
                                    <img src="{{ Storage::url($e->banner_path) }}" class="w-full h-full object-cover" alt="">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br {{ $gradiente }}"></div>
                                @endif
                            </div>
                            <!-- Logo superpuesto -->
                            <div class="text-center -mt-[34px]">
                                <div class="relative mb-[12px] inline-block">
                                    @if($e->logo_display_url)
                                        <img src="{{ $e->logo_display_url }}"
                                            class="rounded-full inline-block w-[68px] h-[68px] object-cover ring-4 ring-white dark:ring-[#0c1427]"
                                            alt="{{ $e->nombre }}">
                                    @else
                                        <div class="rounded-full inline-flex items-center justify-center w-[68px] h-[68px] text-white font-bold text-[26px] ring-4 ring-white dark:ring-[#0c1427] bg-gradient-to-br {{ $gradiente }} select-none">
                                            {{ $inicial }}
                                        </div>
                                    @endif
                                </div>

                                <h3 class="!text-sm !font-semibold !mb-[2px] px-[8px] truncate text-black dark:text-white">
                                    {{ $e->nombre }}
                                </h3>

                                @if($e->empresa)
                                <span class="block text-xs text-primary-500 truncate px-[8px] mb-[2px]">{{ $e->empresa->razon_social }}</span>
                                @endif

                                @php $distritoEfectivo = $e->distrito_efectivo; @endphp
                                @if($distritoEfectivo)
                                <span class="block text-xs text-gray-500 dark:text-gray-400 truncate px-[8px] mb-[4px]">
                                    <i class="material-symbols-outlined !text-[12px] align-middle">location_on</i>
                                    {{ $distritoEfectivo->distrito }}
                                </span>
                                @endif

                                <div class="flex items-center justify-center gap-[6px] mb-[10px] flex-wrap">
                                    <span class="inline-flex items-center gap-[3px] text-xs text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[13px]">work</i>
                                        {{ $e->empleos_count }} empleo{{ $e->empleos_count !== 1 ? 's' : '' }}
                                    </span>
                                    <span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] {{ $e->activo ? 'text-success-600 border border-success-600 bg-success-100 dark:bg-[#0c1427]' : 'text-danger-600 border border-danger-600 bg-danger-100 dark:bg-[#0c1427]' }}">
                                        {{ $e->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>

                                <div class="h-[1px] bg-gray-100 dark:bg-[#172036] my-[10px]"></div>

                                <div class="flex items-center justify-center gap-[8px] pb-[4px]">
                                    <a href="{{ route('dashboard.empleadores.show', $e) }}"
                                        class="inline-block rounded-md py-[5px] px-[12px] text-xs font-medium bg-primary-500 text-white hover:bg-primary-400 transition-all">
                                        Ver detalle
                                    </a>
                                    <a href="{{ route('dashboard.empleadores.edit', $e) }}"
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

            <!-- Paginación -->
            @if($empleadores->hasPages())
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="sm:flex sm:items-center justify-between">
                    <p class="!mb-0 text-sm text-gray-500 dark:text-gray-400">
                        Mostrando {{ $empleadores->firstItem() }}–{{ $empleadores->lastItem() }} de {{ $empleadores->total() }} empleadores
                    </p>
                    <div class="mt-[10px] sm:mt-0">
                        {{ $empleadores->links() }}
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
