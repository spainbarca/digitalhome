<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $compra->concepto }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Detalle de Compra</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.compras.index') }}" class="transition-all hover:text-primary-500">Compras</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        {{ Str::limit($compra->concepto, 40) }}
                    </li>
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

            @if(session('error'))
            <div class="mb-[25px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-danger-700 hover:text-danger-900">
                    <i class="material-symbols-outlined !text-lg">close</i>
                </button>
            </div>
            @endif

            @php
                $logoComercio = $compra->comercio?->logo_efectivo;
                $nombreComercio = $compra->comercio?->nombre_referencial
                    ?: $compra->comercio?->empresa?->razon_social
                    ?: null;
                $nombreMiembro = trim(
                    ($compra->miembro?->user?->persona?->nombres ?? '') . ' ' .
                    ($compra->miembro?->user?->persona?->apellido_paterno ?? '')
                ) ?: ($compra->miembro?->apodo ?? '—');
            @endphp

            <!-- Cabecera -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Panel visual izquierdo -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md h-full">

                        <!-- Total destacado -->
                        <div class="bg-primary-50 dark:bg-[#15203c] rounded-md py-[30px] px-[25px] text-center mb-[20px]">
                            <i class="material-symbols-outlined !text-[48px] text-primary-400 block mb-[8px]">shopping_cart</i>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-[6px]">Total</p>
                            <h3 class="!text-[28px] font-bold text-primary-600 dark:text-primary-400 !mb-[8px]">
                                {{ $compra->moneda?->simbolo }} {{ number_format($compra->total, 2) }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $compra->fecha_compra?->format('d/m/Y') }}</p>
                        </div>

                        <!-- Comercio -->
                        @if($nombreComercio)
                        <div class="flex items-center gap-[10px] mb-[14px] p-[12px] bg-gray-50 dark:bg-[#15203c] rounded-md">
                            @if($logoComercio)
                                <img src="{{ $logoComercio }}" class="w-[36px] h-[36px] object-cover rounded-full flex-shrink-0" alt="">
                            @else
                                <span class="w-[36px] h-[36px] rounded-full bg-primary-100 flex items-center justify-center text-sm font-bold text-primary-700 flex-shrink-0">
                                    {{ mb_strtoupper(mb_substr($nombreComercio, 0, 1)) }}
                                </span>
                            @endif
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400 mb-0">Comercio</p>
                                <p class="text-sm font-medium text-black dark:text-white truncate">{{ $nombreComercio }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Acciones -->
                        <div class="flex items-center gap-[10px] mt-[16px]">
                            <a href="{{ route('dashboard.compras.edit', $compra) }}"
                                class="flex-1 text-center inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <i class="material-symbols-outlined !text-[16px] align-middle mr-[4px]">edit</i>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('dashboard.compras.destroy', $compra) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('¿Eliminar la compra «{{ addslashes($compra->concepto) }}»?')"
                                    class="w-full inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-danger-500 text-white hover:bg-danger-400 text-sm">
                                    <i class="material-symbols-outlined !text-[16px] align-middle mr-[4px]">delete</i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Datos derechos -->
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h4 class="!text-lg !mb-[16px]">Información de la Compra</h4>

                        <ul class="grid grid-cols-1 sm:grid-cols-2 mb-[20px]">
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">Concepto</span>
                                <span class="block text-black dark:text-white font-medium text-sm text-right max-w-[180px] truncate">{{ $compra->concepto }}</span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">Fecha</span>
                                <span class="block text-black dark:text-white font-medium text-sm">{{ $compra->fecha_compra?->format('d/m/Y') }}</span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">Categoría</span>
                                <span class="block">
                                    @if($compra->categoriaCompra)
                                        <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[3px] px-[8px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400">
                                            <i class="material-symbols-outlined !text-[13px]">{{ $compra->categoriaCompra->icono ?? 'label' }}</i>
                                            {{ $compra->categoriaCompra->nombre }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">—</span>
                                    @endif
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">Método de Pago</span>
                                <span class="inline-flex items-center gap-[4px] text-sm text-black dark:text-white">
                                    @if($compra->metodoPago)
                                        <i class="material-symbols-outlined !text-[16px] text-primary-500">{{ $compra->metodoPago->icono ?? 'payment' }}</i>
                                        {{ $compra->metodoPago->nombre }}
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">Moneda</span>
                                <span class="block text-black dark:text-white text-sm">
                                    {{ $compra->moneda?->simbolo }} {{ $compra->moneda?->nombre ?? '—' }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">Miembro</span>
                                <span class="block text-black dark:text-white text-sm">{{ $nombreMiembro }}</span>
                            </li>
                            @if($compra->notas)
                            <li class="sm:col-span-2 py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm mb-[4px]">Notas</span>
                                <p class="text-black dark:text-white text-sm">{{ $compra->notas }}</p>
                            </li>
                            @endif
                        </ul>

                        <a href="{{ route('dashboard.compras.index') }}"
                           class="inline-flex items-center gap-[6px] text-primary-500 hover:text-primary-400 text-sm font-medium transition-all">
                            <i class="material-symbols-outlined !text-[16px]">arrow_back</i>
                            Volver a Compras
                        </a>
                    </div>
                </div>
            </div>

            <!-- ─── Sección Documentos ─────────────────────────────────────────── -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex items-center justify-between mb-[20px]">
                    <div class="flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[22px] text-primary-500">attach_file</i>
                        <h6 class="!mb-0">Documentos</h6>
                        <span class="text-xs text-gray-400">({{ $compra->documentos->count() }})</span>
                    </div>
                    <button type="button" onclick="abrirModalDoc()"
                        class="inline-flex items-center gap-[6px] h-[38px] px-[16px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                        <i class="material-symbols-outlined !text-[16px]">add</i>
                        Agregar documento
                    </button>
                </div>

                @php
                    $hoy = now()->startOfDay();
                @endphp

                @if($compra->documentos->isEmpty())
                    <div class="text-center py-[50px]">
                        <i class="material-symbols-outlined !text-[56px] text-gray-300 dark:text-gray-600 block mb-[12px]">description</i>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mb-[16px]">No hay documentos adjuntos.</p>
                        <button type="button" onclick="abrirModalDoc()"
                            class="inline-flex items-center gap-[6px] px-[16px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            <i class="material-symbols-outlined !text-[16px]">add</i>
                            Agregar primer documento
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[16px]">
                        @foreach($compra->documentos as $doc)
                        @php
                            $ext = strtolower(pathinfo($doc->archivo_path ?? '', PATHINFO_EXTENSION));
                            $esImagen = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                            $esPdf    = $ext === 'pdf';
                            $icono    = $doc->tipoDocumentoCompra?->icono ?? 'description';
                            $tipo     = $doc->tipoDocumentoCompra?->nombre ?? 'Documento';
                            $esGarantia = str_contains(strtolower($tipo), 'garant');
                            $vencColor  = '';
                            $vencLabel  = '';
                            if ($esGarantia && $doc->fecha_vencimiento) {
                                if ($doc->fecha_vencimiento->lt($hoy)) {
                                    $vencColor = 'text-danger-600';
                                    $vencLabel = 'Vencida';
                                } elseif ($doc->fecha_vencimiento->diffInDays($hoy) <= 30) {
                                    $vencColor = 'text-warning-600';
                                    $vencLabel = 'Por vencer';
                                } else {
                                    $vencColor = 'text-success-600';
                                    $vencLabel = 'Vigente';
                                }
                            }
                        @endphp
                        <div class="border border-gray-100 dark:border-[#172036] rounded-md overflow-hidden hover:border-primary-300 transition-all" id="doc-card-{{ $doc->id }}">

                            <!-- Preview -->
                            @if($esImagen && $doc->archivo_path)
                                <div class="h-[140px] bg-gray-50 dark:bg-[#15203c] overflow-hidden">
                                    <img src="{{ asset('storage/' . $doc->archivo_path) }}" class="w-full h-full object-cover" alt="{{ $doc->titulo }}">
                                </div>
                            @elseif($esPdf && $doc->archivo_path)
                                <div class="h-[140px] bg-red-50 dark:bg-[#15203c] flex items-center justify-center">
                                    <i class="material-symbols-outlined !text-[56px] text-red-400">picture_as_pdf</i>
                                </div>
                            @else
                                <div class="h-[140px] bg-gray-50 dark:bg-[#15203c] flex items-center justify-center">
                                    <i class="material-symbols-outlined !text-[56px] text-gray-300 dark:text-gray-600">{{ $icono }}</i>
                                </div>
                            @endif

                            <!-- Info -->
                            <div class="p-[14px]">
                                <div class="flex items-center gap-[6px] mb-[6px]">
                                    <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[2px] px-[7px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400">
                                        <i class="material-symbols-outlined !text-[12px]">{{ $icono }}</i>
                                        {{ $tipo }}
                                    </span>
                                </div>
                                <p class="text-sm font-medium text-black dark:text-white truncate mb-[2px]" title="{{ $doc->titulo }}">{{ $doc->titulo }}</p>
                                @if($doc->fecha_documento)
                                    <p class="text-xs text-gray-400 mb-[4px]">{{ $doc->fecha_documento->format('d/m/Y') }}</p>
                                @endif

                                @if($esGarantia && $doc->fecha_vencimiento)
                                    <p class="text-xs {{ $vencColor }} font-medium flex items-center gap-[3px]">
                                        <i class="material-symbols-outlined !text-[13px]">
                                            {{ $vencLabel === 'Vencida' ? 'error' : ($vencLabel === 'Por vencer' ? 'warning' : 'verified') }}
                                        </i>
                                        Garantía {{ $vencLabel }} · {{ $doc->fecha_vencimiento->format('d/m/Y') }}
                                    </p>
                                @endif

                                <!-- Acciones -->
                                <div class="flex items-center gap-[6px] mt-[10px]">
                                    @if($doc->archivo_path)
                                        <a href="{{ asset('storage/' . $doc->archivo_path) }}" target="_blank"
                                            class="flex-1 inline-flex items-center justify-center gap-[4px] h-[30px] px-[10px] rounded-md bg-primary-50 dark:bg-[#15203c] text-primary-600 text-xs font-medium hover:bg-primary-100 transition-all">
                                            <i class="material-symbols-outlined !text-[14px]">open_in_new</i>
                                            Ver
                                        </a>
                                    @endif
                                    <button type="button"
                                        onclick="abrirModalEditDoc({{ json_encode([
                                            'id'                      => $doc->id,
                                            'tipo_documento_compra_id'=> $doc->tipo_documento_compra_id,
                                            'titulo'                  => $doc->titulo,
                                            'fecha_documento'         => $doc->fecha_documento?->format('Y-m-d'),
                                            'fecha_vencimiento'       => $doc->fecha_vencimiento?->format('Y-m-d'),
                                        ]) }})"
                                        class="flex-1 inline-flex items-center justify-center gap-[4px] h-[30px] px-[10px] rounded-md bg-gray-50 dark:bg-[#172036] text-gray-500 dark:text-gray-400 text-xs font-medium hover:bg-gray-100 transition-all">
                                        <i class="material-symbols-outlined !text-[14px]">edit</i>
                                        Editar
                                    </button>
                                    <form method="POST" action="{{ route('dashboard.compras.documentos.destroy', $doc) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('¿Eliminar el documento «{{ addslashes($doc->titulo) }}»?')"
                                            class="h-[30px] w-[30px] inline-flex items-center justify-center rounded-md bg-danger-50 dark:bg-[#172036] text-danger-500 hover:bg-danger-100 transition-all">
                                            <i class="material-symbols-outlined !text-[14px]">delete</i>
                                        </button>
                                    </form>
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

        <!-- ─── MODAL Documento ──────────────────────────────────────────────── -->
        <div id="modalDocumento" class="fixed inset-0 z-[999] hidden">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalDoc()"></div>
            <!-- Panel -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[520px] bg-white dark:bg-[#0c1427] rounded-md shadow-xl overflow-hidden mx-[16px]">
                <div class="bg-gray-50 dark:bg-[#15203c] flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-base" id="modalDocTitulo">Agregar Documento</h5>
                    <button type="button" onclick="cerrarModalDoc()" class="text-gray-400 hover:text-gray-600 transition-all">
                        <i class="material-symbols-outlined !text-[22px]">close</i>
                    </button>
                </div>

                <form id="formDocumento" method="POST" enctype="multipart/form-data" class="p-[20px]">
                    @csrf
                    <span id="methodSpoof"></span>

                    @if($errors->any())
                    <div class="mb-[14px] bg-danger-100 border border-danger-400 text-danger-700 px-[14px] py-[10px] rounded-md text-sm">
                        <ul class="list-disc ltr:pl-[16px] rtl:pr-[16px]">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px]">

                        <!-- Tipo de documento -->
                        <div class="sm:col-span-2">
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Tipo <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative" id="tipoDocWrapper">
                                <div id="tipoDocTrigger"
                                    class="h-[48px] flex items-center rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] cursor-pointer select-none transition-all hover:border-primary-500">
                                    <i class="material-symbols-outlined !text-[18px] mr-[8px] text-gray-400" id="tipoDocIconPreview">description</i>
                                    <span id="tipoDocLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Selecciona tipo...</span>
                                    <i class="material-symbols-outlined !text-[18px] text-gray-400 transition-transform duration-200" id="tipoDocChevron">expand_more</i>
                                </div>
                                <input type="hidden" name="tipo_documento_compra_id" id="tipo_documento_compra_id" value="">
                                <div id="tipoDocDropdown" class="hidden absolute z-[60] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[2px]">
                                    <ul class="max-h-[200px] overflow-y-auto py-[4px]">
                                        @foreach($tiposDocumento as $td)
                                        <li class="tipo-doc-opcion flex items-center gap-[8px] px-[12px] py-[8px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                            data-id="{{ $td->id }}" data-nombre="{{ $td->nombre }}" data-icono="{{ $td->icono ?? 'description' }}"
                                            data-es-garantia="{{ str_contains(strtolower($td->nombre), 'garant') ? '1' : '0' }}">
                                            <i class="material-symbols-outlined !text-[16px] text-primary-500">{{ $td->icono ?? 'description' }}</i>
                                            <span class="text-sm text-black dark:text-white">{{ $td->nombre }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Título -->
                        <div class="sm:col-span-2">
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Título <span class="text-danger-500">*</span>
                            </label>
                            <input type="text" name="titulo" id="docTitulo" placeholder="Ej: Factura de compra"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                        </div>

                        <!-- Archivo -->
                        <div class="sm:col-span-2">
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm" id="archivoLabel">
                                Archivo <span class="text-danger-500" id="archivoRequerido">*</span>
                                <span id="archivoOpcional" class="hidden text-gray-400 text-xs font-normal">(opcional — deja vacío para mantener el actual)</span>
                            </label>
                            <input type="file" name="archivo" id="docArchivo"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-[10px] file:py-[8px] file:px-[14px] file:rounded-md
                                    file:border-0 file:text-sm file:font-medium
                                    file:bg-primary-50 file:text-primary-600
                                    dark:file:bg-[#15203c] dark:file:text-primary-400
                                    hover:file:bg-primary-100 transition-all cursor-pointer">
                        </div>

                        <!-- Fecha documento -->
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Fecha del documento</label>
                            <input type="date" name="fecha_documento" id="docFechaDocumento"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>

                        <!-- Fecha vencimiento (visible si Garantía) -->
                        <div id="fechaVencimientoWrapper" class="hidden">
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Fecha de vencimiento
                                <span class="text-warning-500 text-xs font-normal ml-[4px]">(garantía)</span>
                            </label>
                            <input type="date" name="fecha_vencimiento" id="docFechaVencimiento"
                                class="h-[48px] rounded-md text-black dark:text-white border border-warning-300 bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all focus:border-warning-400">
                        </div>

                    </div>

                    <div class="mt-[20px] flex justify-end gap-[12px]">
                        <button type="button" onclick="cerrarModalDoc()"
                            class="px-[20px] py-[10px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 text-sm font-medium hover:border-primary-500 hover:text-primary-500 transition-all">
                            Cancelar
                        </button>
                        <button type="submit" id="btnGuardarDoc"
                            class="px-[20px] py-[10px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                Guardar
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @include('partials.front.scripts')

        <script>
        const STORE_URL  = "{{ route('dashboard.compras.documentos.store', $compra) }}";
        const UPDATE_BASE = "{{ url('/dashboard/documentos-compra') }}/";

        // ── Dropdown tipo documento ────────────────────────────────────────────
        (function() {
            const wrapper  = document.getElementById('tipoDocWrapper');
            const trigger  = document.getElementById('tipoDocTrigger');
            const dropdown = document.getElementById('tipoDocDropdown');
            const chevron  = document.getElementById('tipoDocChevron');
            const hidden   = document.getElementById('tipo_documento_compra_id');
            const label    = document.getElementById('tipoDocLabel');
            const iconPrev = document.getElementById('tipoDocIconPreview');
            const opciones = document.querySelectorAll('.tipo-doc-opcion');

            function open()  { dropdown.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; }
            function close() { dropdown.classList.add('hidden');    chevron.style.transform = ''; }

            trigger.addEventListener('click', () => dropdown.classList.contains('hidden') ? open() : close());
            opciones.forEach(li => {
                li.addEventListener('click', () => {
                    hidden.value       = li.dataset.id;
                    label.textContent  = li.dataset.nombre;
                    label.className    = 'text-black dark:text-white text-sm flex-1 truncate';
                    iconPrev.textContent = li.dataset.icono;
                    iconPrev.className   = 'material-symbols-outlined !text-[18px] mr-[8px] text-primary-500';
                    // Mostrar fecha vencimiento solo para garantía
                    const wrapper = document.getElementById('fechaVencimientoWrapper');
                    if (li.dataset.esGarantia === '1') { wrapper.classList.remove('hidden'); }
                    else { wrapper.classList.add('hidden'); document.getElementById('docFechaVencimiento').value = ''; }
                    close();
                });
            });
            document.addEventListener('click', e => { if (!wrapper.contains(e.target)) close(); });
        })();

        // ── Modal ──────────────────────────────────────────────────────────────
        function abrirModalDoc() {
            const modal = document.getElementById('modalDocumento');
            const form  = document.getElementById('formDocumento');

            document.getElementById('modalDocTitulo').textContent = 'Agregar Documento';
            form.action = STORE_URL;
            document.getElementById('methodSpoof').innerHTML = '';
            document.getElementById('docTitulo').value = '';
            document.getElementById('docArchivo').value = '';
            document.getElementById('docFechaDocumento').value = '';
            document.getElementById('docFechaVencimiento').value = '';
            document.getElementById('tipo_documento_compra_id').value = '';
            document.getElementById('tipoDocLabel').textContent = 'Selecciona tipo...';
            document.getElementById('tipoDocLabel').className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
            document.getElementById('tipoDocIconPreview').textContent = 'description';
            document.getElementById('tipoDocIconPreview').className = 'material-symbols-outlined !text-[18px] mr-[8px] text-gray-400';
            document.getElementById('fechaVencimientoWrapper').classList.add('hidden');
            document.getElementById('archivoRequerido').classList.remove('hidden');
            document.getElementById('archivoOpcional').classList.add('hidden');
            document.getElementById('docArchivo').required = true;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function abrirModalEditDoc(data) {
            const modal = document.getElementById('modalDocumento');
            const form  = document.getElementById('formDocumento');

            document.getElementById('modalDocTitulo').textContent = 'Editar Documento';
            form.action = UPDATE_BASE + data.id;
            document.getElementById('methodSpoof').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('docTitulo').value         = data.titulo || '';
            document.getElementById('docArchivo').value        = '';
            document.getElementById('docFechaDocumento').value = data.fecha_documento || '';
            document.getElementById('docFechaVencimiento').value = data.fecha_vencimiento || '';
            document.getElementById('archivoRequerido').classList.add('hidden');
            document.getElementById('archivoOpcional').classList.remove('hidden');
            document.getElementById('docArchivo').required = false;

            // Seleccionar tipo
            const opcion = [...document.querySelectorAll('.tipo-doc-opcion')]
                .find(li => li.dataset.id === data.tipo_documento_compra_id);
            if (opcion) {
                opcion.click();
            }
            if (data.fecha_vencimiento) {
                document.getElementById('fechaVencimientoWrapper').classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalDoc() {
            document.getElementById('modalDocumento').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Abrir modal automáticamente si hay errores de validación del documento
        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                // Si hay errores relacionados con el formulario de documento, abrir modal
                const docFields = ['tipo_documento_compra_id','titulo','archivo','fecha_documento','fecha_vencimiento'];
                const hasDocErrors = {{ json_encode(collect($errors->keys())->intersect(collect(['tipo_documento_compra_id','titulo','archivo','fecha_documento','fecha_vencimiento']))->isNotEmpty()) }};
                if (hasDocErrors) {
                    abrirModalDoc();
                }
            });
        @endif

        document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModalDoc(); });
        </script>
    </body>
</html>
