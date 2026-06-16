<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Capacitaciones</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Capacitaciones</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Laboral</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Capacitaciones</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-success-700 hover:text-success-900">
                    <i class="material-symbols-outlined !text-lg">close</i>
                </button>
            </div>
            @endif

            <!-- Filtros -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <form method="GET" action="{{ route('dashboard.capacitaciones.index') }}">
                    <div class="flex flex-wrap items-center gap-[12px]">

                        <!-- Búsqueda -->
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre..."
                                class="h-[40px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] pl-[36px] pr-[12px] outline-0 text-sm transition-all focus:border-primary-500 w-[200px]">
                            <i class="material-symbols-outlined absolute left-[10px] top-1/2 -translate-y-1/2 !text-[16px] text-gray-400">search</i>
                        </div>

                        <div class="w-px h-[24px] bg-gray-200 dark:bg-[#172036] hidden sm:block"></div>

                        <!-- Filtro Miembro (pills) -->
                        <div class="flex items-center gap-[6px] flex-wrap">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Miembro:</span>
                            <a href="{{ route('dashboard.capacitaciones.index', array_merge(request()->except(['miembro_id','page']), [])) }}"
                               class="inline-flex items-center gap-[6px] px-[10px] py-[5px] rounded-full text-xs font-medium transition-all border
                               {{ !request('miembro_id') ? 'bg-primary-500 text-white border-primary-500' : 'bg-white dark:bg-[#0c1427] text-gray-600 dark:text-gray-300 border-gray-200 dark:border-[#172036] hover:border-primary-400' }}">
                                Todos
                            </a>
                            @foreach($miembros as $m)
                            @php
                                $nombreM = trim(($m->user?->persona?->nombres ?? '') . ' ' . ($m->user?->persona?->apellido_paterno ?? '')) ?: $m->apodo ?: '—';
                                $avatarM = $m->user?->persona?->foto_url;
                            @endphp
                            <a href="{{ route('dashboard.capacitaciones.index', array_merge(request()->except(['miembro_id','page']), ['miembro_id' => $m->id])) }}"
                               class="inline-flex items-center gap-[6px] px-[10px] py-[5px] rounded-full text-xs font-medium transition-all border
                               {{ request('miembro_id') == $m->id ? 'bg-primary-500 text-white border-primary-500' : 'bg-white dark:bg-[#0c1427] text-gray-600 dark:text-gray-300 border-gray-200 dark:border-[#172036] hover:border-primary-400' }}">
                                @if($avatarM)
                                    <img src="{{ $avatarM }}" class="w-[16px] h-[16px] rounded-full object-cover" alt="">
                                @else
                                    <i class="material-symbols-outlined !text-[14px]">person</i>
                                @endif
                                {{ $nombreM }}
                            </a>
                            @endforeach
                        </div>

                        <div class="w-px h-[24px] bg-gray-200 dark:bg-[#172036] hidden sm:block"></div>

                        <!-- Filtro Tipo -->
                        <div class="flex items-center gap-[6px]">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Tipo:</span>
                            <select name="tipo_id" onchange="this.form.submit()"
                                class="h-[36px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[10px] outline-0 text-xs cursor-pointer transition-all focus:border-primary-500">
                                <option value="">Todos</option>
                                @foreach($tipos as $t)
                                    <option value="{{ $t->id }}" {{ request('tipo_id') == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro Vínculo -->
                        <div class="flex items-center gap-[6px]">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Vínculo:</span>
                            <select name="vinculo" onchange="this.form.submit()"
                                class="h-[36px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[10px] outline-0 text-xs cursor-pointer transition-all focus:border-primary-500">
                                <option value="">Todas</option>
                                <option value="libre" {{ request('vinculo') === 'libre' ? 'selected' : '' }}>Libres</option>
                                <option value="empleo" {{ request('vinculo') === 'empleo' ? 'selected' : '' }}>Con empleo</option>
                            </select>
                        </div>

                        <div class="flex-1"></div>

                        @if(request()->hasAny(['q','miembro_id','tipo_id','vinculo']))
                        <a href="{{ route('dashboard.capacitaciones.index') }}"
                           class="inline-flex items-center gap-[4px] h-[36px] px-[12px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-500 text-xs hover:border-primary-500 hover:text-primary-500 transition-all">
                            <i class="material-symbols-outlined !text-[14px]">clear</i>
                            Limpiar
                        </a>
                        @endif

                        <a href="{{ route('dashboard.capacitaciones.create') }}"
                           class="inline-flex items-center gap-[6px] h-[40px] px-[18px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all whitespace-nowrap">
                            <i class="material-symbols-outlined !text-[18px]">add</i>
                            Nueva Capacitación
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabla -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                    <h5 class="!mb-0">
                        <i class="material-symbols-outlined !text-[20px] align-middle mr-[6px] text-primary-500">school</i>
                        Registro de Capacitaciones
                    </h5>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $capacitaciones->total() }} registro(s)</span>
                </div>

                @if($capacitaciones->isEmpty())
                    <div class="text-center py-[60px]">
                        <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[16px]">school</i>
                        <p class="text-gray-500 dark:text-gray-400 mb-[20px]">No hay capacitaciones registradas.</p>
                        <a href="{{ route('dashboard.capacitaciones.create') }}"
                            class="inline-block bg-primary-500 text-white py-[10px] px-[20px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">add</i>
                                Registrar primera capacitación
                            </span>
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-[#172036]">
                                    <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Nombre</th>
                                    <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Tipo</th>
                                    <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Institución</th>
                                    <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Miembro</th>
                                    <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Fecha Fin</th>
                                    <th class="text-center py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Horas</th>
                                    <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Vínculo</th>
                                    <th class="text-center py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Cert.</th>
                                    <th class="text-center py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-[#172036]">
                                @foreach($capacitaciones as $cap)
                                @php
                                    $nombreM  = trim(($cap->hogarMiembro?->user?->persona?->nombres ?? '') . ' ' . ($cap->hogarMiembro?->user?->persona?->apellido_paterno ?? '')) ?: $cap->hogarMiembro?->apodo ?? '—';
                                    $avatarM  = $cap->hogarMiembro?->user?->persona?->foto_url;
                                    $inicialM = mb_strtoupper(mb_substr($nombreM, 0, 1));
                                    $instituc = $cap->institucionEducativa?->nombre_referencial ?? $cap->institucion_nombre ?? '—';
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                    <!-- Nombre -->
                                    <td class="py-[10px] px-[12px]">
                                        <span class="font-medium text-black dark:text-white block truncate max-w-[180px]" title="{{ $cap->nombre }}">
                                            {{ $cap->nombre }}
                                        </span>
                                        @if(!$cap->activo)
                                            <span class="text-[10px] text-gray-400">Inactivo</span>
                                        @endif
                                    </td>
                                    <!-- Tipo -->
                                    <td class="py-[10px] px-[12px]">
                                        @if($cap->tipoCapacitacion)
                                            <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[2px] px-[8px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400 whitespace-nowrap">
                                                <i class="material-symbols-outlined !text-[12px]">{{ $cap->tipoCapacitacion->icono ?? 'school' }}</i>
                                                {{ $cap->tipoCapacitacion->nombre }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">—</span>
                                        @endif
                                    </td>
                                    <!-- Institución -->
                                    <td class="py-[10px] px-[12px] text-xs text-gray-600 dark:text-gray-400 max-w-[140px]">
                                        <span class="truncate block" title="{{ $instituc }}">{{ $instituc }}</span>
                                    </td>
                                    <!-- Miembro -->
                                    <td class="py-[10px] px-[12px]">
                                        <div class="flex items-center gap-[6px]">
                                            @if($avatarM)
                                                <img src="{{ $avatarM }}" class="w-[24px] h-[24px] rounded-full object-cover flex-shrink-0" alt="">
                                            @else
                                                <span class="w-[24px] h-[24px] rounded-full bg-primary-100 dark:bg-[#1a2d4d] flex items-center justify-center text-[10px] font-bold text-primary-700 dark:text-primary-400 flex-shrink-0">
                                                    {{ $inicialM }}
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-700 dark:text-gray-300 truncate max-w-[100px]" title="{{ $nombreM }}">{{ $nombreM }}</span>
                                        </div>
                                    </td>
                                    <!-- Fecha Fin -->
                                    <td class="py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        {{ $cap->fecha_fin?->format('d/m/Y') ?? '—' }}
                                    </td>
                                    <!-- Horas -->
                                    <td class="py-[10px] px-[12px] text-center text-xs text-gray-600 dark:text-gray-400">
                                        @if($cap->horas_academicas !== null)
                                            <span class="font-mono">{{ $cap->horas_academicas }}h</span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">—</span>
                                        @endif
                                    </td>
                                    <!-- Vínculo -->
                                    <td class="py-[10px] px-[12px]">
                                        @if($cap->empleo)
                                            <a href="{{ route('dashboard.empleos.show', $cap->empleo) }}"
                                               class="inline-flex items-center gap-[4px] text-xs font-medium py-[2px] px-[8px] rounded-full bg-orange-50 dark:bg-[#15203c] text-orange-600 dark:text-orange-400 hover:bg-orange-100 transition-all whitespace-nowrap">
                                                <i class="material-symbols-outlined !text-[12px]">work</i>
                                                {{ Str::limit($cap->empleo->cargo, 18) }}
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[2px] px-[8px] rounded-full bg-gray-100 dark:bg-[#172036] text-gray-500 dark:text-gray-400">
                                                <i class="material-symbols-outlined !text-[12px]">person</i>
                                                Libre
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Certificado -->
                                    <td class="py-[10px] px-[12px] text-center">
                                        @if($cap->file_path)
                                            <a href="{{ asset('storage/' . $cap->file_path) }}" target="_blank"
                                               class="inline-flex items-center justify-center w-[26px] h-[26px] rounded bg-primary-50 dark:bg-[#15203c] text-primary-500 hover:bg-primary-100 transition-all"
                                               title="Ver certificado">
                                                <i class="material-symbols-outlined !text-[14px]">attach_file</i>
                                            </a>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">
                                                <i class="material-symbols-outlined !text-[16px]">remove</i>
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Acciones -->
                                    <td class="py-[10px] px-[12px] text-center">
                                        <div class="flex items-center justify-center gap-[4px]">
                                            <a href="{{ route('dashboard.capacitaciones.show', $cap) }}"
                                               class="w-[28px] h-[28px] rounded flex items-center justify-center bg-primary-50 dark:bg-[#15203c] hover:bg-primary-100 transition-all text-primary-500"
                                               title="Ver">
                                                <i class="material-symbols-outlined !text-[15px]">visibility</i>
                                            </a>
                                            <a href="{{ route('dashboard.capacitaciones.edit', $cap) }}"
                                               class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-50 dark:bg-[#172036] hover:bg-gray-100 transition-all text-gray-500 dark:text-gray-400"
                                               title="Editar">
                                                <i class="material-symbols-outlined !text-[15px]">edit</i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.capacitaciones.destroy', $cap) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="_redirect" value="index">
                                                <button type="submit"
                                                    onclick="return confirm('¿Eliminar «{{ addslashes($cap->nombre) }}»?')"
                                                    class="w-[28px] h-[28px] rounded flex items-center justify-center bg-danger-50 dark:bg-[#172036] hover:bg-danger-100 transition-all text-danger-500"
                                                    title="Eliminar">
                                                    <i class="material-symbols-outlined !text-[15px]">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($capacitaciones->hasPages())
                    <div class="sm:flex sm:items-center justify-between mt-[25px] pt-[14px] border-t border-gray-100 dark:border-[#172036]">
                        <p class="!mb-0 text-sm text-gray-500 dark:text-gray-400">
                            Mostrando {{ $capacitaciones->firstItem() }}–{{ $capacitaciones->lastItem() }} de {{ $capacitaciones->total() }} resultados
                        </p>
                        <ol class="mt-[10px] sm:mt-0">
                            <li class="inline-block mx-[1px]">
                                @if($capacitaciones->onFirstPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </span>
                                @else
                                    <a href="{{ $capacitaciones->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </a>
                                @endif
                            </li>
                            @foreach($capacitaciones->getUrlRange(max(1, $capacitaciones->currentPage()-2), min($capacitaciones->lastPage(), $capacitaciones->currentPage()+2)) as $page => $url)
                            <li class="inline-block mx-[1px]">
                                @if($page == $capacitaciones->currentPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                                @endif
                            </li>
                            @endforeach
                            <li class="inline-block mx-[1px]">
                                @if($capacitaciones->hasMorePages())
                                    <a href="{{ $capacitaciones->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
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

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
