<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Consulta del {{ $consulta->fecha?->format('d/m/Y') }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Consulta Médica</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.consultas-medicas.index') }}" class="transition-all hover:text-primary-500">Consultas</a>
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
                $miembroLabel = trim(implode(' ', array_filter([
                    $consulta->hogarMiembro?->user?->persona?->nombres,
                    $consulta->hogarMiembro?->user?->persona?->apellido_paterno,
                    $consulta->hogarMiembro?->user?->persona?->apellido_materno,
                ]))) ?: ($consulta->hogarMiembro?->user?->name ?? '—');
                $espIcono = $consulta->especialidadMedica?->icono ?? 'stethoscope';
            @endphp

            <!-- Layout patient-details: 1/3 + 2/3 -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- ── Columna izquierda ── -->
                <div class="lg:col-span-1">

                    <!-- Card principal: fecha + miembro + resumen -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md mb-[25px]">
                        <div class="trezo-card-content">
                            <div class="flex items-center gap-[20px] mb-[20px] md:mb-[25px]">
                                <div class="w-[80px] h-[80px] rounded-full bg-primary-100 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                    <i class="material-symbols-outlined !text-[36px] text-primary-500">medical_services</i>
                                </div>
                                <div>
                                    <h4 class="!text-[20px] !mb-[4px]">{{ $consulta->fecha?->format('d/m/Y') ?? '—' }}</h4>
                                    @if($consulta->hora)
                                        <span class="block text-sm text-gray-500 dark:text-gray-400 font-mono">{{ substr($consulta->hora, 0, 5) }} h</span>
                                    @endif
                                </div>
                            </div>

                            <h3 class="!text-lg !mb-[15px]">Información General</h3>
                            <ul class="space-y-[10px]">
                                <li>
                                    Miembro: <span class="text-black dark:text-white font-medium">{{ trim($miembroLabel) ?: '—' }}</span>
                                </li>
                                <li>
                                    Especialidad:
                                    <span class="text-black dark:text-white font-medium">
                                        @if($consulta->especialidadMedica)
                                            <span class="inline-flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-[14px] text-primary-500">{{ $espIcono }}</i>
                                                {{ $consulta->especialidadMedica->nombre }}
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </span>
                                </li>
                                <li>
                                    Médico:
                                    <span class="text-black dark:text-white font-medium">
                                        @if($consulta->medico)
                                            <a href="{{ route('dashboard.medicos.show', $consulta->medico) }}" class="hover:text-primary-500 transition-all">
                                                Dr. {{ $consulta->medico->apellidos }}, {{ $consulta->medico->nombres }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </span>
                                </li>
                                <li>
                                    Centro:
                                    <span class="text-black dark:text-white font-medium">
                                        @if($consulta->centroMedico)
                                            <a href="{{ route('dashboard.centros-medicos.show', $consulta->centroMedico) }}" class="hover:text-primary-500 transition-all">
                                                {{ $consulta->centroMedico->nombre_referencial ?? $consulta->centroMedico->empresa?->razon_social ?? '—' }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </span>
                                </li>
                                @if($consulta->costo !== null)
                                <li>
                                    Costo:
                                    <span class="text-black dark:text-white font-medium">
                                        {{ $consulta->moneda?->simbolo ?? '' }} {{ number_format($consulta->costo, 2) }}
                                    </span>
                                </li>
                                @endif
                            </ul>

                            <div class="mt-[20px] pt-[16px] border-t border-gray-100 dark:border-[#172036] flex gap-[10px]">
                                <a href="{{ route('dashboard.consultas-medicas.edit', $consulta) }}"
                                    class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                    <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                        <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">edit</i>
                                        Editar
                                    </span>
                                </a>
                                <a href="{{ route('dashboard.consultas-medicas.index') }}"
                                    class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] text-sm">
                                    Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card motivo -->
                    @if($consulta->motivo)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md mb-[25px]">
                        <div class="trezo-card-content flex items-start gap-[15px]">
                            <div class="w-[44px] h-[44px] rounded-full bg-orange-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0 mt-[2px]">
                                <i class="material-symbols-outlined !text-[20px] text-orange-500">help</i>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">Motivo de consulta</span>
                                <p class="font-medium text-black dark:text-white mt-[4px] text-sm">{{ $consulta->motivo }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- ── Columna derecha ── -->
                <div class="lg:col-span-2 space-y-[25px]">

                    <!-- Diagnóstico + Tratamiento -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-content">
                            @if($consulta->diagnostico)
                            <h3 class="!text-lg !mb-[15px] mt-[20px] md:mt-[25px] first:mt-0">Diagnóstico</h3>
                            <p class="text-sm leading-relaxed whitespace-pre-line">{{ $consulta->diagnostico }}</p>
                            @endif
                            @if($consulta->tratamiento_indicado)
                            <h3 class="!text-lg !mb-[15px] mt-[20px] md:mt-[25px] first:mt-0">Tratamiento Indicado</h3>
                            <p class="text-sm leading-relaxed whitespace-pre-line">{{ $consulta->tratamiento_indicado }}</p>
                            @endif
                            @if($consulta->notas)
                            <h3 class="!text-lg !mb-[15px] mt-[20px] md:mt-[25px] first:mt-0">Notas</h3>
                            <p class="text-sm leading-relaxed whitespace-pre-line">{{ $consulta->notas }}</p>
                            @endif
                            @if(!$consulta->diagnostico && !$consulta->tratamiento_indicado && !$consulta->notas)
                            <p class="text-sm text-gray-400">No hay información clínica registrada.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Documentos vinculados -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-content">
                            <div class="flex items-center justify-between mb-[16px]">
                                <h3 class="!text-lg !mb-0 flex items-center gap-[8px]">
                                    <i class="material-symbols-outlined !text-[18px] text-primary-500">description</i>
                                    Documentos Médicos
                                    <span class="text-sm font-normal text-gray-400">({{ $consulta->documentosMedicos->count() }})</span>
                                </h3>
                                <a href="{{ route('dashboard.documentos-medicos.create', ['consulta_medica_id' => $consulta->id]) }}"
                                    class="inline-flex items-center gap-[5px] text-xs font-medium text-primary-500 border border-primary-500 rounded-md px-[10px] py-[5px] hover:bg-primary-500 hover:text-white transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">upload_file</i>
                                    Subir documento
                                </a>
                            </div>

                            @if($consulta->documentosMedicos->isNotEmpty())
                            <div class="space-y-[10px]">
                                @foreach($consulta->documentosMedicos->sortByDesc('fecha_documento') as $doc)
                                @php
                                    $docIcono = $doc->tipoDocumentoMedico?->icono ?? 'description';
                                    $docMiembro = trim(implode(' ', array_filter([$doc->hogarMiembro?->user?->persona?->nombres, $doc->hogarMiembro?->user?->persona?->apellido_paterno, $doc->hogarMiembro?->user?->persona?->apellido_materno]))) ?: ($doc->hogarMiembro?->user?->name ?? '—');
                                @endphp
                                <div class="flex items-center justify-between p-[12px] rounded-md border border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all group">
                                    <div class="flex items-center gap-[12px]">
                                        <div class="w-[36px] h-[36px] rounded-md bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                            <i class="material-symbols-outlined !text-[18px] text-primary-500">{{ $docIcono }}</i>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-black dark:text-white">{{ $doc->titulo }}</span>
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-[2px]">
                                                {{ $doc->tipoDocumentoMedico?->nombre ?? '—' }} · {{ trim($docMiembro) }}
                                                @if($doc->fecha_documento)
                                                    · {{ $doc->fecha_documento->format('d/m/Y') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('dashboard.documentos-medicos.show', $doc) }}"
                                        class="text-primary-500 opacity-0 group-hover:opacity-100 transition-all">
                                        <i class="material-symbols-outlined !text-[18px]">open_in_new</i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-sm text-gray-400">No hay documentos vinculados a esta consulta.
                                <a href="{{ route('dashboard.documentos-medicos.create', ['consulta_medica_id' => $consulta->id]) }}" class="text-primary-500 hover:underline">Subir el primero.</a>
                            </p>
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
