<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Instituciones Educativas</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Instituciones Educativas</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Instituciones Educativas</li>
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
                    <form method="GET" action="{{ route('dashboard.instituciones-educativas.index') }}" class="flex flex-wrap items-center gap-[10px]">
                        <div class="relative sm:w-[250px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Nombre o empresa..."
                                class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </div>
                        <select name="tipo_id"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0 cursor-pointer transition-all focus:border-primary-500 pr-[28px]">
                            <option value="">Todos los tipos</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}" {{ request('tipo_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="h-[36px] px-[14px] bg-primary-500 text-white rounded-md text-xs hover:bg-primary-400 transition-all">
                            Filtrar
                        </button>
                        @if(request('q') || request('tipo_id'))
                        <a href="{{ route('dashboard.instituciones-educativas.index') }}"
                            class="h-[36px] px-[14px] border border-gray-200 dark:border-[#172036] text-gray-500 dark:text-gray-400 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all flex items-center">
                            Limpiar
                        </a>
                        @endif
                    </form>

                    <a href="{{ route('dashboard.instituciones-educativas.create') }}"
                        class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                            Nueva Institución
                        </span>
                    </a>
                </div>
            </div>

            <!-- Cards grid -->
            <div class="trezo-card mb-[25px]">
                <div class="trezo-card-content">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-[25px]">
                        @forelse($instituciones as $inst)
                        @php
                            $colores   = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                            $bgColor   = $colores[abs(crc32($inst->id)) % count($colores)];
                            $tipoIcono = $inst->tipoInstitucionEducativa?->icono ?? 'school';
                            $nombre    = $inst->nombre_referencial ?? $inst->empresa?->razon_social ?? '(Sin nombre)';
                        @endphp
                        <div class="bg-white dark:bg-[#0c1427] p-[10px] rounded-md">
                            <!-- Banner -->
                            <div class="relative h-[160px] rounded-md overflow-hidden">
                                @if($inst->imagen_path)
                                    <img src="{{ Storage::url($inst->imagen_path) }}" class="w-full h-full object-cover" alt="{{ $nombre }}">
                                @else
                                    <div class="w-full h-full {{ $bgColor }} flex items-center justify-center">
                                        <i class="material-symbols-outlined !text-[56px] text-white opacity-25">{{ $tipoIcono }}</i>
                                    </div>
                                @endif
                            </div>
                            <!-- Overlapping logo -->
                            <div class="text-center -mt-[26px]">
                                <div class="relative mb-[10px] inline-flex items-center justify-center w-[52px] h-[52px] rounded-full bg-white dark:bg-[#0c1427] ring-2 ring-white dark:ring-[#0c1427] shadow-sm overflow-hidden">
                                    @if($inst->logo_path)
                                        <img src="{{ Storage::url($inst->logo_path) }}" alt="{{ $nombre }}" class="w-full h-full object-contain">
                                    @elseif($inst->empresa?->logo_url)
                                        <img src="{{ Storage::url($inst->empresa->logo_url) }}" alt="{{ $inst->empresa->razon_social }}" class="w-full h-full object-contain">
                                    @elseif($inst->empresa)
                                        <span class="text-lg font-bold text-gray-500 dark:text-gray-400 leading-none">{{ strtoupper(mb_substr($inst->empresa->razon_social, 0, 1)) }}</span>
                                    @else
                                        <i class="material-symbols-outlined !text-[24px] text-gray-400">business</i>
                                    @endif
                                </div>
                                <h6 class="!text-sm !font-semibold !mb-[3px] truncate px-[8px]" title="{{ $nombre }}">{{ $nombre }}</h6>
                                @if($inst->empresa)
                                    <span class="block text-xs text-gray-400 mb-[4px] truncate px-[8px]">{{ $inst->empresa->razon_social }}</span>
                                @else
                                    <span class="block text-xs text-gray-300 dark:text-gray-600 mb-[4px]">—</span>
                                @endif
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[12px]">
                                    {{ $inst->matriculas_count }} matrícula{{ $inst->matriculas_count !== 1 ? 's' : '' }}
                                </span>
                                <div class="h-[1px] bg-primary-50 dark:bg-[#172036] mb-[12px]"></div>
                                <div class="flex items-center justify-between gap-[6px] px-[4px]">
                                    <div class="flex items-center gap-[6px]">
                                        <i class="material-symbols-outlined !text-[18px] text-primary-400" title="{{ $inst->tipoInstitucionEducativa?->nombre ?? 'Sin tipo' }}">{{ $tipoIcono }}</i>
                                        <span class="inline-block w-[9px] h-[9px] rounded-full {{ $inst->activo ? 'bg-success-500' : 'bg-danger-400' }}" title="{{ $inst->activo ? 'Activo' : 'Inactivo' }}"></span>
                                    </div>
                                    <a href="{{ route('dashboard.instituciones-educativas.show', $inst) }}"
                                        class="inline-block rounded-[7px] py-[5px] px-[13px] font-medium text-sm bg-primary-500 text-white transition-all hover:bg-primary-400">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-1 sm:col-span-2 md:col-span-3 xl:col-span-4 text-center py-[50px] text-gray-400">
                            <i class="material-symbols-outlined !text-[56px] mb-[10px] block text-gray-300 dark:text-gray-600">school</i>
                            @if(request('q') || request('tipo_id'))
                                No se encontraron instituciones con ese criterio.
                            @else
                                No hay instituciones educativas registradas.
                            @endif
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            @if($instituciones->hasPages())
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="sm:flex sm:items-center justify-between">
                    <p class="!mb-0 text-sm">
                        Mostrando {{ $instituciones->firstItem() }}–{{ $instituciones->lastItem() }} de {{ $instituciones->total() }} resultados
                    </p>
                    <ol class="mt-[10px] sm:mt-0">
                        <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0">
                            @if($instituciones->onFirstPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                </span>
                            @else
                                <a href="{{ $instituciones->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                </a>
                            @endif
                        </li>
                        @foreach($instituciones->getUrlRange(max(1,$instituciones->currentPage()-2), min($instituciones->lastPage(),$instituciones->currentPage()+2)) as $page => $url)
                        <li class="inline-block mx-[1px]">
                            @if($page == $instituciones->currentPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                            @endif
                        </li>
                        @endforeach
                        <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0">
                            @if($instituciones->hasMorePages())
                                <a href="{{ $instituciones->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
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
