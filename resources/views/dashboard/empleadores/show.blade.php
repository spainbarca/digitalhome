<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $empleador->nombre }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $empleador->nombre }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.empleadores.index') }}" class="transition-all hover:text-primary-500">Empleadores</a>
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
                $colores = ['from-primary-400 to-primary-600','from-orange-400 to-orange-600','from-purple-400 to-purple-600','from-success-400 to-success-600','from-pink-400 to-pink-600'];
                $gradiente = $colores[abs(crc32($empleador->id)) % count($colores)];
                $inicial = strtoupper(mb_substr($empleador->nombre, 0, 1));
                $distritoEfectivo = $empleador->distrito_efectivo;
                $direccionEfectiva = $empleador->direccion_efectiva;
                $empresa = $empleador->empresa;
                $estadoSunat = strtoupper($empresa?->estado_sunat ?? '');
                $estadoBadge = match(true) {
                    str_contains($estadoSunat, 'ACTIVO') => 'text-success-600 border border-success-600 bg-success-100',
                    str_contains($estadoSunat, 'BAJA')   => 'text-danger-600 border border-danger-600 bg-danger-100',
                    $estadoSunat !== ''                  => 'text-warning-600 border border-warning-600 bg-warning-100',
                    default                              => '',
                };
            @endphp

            <!-- Banner -->
            <div class="rounded-md overflow-hidden h-[180px] md:h-[220px] relative mb-0">
                @if($empleador->banner_display_url)
                    <img src="{{ $empleador->banner_display_url }}" class="w-full h-full object-cover" alt="">
                @else
                    <div class="w-full h-full bg-gradient-to-br {{ $gradiente }}"></div>
                @endif
                <!-- Botones de acción en banner -->
                <div class="absolute top-[12px] right-[12px] flex gap-[8px]">
                    <a href="{{ route('dashboard.empleadores.edit', $empleador) }}"
                        class="inline-flex items-center gap-[6px] px-[12px] py-[7px] rounded-md bg-white/90 dark:bg-[#0c1427]/90 text-black dark:text-white text-sm font-medium hover:bg-white transition-all shadow-sm">
                        <i class="material-symbols-outlined !text-[16px]">edit</i>
                        Editar
                    </a>
                    <form method="POST" action="{{ route('dashboard.empleadores.destroy', $empleador) }}"
                        onsubmit="return confirm('¿Eliminar este empleador?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-[6px] px-[12px] py-[7px] rounded-md bg-danger-500/90 text-white text-sm font-medium hover:bg-danger-500 transition-all shadow-sm">
                            <i class="material-symbols-outlined !text-[16px]">delete</i>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mt-[25px] mb-[25px]">

                <!-- Columna izquierda: identidad y contacto -->
                <div class="lg:col-span-1 space-y-[25px]">

                    <!-- Card identidad -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md text-center">
                        <!-- Logo con ring, -mt para solapar con banner -->
                        <div class="flex justify-center -mt-[60px] mb-[16px]">
                            @if($empleador->logo_display_url)
                                <img src="{{ $empleador->logo_display_url }}"
                                    class="w-[96px] h-[96px] rounded-full object-cover ring-4 ring-white dark:ring-[#0c1427] shadow-md"
                                    alt="{{ $empleador->nombre }}">
                            @else
                                <div class="w-[96px] h-[96px] rounded-full bg-gradient-to-br {{ $gradiente }} flex items-center justify-center text-white font-bold text-[36px] ring-4 ring-white dark:ring-[#0c1427] shadow-md select-none">
                                    {{ $inicial }}
                                </div>
                            @endif
                        </div>

                        <h5 class="font-semibold text-black dark:text-white mb-[4px]">{{ $empleador->nombre }}</h5>

                        @if($empleador->sigla_resuelta)
                            <span class="inline-block text-xs font-mono font-semibold px-[8px] py-[2px] rounded bg-gray-100 dark:bg-[#15203c] text-gray-500 dark:text-gray-400 mb-[8px]">{{ $empleador->sigla_resuelta }}</span>
                        @endif

                        <div class="flex flex-wrap justify-center gap-[6px] mb-[16px]">
                            <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] {{ $empleador->activo ? 'text-success-600 border border-success-600 bg-success-100' : 'text-danger-600 border border-danger-600 bg-danger-100' }}">
                                {{ $empleador->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        @if($empleador->descripcion)
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-left">{{ $empleador->descripcion }}</p>
                        @endif
                    </div>

                    <!-- Card contacto -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">contact_phone</i>
                            Contacto
                        </h6>
                        <ul class="space-y-[12px]">
                            @if($empleador->telefono)
                            <li class="flex items-start gap-[10px] text-sm">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400 mt-[1px] flex-shrink-0">phone</i>
                                <span class="text-gray-700 dark:text-gray-300">{{ $empleador->telefono }}</span>
                            </li>
                            @endif
                            @if($empleador->email)
                            <li class="flex items-start gap-[10px] text-sm">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400 mt-[1px] flex-shrink-0">mail</i>
                                <a href="mailto:{{ $empleador->email }}" class="text-primary-500 hover:underline">{{ $empleador->email }}</a>
                            </li>
                            @endif
                            @if($empleador->sitio_web)
                            <li class="flex items-start gap-[10px] text-sm">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400 mt-[1px] flex-shrink-0">language</i>
                                <a href="{{ $empleador->sitio_web }}" target="_blank" rel="noopener" class="text-primary-500 hover:underline truncate">{{ $empleador->sitio_web }}</a>
                            </li>
                            @endif
                            @if($direccionEfectiva)
                            <li class="flex items-start gap-[10px] text-sm">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400 mt-[1px] flex-shrink-0">location_on</i>
                                <span class="text-gray-700 dark:text-gray-300">{{ $direccionEfectiva }}</span>
                            </li>
                            @endif
                            @if($distritoEfectivo)
                            <li class="flex items-start gap-[10px] text-sm">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400 mt-[1px] flex-shrink-0">map</i>
                                <span class="text-gray-700 dark:text-gray-300">
                                    {{ $distritoEfectivo->distrito }} — {{ $distritoEfectivo->provincia }} — {{ $distritoEfectivo->departamento }}
                                </span>
                            </li>
                            @endif
                            @if(!$empleador->telefono && !$empleador->email && !$empleador->sitio_web && !$direccionEfectiva)
                            <li class="text-sm text-gray-400">Sin datos de contacto registrados.</li>
                            @endif
                        </ul>
                    </div>

                    <!-- Card empresa (si tiene) -->
                    @if($empresa)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">business</i>
                            Empresa Vinculada
                        </h6>
                        <div class="flex items-center gap-[12px] mb-[12px]">
                            @if($empresa->logo_url)
                                <img src="{{ Storage::url($empresa->logo_url) }}"
                                    class="w-[48px] h-[48px] rounded-md object-cover flex-shrink-0" alt="{{ $empresa->razon_social }}">
                            @else
                                @php $bgEmp = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'][abs(crc32($empresa->id)) % 5]; @endphp
                                <div class="w-[48px] h-[48px] rounded-md {{ $bgEmp }} flex items-center justify-center text-white font-bold text-[20px] flex-shrink-0 select-none">
                                    {{ strtoupper(mb_substr($empresa->razon_social, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <a href="{{ route('dashboard.empresas.show', $empresa) }}"
                                    class="block text-sm font-semibold text-black dark:text-white hover:text-primary-500 truncate">
                                    {{ $empresa->razon_social }}
                                </a>
                                @if($empresa->ruc)
                                <span class="text-xs text-gray-400 font-mono">RUC: {{ $empresa->ruc }}</span>
                                @endif
                            </div>
                        </div>
                        @if($empresa->estado_sunat || $empresa->condicion_sunat)
                        <div class="flex flex-wrap gap-[6px]">
                            @if($empresa->estado_sunat && $estadoBadge)
                            <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] {{ $estadoBadge }}">
                                {{ $empresa->estado_sunat }}
                            </span>
                            @endif
                            @if($empresa->condicion_sunat)
                            <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] text-gray-600 border border-gray-300 bg-gray-50 dark:bg-[#15203c]">
                                {{ $empresa->condicion_sunat }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif

                </div>

                <!-- Columna derecha: empleos -->
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                            <h5 class="!mb-0 flex items-center gap-[8px]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">work</i>
                                Empleos asociados
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $empleador->empleos->count() }})</span>
                            </h5>
                        </div>

                        @if($empleador->empleos->isEmpty())
                        <div class="text-center py-[40px] text-gray-400">
                            <i class="material-symbols-outlined !text-[40px] block mb-[8px]">work_off</i>
                            Sin empleos registrados para este empleador.
                        </div>
                        @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-[#172036]">
                                        <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Miembro</th>
                                        <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Cargo</th>
                                        <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Modalidad</th>
                                        <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Estado</th>
                                        <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Inicio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($empleador->empleos as $empleo)
                                    <tr class="border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                        <td class="py-[10px] px-[12px]">
                                            @php
                                                $persona = $empleo->hogarMiembro?->user?->persona;
                                                $nombreMiembro = $persona
                                                    ? trim(($persona->nombre ?? '') . ' ' . ($persona->apellido_paterno ?? ''))
                                                    : '—';
                                            @endphp
                                            <span class="text-sm text-black dark:text-white font-medium">{{ $nombreMiembro }}</span>
                                        </td>
                                        <td class="py-[10px] px-[12px] text-sm text-gray-600 dark:text-gray-400">{{ $empleo->cargo ?? '—' }}</td>
                                        <td class="py-[10px] px-[12px] text-sm text-gray-600 dark:text-gray-400">{{ $empleo->modalidadLaboral?->nombre ?? '—' }}</td>
                                        <td class="py-[10px] px-[12px]">
                                            @if($empleo->estadoEmpleo)
                                            @php
                                                $colorClases = [
                                                    'green'   => 'text-success-600 border-success-600 bg-success-100',
                                                    'red'     => 'text-danger-600 border-danger-600 bg-danger-100',
                                                    'orange'  => 'text-orange-600 border-orange-400 bg-orange-50',
                                                    'blue'    => 'text-blue-600 border-blue-400 bg-blue-50',
                                                    'gray'    => 'text-gray-600 border-gray-400 bg-gray-50',
                                                    'yellow'  => 'text-yellow-700 border-yellow-400 bg-yellow-50',
                                                    'indigo'  => 'text-indigo-600 border-indigo-400 bg-indigo-50',
                                                ];
                                                $colorBadge = $colorClases[$empleo->estadoEmpleo->color ?? ''] ?? 'text-gray-600 border-gray-300 bg-gray-50';
                                            @endphp
                                            <span class="inline-block text-xs font-medium px-[8px] py-[2px] rounded-[100px] border {{ $colorBadge }}">
                                                {{ $empleo->estadoEmpleo->nombre }}
                                            </span>
                                            @else
                                            <span class="text-sm text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="py-[10px] px-[12px] text-sm text-gray-600 dark:text-gray-400">
                                            {{ $empleo->fecha_inicio?->format('d/m/Y') ?? '—' }}
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

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
