<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <title>{{ $empleo->cargo }} — Empleo</title>
        @vite('resources/css/app.css')
        <style>
            .select2-container--default .select2-selection--single { height:44px;border-radius:6px;border-color:#e5e7eb;display:flex;align-items:center;padding:0 12px; }
            .select2-container--default .select2-selection--single .select2-selection__rendered { line-height:normal;padding:0;color:inherit;display:flex;align-items:center;gap:8px;width:100%; }
            .select2-container--default .select2-selection--single .select2-selection__arrow { height:44px;top:0; }
            .select2-container--default .select2-selection--single .select2-selection__clear { margin-right:8px; }
            .select2-dropdown { border-color:#e5e7eb;border-radius:6px;z-index:10000; }
            .select2-search--dropdown .select2-search__field { border-radius:4px;border-color:#e5e7eb;outline:none; }
            .select2-results__option { display:flex;align-items:center;gap:8px;padding:9px 12px; }
            .select2-container--default .select2-results__option--highlighted { background-color:#f0f6ff;color:#4f88e4; }
            .dark .select2-container--default .select2-selection--single { background-color:#0c1427;border-color:#172036;color:#fff; }
            .dark .select2-dropdown { background-color:#0c1427;border-color:#172036; }
            .dark .select2-results__option { color:#fff; }
            .dark .select2-container--default .select2-results__option--highlighted { background-color:#15203c; }
        </style>
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Detalle de Empleo</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.empleos.index') }}" class="transition-all hover:text-primary-500">Empleos</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">{{ Str::limit($empleo->cargo, 40) }}</li>
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
                $logoUrl       = $empleo->empleador?->logo_display_url;
                $nombreEmp     = $empleo->empleador?->empresa?->razon_social ?? $empleo->empleador?->nombre ?? '—';
                $nombreEmpRow  = $empleo->empleador?->nombre;
                $inicialEmp    = mb_strtoupper(mb_substr($nombreEmp, 0, 1));
                $estado     = $empleo->estadoEmpleo;
                $nombreM    = trim(($empleo->hogarMiembro?->user?->persona?->nombres ?? '') . ' ' . ($empleo->hogarMiembro?->user?->persona?->apellido_paterno ?? '')) ?: $empleo->hogarMiembro?->apodo ?? '—';
                $avatarM    = $empleo->hogarMiembro?->user?->persona?->foto_url;
                $inicialM   = mb_strtoupper(mb_substr($nombreM, 0, 1));
                $periodoFin = $empleo->es_actual ? 'Actual' : ($empleo->fecha_fin ? $empleo->fecha_fin->format('M Y') : '—');
                $periodoIni = $empleo->fecha_inicio ? $empleo->fecha_inicio->format('M Y') : '—';
            @endphp

            <!-- ── Cabecera ───────────────────────────────────────────────────── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Panel izquierdo: logo + badge actual + acciones -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md h-full flex flex-col">
                        <div class="text-center py-[24px] px-[16px] rounded-md mb-[20px]
                            {{ $empleo->es_actual ? 'bg-primary-50 dark:bg-[#15203c]' : 'bg-gray-50 dark:bg-[#15203c]' }}">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" class="w-[72px] h-[72px] rounded-md object-cover mx-auto mb-[12px] border border-gray-100 dark:border-[#172036]" alt="{{ $nombreEmp }}">
                            @else
                                <span class="w-[72px] h-[72px] rounded-md bg-primary-100 dark:bg-[#1a2d4d] flex items-center justify-center text-2xl font-bold text-primary-700 dark:text-primary-400 mx-auto mb-[12px]">
                                    {{ $inicialEmp }}
                                </span>
                            @endif

                            @if($empleo->es_actual)
                                <span class="inline-flex items-center gap-[4px] text-[11px] font-bold py-[3px] px-[10px] rounded-full bg-primary-500 text-white uppercase tracking-wide mb-[8px]">
                                    <i class="material-symbols-outlined !text-[12px]">star</i>
                                    Empleo Actual
                                </span>
                            @endif

                            @if($estado)
                                <div>
                                    <span class="inline-flex items-center gap-[4px] text-[12px] font-medium py-[3px] px-[10px] rounded-full"
                                          style="background-color: {{ $estado->color }}22; color: {{ $estado->color }}">
                                        <i class="material-symbols-outlined !text-[13px]">{{ $estado->icono ?? 'flag' }}</i>
                                        {{ $estado->nombre }}
                                    </span>
                                </div>
                            @endif

                            @if($empleo->empleador?->sigla_resuelta)
                                <div class="mt-[6px]">
                                    <span class="inline-flex items-center gap-[4px] text-[11px] font-semibold py-[2px] px-[8px] rounded-full bg-gray-100 dark:bg-[#1a2d4d] text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[12px]">domain</i>
                                        {{ $empleo->empleador->sigla_resuelta }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Miembro -->
                        <div class="flex items-center gap-[10px] mb-[14px] p-[12px] bg-gray-50 dark:bg-[#15203c] rounded-md">
                            @if($avatarM)
                                <img src="{{ $avatarM }}" class="w-[36px] h-[36px] rounded-full object-cover flex-shrink-0" alt="">
                            @else
                                <span class="w-[36px] h-[36px] rounded-full bg-primary-100 flex items-center justify-center text-sm font-bold text-primary-700 flex-shrink-0">{{ $inicialM }}</span>
                            @endif
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400 mb-0">Miembro</p>
                                <p class="text-sm font-medium text-black dark:text-white truncate">{{ $nombreM }}</p>
                            </div>
                        </div>

                        <div class="flex-1"></div>

                        <!-- Acciones -->
                        <div class="flex items-center gap-[10px] mt-[16px]">
                            <a href="{{ route('dashboard.empleos.edit', $empleo) }}"
                                class="flex-1 text-center inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <i class="material-symbols-outlined !text-[16px] align-middle mr-[4px]">edit</i>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('dashboard.empleos.destroy', $empleo) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('¿Eliminar el empleo «{{ addslashes($empleo->cargo) }}»?')"
                                    class="w-full inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-danger-500 text-white hover:bg-danger-400 text-sm">
                                    <i class="material-symbols-outlined !text-[16px] align-middle mr-[4px]">delete</i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panel derecho: detalles -->
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h4 class="!text-lg !mb-[16px]">{{ $empleo->cargo }}</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mb-[16px]">{{ $nombreEmp }}</p>

                        <ul class="grid grid-cols-1 sm:grid-cols-2 mb-[20px]">
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">Período</span>
                                <span class="block text-black dark:text-white font-medium text-sm">{{ $periodoIni }} — {{ $periodoFin }}</span>
                            </li>
                            @if($nombreEmpRow)
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">Empleador</span>
                                <span class="block text-black dark:text-white font-medium text-sm text-right">{{ $nombreEmpRow }}</span>
                            </li>
                            @endif
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">Salario</span>
                                <span class="block text-black dark:text-white font-medium text-sm">
                                    @if($empleo->salario)
                                        {{ $empleo->moneda?->simbolo }} {{ number_format($empleo->salario, 2) }}
                                    @else <span class="text-gray-400">—</span> @endif
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">Modalidad</span>
                                <span class="inline-flex items-center gap-[4px] text-sm text-black dark:text-white">
                                    @if($empleo->modalidadLaboral)
                                        <i class="material-symbols-outlined !text-[16px] text-primary-500">{{ $empleo->modalidadLaboral->icono ?? 'work' }}</i>
                                        {{ $empleo->modalidadLaboral->nombre }}
                                    @else <span class="text-gray-400">—</span> @endif
                                </span>
                            </li>
                            @if($empleo->fecha_vencimiento_contrato)
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">Venc. Contrato</span>
                                <span class="block text-black dark:text-white font-medium text-sm">{{ $empleo->fecha_vencimiento_contrato->format('d/m/Y') }}</span>
                            </li>
                            @endif
                        </ul>

                        @if($empleo->logros)
                        <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[16px] mb-[16px]">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-[6px]">Logros</p>
                            <p class="text-sm text-black dark:text-white leading-[1.6]">{{ $empleo->logros }}</p>
                        </div>
                        @endif

                        @if($empleo->descripcion)
                        <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[16px] mb-[16px]">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-[6px]">Descripción</p>
                            <p class="text-sm text-black dark:text-white leading-[1.6]">{{ $empleo->descripcion }}</p>
                        </div>
                        @endif

                        <a href="{{ route('dashboard.empleos.index') }}"
                           class="inline-flex items-center gap-[6px] text-primary-500 hover:text-primary-400 text-sm font-medium transition-all">
                            <i class="material-symbols-outlined !text-[16px]">arrow_back</i>
                            Volver a Empleos
                        </a>
                    </div>
                </div>
            </div>

            <!-- ── Tabs ──────────────────────────────────────────────────────── -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-tabs" id="trezo-tabs">
                    <ul class="navs mb-[20px] border-b border-gray-100 dark:border-[#172036] flex flex-wrap gap-y-[4px]">
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabDocumentos" class="nav-link active flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">description</i>
                                Documentos
                                <span class="ml-[4px] text-[10px] font-bold bg-gray-100 dark:bg-[#172036] text-gray-500 dark:text-gray-400 px-[6px] py-[1px] rounded-full">{{ $empleo->documentosLaborales->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabBeneficios" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">card_giftcard</i>
                                Beneficios
                                <span class="ml-[4px] text-[10px] font-bold bg-gray-100 dark:bg-[#172036] text-gray-500 dark:text-gray-400 px-[6px] py-[1px] rounded-full">{{ $empleo->empleoBeneficios->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabReferencias" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">contacts</i>
                                Referencias
                                <span class="ml-[4px] text-[10px] font-bold bg-gray-100 dark:bg-[#172036] text-gray-500 dark:text-gray-400 px-[6px] py-[1px] rounded-full">{{ $empleo->empleoReferencias->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabCapacitaciones" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">school</i>
                                Capacitaciones
                                <span class="ml-[4px] text-[10px] font-bold bg-gray-100 dark:bg-[#172036] text-gray-500 dark:text-gray-400 px-[6px] py-[1px] rounded-full">{{ $empleo->capacitaciones->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- ─── TAB 1: Documentos ──────────────────────────────────── --}}
                        <div class="tab-pane active" id="tabDocumentos">
                            <div class="flex items-center justify-between mb-[16px]">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Documentos laborales (contratos, boletas, constancias...)</p>
                                <button type="button" onclick="abrirModal('doc')"
                                    class="inline-flex items-center gap-[6px] h-[36px] px-[14px] rounded-md bg-primary-500 text-white text-xs font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i>
                                    Agregar
                                </button>
                            </div>

                            @if($empleo->documentosLaborales->isEmpty())
                                <div class="text-center py-[40px]">
                                    <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[8px]">description</i>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm">No hay documentos agregados aún.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-gray-100 dark:border-[#172036]">
                                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tipo</th>
                                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Título</th>
                                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Período</th>
                                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Emisión</th>
                                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Vencim.</th>
                                                <th class="py-[10px] px-[12px]"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50 dark:divide-[#172036]">
                                            @foreach($empleo->documentosLaborales as $doc)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                                <td class="py-[10px] px-[12px]">
                                                    <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[2px] px-[8px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400">
                                                        <i class="material-symbols-outlined !text-[12px]">{{ $doc->tipoDocumentoLaboral?->icono ?? 'description' }}</i>
                                                        {{ $doc->tipoDocumentoLaboral?->nombre ?? '—' }}
                                                    </span>
                                                </td>
                                                <td class="py-[10px] px-[12px] font-medium text-black dark:text-white">
                                                    {{ $doc->titulo }}
                                                    @if($doc->numero_documento)
                                                        <span class="block text-xs text-gray-400">N° {{ $doc->numero_documento }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-[10px] px-[12px] text-gray-500 dark:text-gray-400">
                                                    @if($doc->periodo_mes && $doc->periodo_anio)
                                                        {{ str_pad($doc->periodo_mes, 2, '0', STR_PAD_LEFT) }}/{{ $doc->periodo_anio }}
                                                    @else <span class="text-gray-300">—</span> @endif
                                                </td>
                                                <td class="py-[10px] px-[12px] text-gray-500 dark:text-gray-400">{{ $doc->fecha_emision?->format('d/m/Y') ?? '—' }}</td>
                                                <td class="py-[10px] px-[12px] text-gray-500 dark:text-gray-400">{{ $doc->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                                                <td class="py-[10px] px-[12px]">
                                                    <div class="flex items-center gap-[6px] justify-end">
                                                        @if($doc->file_path)
                                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                                                class="h-[28px] w-[28px] inline-flex items-center justify-center rounded-md bg-primary-50 dark:bg-[#15203c] text-primary-600 hover:bg-primary-100 transition-all"
                                                                title="Ver archivo">
                                                                <i class="material-symbols-outlined !text-[13px]">open_in_new</i>
                                                            </a>
                                                        @endif
                                                        <button type="button"
                                                            onclick="abrirModalEditar('doc', {{ json_encode([
                                                                'id'                        => $doc->id,
                                                                'tipo_documento_laboral_id' => $doc->tipo_documento_laboral_id,
                                                                'requiere_vencimiento'      => (bool) $doc->tipoDocumentoLaboral?->requiere_vencimiento,
                                                                'titulo'                    => $doc->titulo,
                                                                'numero_documento'          => $doc->numero_documento,
                                                                'periodo_mes'               => $doc->periodo_mes,
                                                                'periodo_anio'              => $doc->periodo_anio,
                                                                'fecha_emision'             => $doc->fecha_emision?->format('Y-m-d'),
                                                                'fecha_vencimiento'         => $doc->fecha_vencimiento?->format('Y-m-d'),
                                                                'notas'                     => $doc->notas,
                                                                'activo'                    => (bool) $doc->activo,
                                                            ]) }})"
                                                            class="h-[28px] w-[28px] inline-flex items-center justify-center rounded-md bg-gray-50 dark:bg-[#172036] text-gray-500 hover:bg-gray-100 transition-all">
                                                            <i class="material-symbols-outlined !text-[13px]">edit</i>
                                                        </button>
                                                        <form method="POST" action="{{ route('dashboard.documentos-laborales.destroy', $doc) }}" class="inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" onclick="return confirm('¿Eliminar este documento?')"
                                                                class="h-[28px] w-[28px] inline-flex items-center justify-center rounded-md bg-danger-50 dark:bg-[#172036] text-danger-500 hover:bg-danger-100 transition-all">
                                                                <i class="material-symbols-outlined !text-[13px]">delete</i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- ─── TAB 2: Beneficios ──────────────────────────────────── --}}
                        <div class="tab-pane" id="tabBeneficios">
                            <div class="flex items-center justify-between mb-[16px]">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Beneficios recibidos en este empleo</p>
                                <button type="button" onclick="abrirModal('ben')"
                                    class="inline-flex items-center gap-[6px] h-[36px] px-[14px] rounded-md bg-primary-500 text-white text-xs font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i>
                                    Agregar
                                </button>
                            </div>

                            @if($empleo->empleoBeneficios->isEmpty())
                                <div class="text-center py-[40px]">
                                    <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[8px]">card_giftcard</i>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm">No hay beneficios registrados.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[14px]">
                                    @foreach($empleo->empleoBeneficios as $ben)
                                    <div class="border border-gray-100 dark:border-[#172036] rounded-md p-[16px] hover:border-primary-300 transition-all">
                                        <div class="flex items-center gap-[8px] mb-[8px]">
                                            <span class="w-[32px] h-[32px] rounded-md bg-primary-50 dark:bg-[#15203c] flex items-center justify-center text-primary-600 dark:text-primary-400 flex-shrink-0">
                                                <i class="material-symbols-outlined !text-[16px]">{{ $ben->tipoBeneficio?->icono ?? 'card_giftcard' }}</i>
                                            </span>
                                            <span class="font-medium text-black dark:text-white text-sm">{{ $ben->tipoBeneficio?->nombre ?? '—' }}</span>
                                        </div>
                                        @if($ben->entidad)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-[4px]">
                                                <i class="material-symbols-outlined !text-[12px] align-middle">business</i>
                                                {{ $ben->entidad }}
                                            </p>
                                        @endif
                                        @if($ben->detalle)
                                            <p class="text-xs text-gray-600 dark:text-gray-300 mt-[6px]">{{ $ben->detalle }}</p>
                                        @endif
                                        <div class="flex items-center gap-[6px] mt-[12px] pt-[10px] border-t border-gray-50 dark:border-[#172036]">
                                            <button type="button"
                                                onclick="abrirModalEditar('ben', {{ json_encode([
                                                    'id'               => $ben->id,
                                                    'tipo_beneficio_id'=> $ben->tipo_beneficio_id,
                                                    'detalle'          => $ben->detalle,
                                                    'entidad'          => $ben->entidad,
                                                    'activo'           => (bool) $ben->activo,
                                                ]) }})"
                                                class="flex-1 h-[28px] inline-flex items-center justify-center gap-[4px] rounded-md bg-gray-50 dark:bg-[#172036] text-gray-500 text-xs hover:bg-gray-100 transition-all">
                                                <i class="material-symbols-outlined !text-[13px]">edit</i>
                                                Editar
                                            </button>
                                            <form method="POST" action="{{ route('dashboard.empleo-beneficios.destroy', $ben) }}" class="flex-1">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Eliminar este beneficio?')"
                                                    class="w-full h-[28px] inline-flex items-center justify-center gap-[4px] rounded-md bg-danger-50 dark:bg-[#172036] text-danger-500 text-xs hover:bg-danger-100 transition-all">
                                                    <i class="material-symbols-outlined !text-[13px]">delete</i>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- ─── TAB 3: Referencias ─────────────────────────────────── --}}
                        <div class="tab-pane" id="tabReferencias">
                            <div class="flex items-center justify-between mb-[16px]">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Referencias laborales</p>
                                <button type="button" onclick="abrirModal('ref')"
                                    class="inline-flex items-center gap-[6px] h-[36px] px-[14px] rounded-md bg-primary-500 text-white text-xs font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i>
                                    Agregar
                                </button>
                            </div>

                            @if($empleo->empleoReferencias->isEmpty())
                                <div class="text-center py-[40px]">
                                    <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[8px]">contacts</i>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm">No hay referencias laborales registradas.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-[14px]">
                                    @foreach($empleo->empleoReferencias as $ref)
                                    <div class="border border-gray-100 dark:border-[#172036] rounded-md p-[16px] hover:border-primary-300 transition-all">
                                        <div class="flex items-start justify-between gap-[8px]">
                                            <div>
                                                <p class="font-semibold text-black dark:text-white text-sm mb-[2px]">{{ $ref->nombre }}</p>
                                                @if($ref->cargo)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-[6px]">{{ $ref->cargo }}</p>
                                                @endif
                                                @if($ref->relacion)
                                                    <span class="inline-flex items-center gap-[3px] text-[11px] py-[2px] px-[6px] rounded-full bg-gray-100 dark:bg-[#172036] text-gray-600 dark:text-gray-300 mb-[6px]">
                                                        {{ $ref->relacion }}
                                                    </span>
                                                @endif
                                                <div class="flex flex-col gap-[2px] mt-[4px]">
                                                    @if($ref->telefono)
                                                        <a href="tel:{{ $ref->telefono }}" class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-[4px] hover:text-primary-500 transition-all">
                                                            <i class="material-symbols-outlined !text-[13px]">phone</i>{{ $ref->telefono }}
                                                        </a>
                                                    @endif
                                                    @if($ref->email)
                                                        <a href="mailto:{{ $ref->email }}" class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-[4px] hover:text-primary-500 transition-all">
                                                            <i class="material-symbols-outlined !text-[13px]">email</i>{{ $ref->email }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-[4px] flex-shrink-0">
                                                <button type="button"
                                                    onclick="abrirModalEditar('ref', {{ json_encode([
                                                        'id'       => $ref->id,
                                                        'nombre'   => $ref->nombre,
                                                        'cargo'    => $ref->cargo,
                                                        'relacion' => $ref->relacion,
                                                        'telefono' => $ref->telefono,
                                                        'email'    => $ref->email,
                                                        'activo'   => (bool) $ref->activo,
                                                    ]) }})"
                                                    class="h-[28px] w-[28px] inline-flex items-center justify-center rounded-md bg-gray-50 dark:bg-[#172036] text-gray-500 hover:bg-gray-100 transition-all">
                                                    <i class="material-symbols-outlined !text-[13px]">edit</i>
                                                </button>
                                                <form method="POST" action="{{ route('dashboard.empleo-referencias.destroy', $ref) }}" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" onclick="return confirm('¿Eliminar esta referencia?')"
                                                        class="h-[28px] w-[28px] inline-flex items-center justify-center rounded-md bg-danger-50 dark:bg-[#172036] text-danger-500 hover:bg-danger-100 transition-all">
                                                        <i class="material-symbols-outlined !text-[13px]">delete</i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- ─── TAB 4: Capacitaciones ──────────────────────────────── --}}
                        <div class="tab-pane" id="tabCapacitaciones">
                            <div class="flex items-center justify-between mb-[16px]">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Cursos y certificaciones vinculados a este empleo</p>
                                <button type="button" onclick="abrirModal('cap')"
                                    class="inline-flex items-center gap-[6px] h-[36px] px-[14px] rounded-md bg-primary-500 text-white text-xs font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i>
                                    Agregar
                                </button>
                            </div>

                            @if($empleo->capacitaciones->isEmpty())
                                <div class="text-center py-[40px]">
                                    <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[8px]">school</i>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm">No hay capacitaciones registradas.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-gray-100 dark:border-[#172036]">
                                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nombre</th>
                                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tipo</th>
                                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Institución</th>
                                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Período</th>
                                                <th class="py-[10px] px-[12px]"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50 dark:divide-[#172036]">
                                            @foreach($empleo->capacitaciones as $cap)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                                <td class="py-[10px] px-[12px] font-medium text-black dark:text-white">{{ $cap->nombre }}</td>
                                                <td class="py-[10px] px-[12px]">
                                                    @if($cap->tipoCapacitacion)
                                                        <span class="inline-flex items-center gap-[4px] text-xs py-[2px] px-[8px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400">
                                                            <i class="material-symbols-outlined !text-[12px]">{{ $cap->tipoCapacitacion->icono ?? 'school' }}</i>
                                                            {{ $cap->tipoCapacitacion->nombre }}
                                                        </span>
                                                    @else <span class="text-gray-300">—</span> @endif
                                                </td>
                                                <td class="py-[10px] px-[12px] text-gray-500 dark:text-gray-400">{{ $cap->institucionEducativa?->nombre_referencial ?? $cap->institucion_nombre ?? '—' }}</td>
                                                <td class="py-[10px] px-[12px] text-gray-500 dark:text-gray-400">
                                                    {{ $cap->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                                    @if($cap->fecha_fin) — {{ $cap->fecha_fin->format('d/m/Y') }} @endif
                                                </td>
                                                <td class="py-[10px] px-[12px]">
                                                    <div class="flex items-center gap-[6px] justify-end">
                                                        @if($cap->file_path)
                                                            <a href="{{ asset('storage/' . $cap->file_path) }}" target="_blank"
                                                                class="h-[28px] w-[28px] inline-flex items-center justify-center rounded-md bg-primary-50 dark:bg-[#15203c] text-primary-600 hover:bg-primary-100 transition-all">
                                                                <i class="material-symbols-outlined !text-[13px]">open_in_new</i>
                                                            </a>
                                                        @endif
                                                        <button type="button"
                                                            onclick="abrirModalEditar('cap', {{ json_encode([
                                                                'id'                       => $cap->id,
                                                                'tipo_capacitacion_id'     => $cap->tipo_capacitacion_id,
                                                                'institucion_educativa_id' => $cap->institucion_educativa_id,
                                                                'nombre'                   => $cap->nombre,
                                                                'descripcion'              => $cap->descripcion,
                                                                'institucion_nombre'       => $cap->institucion_nombre,
                                                                'fecha_inicio'             => $cap->fecha_inicio?->format('Y-m-d'),
                                                                'fecha_fin'                => $cap->fecha_fin?->format('Y-m-d'),
                                                                'fecha_vencimiento'        => $cap->fecha_vencimiento?->format('Y-m-d'),
                                                                'codigo_certificado'       => $cap->codigo_certificado,
                                                                'url_verificacion'         => $cap->url_verificacion,
                                                                'horas_academicas'         => $cap->horas_academicas,
                                                                'notas'                    => $cap->notas,
                                                                'activo'                   => (bool) $cap->activo,
                                                            ]) }})"
                                                            class="h-[28px] w-[28px] inline-flex items-center justify-center rounded-md bg-gray-50 dark:bg-[#172036] text-gray-500 hover:bg-gray-100 transition-all">
                                                            <i class="material-symbols-outlined !text-[13px]">edit</i>
                                                        </button>
                                                        <form method="POST" action="{{ route('dashboard.capacitaciones.destroy', $cap) }}" class="inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" onclick="return confirm('¿Eliminar esta capacitación?')"
                                                                class="h-[28px] w-[28px] inline-flex items-center justify-center rounded-md bg-danger-50 dark:bg-[#172036] text-danger-500 hover:bg-danger-100 transition-all">
                                                                <i class="material-symbols-outlined !text-[13px]">delete</i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                    </div>{{-- /.tab-content --}}
                </div>{{-- /.trezo-tabs --}}
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        {{-- ─── MODAL Documento Laboral ───────────────────────────────────────── --}}
        <div id="modalDoc" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModal('doc')"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[560px] bg-white dark:bg-[#0c1427] rounded-md shadow-xl overflow-hidden mx-[16px]">
                <div class="bg-gray-50 dark:bg-[#15203c] flex items-center justify-between p-[18px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-base flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">description</i>
                        <span id="modalDocTitulo">Agregar Documento</span>
                    </h5>
                    <button type="button" onclick="cerrarModal('doc')" class="text-gray-400 hover:text-gray-600 transition-all">
                        <i class="material-symbols-outlined !text-[20px]">close</i>
                    </button>
                </div>
                <form id="formDoc" method="POST" enctype="multipart/form-data" class="p-[18px]">
                    @csrf
                    <span id="docMethodSpoof"></span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[14px]">
                        <div class="sm:col-span-2">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Tipo <span class="text-danger-500">*</span></label>
                            <select name="tipo_documento_laboral_id" id="docTipo" class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm">
                                <option value="">Selecciona tipo...</option>
                                @foreach($tiposDocumentoLaboral as $td)
                                    <option value="{{ $td->id }}"
                                        data-icono="{{ $td->icono ?? 'description' }}"
                                        data-requiere="{{ $td->requiere_vencimiento ? '1' : '0' }}">{{ $td->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Título <span class="text-danger-500">*</span></label>
                            <input type="text" name="titulo" id="docTitulo" placeholder="Ej: Contrato de trabajo"
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">N° Documento</label>
                            <input type="text" name="numero_documento" id="docNumero" placeholder="Opcional"
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Período (mes/año)</label>
                            <div class="flex gap-[8px]">
                                <div class="flex-1 min-w-0">
                                    <select name="periodo_mes" id="docPeriodoMes">
                                        <option value="">Mes...</option>
                                        <option value="1">01 — Ene</option>
                                        <option value="2">02 — Feb</option>
                                        <option value="3">03 — Mar</option>
                                        <option value="4">04 — Abr</option>
                                        <option value="5">05 — May</option>
                                        <option value="6">06 — Jun</option>
                                        <option value="7">07 — Jul</option>
                                        <option value="8">08 — Ago</option>
                                        <option value="9">09 — Sep</option>
                                        <option value="10">10 — Oct</option>
                                        <option value="11">11 — Nov</option>
                                        <option value="12">12 — Dic</option>
                                    </select>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <select name="periodo_anio" id="docPeriodoAnio">
                                        <option value="">Año...</option>
                                        @php $anioActual = (int) date('Y'); @endphp
                                        @for($a = $anioActual + 3; $a >= 2000; $a--)
                                            <option value="{{ $a }}">{{ $a }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Fecha Emisión</label>
                            <input type="date" name="fecha_emision" id="docFechaEmision"
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div id="docVencimientoWrap" class="hidden">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Fecha Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" id="docFechaVenc"
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm" id="docArchivoLabel">
                                Archivo <span id="docArchivoReq" class="text-danger-500">*</span>
                                <span id="docArchivoOpc" class="hidden text-xs text-gray-400 font-normal">(deja vacío para conservar el actual)</span>
                            </label>
                            <input type="file" name="archivo" id="docArchivo"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-[10px] file:py-[7px] file:px-[12px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-600 dark:file:bg-[#15203c] dark:file:text-primary-400 hover:file:bg-primary-100 transition-all cursor-pointer">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Notas</label>
                            <textarea name="notas" id="docNotas" rows="2" placeholder="Opcional"
                                class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] py-[10px] block w-full outline-0 text-sm transition-all focus:border-primary-500"></textarea>
                        </div>
                        <div class="sm:col-span-2 flex items-center justify-between py-[2px]">
                            <span class="text-black dark:text-white font-medium text-sm">Activo</span>
                            <div class="flex items-center gap-[8px]">
                                <input type="hidden" name="activo" value="0">
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="activo" value="1" class="sr-only peer" id="docActivo" checked>
                                    <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-[16px] flex justify-end gap-[10px]">
                        <button type="button" onclick="cerrarModal('doc')"
                            class="px-[18px] py-[9px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 text-sm font-medium hover:border-primary-500 hover:text-primary-500 transition-all">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-[18px] py-[9px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[15px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                Guardar
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── MODAL Beneficio ────────────────────────────────────────────────── --}}
        <div id="modalBen" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModal('ben')"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[480px] bg-white dark:bg-[#0c1427] rounded-md shadow-xl overflow-hidden mx-[16px]">
                <div class="bg-gray-50 dark:bg-[#15203c] flex items-center justify-between p-[18px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-base flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">card_giftcard</i>
                        <span id="modalBenTitulo">Agregar Beneficio</span>
                    </h5>
                    <button type="button" onclick="cerrarModal('ben')" class="text-gray-400 hover:text-gray-600">
                        <i class="material-symbols-outlined !text-[20px]">close</i>
                    </button>
                </div>
                <form id="formBen" method="POST" class="p-[18px]">
                    @csrf
                    <span id="benMethodSpoof"></span>
                    <div class="grid grid-cols-1 gap-[14px]">
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Tipo de Beneficio <span class="text-danger-500">*</span></label>
                            <select name="tipo_beneficio_id" id="benTipo" class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm">
                                <option value="">Selecciona tipo...</option>
                                @foreach($tiposBeneficio as $tb)
                                    <option value="{{ $tb->id }}" data-icono="{{ $tb->icono ?? 'card_giftcard' }}">{{ $tb->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Entidad / Proveedor</label>
                            <input type="text" name="entidad" id="benEntidad" placeholder="Ej: EsSalud, AFP Integra..."
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Detalle</label>
                            <textarea name="detalle" id="benDetalle" rows="2" placeholder="Descripción del beneficio..."
                                class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] py-[10px] block w-full outline-0 text-sm transition-all focus:border-primary-500"></textarea>
                        </div>
                        <div class="flex items-center justify-between py-[2px]">
                            <span class="text-black dark:text-white font-medium text-sm">Activo</span>
                            <div class="flex items-center gap-[8px]">
                                <input type="hidden" name="activo" value="0">
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="activo" value="1" class="sr-only peer" id="benActivo" checked>
                                    <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-[16px] flex justify-end gap-[10px]">
                        <button type="button" onclick="cerrarModal('ben')"
                            class="px-[18px] py-[9px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 text-sm font-medium hover:border-primary-500 hover:text-primary-500 transition-all">
                            Cancelar
                        </button>
                        <button type="submit" class="px-[18px] py-[9px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[15px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                Guardar
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── MODAL Referencia ──────────────────────────────────────────────── --}}
        <div id="modalRef" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModal('ref')"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[480px] bg-white dark:bg-[#0c1427] rounded-md shadow-xl overflow-hidden mx-[16px]">
                <div class="bg-gray-50 dark:bg-[#15203c] flex items-center justify-between p-[18px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-base flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">contacts</i>
                        <span id="modalRefTitulo">Agregar Referencia</span>
                    </h5>
                    <button type="button" onclick="cerrarModal('ref')" class="text-gray-400 hover:text-gray-600">
                        <i class="material-symbols-outlined !text-[20px]">close</i>
                    </button>
                </div>
                <form id="formRef" method="POST" class="p-[18px]">
                    @csrf
                    <span id="refMethodSpoof"></span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[14px]">
                        <div class="sm:col-span-2">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Nombre <span class="text-danger-500">*</span></label>
                            <input type="text" name="nombre" id="refNombre" placeholder="Nombre completo"
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Cargo</label>
                            <input type="text" name="cargo" id="refCargo" placeholder="Cargo/Puesto"
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Relación</label>
                            <select name="relacion" id="refRelacion">
                                <option value="">Sin especificar...</option>
                                <option value="Jefe directo" data-icono="manage_accounts">Jefe directo</option>
                                <option value="Colega" data-icono="group">Colega</option>
                                <option value="RRHH" data-icono="badge">RRHH</option>
                                <option value="Otro" data-icono="person">Otro</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Teléfono</label>
                            <input type="text" name="telefono" id="refTelefono" placeholder="+51 999 999 999"
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Email</label>
                            <input type="email" name="email" id="refEmail" placeholder="correo@ejemplo.com"
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div class="sm:col-span-2 flex items-center justify-between py-[2px]">
                            <span class="text-black dark:text-white font-medium text-sm">Activo</span>
                            <div class="flex items-center gap-[8px]">
                                <input type="hidden" name="activo" value="0">
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="activo" value="1" class="sr-only peer" id="refActivo" checked>
                                    <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-[16px] flex justify-end gap-[10px]">
                        <button type="button" onclick="cerrarModal('ref')"
                            class="px-[18px] py-[9px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 text-sm font-medium hover:border-primary-500 hover:text-primary-500 transition-all">
                            Cancelar
                        </button>
                        <button type="submit" class="px-[18px] py-[9px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[15px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                Guardar
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── MODAL Capacitación ─────────────────────────────────────────────── --}}
        <div id="modalCap" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModal('cap')"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[560px] max-h-[90vh] overflow-y-auto bg-white dark:bg-[#0c1427] rounded-md shadow-xl mx-[16px]">
                <div class="sticky top-0 z-10 bg-gray-50 dark:bg-[#15203c] flex items-center justify-between p-[18px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-base flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">school</i>
                        <span id="modalCapTitulo">Agregar Capacitación</span>
                    </h5>
                    <button type="button" onclick="cerrarModal('cap')" class="text-gray-400 hover:text-gray-600">
                        <i class="material-symbols-outlined !text-[20px]">close</i>
                    </button>
                </div>
                <form id="formCap" method="POST" enctype="multipart/form-data" class="p-[18px]">
                    @csrf
                    <span id="capMethodSpoof"></span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[14px]">
                        <div class="sm:col-span-2">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Nombre <span class="text-danger-500">*</span></label>
                            <input type="text" name="nombre" id="capNombre" placeholder="Nombre del curso..."
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Tipo</label>
                            <select name="tipo_capacitacion_id" id="capTipo">
                                <option value="">Sin tipo...</option>
                                @foreach($tiposCapacitacion as $tc)
                                    <option value="{{ $tc->id }}" data-icono="{{ $tc->icono ?? 'school' }}">{{ $tc->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Institución vinculada</label>
                            <select name="institucion_educativa_id" id="capInstitucionEd">
                                <option value="">Sin vincular...</option>
                                @foreach($institucionesEducativas as $ie)
                                    <option value="{{ $ie->id }}" data-logo="{{ $ie->logo_path ? asset('storage/' . $ie->logo_path) : '' }}">{{ $ie->nombre_referencial }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">O nombre de institución libre</label>
                            <input type="text" name="institucion_nombre" id="capInstitucionNombre" placeholder="Ej: SENCICO, Coursera, Universidad..."
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" id="capFechaInicio"
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Fecha Fin</label>
                            <input type="date" name="fecha_fin" id="capFechaFin"
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Vencimiento Cert.</label>
                            <input type="date" name="fecha_vencimiento" id="capFechaVenc"
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>
                        {{-- Detalles del certificado --}}
                        <div class="sm:col-span-2">
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-[10px] mt-[2px]">Detalles del certificado</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[14px]">
                                <div>
                                    <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Código / Folio</label>
                                    <input type="text" name="codigo_certificado" id="capCodigoCert" placeholder="Ej: ABC-123456"
                                        class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Horas académicas</label>
                                    <input type="number" name="horas_academicas" id="capHorasAcad" min="0" placeholder="Ej: 40"
                                        class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">URL de verificación</label>
                                    <input type="url" name="url_verificacion" id="capUrlVerif" placeholder="https://coursera.org/verify/..."
                                        class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm" id="capArchivoLabel">
                                Certificado
                                <span id="capArchivoReq" class="text-xs text-gray-400 font-normal">(PDF / JPG / PNG — máx. 10 MB)</span>
                                <span id="capArchivoOpc" class="hidden text-xs text-gray-400 font-normal">(dejar vacío para conservar el actual)</span>
                            </label>
                            <input type="file" name="archivo" id="capArchivo"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-[10px] file:py-[7px] file:px-[12px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-600 dark:file:bg-[#15203c] dark:file:text-primary-400 hover:file:bg-primary-100 transition-all cursor-pointer">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Descripción</label>
                            <textarea name="descripcion" id="capDescripcion" rows="2" placeholder="Opcional"
                                class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] py-[10px] block w-full outline-0 text-sm transition-all focus:border-primary-500"></textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Notas</label>
                            <textarea name="notas" id="capNotas" rows="2" placeholder="Opcional"
                                class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] py-[10px] block w-full outline-0 text-sm transition-all focus:border-primary-500"></textarea>
                        </div>
                        <div class="sm:col-span-2 flex items-center justify-between p-[12px] bg-gray-50 dark:bg-[#15203c] rounded-md">
                            <span class="text-sm font-medium text-black dark:text-white">Activo</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="activo" value="0">
                                <input type="checkbox" name="activo" value="1" id="capActivo" class="sr-only peer" checked>
                                <div class="w-[40px] h-[22px] bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[18px] after:w-[18px] after:transition-all dark:border-gray-600 peer-checked:bg-primary-500"></div>
                            </label>
                        </div>
                    </div>
                    <div class="mt-[16px] flex justify-end gap-[10px]">
                        <button type="button" onclick="cerrarModal('cap')"
                            class="px-[18px] py-[9px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 text-sm font-medium hover:border-primary-500 hover:text-primary-500 transition-all">
                            Cancelar
                        </button>
                        <button type="submit" class="px-[18px] py-[9px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[15px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                Guardar
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @include('partials.front.scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        const STORE_DOC  = "{{ route('dashboard.empleos.documentos-laborales.store', $empleo) }}";
        const STORE_BEN  = "{{ route('dashboard.empleos.beneficios.store', $empleo) }}";
        const STORE_REF  = "{{ route('dashboard.empleos.referencias.store', $empleo) }}";
        const STORE_CAP  = "{{ route('dashboard.empleos.capacitaciones.store', $empleo) }}";
        const BASE_DOC   = "{{ url('/dashboard/documentos-laborales') }}/";
        const BASE_BEN   = "{{ url('/dashboard/empleo-beneficios') }}/";
        const BASE_REF   = "{{ url('/dashboard/empleo-referencias') }}/";
        const BASE_CAP   = "{{ url('/dashboard/capacitaciones') }}/";

        function abrirModal(tipo) {
            const id = 'modal' + tipo.charAt(0).toUpperCase() + tipo.slice(1);
            resetModal(tipo);
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModal(tipo) {
            const id = 'modal' + tipo.charAt(0).toUpperCase() + tipo.slice(1);
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }

        function resetModal(tipo) {
            if (tipo === 'doc') {
                document.getElementById('modalDocTitulo').textContent = 'Agregar Documento';
                document.getElementById('formDoc').action = STORE_DOC;
                document.getElementById('docMethodSpoof').innerHTML = '';
                ['docTitulo','docNumero','docFechaEmision','docFechaVenc','docNotas'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
                document.getElementById('docArchivo').value = '';
                document.getElementById('docArchivoReq').classList.remove('hidden');
                document.getElementById('docArchivoOpc').classList.add('hidden');
                document.getElementById('docVencimientoWrap').classList.add('hidden');
                document.getElementById('docActivo').checked = true;
                if (typeof $ !== 'undefined') {
                    $('#docTipo').val(null).trigger('change');
                    $('#docPeriodoMes').val(null).trigger('change');
                    $('#docPeriodoAnio').val(null).trigger('change');
                }
            } else if (tipo === 'ben') {
                document.getElementById('modalBenTitulo').textContent = 'Agregar Beneficio';
                document.getElementById('formBen').action = STORE_BEN;
                document.getElementById('benMethodSpoof').innerHTML = '';
                ['benEntidad','benDetalle'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
                if (typeof $ !== 'undefined') $('#benTipo').val(null).trigger('change');
                document.getElementById('benActivo').checked = true;
            } else if (tipo === 'ref') {
                document.getElementById('modalRefTitulo').textContent = 'Agregar Referencia';
                document.getElementById('formRef').action = STORE_REF;
                document.getElementById('refMethodSpoof').innerHTML = '';
                ['refNombre','refCargo','refTelefono','refEmail'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
                if (typeof $ !== 'undefined') $('#refRelacion').val(null).trigger('change');
                document.getElementById('refActivo').checked = true;
            } else if (tipo === 'cap') {
                document.getElementById('modalCapTitulo').textContent = 'Agregar Capacitación';
                document.getElementById('formCap').action = STORE_CAP;
                document.getElementById('capMethodSpoof').innerHTML = '';
                ['capNombre','capInstitucionNombre','capFechaInicio','capFechaFin','capFechaVenc','capCodigoCert','capUrlVerif','capHorasAcad','capDescripcion','capNotas'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
                document.getElementById('capArchivo').value = '';
                document.getElementById('capArchivoReq').classList.remove('hidden');
                document.getElementById('capArchivoOpc').classList.add('hidden');
                document.getElementById('capActivo').checked = true;
                if (typeof $ !== 'undefined') {
                    $('#capTipo').val(null).trigger('change');
                    $('#capInstitucionEd').val(null).trigger('change');
                }
            }
        }

        function abrirModalEditar(tipo, data) {
            abrirModal(tipo);
            const spoof = '<input type="hidden" name="_method" value="PUT">';
            if (tipo === 'doc') {
                document.getElementById('modalDocTitulo').textContent = 'Editar Documento';
                document.getElementById('formDoc').action = BASE_DOC + data.id;
                document.getElementById('docMethodSpoof').innerHTML = spoof;
                if (typeof $ !== 'undefined') $('#docTipo').val(data.tipo_documento_laboral_id || null).trigger('change');
                document.getElementById('docTitulo').value         = data.titulo || '';
                document.getElementById('docNumero').value         = data.numero_documento || '';
                $('#docPeriodoMes').val(data.periodo_mes  || null).trigger('change');
                $('#docPeriodoAnio').val(data.periodo_anio || null).trigger('change');
                document.getElementById('docFechaEmision').value   = data.fecha_emision || '';
                document.getElementById('docFechaVenc').value      = data.fecha_vencimiento || '';
                document.getElementById('docNotas').value          = data.notas || '';
                document.getElementById('docArchivoReq').classList.add('hidden');
                document.getElementById('docArchivoOpc').classList.remove('hidden');
                document.getElementById('docVencimientoWrap').classList.toggle('hidden', !data.requiere_vencimiento);
                document.getElementById('docActivo').checked = !!data.activo;
            } else if (tipo === 'ben') {
                document.getElementById('modalBenTitulo').textContent = 'Editar Beneficio';
                document.getElementById('formBen').action = BASE_BEN + data.id;
                document.getElementById('benMethodSpoof').innerHTML = spoof;
                if (typeof $ !== 'undefined') $('#benTipo').val(data.tipo_beneficio_id || null).trigger('change');
                document.getElementById('benEntidad').value = data.entidad || '';
                document.getElementById('benDetalle').value = data.detalle || '';
                document.getElementById('benActivo').checked = !!data.activo;
            } else if (tipo === 'ref') {
                document.getElementById('modalRefTitulo').textContent = 'Editar Referencia';
                document.getElementById('formRef').action = BASE_REF + data.id;
                document.getElementById('refMethodSpoof').innerHTML = spoof;
                document.getElementById('refNombre').value   = data.nombre || '';
                document.getElementById('refCargo').value    = data.cargo || '';
                document.getElementById('refTelefono').value = data.telefono || '';
                document.getElementById('refEmail').value    = data.email || '';
                if (typeof $ !== 'undefined') $('#refRelacion').val(data.relacion || null).trigger('change');
                document.getElementById('refActivo').checked = !!data.activo;
            } else if (tipo === 'cap') {
                document.getElementById('modalCapTitulo').textContent = 'Editar Capacitación';
                document.getElementById('formCap').action = BASE_CAP + data.id;
                document.getElementById('capMethodSpoof').innerHTML = spoof;
                document.getElementById('capNombre').value             = data.nombre || '';
                document.getElementById('capInstitucionNombre').value  = data.institucion_nombre || '';
                document.getElementById('capFechaInicio').value        = data.fecha_inicio || '';
                document.getElementById('capFechaFin').value           = data.fecha_fin || '';
                document.getElementById('capFechaVenc').value          = data.fecha_vencimiento || '';
                document.getElementById('capCodigoCert').value         = data.codigo_certificado || '';
                document.getElementById('capUrlVerif').value           = data.url_verificacion || '';
                document.getElementById('capHorasAcad').value          = data.horas_academicas ?? '';
                document.getElementById('capDescripcion').value        = data.descripcion || '';
                document.getElementById('capNotas').value              = data.notas || '';
                document.getElementById('capArchivoReq').classList.add('hidden');
                document.getElementById('capArchivoOpc').classList.remove('hidden');
                document.getElementById('capActivo').checked = !!data.activo;
                if (typeof $ !== 'undefined') {
                    $('#capTipo').val(data.tipo_capacitacion_id || null).trigger('change');
                    $('#capInstitucionEd').val(data.institucion_educativa_id || null).trigger('change');
                }
            }
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                ['doc','ben','ref','cap'].forEach(t => {
                    const el = document.getElementById('modal' + t.charAt(0).toUpperCase() + t.slice(1));
                    if (el && !el.classList.contains('hidden')) cerrarModal(t);
                });
            }
        });

        $(function () {
            function docTipoTemplate(opt) {
                if (!opt.id) return opt.text;
                const icono = opt.element?.dataset?.icono || 'description';
                return $(`<span class="flex items-center gap-[8px]">
                    <i class="material-symbols-outlined !text-[16px] text-primary-500">${icono}</i>
                    <span>${opt.text}</span>
                </span>`);
            }

            $('#docTipo').select2({
                placeholder: 'Selecciona tipo...',
                allowClear: true,
                dropdownParent: $('#modalDoc'),
                templateResult: docTipoTemplate,
                templateSelection: docTipoTemplate,
                width: '100%',
            }).on('change', function () {
                const opt = this.options[this.selectedIndex];
                const requiere = opt?.dataset?.requiere === '1';
                document.getElementById('docVencimientoWrap').classList.toggle('hidden', !requiere);
                if (!requiere) document.getElementById('docFechaVenc').value = '';
            });

            function benTipoTemplate(opt) {
                if (!opt.id) return opt.text;
                const icono = opt.element?.dataset?.icono || 'card_giftcard';
                return $(`<span class="flex items-center gap-[8px]">
                    <i class="material-symbols-outlined !text-[16px] text-primary-500">${icono}</i>
                    <span>${opt.text}</span>
                </span>`);
            }

            $('#benTipo').select2({
                placeholder: 'Selecciona tipo...',
                allowClear: true,
                dropdownParent: $('#modalBen'),
                templateResult: benTipoTemplate,
                templateSelection: benTipoTemplate,
                width: '100%',
            });

            function refRelacionTemplate(opt) {
                if (!opt.id) return opt.text;
                const icono = opt.element?.dataset?.icono || 'person';
                return $(`<span class="flex items-center gap-[8px]">
                    <i class="material-symbols-outlined !text-[16px] text-primary-500">${icono}</i>
                    <span>${opt.text}</span>
                </span>`);
            }

            $('#refRelacion').select2({
                placeholder: 'Sin especificar...',
                allowClear: true,
                dropdownParent: $('#modalRef'),
                width: '100%',
                minimumResultsForSearch: Infinity,
                templateResult: refRelacionTemplate,
                templateSelection: refRelacionTemplate,
            });

            $('#docPeriodoMes').select2({
                placeholder: 'Mes...',
                allowClear: true,
                dropdownParent: $('#modalDoc'),
                width: '100%',
                minimumResultsForSearch: Infinity,
            });

            $('#docPeriodoAnio').select2({
                placeholder: 'Año...',
                allowClear: true,
                dropdownParent: $('#modalDoc'),
                width: '100%',
                minimumResultsForSearch: Infinity,
            });

            function capTipoTemplate(opt) {
                if (!opt.id) return opt.text;
                const icono = opt.element?.dataset?.icono || 'school';
                return $(`<span class="flex items-center gap-[8px]">
                    <i class="material-symbols-outlined !text-[16px] text-primary-500">${icono}</i>
                    <span>${opt.text}</span>
                </span>`);
            }

            $('#capTipo').select2({
                placeholder: 'Sin tipo...',
                allowClear: true,
                dropdownParent: $('#modalCap'),
                templateResult: capTipoTemplate,
                templateSelection: capTipoTemplate,
                width: '100%',
            });

            function capInstEdTemplate(opt) {
                if (!opt.id) return opt.text;
                const logo = opt.element?.dataset?.logo;
                const img = logo
                    ? `<img src="${logo}" style="width:20px;height:20px;border-radius:3px;object-fit:contain;" />`
                    : `<i class="material-symbols-outlined" style="font-size:18px;color:#9ca3af;line-height:1;">apartment</i>`;
                return $(`<span style="display:flex;align-items:center;gap:8px;">${img}<span>${opt.text}</span></span>`);
            }

            $('#capInstitucionEd').select2({
                placeholder: 'Sin vincular...',
                allowClear: true,
                dropdownParent: $('#modalCap'),
                templateResult: capInstEdTemplate,
                templateSelection: capInstEdTemplate,
                width: '100%',
            });
        });
        </script>
    </body>
</html>
