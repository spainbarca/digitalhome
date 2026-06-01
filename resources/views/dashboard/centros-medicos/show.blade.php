<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        @php $nombreEfectivo = $centro->nombre_referencial ?? $centro->empresa?->razon_social ?? '(Sin nombre)'; @endphp
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
                        <a href="{{ route('dashboard.centros-medicos.index') }}" class="transition-all hover:text-primary-500">Centros Médicos</a>
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
                $bgColor   = $colores[abs(crc32($centro->id)) % count($colores)];
                $tipoIcono = $centro->tipoCentroMedico?->icono ?? 'local_hospital';
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Panel izquierdo -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md text-center">
                        <div class="flex justify-center mb-[16px]">
                            @if($centro->imagen_path)
                                <img src="{{ Storage::url($centro->imagen_path) }}"
                                    class="w-[96px] h-[96px] rounded-full object-cover ring-4 ring-primary-100 dark:ring-primary-900/30"
                                    alt="{{ $nombreEfectivo }}">
                            @else
                                <div class="w-[96px] h-[96px] rounded-full {{ $bgColor }} flex items-center justify-center text-white font-bold text-[36px] select-none ring-4 ring-primary-100 dark:ring-primary-900/30">
                                    {{ strtoupper(mb_substr($nombreEfectivo, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <h5 class="font-semibold text-black dark:text-white mb-[4px]">{{ $nombreEfectivo }}</h5>

                        @if($centro->empresa)
                            <a href="{{ route('dashboard.empresas.show', $centro->empresa) }}"
                                class="block text-sm text-primary-500 hover:underline mb-[8px]">
                                {{ $centro->empresa->razon_social }}
                            </a>
                            <p class="text-xs text-gray-400 font-mono mb-[12px]">RUC: {{ $centro->empresa->ruc }}</p>
                        @endif

                        <div class="flex flex-wrap justify-center gap-[6px] mb-[16px]">
                            @if($centro->tipoCentroMedico)
                                <span class="inline-flex items-center gap-[5px] text-xs font-medium px-[10px] py-[4px] rounded-md bg-primary-50 dark:bg-[#15203c] text-primary-500">
                                    <i class="material-symbols-outlined !text-[14px]">{{ $tipoIcono }}</i>
                                    {{ $centro->tipoCentroMedico->nombre }}
                                </span>
                            @endif
                            <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] {{ $centro->activo ? 'text-success-600 border border-success-600 bg-success-100' : 'text-danger-600 border border-danger-600 bg-danger-100' }}">
                                {{ $centro->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <div class="mt-[20px] pt-[16px] border-t border-gray-100 dark:border-[#172036] flex gap-[10px] justify-center">
                            <a href="{{ route('dashboard.centros-medicos.edit', $centro) }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">edit</i>
                                    Editar
                                </span>
                            </a>
                            <a href="{{ route('dashboard.centros-medicos.index') }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] text-sm">
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Panel derecho -->
                <div class="lg:col-span-2 space-y-[25px]">

                    <!-- Datos del centro -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">local_hospital</i>
                            Datos del Centro
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[12px]">
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Nombre referencial</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $centro->nombre_referencial ?: '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Tipo de centro</span>
                                <span class="block text-sm font-medium text-black dark:text-white flex items-center gap-[6px]">
                                    @if($centro->tipoCentroMedico)
                                        <i class="material-symbols-outlined !text-[16px] text-primary-500">{{ $tipoIcono }}</i>
                                        {{ $centro->tipoCentroMedico->nombre }}
                                    @else
                                        —
                                    @endif
                                </span>
                            </div>
                            @if($centro->notas)
                            <div class="sm:col-span-2">
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Notas</span>
                                <p class="text-sm text-black dark:text-white whitespace-pre-line">{{ $centro->notas }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Empresa vinculada -->
                    @if($centro->empresa)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">business</i>
                            Empresa Vinculada
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[12px]">
                            @php
                            $datosEmpresa = [
                                ['Razón social', $centro->empresa->razon_social],
                                ['RUC',          $centro->empresa->ruc],
                                ['Estado SUNAT', $centro->empresa->estado_sunat],
                                ['Condición',    $centro->empresa->condicion_sunat],
                                ['Sector',       $centro->empresa->sector?->nombre],
                            ];
                            @endphp
                            @foreach($datosEmpresa as [$lbl, $val])
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">{{ $lbl }}</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $val ?: '—' }}</span>
                            </div>
                            @endforeach
                            <div class="sm:col-span-2 pt-[8px]">
                                <a href="{{ route('dashboard.empresas.show', $centro->empresa) }}"
                                    class="text-sm text-primary-500 hover:underline flex items-center gap-[4px]">
                                    <i class="material-symbols-outlined !text-[16px]">open_in_new</i>
                                    Ver ficha completa de la empresa
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Consultas médicas vinculadas -->
            @if($centro->consultasMedicas->isNotEmpty())
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                    <i class="material-symbols-outlined !text-[18px] text-primary-500">medical_services</i>
                    Consultas Médicas
                    <span class="ml-[6px] text-xs font-normal text-gray-400">({{ $centro->consultasMedicas->count() }})</span>
                </h6>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md">Fecha</th>
                                <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">Miembro</th>
                                <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">Médico</th>
                                <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">Motivo</th>
                            </tr>
                        </thead>
                        <tbody class="text-black dark:text-white">
                            @foreach($centro->consultasMedicas->sortByDesc('fecha') as $consulta)
                            <tr>
                                <td class="ltr:text-left whitespace-nowrap px-[20px] py-[13px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r text-sm font-mono">
                                    {{ $consulta->fecha?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="ltr:text-left whitespace-nowrap px-[20px] py-[13px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r text-sm">
                                    {{ $consulta->hogarMiembro?->apodo ?? $consulta->hogarMiembro?->rol ?? '—' }}
                                </td>
                                <td class="ltr:text-left whitespace-nowrap px-[20px] py-[13px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r text-sm">
                                    @if($consulta->medico)
                                        {{ trim($consulta->medico->nombres . ' ' . $consulta->medico->apellidos) }}
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="ltr:text-left px-[20px] py-[13px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r text-sm text-gray-600 dark:text-gray-400 max-w-[300px] truncate">
                                    {{ $consulta->motivo ?: '—' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
