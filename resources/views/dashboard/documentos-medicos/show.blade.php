<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $documento->titulo }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $documento->titulo }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.documentos-medicos.index') }}" class="transition-all hover:text-primary-500">Documentos</a>
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
                $tipoIcono  = $documento->tipoDocumentoMedico?->icono ?? 'description';
                $fileUrl    = $documento->archivo_path ? Storage::url($documento->archivo_path) : null;
                $isImage    = $fileUrl && str_starts_with($documento->archivo_mime ?? '', 'image/');
                $isPdf      = $fileUrl && $documento->archivo_mime === 'application/pdf';
                $fileSize   = $documento->archivo_size
                    ? ($documento->archivo_size >= 1024*1024
                        ? round($documento->archivo_size / (1024*1024), 2) . ' MB'
                        : round($documento->archivo_size / 1024, 0) . ' KB')
                    : null;
                $miembroLabel = trim(implode(' ', array_filter([
                    $documento->hogarMiembro?->user?->persona?->nombres,
                    $documento->hogarMiembro?->user?->persona?->apellido_paterno,
                    $documento->hogarMiembro?->user?->persona?->apellido_materno,
                ]))) ?: ($documento->hogarMiembro?->user?->name ?? '—');
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-[25px] mb-[25px]">

                <!-- ── Columna izquierda (2/5): archivo ── -->
                <div class="lg:col-span-2 flex flex-col gap-[25px]">

                    <!-- Preview del archivo -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">{{ $tipoIcono }}</i>
                            Archivo
                        </h6>

                        @if($fileUrl)
                            @if($isImage)
                                <img src="{{ $fileUrl }}" alt="{{ $documento->titulo }}"
                                    class="w-full h-auto max-h-[300px] object-contain rounded-md border border-gray-100 dark:border-[#172036] mb-[12px]">
                                <a href="{{ $fileUrl }}" target="_blank" rel="noopener"
                                    class="inline-flex items-center gap-[6px] text-sm font-medium text-primary-500 hover:underline">
                                    <i class="material-symbols-outlined !text-[16px]">open_in_new</i>
                                    Ver imagen completa
                                </a>
                            @elseif($isPdf)
                                <iframe src="{{ $fileUrl }}" class="w-full h-[350px] rounded-md border border-gray-100 dark:border-[#172036] mb-[12px]"></iframe>
                                <a href="{{ $fileUrl }}" target="_blank" rel="noopener"
                                    class="inline-flex items-center gap-[6px] text-sm font-medium text-primary-500 hover:underline">
                                    <i class="material-symbols-outlined !text-[16px]">open_in_new</i>
                                    Abrir PDF en nueva pestaña
                                </a>
                            @else
                                <div class="flex items-center gap-[12px] p-[16px] rounded-md bg-gray-50 dark:bg-[#15203c] border border-gray-100 dark:border-[#172036] mb-[12px]">
                                    <i class="material-symbols-outlined !text-[32px] text-primary-500">attach_file</i>
                                    <div>
                                        <span class="block text-sm font-medium text-black dark:text-white">{{ $documento->archivo_nombre_original ?? basename($documento->archivo_path) }}</span>
                                        @if($fileSize)<span class="block text-xs text-gray-400 mt-[2px]">{{ $fileSize }}</span>@endif
                                        <span class="block text-xs text-gray-400">{{ $documento->archivo_mime ?? '' }}</span>
                                    </div>
                                </div>
                                <a href="{{ $fileUrl }}" download="{{ $documento->archivo_nombre_original ?? 'documento' }}"
                                    class="inline-flex items-center gap-[6px] px-[14px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">download</i>
                                    Descargar archivo
                                </a>
                            @endif
                        @else
                            <div class="text-center py-[30px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[8px]">attach_file</i>
                                <p class="text-sm text-gray-400">No hay archivo adjunto.</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- ── Columna derecha (3/5): metadatos ── -->
                <div class="lg:col-span-3 flex flex-col gap-[25px]">

                    <!-- Datos del documento -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">info</i>
                            Datos del Documento
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px]">
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Título</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $documento->titulo }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Tipo</span>
                                <span class="block text-sm font-medium text-black dark:text-white">
                                    @if($documento->tipoDocumentoMedico)
                                        <span class="inline-flex items-center gap-[5px]">
                                            <i class="material-symbols-outlined !text-[14px] text-primary-500">{{ $tipoIcono }}</i>
                                            {{ $documento->tipoDocumentoMedico->nombre }}
                                        </span>
                                    @else
                                        —
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Miembro</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ trim($miembroLabel) ?: '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Fecha del documento</span>
                                <span class="block text-sm font-medium text-black dark:text-white font-mono">
                                    {{ $documento->fecha_documento?->format('d/m/Y') ?? '—' }}
                                </span>
                            </div>
                            @if($fileSize)
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Tamaño</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $fileSize }}</span>
                            </div>
                            @endif
                            @if($documento->archivo_nombre_original)
                            <div class="sm:col-span-2">
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Nombre original</span>
                                <span class="block text-sm font-medium text-black dark:text-white break-all">{{ $documento->archivo_nombre_original }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="mt-[16px] pt-[16px] border-t border-gray-100 dark:border-[#172036] flex gap-[10px] flex-wrap">
                            <a href="{{ route('dashboard.documentos-medicos.edit', $documento) }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">edit</i>
                                    Editar
                                </span>
                            </a>
                            @if($fileUrl)
                            <a href="{{ $fileUrl }}" download="{{ $documento->archivo_nombre_original ?? 'documento' }}" target="_blank"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white text-sm">
                                <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">download</i>
                                    Descargar
                                </span>
                            </a>
                            @endif
                            <a href="{{ route('dashboard.documentos-medicos.index') }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] text-sm">
                                Volver
                            </a>
                        </div>
                    </div>

                    <!-- Consulta vinculada -->
                    @if($documento->consultaMedica)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">medical_services</i>
                            Consulta Vinculada
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[12px]">
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Fecha</span>
                                <span class="block text-sm font-medium text-black dark:text-white font-mono">{{ $documento->consultaMedica->fecha?->format('d/m/Y') ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Médico</span>
                                <span class="block text-sm font-medium text-black dark:text-white">
                                    @if($documento->consultaMedica->medico)
                                        Dr. {{ $documento->consultaMedica->medico->apellidos }}, {{ $documento->consultaMedica->medico->nombres }}
                                    @else
                                        —
                                    @endif
                                </span>
                            </div>
                            @if($documento->consultaMedica->motivo)
                            <div class="sm:col-span-2">
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Motivo</span>
                                <span class="block text-sm text-black dark:text-white">{{ $documento->consultaMedica->motivo }}</span>
                            </div>
                            @endif
                            <div class="sm:col-span-2 pt-[8px]">
                                <a href="{{ route('dashboard.consultas-medicas.show', $documento->consultaMedica) }}"
                                    class="text-sm text-primary-500 hover:underline flex items-center gap-[4px]">
                                    <i class="material-symbols-outlined !text-[16px]">open_in_new</i>
                                    Ver detalle de la consulta
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Notas -->
                    @if($documento->notas)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">notes</i>
                            Notas
                        </h6>
                        <p class="text-sm leading-relaxed whitespace-pre-line text-gray-600 dark:text-gray-400">{{ $documento->notas }}</p>
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
