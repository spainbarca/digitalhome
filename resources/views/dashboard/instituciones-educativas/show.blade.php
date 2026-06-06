<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        @php $nombreEfectivo = $institucion->nombre_referencial ?? $institucion->empresa?->razon_social ?? '(Sin nombre)'; @endphp
        <title>{{ $nombreEfectivo }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $nombreEfectivo }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.instituciones-educativas.index') }}" class="transition-all hover:text-primary-500">Instituciones Educativas</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Detalle</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            @php
                $colores   = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                $bgColor   = $colores[abs(crc32($institucion->id)) % count($colores)];
                $tipoIcono = $institucion->tipoInstitucionEducativa?->icono ?? 'school';

                $colorClasses = [
                    'green'  => 'text-success-600 border border-success-600 bg-success-100',
                    'red'    => 'text-danger-600 border border-danger-600 bg-danger-100',
                    'blue'   => 'text-primary-600 border border-primary-600 bg-primary-100',
                    'orange' => 'text-orange-600 border border-orange-600 bg-orange-100',
                    'purple' => 'text-purple-600 border border-purple-600 bg-purple-100',
                    'grey'   => 'text-gray-600 border border-gray-600 bg-gray-100',
                ];

                $matTodas      = $institucion->matriculas;
                $matActivas    = $matTodas->filter(fn($m) => str_contains(strtolower($m->estadoMatricula?->nombre ?? ''), 'activ'));
                $matCulminadas = $matTodas->filter(fn($m) =>
                    str_contains(strtolower($m->estadoMatricula?->nombre ?? ''), 'culmina') ||
                    str_contains(strtolower($m->estadoMatricula?->nombre ?? ''), 'finaliz') ||
                    str_contains(strtolower($m->estadoMatricula?->nombre ?? ''), 'egres'));
                $matRetiradas  = $matTodas->filter(fn($m) =>
                    str_contains(strtolower($m->estadoMatricula?->nombre ?? ''), 'retir') ||
                    str_contains(strtolower($m->estadoMatricula?->nombre ?? ''), 'baja') ||
                    str_contains(strtolower($m->estadoMatricula?->nombre ?? ''), 'deserc'));
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] mb-[25px]">

                <!-- Panel izquierdo: datos de la institución -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] rounded-md text-center overflow-hidden">

                        {{-- Imagen a ancho completo, sin recorte --}}
                        @if($institucion->imagen_path)
                            <img src="{{ Storage::url($institucion->imagen_path) }}"
                                class="w-full h-auto block" alt="Imagen {{ $nombreEfectivo }}">
                        @else
                            <div class="w-full h-[120px] {{ $bgColor }} flex items-center justify-center">
                                <i class="material-symbols-outlined !text-[56px] text-white opacity-20">{{ $tipoIcono }}</i>
                            </div>
                        @endif

                        <div class="px-[25px] pb-[25px]">

                            {{-- Círculo logo superpuesto --}}
                            <div class="flex justify-center -mt-[40px] mb-[14px]">
                                <div class="w-20 h-20 rounded-full ring-4 ring-white dark:ring-[#0c1427] shadow-lg border-[3px] border-primary-200 dark:border-primary-900 overflow-hidden bg-white dark:bg-[#1a2744] flex items-center justify-center">
                                    @if($institucion->logo_path)
                                        <img src="{{ Storage::url($institucion->logo_path) }}"
                                            class="w-full h-full object-contain"
                                            alt="{{ $nombreEfectivo }}">
                                    @else
                                        <i class="material-symbols-outlined !text-[38px] text-primary-400">{{ $tipoIcono }}</i>
                                    @endif
                                </div>
                            </div>

                            <h5 class="font-semibold text-black dark:text-white mb-[4px]">{{ $nombreEfectivo }}</h5>

                            @if($institucion->empresa)
                                <a href="{{ route('dashboard.empresas.show', $institucion->empresa) }}"
                                    class="block text-sm text-primary-500 hover:underline mb-[4px]">
                                    {{ $institucion->empresa->razon_social }}
                                </a>
                                @if($institucion->empresa->ruc)
                                    <p class="text-xs text-gray-400 font-mono mb-[12px]">RUC: {{ $institucion->empresa->ruc }}</p>
                                @else
                                    <div class="mb-[12px]"></div>
                                @endif
                            @endif

                            <div class="flex flex-wrap justify-center gap-[6px] mb-[16px]">
                                @if($institucion->tipoInstitucionEducativa)
                                    <span class="inline-flex items-center gap-[5px] text-xs font-medium px-[10px] py-[4px] rounded-md bg-primary-50 dark:bg-[#15203c] text-primary-500">
                                        <i class="material-symbols-outlined !text-[14px]">{{ $tipoIcono }}</i>
                                        {{ $institucion->tipoInstitucionEducativa->nombre }}
                                    </span>
                                @endif
                                <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] {{ $institucion->activo ? 'text-success-600 border border-success-600 bg-success-100' : 'text-danger-600 border border-danger-600 bg-danger-100' }}">
                                    {{ $institucion->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>

                            @if($institucion->notas)
                            <div class="text-left mb-[16px] px-[4px]">
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Notas</span>
                                <p class="text-sm text-black dark:text-white whitespace-pre-line">{{ $institucion->notas }}</p>
                            </div>
                            @endif

                            @if($institucion->empresa)
                            <div class="text-left mb-[16px] bg-gray-50 dark:bg-[#0a0e19] rounded-md p-[12px]">
                                <h6 class="font-semibold text-black dark:text-white mb-[8px] text-xs flex items-center gap-[6px]">
                                    <i class="material-symbols-outlined !text-[14px] text-primary-500">business</i>
                                    Empresa
                                </h6>
                                <div class="grid grid-cols-2 gap-[6px] text-xs">
                                    <div>
                                        <span class="block text-gray-400 mb-[1px]">Razón social</span>
                                        <span class="font-medium text-black dark:text-white">{{ $institucion->empresa->razon_social }}</span>
                                    </div>
                                    @if($institucion->empresa->ruc)
                                    <div>
                                        <span class="block text-gray-400 mb-[1px]">RUC</span>
                                        <span class="font-medium text-black dark:text-white font-mono">{{ $institucion->empresa->ruc }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <div class="mt-[16px] pt-[16px] border-t border-gray-100 dark:border-[#172036] flex flex-wrap gap-[10px] justify-center">
                                <a href="{{ route('dashboard.instituciones-educativas.edit', $institucion) }}"
                                    class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                    <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                        <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">edit</i>
                                        Editar
                                    </span>
                                </a>
                                <form method="POST" action="{{ route('dashboard.instituciones-educativas.destroy', $institucion) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('¿Eliminar la institución «{{ addslashes($nombreEfectivo) }}»? Esta acción no se puede deshacer.')"
                                        class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-danger-500 text-white hover:bg-danger-400 text-sm">
                                        <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                            <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">delete</i>
                                            Eliminar
                                        </span>
                                    </button>
                                </form>
                                <a href="{{ route('dashboard.instituciones-educativas.index') }}"
                                    class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] text-sm">
                                    Volver
                                </a>
                            </div>

                        </div>{{-- /px-[25px] --}}
                    </div>
                </div>

                <!-- Panel derecho: Matrículas con tabs -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <h5 class="!mb-0">
                                Matrículas
                                <span class="ml-[6px] text-xs font-normal text-gray-400">({{ $matTodas->count() }})</span>
                            </h5>
                            <a href="/dashboard/matriculas/create?institucion_educativa_id={{ $institucion->id }}"
                                class="inline-block transition-all rounded-md font-medium px-[10px] py-[6px] text-xs text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                                <span class="inline-block relative ltr:pl-[18px] rtl:pr-[18px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">add</i>
                                    Nueva Matrícula
                                </span>
                            </a>
                        </div>
                        <div class="trezo-card-content">
                            <div class="trezo-tabs" id="trezo-tabs-matriculas">
                                <ul class="nfts-navs mb-[20px]">
                                    <li class="nav-item inline-block ltr:mr-[16px] rtl:ml-[16px] ltr:md:mr-[25px] rtl:md:ml-[25px]">
                                        <button type="button" data-tab="matTab1" class="nav-link active block transition-all relative uppercase md:tracking-[1px] text-xs font-semibold">
                                            Todas <span class="ml-[4px] text-gray-400 font-normal normal-case">({{ $matTodas->count() }})</span>
                                        </button>
                                    </li>
                                    <li class="nav-item inline-block ltr:mr-[16px] rtl:ml-[16px] ltr:md:mr-[25px] rtl:md:ml-[25px]">
                                        <button type="button" data-tab="matTab2" class="nav-link block transition-all relative uppercase md:tracking-[1px] text-xs font-semibold">
                                            Activas <span class="ml-[4px] text-gray-400 font-normal normal-case">({{ $matActivas->count() }})</span>
                                        </button>
                                    </li>
                                    <li class="nav-item inline-block ltr:mr-[16px] rtl:ml-[16px] ltr:md:mr-[25px] rtl:md:ml-[25px]">
                                        <button type="button" data-tab="matTab3" class="nav-link block transition-all relative uppercase md:tracking-[1px] text-xs font-semibold">
                                            Culminadas <span class="ml-[4px] text-gray-400 font-normal normal-case">({{ $matCulminadas->count() }})</span>
                                        </button>
                                    </li>
                                    <li class="nav-item inline-block ltr:mr-[16px] rtl:ml-[16px]">
                                        <button type="button" data-tab="matTab4" class="nav-link block transition-all relative uppercase md:tracking-[1px] text-xs font-semibold">
                                            Retiradas <span class="ml-[4px] text-gray-400 font-normal normal-case">({{ $matRetiradas->count() }})</span>
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content">

                                    {{-- TAB 1: Todas --}}
                                    <div class="tab-pane active" id="matTab1">
                                        @if($matTodas->isEmpty())
                                        <div class="text-center py-[30px] text-gray-400">
                                            <i class="material-symbols-outlined !text-[36px] block mb-[8px] text-gray-300 dark:text-gray-600">school</i>
                                            No hay matrículas registradas.
                                        </div>
                                        @else
                                        <div class="overflow-x-auto">
                                            <table class="w-full">
                                                <thead>
                                                    <tr>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs ltr:first:rounded-tl-md ltr:last:rounded-tr-md">Miembro</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs">Nivel</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs">Año / Grado</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs ltr:last:rounded-tr-md">Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-black dark:text-white">
                                                    @foreach($matTodas as $mat)
                                                    @php $color = $mat->estadoMatricula?->color ?? 'grey'; @endphp
                                                    <tr>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm">
                                                            {{ $mat->hogarMiembro?->user?->name ?? '—' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $mat->nivelEducativo?->nombre ?? '—' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $mat->año_lectivo ?? '—' }}{{ $mat->grado_ciclo ? ' — ' . $mat->grado_ciclo : '' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036]">
                                                            @if($mat->estadoMatricula)
                                                            <span class="text-xs font-medium px-[7px] py-[2px] rounded-[100px] {{ $colorClasses[$color] ?? $colorClasses['grey'] }}">
                                                                {{ $mat->estadoMatricula->nombre }}
                                                            </span>
                                                            @else
                                                            <span class="text-gray-400 text-xs">—</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- TAB 2: Activas --}}
                                    <div class="tab-pane" id="matTab2">
                                        @if($matActivas->isEmpty())
                                        <div class="text-center py-[30px] text-gray-400">
                                            <i class="material-symbols-outlined !text-[36px] block mb-[8px] text-gray-300 dark:text-gray-600">check_circle</i>
                                            No hay matrículas activas.
                                        </div>
                                        @else
                                        <div class="overflow-x-auto">
                                            <table class="w-full">
                                                <thead>
                                                    <tr>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs ltr:first:rounded-tl-md">Miembro</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs">Nivel</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs">Año / Grado</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs ltr:last:rounded-tr-md">Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-black dark:text-white">
                                                    @foreach($matActivas as $mat)
                                                    @php $color = $mat->estadoMatricula?->color ?? 'grey'; @endphp
                                                    <tr>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm">
                                                            {{ $mat->hogarMiembro?->user?->name ?? '—' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $mat->nivelEducativo?->nombre ?? '—' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $mat->año_lectivo ?? '—' }}{{ $mat->grado_ciclo ? ' — ' . $mat->grado_ciclo : '' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036]">
                                                            @if($mat->estadoMatricula)
                                                            <span class="text-xs font-medium px-[7px] py-[2px] rounded-[100px] {{ $colorClasses[$color] ?? $colorClasses['grey'] }}">
                                                                {{ $mat->estadoMatricula->nombre }}
                                                            </span>
                                                            @else
                                                            <span class="text-gray-400 text-xs">—</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- TAB 3: Culminadas --}}
                                    <div class="tab-pane" id="matTab3">
                                        @if($matCulminadas->isEmpty())
                                        <div class="text-center py-[30px] text-gray-400">
                                            <i class="material-symbols-outlined !text-[36px] block mb-[8px] text-gray-300 dark:text-gray-600">school</i>
                                            No hay matrículas culminadas.
                                        </div>
                                        @else
                                        <div class="overflow-x-auto">
                                            <table class="w-full">
                                                <thead>
                                                    <tr>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs ltr:first:rounded-tl-md">Miembro</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs">Nivel</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs">Año / Grado</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs ltr:last:rounded-tr-md">Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-black dark:text-white">
                                                    @foreach($matCulminadas as $mat)
                                                    @php $color = $mat->estadoMatricula?->color ?? 'grey'; @endphp
                                                    <tr>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm">
                                                            {{ $mat->hogarMiembro?->user?->name ?? '—' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $mat->nivelEducativo?->nombre ?? '—' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $mat->año_lectivo ?? '—' }}{{ $mat->grado_ciclo ? ' — ' . $mat->grado_ciclo : '' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036]">
                                                            @if($mat->estadoMatricula)
                                                            <span class="text-xs font-medium px-[7px] py-[2px] rounded-[100px] {{ $colorClasses[$color] ?? $colorClasses['grey'] }}">
                                                                {{ $mat->estadoMatricula->nombre }}
                                                            </span>
                                                            @else
                                                            <span class="text-gray-400 text-xs">—</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- TAB 4: Retiradas --}}
                                    <div class="tab-pane" id="matTab4">
                                        @if($matRetiradas->isEmpty())
                                        <div class="text-center py-[30px] text-gray-400">
                                            <i class="material-symbols-outlined !text-[36px] block mb-[8px] text-gray-300 dark:text-gray-600">exit_to_app</i>
                                            No hay matrículas retiradas.
                                        </div>
                                        @else
                                        <div class="overflow-x-auto">
                                            <table class="w-full">
                                                <thead>
                                                    <tr>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs ltr:first:rounded-tl-md">Miembro</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs">Nivel</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs">Año / Grado</th>
                                                        <th class="font-medium ltr:text-left px-[12px] py-[8px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap text-xs ltr:last:rounded-tr-md">Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-black dark:text-white">
                                                    @foreach($matRetiradas as $mat)
                                                    @php $color = $mat->estadoMatricula?->color ?? 'grey'; @endphp
                                                    <tr>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm">
                                                            {{ $mat->hogarMiembro?->user?->name ?? '—' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $mat->nivelEducativo?->nombre ?? '—' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $mat->año_lectivo ?? '—' }}{{ $mat->grado_ciclo ? ' — ' . $mat->grado_ciclo : '' }}
                                                        </td>
                                                        <td class="ltr:text-left whitespace-nowrap px-[12px] py-[10px] border-b border-gray-100 dark:border-[#172036]">
                                                            @if($mat->estadoMatricula)
                                                            <span class="text-xs font-medium px-[7px] py-[2px] rounded-[100px] {{ $colorClasses[$color] ?? $colorClasses['grey'] }}">
                                                                {{ $mat->estadoMatricula->nombre }}
                                                            </span>
                                                            @else
                                                            <span class="text-gray-400 text-xs">—</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
