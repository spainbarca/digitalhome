<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('partials.front.styles')

        <title>Propiedades Inmuebles</title>

        @vite('resources/css/app.css')

    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">
                    Propiedades Inmuebles
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
                        Propiedades
                    </li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-600 text-success-700 px-[20px] py-[12px] rounded-md">
                {{ session('success') }}
            </div>
            @endif

            <!-- Propiedades -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                    <div class="trezo-card-title">
                        <form method="GET" action="{{ route('dashboard.propiedades.index') }}" class="relative sm:w-[265px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">
                                    search
                                </i>
                            </label>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar propiedad..." class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] ltr:md:pr-[16px] rtl:pl-[13px] rtl:md:pl-[16px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </form>
                    </div>
                    <div class="trezo-card-subtitle mt-[15px] sm:mt-0">
                        <a href="{{ route('dashboard.propiedades.create') }}" class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">
                                    add
                                </i>
                                Nueva Propiedad
                            </span>
                        </a>
                    </div>
                </div>

                <div class="trezo-card-content">
                    @if($propiedades->isEmpty())
                    <div class="text-center py-[60px] text-gray-500 dark:text-gray-400">
                        <i class="material-symbols-outlined text-6xl block mb-[10px] text-gray-300 dark:text-gray-600">home_work</i>
                        <p>No se encontraron propiedades.</p>
                        <a href="{{ route('dashboard.propiedades.create') }}" class="inline-block mt-[15px] transition-all rounded-md font-medium px-[13px] py-[6px] bg-primary-500 text-white hover:bg-primary-400">
                            Agregar primera propiedad
                        </a>
                    </div>
                    @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[20px] md:gap-[25px]">
                        @foreach($propiedades as $propiedad)
                        <div class="border border-gray-100 dark:border-[#172036] rounded-md overflow-hidden transition-all hover:shadow-md">
                            <!-- Imagen -->
                            <div class="h-[160px] overflow-hidden bg-gray-100 dark:bg-[#15203c] relative">
                                @if($propiedad->avatar_url)
                                    <img src="{{ $propiedad->avatar_url }}" class="w-full h-full object-cover" alt="{{ $propiedad->alias }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="material-symbols-outlined text-gray-300 dark:text-gray-600" style="font-size:64px">home_work</i>
                                    </div>
                                @endif
                                <!-- Badge activo/inactivo -->
                                <span class="absolute top-[10px] ltr:right-[10px] rtl:left-[10px] inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px]
                                    {{ $propiedad->activo
                                        ? 'text-success-600 border border-success-600 bg-success-100 dark:bg-[#0c1427] dark:border-success-600'
                                        : 'text-danger-600 border border-danger-600 bg-danger-100 dark:bg-[#0c1427] dark:border-danger-600' }}">
                                    {{ $propiedad->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            <!-- Cuerpo -->
                            <div class="p-[15px] bg-white dark:bg-[#0c1427]">
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">
                                    {{ $propiedad->tipoInmueble?->nombre ?? '—' }}
                                </span>
                                <h6 class="font-medium text-black dark:text-white text-[15px] mb-[6px] truncate">
                                    {{ $propiedad->alias }}
                                </h6>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate mb-[12px]">
                                    <i class="ri-map-pin-line text-primary-500 mr-[4px]"></i>
                                    {{ $propiedad->direccion }}
                                </p>
                                <!-- Acciones -->
                                <div class="flex items-center gap-[9px] pt-[10px] border-t border-gray-100 dark:border-[#172036]">
                                    <a href="{{ route('dashboard.propiedades.show', $propiedad) }}" class="text-primary-500 leading-none custom-tooltip" data-text="Ver">
                                        <i class="material-symbols-outlined !text-md">visibility</i>
                                    </a>
                                    <a href="{{ route('dashboard.propiedades.edit', $propiedad) }}" class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip" data-text="Editar">
                                        <i class="material-symbols-outlined !text-md">edit</i>
                                    </a>
                                    <form method="POST" action="{{ route('dashboard.propiedades.destroy', $propiedad) }}" class="inline leading-none">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger-500 leading-none custom-tooltip" data-text="Eliminar"
                                            onclick="return confirm('¿Eliminar la propiedad «{{ addslashes($propiedad->alias) }}»?')">
                                            <i class="material-symbols-outlined !text-md">delete</i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    @if($propiedades->hasPages())
                    <div class="mt-[20px] md:mt-[25px] px-[20px] py-[12px] md:py-[15px] rounded-b-md border-t border-gray-100 dark:border-[#172036] sm:flex sm:items-center justify-between">
                        <p class="!mb-0 text-sm">
                            Mostrando {{ $propiedades->firstItem() }}-{{ $propiedades->lastItem() }} de {{ $propiedades->total() }} resultados
                        </p>
                        <ol class="mt-[10px] sm:mt-0">
                            <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                @if($propiedades->onFirstPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </span>
                                @else
                                    <a href="{{ $propiedades->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </a>
                                @endif
                            </li>
                            @foreach($propiedades->getUrlRange(max(1, $propiedades->currentPage() - 2), min($propiedades->lastPage(), $propiedades->currentPage() + 2)) as $page => $url)
                            <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                @if($page == $propiedades->currentPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                                @endif
                            </li>
                            @endforeach
                            <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                @if($propiedades->hasMorePages())
                                    <a href="{{ $propiedades->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
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
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
