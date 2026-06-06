<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Matrículas</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Matrículas</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Matrículas</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            @php
            $colorMap = [
                'green'  => 'bg-success-100 text-success-700 border border-success-300',
                'red'    => 'bg-danger-100 text-danger-700 border border-danger-300',
                'blue'   => 'bg-primary-100 text-primary-700 border border-primary-300',
                'orange' => 'bg-orange-100 text-orange-700 border border-orange-300',
                'purple' => 'bg-purple-100 text-purple-700 border border-purple-300',
                'grey'   => 'bg-gray-100 text-gray-700 border border-gray-300',
            ];
            @endphp

            {{-- FILA 1: Filtro por miembro --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[20px] p-[20px] md:p-[25px] rounded-md">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-[12px] uppercase tracking-wide">Filtrar por miembro</p>
                <div class="flex flex-wrap gap-[8px]">
                    <a href="{{ route('dashboard.matriculas.index', array_filter(['tipo_id' => $tipoId, 'estado_id' => $estadoId])) }}"
                       class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-[8px] border text-sm font-medium transition-all
                           {{ !$miembroId ? 'bg-primary-500 border-primary-500 text-white' : 'border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500' }}">
                        <i class="material-symbols-outlined !text-[16px]">groups</i>
                        Todos
                    </a>
                    @foreach($miembros as $m)
                        @php
                            $nombreM = trim(implode(' ', array_filter([$m->user?->persona?->nombres, $m->user?->persona?->apellido_paterno, $m->user?->persona?->apellido_materno]))) ?: ($m->user?->name ?? 'Sin nombre');
                            $avatarM = $m->user?->persona?->avatar_url ?? null;
                        @endphp
                        <a href="{{ route('dashboard.matriculas.index', array_filter(['miembro_id' => $m->id, 'tipo_id' => $tipoId, 'estado_id' => $estadoId])) }}"
                           class="inline-flex items-center gap-[7px] px-[14px] py-[6px] rounded-[8px] border text-sm font-medium transition-all
                               {{ $miembroId === $m->id ? 'bg-primary-500 border-primary-500 text-white' : 'border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500' }}">
                            @if($avatarM)
                                <img src="{{ $avatarM }}" class="w-[20px] h-[20px] rounded-full object-cover flex-shrink-0" alt="">
                            @else
                                <span class="w-[20px] h-[20px] rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-[10px] font-bold flex-shrink-0">{{ mb_strtoupper(mb_substr($nombreM, 0, 1)) }}</span>
                            @endif
                            <span class="max-w-[140px] truncate">{{ $nombreM }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- FILA 2: Filtros secundarios --}}
            <form method="GET" action="{{ route('dashboard.matriculas.index') }}" class="trezo-card bg-white dark:bg-[#0c1427] mb-[20px] p-[20px] md:p-[25px] rounded-md">
                @if($miembroId)<input type="hidden" name="miembro_id" value="{{ $miembroId }}">@endif
                <div class="flex flex-wrap items-end gap-[14px]">
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-[6px] font-medium uppercase tracking-wide">Tipo de institución</label>
                        <select name="tipo_id" class="h-[42px] rounded-md text-sm text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 focus:border-primary-500">
                            <option value="">Todos los tipos</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}" {{ $tipoId === $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-[6px] font-medium uppercase tracking-wide">Estado</label>
                        <select name="estado_id" class="h-[42px] rounded-md text-sm text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 focus:border-primary-500">
                            <option value="">Todos los estados</option>
                            @foreach($estados as $est)
                                <option value="{{ $est->id }}" {{ $estadoId === $est->id ? 'selected' : '' }}>{{ $est->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-[8px]">
                        <button type="submit" class="h-[42px] px-[18px] bg-primary-500 text-white rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                            <span class="inline-flex items-center gap-[5px]"><i class="material-symbols-outlined !text-[16px]">filter_list</i> Filtrar</span>
                        </button>
                        <a href="{{ route('dashboard.matriculas.index') }}" class="h-[42px] px-[14px] flex items-center border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 rounded-md hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all text-sm">
                            Limpiar
                        </a>
                    </div>
                    <a href="{{ route('dashboard.matriculas.create') }}"
                       class="h-[42px] px-[18px] flex items-center gap-[6px] bg-success-500 text-white rounded-md hover:bg-success-400 transition-all text-sm font-medium ml-auto">
                        <i class="material-symbols-outlined !text-[16px]">add</i> Nueva matrícula
                    </a>
                </div>
            </form>

            {{-- FILA 3: Grid de cards --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">

                @if($matriculas->isEmpty())
                    <div class="text-center py-[60px]">
                        <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[16px]">school</i>
                        <p class="text-gray-500 dark:text-gray-400 mb-[20px]">No hay matrículas registradas.</p>
                        <a href="{{ route('dashboard.matriculas.create') }}"
                           class="inline-block bg-primary-500 text-white py-[10px] px-[20px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">add</i>
                                Registrar primera matrícula
                            </span>
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-[20px] sm:gap-[25px]">
                        @foreach($matriculas as $mat)
                            @php
                                $inst        = $mat->institucionEducativa;
                                $tipoIco     = $inst?->tipoInstitucionEducativa?->icono ?? 'school';
                                $nombreMat   = trim(implode(' ', array_filter([
                                    $mat->hogarMiembro?->user?->persona?->nombres,
                                    $mat->hogarMiembro?->user?->persona?->apellido_paterno,
                                    $mat->hogarMiembro?->user?->persona?->apellido_materno,
                                ]))) ?: ($mat->hogarMiembro?->user?->name ?? '—');
                                $avatarMat   = $mat->hogarMiembro?->user?->persona?->avatar_url ?? null;
                                $estadoColor = $colorMap[$mat->estadoMatricula?->color ?? 'grey'] ?? $colorMap['grey'];
                            @endphp
                            <div class="bg-white dark:bg-[#0c1427] border border-gray-100 dark:border-[#172036] rounded-xl flex flex-col shadow-sm hover:shadow-md transition-shadow">
                                <div class="p-[14px] flex flex-col gap-[10px] flex-1">

                                    {{-- Header: logo + nombre institución + acciones --}}
                                    <div class="flex items-start justify-between gap-[8px]">
                                        <div class="flex items-center gap-[8px] min-w-0">
                                            <div class="w-[38px] h-[38px] rounded-lg bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                @if($inst?->logo_path)
                                                    <img src="{{ asset('storage/' . $inst->logo_path) }}" class="w-full h-full object-contain" alt="">
                                                @else
                                                    <i class="material-symbols-outlined !text-[20px] text-primary-400">{{ $tipoIco }}</i>
                                                @endif
                                            </div>
                                            <p class="text-sm font-semibold text-black dark:text-white truncate leading-tight">
                                                {{ $inst?->nombre_referencial ?? '(Sin institución)' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-[2px] flex-shrink-0">
                                            <a href="{{ route('dashboard.matriculas.show', $mat) }}"
                                               class="w-[26px] h-[26px] rounded flex items-center justify-center text-gray-400 hover:text-primary-500 hover:bg-primary-50 dark:hover:bg-[#15203c] transition-all" title="Ver">
                                                <i class="material-symbols-outlined !text-[15px]">visibility</i>
                                            </a>
                                            <a href="{{ route('dashboard.matriculas.edit', $mat) }}"
                                               class="w-[26px] h-[26px] rounded flex items-center justify-center text-gray-400 hover:text-primary-500 hover:bg-primary-50 dark:hover:bg-[#15203c] transition-all" title="Editar">
                                                <i class="material-symbols-outlined !text-[15px]">edit</i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.matriculas.destroy', $mat) }}" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('¿Eliminar esta matrícula? Esta acción no se puede deshacer.')"
                                                    class="w-[26px] h-[26px] rounded flex items-center justify-center text-gray-400 hover:text-danger-500 hover:bg-danger-50 dark:hover:bg-danger-900/20 transition-all" title="Eliminar">
                                                    <i class="material-symbols-outlined !text-[15px]">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Miembro --}}
                                    <div class="flex items-center gap-[7px]">
                                        @if($avatarMat)
                                            <img src="{{ $avatarMat }}" class="w-[22px] h-[22px] rounded-full object-cover flex-shrink-0" alt="">
                                        @else
                                            <span class="w-[22px] h-[22px] rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-[10px] font-bold flex-shrink-0">{{ mb_strtoupper(mb_substr($nombreMat, 0, 1)) }}</span>
                                        @endif
                                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $nombreMat }}</span>
                                    </div>

                                    {{-- Nivel educativo --}}
                                    @if($mat->nivelEducativo)
                                        <div class="flex items-center gap-[5px] text-xs text-gray-500 dark:text-gray-400">
                                            <i class="material-symbols-outlined !text-[14px] text-primary-400">{{ $mat->nivelEducativo->icono ?? 'layers' }}</i>
                                            <span>{{ $mat->nivelEducativo->nombre }}</span>
                                        </div>
                                    @endif

                                    {{-- Año lectivo + grado/ciclo --}}
                                    @if($mat->año_lectivo || $mat->grado_ciclo)
                                        <div class="flex items-center gap-[5px] text-xs text-gray-500 dark:text-gray-400">
                                            <i class="material-symbols-outlined !text-[14px] text-gray-400">calendar_today</i>
                                            <span>{{ implode(' · ', array_filter([$mat->año_lectivo, $mat->grado_ciclo])) }}</span>
                                        </div>
                                    @endif

                                    {{-- Footer: estado + costo --}}
                                    <div class="mt-auto pt-[10px] border-t border-gray-100 dark:border-[#172036] flex items-center justify-between gap-[6px]">
                                        @if($mat->estadoMatricula)
                                            <span class="inline-flex items-center gap-[4px] text-[11px] font-medium px-[8px] py-[2px] rounded-[100px] {{ $estadoColor }}">
                                                @if($mat->estadoMatricula->icono)
                                                    <i class="material-symbols-outlined !text-[12px]">{{ $mat->estadoMatricula->icono }}</i>
                                                @endif
                                                {{ $mat->estadoMatricula->nombre }}
                                            </span>
                                        @else
                                            <span></span>
                                        @endif
                                        @if($mat->costo_mensual !== null)
                                            <span class="text-xs font-semibold text-black dark:text-white whitespace-nowrap">
                                                {{ $mat->moneda?->simbolo ?? '' }} {{ number_format($mat->costo_mensual, 2) }}
                                                <span class="text-gray-400 font-normal">/mes</span>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($matriculas->hasPages())
                    <div class="sm:flex sm:items-center justify-between mt-[25px] pt-[14px] border-t border-gray-100 dark:border-[#172036]">
                        <p class="!mb-0 text-sm">Mostrando {{ $matriculas->firstItem() }}–{{ $matriculas->lastItem() }} de {{ $matriculas->total() }} resultados</p>
                        <ol class="mt-[10px] sm:mt-0">
                            <li class="inline-block mx-[1px]">
                                @if($matriculas->onFirstPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i></span>
                                @else
                                    <a href="{{ $matriculas->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i></a>
                                @endif
                            </li>
                            @foreach($matriculas->getUrlRange(max(1,$matriculas->currentPage()-2), min($matriculas->lastPage(),$matriculas->currentPage()+2)) as $page => $url)
                            <li class="inline-block mx-[1px]">
                                @if($page == $matriculas->currentPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                                @endif
                            </li>
                            @endforeach
                            <li class="inline-block mx-[1px]">
                                @if($matriculas->hasMorePages())
                                    <a href="{{ $matriculas->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i></a>
                                @else
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i></span>
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
