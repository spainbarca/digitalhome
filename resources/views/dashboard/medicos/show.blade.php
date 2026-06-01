<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        @php $nombreCompleto = 'Dr. ' . trim($medico->apellidos . ', ' . $medico->nombres); @endphp
        <title>{{ $nombreCompleto }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Detalle del Médico</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.medicos.index') }}" class="transition-all hover:text-primary-500">Médicos</a>
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
                $bgColor   = $colores[abs(crc32($medico->id)) % count($colores)];
                $espIcono  = $medico->especialidadMedica?->icono ?? 'stethoscope';
            @endphp

            <!-- grid igual que patient-details: 1/3 izquierda + 2/3 derecha -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- ── Columna izquierda ── -->
                <div class="lg:col-span-1">

                    <!-- Card foto + nombre (igual que patient-details) -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md mb-[25px]">
                        <div class="trezo-card-content">
                            <div class="flex items-center gap-[20px] mb-[20px] md:mb-[25px]">
                                @if($medico->imagen_path)
                                    <img src="{{ Storage::url($medico->imagen_path) }}"
                                        alt="{{ $nombreCompleto }}"
                                        class="rounded-full w-[100px] h-[100px] object-cover flex-shrink-0">
                                @else
                                    <div class="rounded-full w-[100px] h-[100px] {{ $bgColor }} flex items-center justify-center text-white font-bold text-[36px] select-none flex-shrink-0">
                                        {{ strtoupper(mb_substr($medico->apellidos, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h4 class="!text-[20px] !mb-[6px]">{{ trim($medico->nombres . ' ' . $medico->apellidos) }}</h4>
                                    @if($medico->cmp)
                                        <span class="block text-sm text-gray-500 dark:text-gray-400">CMP: <span class="text-black dark:text-white font-mono">{{ $medico->cmp }}</span></span>
                                    @endif
                                </div>
                            </div>

                            <h3 class="!text-lg !mb-[15px]">Información Profesional</h3>
                            <ul>
                                <li class="mb-[12px] last:mb-0">
                                    Especialidad:
                                    <span class="text-black dark:text-white">
                                        @if($medico->especialidadMedica)
                                            <span class="inline-flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-[14px] text-primary-500">{{ $espIcono }}</i>
                                                {{ $medico->especialidadMedica->nombre }}
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </span>
                                </li>
                                <li class="mb-[12px] last:mb-0">
                                    Estado:
                                    <span class="inline-block ml-[4px] px-[8px] py-[2px] rounded-[100px] text-xs font-medium {{ $medico->activo ? 'text-success-600 border border-success-600 bg-success-100' : 'text-danger-600 border border-danger-600 bg-danger-100' }}">
                                        {{ $medico->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </li>
                            </ul>

                            <div class="mt-[20px] pt-[16px] border-t border-gray-100 dark:border-[#172036] flex gap-[10px]">
                                <a href="{{ route('dashboard.medicos.edit', $medico) }}"
                                    class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                    <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                        <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">edit</i>
                                        Editar
                                    </span>
                                </a>
                                <a href="{{ route('dashboard.medicos.index') }}"
                                    class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] text-sm">
                                    Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card email (estilo patient-details) -->
                    @if($medico->email)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md mb-[25px]">
                        <div class="trezo-card-content flex items-center gap-[15px]">
                            <div class="w-[44px] h-[44px] rounded-full bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">mail</i>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">Correo electrónico</span>
                                <a href="mailto:{{ $medico->email }}"
                                    class="block font-semibold text-black dark:text-white mt-[4px] hover:text-primary-500 transition-all text-sm break-all">
                                    {{ $medico->email }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Card teléfono -->
                    @if($medico->telefono)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md mb-[25px]">
                        <div class="trezo-card-content flex items-center gap-[15px]">
                            <div class="w-[44px] h-[44px] rounded-full bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">phone</i>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">Teléfono</span>
                                <h5 class="!mb-0 !font-semibold !text-md mt-[4px]">{{ $medico->telefono }}</h5>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Card CMP -->
                    @if($medico->cmp)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md mb-[25px]">
                        <div class="trezo-card-content flex items-center gap-[15px]">
                            <div class="w-[44px] h-[44px] rounded-full bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">badge</i>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">CMP</span>
                                <h5 class="!mb-0 !font-semibold !text-md mt-[4px] font-mono">{{ $medico->cmp }}</h5>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- ── Columna derecha ── -->
                <div class="lg:col-span-2">

                    <!-- Notas -->
                    @if($medico->notas)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md mb-[25px]">
                        <div class="trezo-card-content">
                            <h3 class="!text-lg !mb-[15px] mt-[20px] md:mt-[25px] first:mt-0">Notas</h3>
                            <p class="whitespace-pre-line text-sm leading-relaxed">{{ $medico->notas }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Consultas médicas -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-content">
                            <h3 class="!text-lg !mb-[15px] mt-[20px] md:mt-[25px] first:mt-0 flex items-center gap-[8px]">
                                <i class="material-symbols-outlined !text-[18px] text-primary-500">medical_services</i>
                                Consultas Médicas
                                <span class="text-sm font-normal text-gray-400">({{ $medico->consultasMedicas->count() }})</span>
                            </h3>

                            @if($medico->consultasMedicas->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr>
                                            <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400 ltr:first:pl-0">Fecha</th>
                                            <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400">Miembro</th>
                                            <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400 ltr:last:pr-0">Motivo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-black dark:text-white">
                                        @foreach($medico->consultasMedicas->sortByDesc('fecha') as $consulta)
                                        <tr>
                                            <td class="ltr:text-left whitespace-nowrap px-[20px] py-[12.5px] ltr:first:pl-0 border-b border-primary-50 dark:border-[#172036] text-xs font-semibold font-mono text-gray-600 dark:text-gray-400">
                                                {{ $consulta->fecha?->format('d/m/Y') ?? '—' }}
                                            </td>
                                            <td class="ltr:text-left whitespace-nowrap px-[20px] py-[12.5px] border-b border-primary-50 dark:border-[#172036] text-xs font-semibold text-gray-600 dark:text-gray-400">
                                                {{ trim(implode(' ', array_filter([$consulta->hogarMiembro?->user?->persona?->nombres, $consulta->hogarMiembro?->user?->persona?->apellido_paterno, $consulta->hogarMiembro?->user?->persona?->apellido_materno]))) ?: ($consulta->hogarMiembro?->user?->name ?? '—') }}
                                            </td>
                                            <td class="ltr:text-left px-[20px] py-[12.5px] ltr:last:pr-0 border-b border-primary-50 dark:border-[#172036] text-xs text-gray-500 dark:text-gray-400 max-w-[280px] truncate">
                                                {{ $consulta->motivo ?: '—' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-sm text-gray-400">Este médico no tiene consultas registradas.</p>
                            @endif
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
