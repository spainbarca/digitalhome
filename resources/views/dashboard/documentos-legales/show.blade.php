<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $documentoLegal->titulo }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $documentoLegal->titulo }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.documentos-legales.index') }}" class="transition-all hover:text-primary-500">Documentos</a>
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
                $tipoIcono   = $documentoLegal->tipoDocumentoLegal?->icono ?? 'description';
                $fileUrl     = $documentoLegal->file_path ? Storage::url($documentoLegal->file_path) : null;
                $ext         = $fileUrl ? strtolower(pathinfo($documentoLegal->file_path, PATHINFO_EXTENSION)) : '';
                $isImage     = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                $isPdf       = $ext === 'pdf';

                $estadoColor = $documentoLegal->estadoDocumentoLegal?->color ?? 'gray';
                $badgeCls    = match($estadoColor) {
                    'green'  => 'text-success-600 bg-success-100 border-success-200',
                    'orange' => 'text-orange-600 bg-orange-100 border-orange-200',
                    'red'    => 'text-danger-600 bg-danger-100 border-danger-200',
                    'blue'   => 'text-primary-600 bg-primary-100 border-primary-200',
                    default  => 'text-gray-600 bg-gray-100 border-gray-200',
                };

                $categoriasMap = ['personal'=>'Personal','propiedad'=>'Propiedad','seguro'=>'Seguro','contrato'=>'Contrato','denuncia'=>'Denuncia','otro'=>'Otro'];
                $catLabel      = $categoriasMap[$documentoLegal->tipoDocumentoLegal?->categoria ?? ''] ?? '—';

                $miembroLabel = '';
                if ($documentoLegal->hogarMiembro) {
                    $miembroLabel = trim(implode(' ', array_filter([
                        $documentoLegal->hogarMiembro?->user?->persona?->nombres,
                        $documentoLegal->hogarMiembro?->user?->persona?->apellido_paterno,
                        $documentoLegal->hogarMiembro?->user?->persona?->apellido_materno,
                    ]))) ?: ($documentoLegal->hogarMiembro?->user?->name ?? '—');
                }

                $hoy   = now()->startOfDay();
                $vence = $documentoLegal->fecha_vencimiento;
                $vencida = $vence && $vence->lt($hoy);
                $porVencer = $vence && !$vencida && $vence->lte($hoy->copy()->addDays(30));
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-[25px] mb-[25px]">

                <!-- ── Columna izquierda (2/5): archivo ── -->
                <div class="lg:col-span-2 flex flex-col gap-[25px]">

                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">{{ $tipoIcono }}</i>
                            Archivo adjunto
                        </h6>

                        @if($fileUrl)
                            @if($isImage)
                                <img src="{{ $fileUrl }}" alt="{{ $documentoLegal->titulo }}"
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
                                    <span class="block text-sm font-medium text-black dark:text-white break-all">{{ basename($documentoLegal->file_path) }}</span>
                                </div>
                            @endif
                            <div class="mt-[12px]">
                                <a href="{{ $fileUrl }}" download target="_blank"
                                    class="inline-flex items-center gap-[6px] px-[14px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">download</i>
                                    Descargar archivo
                                </a>
                            </div>
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

                    <!-- Datos principales -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">info</i>
                            Datos del Documento
                        </h6>

                        <!-- Estado badge prominente -->
                        <div class="mb-[16px]">
                            @if($documentoLegal->estadoDocumentoLegal)
                            <span class="text-xs font-semibold border rounded-full px-[12px] py-[4px] {{ $badgeCls }}">
                                {{ $documentoLegal->estadoDocumentoLegal->nombre }}
                            </span>
                            @endif
                            @if($vencida)
                            <span class="ml-[6px] text-xs font-semibold border rounded-full px-[12px] py-[4px] text-danger-600 bg-danger-100 border-danger-200">
                                Vencido hace {{ abs($hoy->diffInDays($vence)) }} días
                            </span>
                            @elseif($porVencer)
                            <span class="ml-[6px] text-xs font-semibold border rounded-full px-[12px] py-[4px] text-orange-600 bg-orange-100 border-orange-200">
                                Vence en {{ $hoy->diffInDays($vence) }} días
                            </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px]">
                            <div class="sm:col-span-2">
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Título</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $documentoLegal->titulo }}</span>
                            </div>
                            @if($documentoLegal->numero_documento)
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Número de documento</span>
                                <span class="block text-sm font-medium text-black dark:text-white font-mono">{{ $documentoLegal->numero_documento }}</span>
                            </div>
                            @endif
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Tipo</span>
                                <span class="block text-sm font-medium text-black dark:text-white">
                                    @if($documentoLegal->tipoDocumentoLegal)
                                    <span class="inline-flex items-center gap-[5px]">
                                        <i class="material-symbols-outlined !text-[14px] text-primary-500">{{ $tipoIcono }}</i>
                                        {{ $documentoLegal->tipoDocumentoLegal->nombre }}
                                    </span>
                                    @else —
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Categoría</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $catLabel }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Fecha de emisión</span>
                                <span class="block text-sm font-medium text-black dark:text-white font-mono">
                                    {{ $documentoLegal->fecha_emision?->format('d/m/Y') ?? '—' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Fecha de vencimiento</span>
                                <span class="block text-sm font-medium {{ $vencida ? 'text-danger-600' : ($porVencer ? 'text-orange-600' : 'text-black dark:text-white') }} font-mono">
                                    {{ $vence?->format('d/m/Y') ?? '—' }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-[16px] pt-[16px] border-t border-gray-100 dark:border-[#172036] flex gap-[10px] flex-wrap">
                            <a href="{{ route('dashboard.documentos-legales.edit', $documentoLegal) }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">edit</i>
                                    Editar
                                </span>
                            </a>
                            <form method="POST" action="{{ route('dashboard.documentos-legales.destroy', $documentoLegal) }}"
                                onsubmit="return confirm('¿Eliminar este documento legal?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-danger-500 text-white hover:bg-danger-400 text-sm">
                                    <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                        <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">delete</i>
                                        Eliminar
                                    </span>
                                </button>
                            </form>
                            <a href="{{ route('dashboard.documentos-legales.index') }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] text-sm">
                                Volver
                            </a>
                        </div>
                    </div>

                    <!-- Vinculación -->
                    @if($documentoLegal->hogarMiembro || $documentoLegal->propiedadInmueble)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[14px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">link</i>
                            Vinculado a
                        </h6>
                        @if($documentoLegal->hogarMiembro)
                        <div class="flex items-center gap-[10px]">
                            <div class="w-[36px] h-[36px] rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined !text-[18px] text-primary-500">person</i>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-400 mb-[1px]">Miembro del hogar</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $miembroLabel }}</span>
                            </div>
                        </div>
                        @endif
                        @if($documentoLegal->propiedadInmueble)
                        <div class="flex items-center gap-[10px] {{ $documentoLegal->hogarMiembro ? 'mt-[12px] pt-[12px] border-t border-gray-100 dark:border-[#172036]' : '' }}">
                            <div class="w-[36px] h-[36px] rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined !text-[18px] text-primary-500">{{ $documentoLegal->propiedadInmueble->tipoInmueble?->icono ?? 'home' }}</i>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-400 mb-[1px]">Propiedad</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $documentoLegal->propiedadInmueble->alias }}</span>
                                @if($documentoLegal->propiedadInmueble->direccion)
                                <span class="block text-xs text-gray-400">{{ $documentoLegal->propiedadInmueble->direccion }}</span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Entidad emisora -->
                    @if($documentoLegal->entidadLegal)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[14px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">account_balance</i>
                            Entidad Emisora
                        </h6>
                        <div class="flex items-center gap-[12px]">
                            @if($documentoLegal->entidadLegal->logo_path)
                                <img src="{{ Storage::url($documentoLegal->entidadLegal->logo_path) }}"
                                    class="w-[44px] h-[44px] rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-[44px] h-[44px] rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-primary-600 font-bold text-lg">{{ mb_substr($documentoLegal->entidadLegal->nombre, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('dashboard.entidades-legales.show', $documentoLegal->entidadLegal) }}"
                                    class="block text-sm font-medium text-primary-500 hover:underline">
                                    {{ $documentoLegal->entidadLegal->nombre }}
                                </a>
                                <span class="block text-xs text-gray-400">{{ $documentoLegal->entidadLegal->tipoEntidadLegal?->nombre ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Notas -->
                    @if($documentoLegal->notas)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[14px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">notes</i>
                            Notas
                        </h6>
                        <p class="text-sm text-black dark:text-white whitespace-pre-line">{{ $documentoLegal->notas }}</p>
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
