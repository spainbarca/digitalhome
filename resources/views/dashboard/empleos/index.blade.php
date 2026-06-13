<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Empleos</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Empleos</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Laboral</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Empleos</li>
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

            <!-- Filtros + Botón Nuevo -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <form method="GET" action="{{ route('dashboard.empleos.index') }}">
                    <div class="flex flex-wrap items-center gap-[12px]">

                        <!-- Filtro Miembro -->
                        <div class="flex items-center gap-[6px] flex-wrap">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Miembro:</span>
                            <a href="{{ route('dashboard.empleos.index', array_merge(request()->except('miembro_id'), [])) }}"
                               class="inline-flex items-center gap-[6px] px-[12px] py-[6px] rounded-full text-xs font-medium transition-all border
                               {{ !request('miembro_id') ? 'bg-primary-500 text-white border-primary-500' : 'bg-white dark:bg-[#0c1427] text-gray-600 dark:text-gray-300 border-gray-200 dark:border-[#172036] hover:border-primary-400' }}">
                                Todos
                            </a>
                            @foreach($miembros as $m)
                            @php
                                $nombreM  = trim(($m->user?->persona?->nombres ?? '') . ' ' . ($m->user?->persona?->apellido_paterno ?? '')) ?: $m->apodo ?: '—';
                                $avatarM  = $m->user?->persona?->foto_url;
                                $inicialM = mb_strtoupper(mb_substr($nombreM, 0, 1));
                            @endphp
                            <a href="{{ route('dashboard.empleos.index', array_merge(request()->except('miembro_id'), ['miembro_id' => $m->id])) }}"
                               class="inline-flex items-center gap-[6px] px-[12px] py-[6px] rounded-full text-xs font-medium transition-all border
                               {{ request('miembro_id') == $m->id ? 'bg-primary-500 text-white border-primary-500' : 'bg-white dark:bg-[#0c1427] text-gray-600 dark:text-gray-300 border-gray-200 dark:border-[#172036] hover:border-primary-400' }}">
                                @if($avatarM)
                                    <img src="{{ $avatarM }}" class="w-[18px] h-[18px] rounded-full object-cover" alt="">
                                @else
                                    <i class="material-symbols-outlined !text-[18px] text-gray-400">person</i>
                                @endif
                                {{ $nombreM }}
                            </a>
                            @endforeach
                        </div>

                        <div class="w-px h-[24px] bg-gray-200 dark:bg-[#172036] hidden sm:block"></div>

                        <!-- Filtro Estado -->
                        <div class="flex items-center gap-[6px] flex-wrap">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Estado:</span>
                            <a href="{{ route('dashboard.empleos.index', array_merge(request()->except('estado_id'), [])) }}"
                               class="inline-flex items-center gap-[6px] px-[12px] py-[6px] rounded-full text-xs font-medium transition-all border
                               {{ !request('estado_id') ? 'bg-primary-500 text-white border-primary-500' : 'bg-white dark:bg-[#0c1427] text-gray-600 dark:text-gray-300 border-gray-200 dark:border-[#172036] hover:border-primary-400' }}">
                                Todos
                            </a>
                            @foreach($estados as $estado)
                            <a href="{{ route('dashboard.empleos.index', array_merge(request()->except('estado_id'), ['estado_id' => $estado->id])) }}"
                               class="inline-flex items-center gap-[6px] px-[12px] py-[6px] rounded-full text-xs font-medium transition-all border
                               {{ request('estado_id') == $estado->id ? 'bg-primary-500 text-white border-primary-500' : 'bg-white dark:bg-[#0c1427] text-gray-600 dark:text-gray-300 border-gray-200 dark:border-[#172036] hover:border-primary-400' }}">
                                <i class="material-symbols-outlined !text-[13px]">{{ $estado->icono ?? 'flag' }}</i>
                                {{ $estado->nombre }}
                            </a>
                            @endforeach
                        </div>

                        <div class="flex-1"></div>

                        <!-- Botón Nuevo -->
                        <a href="{{ route('dashboard.empleos.create') }}"
                           class="inline-flex items-center gap-[6px] h-[40px] px-[18px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all whitespace-nowrap">
                            <i class="material-symbols-outlined !text-[18px]">add</i>
                            Nuevo Empleo
                        </a>
                    </div>
                </form>
            </div>

            <!-- Timeline de Empleos -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">

                @if($empleos->isEmpty())
                    <div class="text-center py-[60px]">
                        <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[14px]">work</i>
                        <h6 class="text-gray-400 dark:text-gray-500 !mb-[8px]">No hay empleos registrados</h6>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mb-[20px]">Registra el historial laboral de los miembros del hogar.</p>
                        <a href="{{ route('dashboard.empleos.create') }}"
                           class="inline-flex items-center gap-[6px] px-[18px] py-[10px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            <i class="material-symbols-outlined !text-[18px]">add</i>
                            Agregar primer empleo
                        </a>
                    </div>
                @else
                    <div class="relative">
                        <!-- Eje vertical -->
                        <span class="block absolute top-0 bottom-0 ltr:left-[6px] rtl:right-[6px] ltr:md:left-[160px] rtl:md:right-[160px] mt-[5px] ltr:border-l rtl:border-r border-dashed border-gray-200 dark:border-[#172036]"></span>

                        @foreach($empleos as $empleo)
                        @php
                            $logoUrl    = $empleo->empleador?->logo_display_url;
                            $nombreEmp  = $empleo->empleador?->empresa?->razon_social ?? $empleo->empleador?->nombre ?? '—';
                            $inicialEmp = mb_strtoupper(mb_substr($nombreEmp, 0, 1));
                            $estado     = $empleo->estadoEmpleo;
                            $colorDot   = $estado?->color ?? '#6b7280';
                            $nombreM    = trim(($empleo->hogarMiembro?->user?->persona?->nombres ?? '') . ' ' . ($empleo->hogarMiembro?->user?->persona?->apellido_paterno ?? '')) ?: $empleo->hogarMiembro?->apodo ?? '—';
                            $avatarM    = $empleo->hogarMiembro?->user?->persona?->foto_url;
                            $inicialM   = mb_strtoupper(mb_substr($nombreM, 0, 1));
                            $periodoFin = $empleo->es_actual ? 'Actual' : ($empleo->fecha_fin ? $empleo->fecha_fin->format('M Y') : '—');
                            $periodoIni = $empleo->fecha_inicio ? $empleo->fecha_inicio->format('M Y') : '—';
                        @endphp

                        <div class="relative ltr:pl-[25px] rtl:pr-[25px] ltr:md:pl-[190px] rtl:md:pr-[190px] mb-[25px] last:mb-0">

                            <!-- Dot en eje -->
                            <span class="block absolute top-[16px] ltr:left-0 rtl:right-0 ltr:md:left-[154px] rtl:md:right-[154px] w-[14px] h-[14px] rounded-full border-[3px] border-white dark:border-[#0c1427] shadow"
                                  style="background-color: {{ $colorDot }}"></span>

                            <!-- Fecha en el eje (solo md+) -->
                            <span class="md:absolute md:top-[12px] ltr:md:left-0 rtl:md:right-0 text-xs text-gray-400 dark:text-gray-500 block mb-[8px] md:mb-0 w-[140px] ltr:md:text-right rtl:md:text-left leading-[1.4]">
                                {{ $periodoIni }}
                            </span>

                            <!-- Card del empleo -->
                            <div class="rounded-md border transition-all hover:shadow-md
                                {{ $empleo->es_actual
                                    ? 'border-primary-300 dark:border-primary-700 bg-primary-50 dark:bg-[#15203c]'
                                    : 'border-gray-100 dark:border-[#172036] bg-white dark:bg-[#0c1427]' }}">

                                <div class="p-[16px] md:p-[20px]">
                                    <div class="flex items-start gap-[14px]">

                                        <!-- Logo empleador -->
                                        @if($logoUrl)
                                            <img src="{{ $logoUrl }}" class="w-[48px] h-[48px] rounded-md object-cover flex-shrink-0 border border-gray-100 dark:border-[#172036]" alt="{{ $nombreEmp }}">
                                        @else
                                            <span class="w-[48px] h-[48px] rounded-md bg-primary-100 dark:bg-[#1a2d4d] flex items-center justify-center text-lg font-bold text-primary-700 dark:text-primary-400 flex-shrink-0">
                                                {{ $inicialEmp }}
                                            </span>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-wrap items-center gap-[8px] mb-[4px]">
                                                <h6 class="!mb-0 text-black dark:text-white font-semibold truncate">{{ $empleo->cargo }}</h6>
                                                @if($empleo->es_actual)
                                                    <span class="inline-flex items-center gap-[3px] text-[10px] font-bold py-[2px] px-[8px] rounded-full bg-primary-500 text-white uppercase tracking-wide">
                                                        <i class="material-symbols-outlined !text-[11px]">star</i>
                                                        Actual
                                                    </span>
                                                @endif
                                                @if($estado)
                                                    <span class="inline-flex items-center gap-[3px] text-[11px] font-medium py-[2px] px-[8px] rounded-full"
                                                          style="background-color: {{ $estado->color }}22; color: {{ $estado->color }}">
                                                        <i class="material-symbols-outlined !text-[12px]">{{ $estado->icono ?? 'flag' }}</i>
                                                        {{ $estado->nombre }}
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-[6px]">{{ $nombreEmp }}</p>

                                            <div class="flex flex-wrap items-center gap-[12px] text-xs text-gray-400 dark:text-gray-500">
                                                <span class="flex items-center gap-[4px]">
                                                    <i class="material-symbols-outlined !text-[13px]">calendar_today</i>
                                                    {{ $periodoIni }} — {{ $periodoFin }}
                                                </span>
                                                @if($empleo->modalidadLaboral)
                                                    <span class="flex items-center gap-[4px]">
                                                        <i class="material-symbols-outlined !text-[13px]">{{ $empleo->modalidadLaboral->icono ?? 'work' }}</i>
                                                        {{ $empleo->modalidadLaboral->nombre }}
                                                    </span>
                                                @endif
                                                @if($empleo->salario)
                                                    <span class="flex items-center gap-[4px]">
                                                        <i class="material-symbols-outlined !text-[13px]">payments</i>
                                                        {{ $empleo->moneda?->simbolo }}{{ number_format($empleo->salario, 2) }}
                                                    </span>
                                                @endif
                                                <!-- Miembro -->
                                                <span class="flex items-center gap-[4px]">
                                                    @if($avatarM)
                                                        <img src="{{ $avatarM }}" class="w-[16px] h-[16px] rounded-full object-cover" alt="">
                                                    @else
                                                        <span class="w-[16px] h-[16px] rounded-full bg-gray-200 dark:bg-[#172036] flex items-center justify-center text-[9px] font-bold text-gray-500">{{ $inicialM }}</span>
                                                    @endif
                                                    {{ $nombreM }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Acciones -->
                                        <div class="flex items-center gap-[6px] flex-shrink-0">
                                            <a href="{{ route('dashboard.empleos.show', $empleo) }}"
                                               class="h-[32px] px-[10px] inline-flex items-center gap-[4px] rounded-md bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400 text-xs font-medium hover:bg-primary-100 transition-all">
                                                <i class="material-symbols-outlined !text-[14px]">visibility</i>
                                                Ver
                                            </a>
                                            <a href="{{ route('dashboard.empleos.edit', $empleo) }}"
                                               class="h-[32px] w-[32px] inline-flex items-center justify-center rounded-md bg-gray-50 dark:bg-[#172036] text-gray-500 dark:text-gray-400 hover:bg-gray-100 transition-all">
                                                <i class="material-symbols-outlined !text-[14px]">edit</i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.empleos.destroy', $empleo) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('¿Eliminar el empleo «{{ addslashes($empleo->cargo) }}»?')"
                                                    class="h-[32px] w-[32px] inline-flex items-center justify-center rounded-md bg-danger-50 dark:bg-[#172036] text-danger-500 hover:bg-danger-100 transition-all">
                                                    <i class="material-symbols-outlined !text-[14px]">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
