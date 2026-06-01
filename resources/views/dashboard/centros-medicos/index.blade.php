<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Centros Médicos</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Centros Médicos</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Centros Médicos</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex flex-wrap items-center gap-[12px] justify-between">
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('dashboard.centros-medicos.index') }}" class="flex flex-wrap items-center gap-[10px]">
                        <div class="relative sm:w-[250px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Nombre o empresa..."
                                class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </div>
                        <select name="tipo_centro_medico_id"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0 cursor-pointer transition-all focus:border-primary-500 pr-[28px]">
                            <option value="">Todos los tipos</option>
                            @foreach($tiposCentro as $tipo)
                                <option value="{{ $tipo->id }}" {{ $tipoId == $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="h-[36px] px-[14px] bg-primary-500 text-white rounded-md text-xs hover:bg-primary-400 transition-all">
                            Filtrar
                        </button>
                        @if($search || $tipoId)
                        <a href="{{ route('dashboard.centros-medicos.index') }}"
                            class="h-[36px] px-[14px] border border-gray-200 dark:border-[#172036] text-gray-500 dark:text-gray-400 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all flex items-center">
                            Limpiar
                        </a>
                        @endif
                    </form>

                    <a href="{{ route('dashboard.centros-medicos.create') }}"
                        class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                            Nuevo Centro
                        </span>
                    </a>
                </div>

                <div class="trezo-card-content">
                    <div class="table-responsive overflow-x-auto">
                        <table class="w-full">
                            <thead class="text-black dark:text-white">
                                <tr>
                                    <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md">Imagen</th>
                                    <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">Nombre</th>
                                    <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">Empresa</th>
                                    <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">Tipo</th>
                                    <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">Estado</th>
                                    <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-black dark:text-white">
                                @forelse($centros as $c)
                                @php
                                    $nombreEfectivo = $c->nombre_referencial ?? $c->empresa?->razon_social ?? '(Sin nombre)';
                                    $colores = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                                    $bgColor = $colores[abs(crc32($c->id)) % count($colores)];
                                    $tipoIcono = $c->tipoCentroMedico?->icono ?? 'local_hospital';
                                @endphp
                                <tr>
                                    <td class="ltr:text-left whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r">
                                        @if($c->imagen_path)
                                            <img src="{{ Storage::url($c->imagen_path) }}"
                                                class="w-[40px] h-[40px] rounded-full object-cover" alt="{{ $nombreEfectivo }}">
                                        @else
                                            <div class="w-[40px] h-[40px] rounded-full {{ $bgColor }} flex items-center justify-center text-white font-bold text-[15px] select-none">
                                                {{ strtoupper(mb_substr($nombreEfectivo, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r">
                                        <a href="{{ route('dashboard.centros-medicos.show', $c) }}"
                                            class="block font-medium text-[15px] hover:text-primary-500 transition-all">
                                            {{ $nombreEfectivo }}
                                        </a>
                                        @if($c->nombre_referencial && $c->empresa)
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-[2px]">{{ $c->empresa->ruc }}</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r text-sm">
                                        @if($c->empresa)
                                            <a href="{{ route('dashboard.empresas.show', $c->empresa) }}"
                                                class="text-primary-500 hover:underline text-sm">
                                                {{ $c->empresa->razon_social }}
                                            </a>
                                        @else
                                            <span class="text-gray-400">Sin empresa</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r">
                                        @if($c->tipoCentroMedico)
                                            <span class="flex items-center gap-[6px] text-sm">
                                                <i class="material-symbols-outlined !text-[16px] text-primary-500">{{ $tipoIcono }}</i>
                                                {{ $c->tipoCentroMedico->nombre }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">—</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r">
                                        @if($c->activo)
                                            <span class="px-[8px] py-[3px] inline-block bg-success-50 dark:bg-[#15203c] text-success-600 rounded-sm font-medium text-xs">Activo</span>
                                        @else
                                            <span class="px-[8px] py-[3px] inline-block bg-danger-50 dark:bg-[#15203c] text-danger-500 rounded-sm font-medium text-xs">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r">
                                        <div class="flex items-center gap-[9px]">
                                            <a href="{{ route('dashboard.centros-medicos.show', $c) }}"
                                                class="text-primary-500 leading-none custom-tooltip" data-text="Ver">
                                                <i class="material-symbols-outlined !text-md">visibility</i>
                                            </a>
                                            <a href="{{ route('dashboard.centros-medicos.edit', $c) }}"
                                                class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip" data-text="Editar">
                                                <i class="material-symbols-outlined !text-md">edit</i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.centros-medicos.destroy', $c) }}" class="inline leading-none">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-danger-500 leading-none custom-tooltip" data-text="Eliminar"
                                                    onclick="return confirm('¿Eliminar el centro «{{ addslashes($nombreEfectivo) }}»?')">
                                                    <i class="material-symbols-outlined !text-md">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center px-[20px] py-[50px] text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">local_hospital</i>
                                        @if($search || $tipoId)
                                            No se encontraron centros médicos con ese criterio.
                                        @else
                                            No hay centros médicos registrados.
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($centros->hasPages())
                    <div class="px-[20px] py-[12px] md:py-[14px] rounded-b-md border-l border-r border-b border-gray-100 dark:border-[#172036] sm:flex sm:items-center justify-between">
                        <p class="!mb-0 text-sm">
                            Mostrando {{ $centros->firstItem() }}–{{ $centros->lastItem() }} de {{ $centros->total() }} resultados
                        </p>
                        <ol class="mt-[10px] sm:mt-0">
                            <li class="inline-block mx-[1px]">
                                @if($centros->onFirstPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </span>
                                @else
                                    <a href="{{ $centros->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </a>
                                @endif
                            </li>
                            @foreach($centros->getUrlRange(max(1,$centros->currentPage()-2), min($centros->lastPage(),$centros->currentPage()+2)) as $page => $url)
                            <li class="inline-block mx-[1px]">
                                @if($page == $centros->currentPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                                @endif
                            </li>
                            @endforeach
                            <li class="inline-block mx-[1px]">
                                @if($centros->hasMorePages())
                                    <a href="{{ $centros->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
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
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
