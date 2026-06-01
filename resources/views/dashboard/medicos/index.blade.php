<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Médicos</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Médicos</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Centro Médico</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Médicos</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('dashboard.medicos.index') }}" class="flex flex-wrap items-center gap-[10px]">
                        <div class="relative sm:w-[250px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Nombre, apellidos o CMP..."
                                class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </div>
                        <select name="especialidad_medica_id"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0 cursor-pointer transition-all focus:border-primary-500 pr-[28px]">
                            <option value="">Todas las especialidades</option>
                            @foreach($especialidades as $esp)
                                <option value="{{ $esp->id }}" {{ $especialidadId == $esp->id ? 'selected' : '' }}>{{ $esp->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="h-[36px] px-[14px] bg-primary-500 text-white rounded-md text-xs hover:bg-primary-400 transition-all">
                            Filtrar
                        </button>
                        @if($search || $especialidadId)
                        <a href="{{ route('dashboard.medicos.index') }}"
                            class="h-[36px] px-[14px] border border-gray-200 dark:border-[#172036] text-gray-500 dark:text-gray-400 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all flex items-center">
                            Limpiar
                        </a>
                        @endif
                    </form>

                    <a href="{{ route('dashboard.medicos.create') }}"
                        class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white mt-[15px] sm:mt-0">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                            Nuevo Médico
                        </span>
                    </a>
                </div>

                <div class="trezo-card-content">
                    <div class="table-responsive overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left rtl:text-right pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400 ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:first:pl-0">Foto</th>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left rtl:text-right pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400 ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:first:pl-0">Médico</th>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left rtl:text-right pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400 ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:first:pl-0">Especialidad</th>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left rtl:text-right pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400 ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:first:pl-0">Contacto</th>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left rtl:text-right pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400 ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:first:pl-0">Estado</th>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left rtl:text-right pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400 ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:first:pl-0">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-black dark:text-white">
                                @forelse($medicos as $m)
                                @php
                                    $nombreCompleto = trim($m->apellidos . ', ' . $m->nombres);
                                    $colores = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                                    $bgColor = $colores[abs(crc32($m->id)) % count($colores)];
                                    $espIcono = $m->especialidadMedica?->icono ?? 'stethoscope';
                                @endphp
                                <tr>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[12.5px] ltr:first:pl-0 rtl:first:pr-0 border-b border-primary-50 dark:border-[#172036] ltr:last:pr-0 rtl:last:pl-0">
                                        @if($m->imagen_path)
                                            <img src="{{ Storage::url($m->imagen_path) }}"
                                                class="w-[35px] h-[35px] rounded-full object-cover" alt="{{ $nombreCompleto }}">
                                        @else
                                            <div class="w-[35px] h-[35px] rounded-full {{ $bgColor }} flex items-center justify-center text-white font-bold text-sm select-none">
                                                {{ strtoupper(mb_substr($m->apellidos, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[12.5px] ltr:first:pl-0 rtl:first:pr-0 border-b border-primary-50 dark:border-[#172036] ltr:last:pr-0 rtl:last:pl-0">
                                        <a href="{{ route('dashboard.medicos.show', $m) }}"
                                            class="block font-semibold text-sm hover:text-primary-500 transition-all">
                                            {{ $nombreCompleto }}
                                        </a>
                                        @if($m->cmp)
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 font-mono mt-[2px]">CMP: {{ $m->cmp }}</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[12.5px] ltr:first:pl-0 rtl:first:pr-0 border-b border-primary-50 dark:border-[#172036] ltr:last:pr-0 rtl:last:pl-0">
                                        @if($m->especialidadMedica)
                                            <span class="flex items-center gap-[5px] text-xs font-semibold text-gray-600 dark:text-gray-400">
                                                <i class="material-symbols-outlined !text-[14px] text-primary-500">{{ $espIcono }}</i>
                                                {{ $m->especialidadMedica->nombre }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[12.5px] ltr:first:pl-0 rtl:first:pr-0 border-b border-primary-50 dark:border-[#172036] ltr:last:pr-0 rtl:last:pl-0">
                                        @if($m->email)
                                            <span class="block text-xs font-semibold text-primary-500">{{ $m->email }}</span>
                                        @endif
                                        @if($m->telefono)
                                            <span class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mt-[2px]">{{ $m->telefono }}</span>
                                        @endif
                                        @if(!$m->email && !$m->telefono)
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[12.5px] ltr:first:pl-0 rtl:first:pr-0 border-b border-primary-50 dark:border-[#172036] ltr:last:pr-0 rtl:last:pl-0">
                                        @if($m->activo)
                                            <span class="px-[8px] py-[3px] inline-block bg-success-50 dark:bg-[#15203c] text-success-600 rounded-sm font-medium text-xs">Activo</span>
                                        @else
                                            <span class="px-[8px] py-[3px] inline-block bg-danger-50 dark:bg-[#15203c] text-danger-500 rounded-sm font-medium text-xs">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[12.5px] ltr:first:pl-0 rtl:first:pr-0 border-b border-primary-50 dark:border-[#172036] ltr:last:pr-0 rtl:last:pl-0">
                                        <div class="flex items-center gap-[9px]">
                                            <a href="{{ route('dashboard.medicos.show', $m) }}"
                                                class="text-primary-500 leading-none custom-tooltip" data-text="Ver">
                                                <i class="material-symbols-outlined !text-md">visibility</i>
                                            </a>
                                            <a href="{{ route('dashboard.medicos.edit', $m) }}"
                                                class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip" data-text="Editar">
                                                <i class="material-symbols-outlined !text-md">edit</i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.medicos.destroy', $m) }}" class="inline leading-none">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-danger-500 leading-none custom-tooltip" data-text="Eliminar"
                                                    onclick="return confirm('¿Eliminar al Dr. {{ addslashes($nombreCompleto) }}?')">
                                                    <i class="material-symbols-outlined !text-md">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center px-[20px] py-[50px] text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">medical_services</i>
                                        @if($search || $especialidadId)
                                            No se encontraron médicos con ese criterio.
                                        @else
                                            No hay médicos registrados.
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($medicos->hasPages())
                    <div class="pt-[12.5px] sm:flex sm:items-center justify-between">
                        <p class="!mb-0 text-xs">
                            Mostrando {{ $medicos->firstItem() }}–{{ $medicos->lastItem() }} de {{ $medicos->total() }} resultados
                        </p>
                        <ol class="mt-[10px] sm:mt-0">
                            <li class="inline-block mx-[1px]">
                                @if($medicos->onFirstPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </span>
                                @else
                                    <a href="{{ $medicos->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </a>
                                @endif
                            </li>
                            @foreach($medicos->getUrlRange(max(1,$medicos->currentPage()-2), min($medicos->lastPage(),$medicos->currentPage()+2)) as $page => $url)
                            <li class="inline-block mx-[1px]">
                                @if($page == $medicos->currentPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                                @endif
                            </li>
                            @endforeach
                            <li class="inline-block mx-[1px]">
                                @if($medicos->hasMorePages())
                                    <a href="{{ $medicos->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
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
