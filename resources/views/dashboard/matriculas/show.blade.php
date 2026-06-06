<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Detalle de Matrícula</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">Detalle de Matrícula</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.matriculas.index') }}" class="transition-all hover:text-primary-500">Matrículas</a>
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
                $inst      = $matricula->institucionEducativa;
                $tipoIco   = $inst?->tipoInstitucionEducativa?->icono ?? 'school';
                $bgColors  = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                $bgCard    = $bgColors[abs(crc32($matricula->id)) % count($bgColors)];
                $nombreMiembro = trim(implode(' ', array_filter([
                    $matricula->hogarMiembro?->user?->persona?->nombres,
                    $matricula->hogarMiembro?->user?->persona?->apellido_paterno,
                    $matricula->hogarMiembro?->user?->persona?->apellido_materno,
                ]))) ?: ($matricula->hogarMiembro?->user?->name ?? '—');
                $avatarMiembro = $matricula->hogarMiembro?->user?->persona?->avatar_url ?? null;

                $colorMap = [
                    'green'  => 'bg-success-100 text-success-700 border border-success-300',
                    'red'    => 'bg-danger-100 text-danger-700 border border-danger-300',
                    'blue'   => 'bg-primary-100 text-primary-700 border border-primary-300',
                    'orange' => 'bg-orange-100 text-orange-700 border border-orange-300',
                    'purple' => 'bg-purple-100 text-purple-700 border border-purple-300',
                    'grey'   => 'bg-gray-100 text-gray-700 border border-gray-300',
                ];
                $estadoClass = $colorMap[$matricula->estadoMatricula?->color ?? 'grey'] ?? $colorMap['grey'];

                $pagos         = $matricula->pagosEducativos;
                $totalPagado   = $pagos->where('estado', 'pagado')->sum('monto');
                $totalPendiente= $pagos->where('estado', 'pendiente')->sum('monto');
                $totalVencido  = $pagos->where('estado', 'vencido')->sum('monto');
            @endphp

            {{-- SECCIÓN SUPERIOR: Resumen de matrícula --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] rounded-md overflow-hidden">

                {{-- Banner institución --}}
                <div class="relative h-[140px]">
                    @if($inst?->imagen_path)
                        <img src="{{ Storage::url($inst->imagen_path) }}" class="w-full h-full object-cover" alt="">
                    @else
                        <div class="w-full h-full {{ $bgCard }} flex items-center justify-center">
                            <i class="material-symbols-outlined !text-[60px] text-white opacity-20">{{ $tipoIco }}</i>
                        </div>
                    @endif
                    {{-- Botones acción top-right --}}
                    <div class="absolute top-[12px] right-[12px] flex items-center gap-[6px]">
                        <a href="{{ route('dashboard.matriculas.edit', $matricula) }}"
                           class="inline-flex items-center gap-[5px] px-[12px] py-[6px] rounded-md bg-white/80 hover:bg-white text-sm font-medium text-gray-700 transition-all shadow-sm">
                            <i class="material-symbols-outlined !text-[15px]">edit</i> Editar
                        </a>
                        <form method="POST" action="{{ route('dashboard.matriculas.destroy', $matricula) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('¿Eliminar esta matrícula? Esta acción no se puede deshacer.')"
                                class="inline-flex items-center gap-[5px] px-[12px] py-[6px] rounded-md bg-danger-500/80 hover:bg-danger-500 text-sm font-medium text-white transition-all shadow-sm">
                                <i class="material-symbols-outlined !text-[15px]">delete</i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="p-[25px]">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[20px]">

                        {{-- Institución --}}
                        <div class="flex items-start gap-[12px]">
                            <div class="w-[48px] h-[48px] rounded-full bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($inst?->logo_path)
                                    <img src="{{ Storage::url($inst->logo_path) }}" class="w-full h-full object-contain" alt="">
                                @else
                                    <i class="material-symbols-outlined !text-[22px] text-primary-400">{{ $tipoIco }}</i>
                                @endif
                            </div>
                            <div>
                                <span class="block text-xs text-gray-400 mb-[2px]">Institución</span>
                                <span class="block font-semibold text-black dark:text-white text-sm">{{ $inst?->nombre_referencial ?? '(Sin institución)' }}</span>
                                @if($inst?->tipoInstitucionEducativa)
                                    <span class="text-xs text-gray-500">{{ $inst->tipoInstitucionEducativa->nombre }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Miembro --}}
                        <div class="flex items-start gap-[12px]">
                            @if($avatarMiembro)
                                <img src="{{ $avatarMiembro }}" class="w-[48px] h-[48px] rounded-full object-cover flex-shrink-0" alt="">
                            @else
                                <div class="w-[48px] h-[48px] rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 font-bold text-gray-600 dark:text-gray-300">
                                    {{ mb_strtoupper(mb_substr($nombreMiembro, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <span class="block text-xs text-gray-400 mb-[2px]">Estudiante</span>
                                <span class="block font-semibold text-black dark:text-white text-sm">{{ $nombreMiembro }}</span>
                                @if($matricula->estadoMatricula)
                                    <span class="inline-block text-[11px] font-medium px-[8px] py-[2px] rounded-[100px] {{ $estadoClass }} mt-[4px]">{{ $matricula->estadoMatricula->nombre }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Nivel + Turno --}}
                        <div>
                            <span class="block text-xs text-gray-400 mb-[6px]">Nivel y turno</span>
                            <div class="flex flex-col gap-[4px]">
                                @if($matricula->nivelEducativo)
                                    <div class="flex items-center gap-[6px] text-sm text-black dark:text-white">
                                        <i class="material-symbols-outlined !text-[16px] text-primary-400">{{ $matricula->nivelEducativo->icono ?? 'layers' }}</i>
                                        {{ $matricula->nivelEducativo->nombre }}
                                    </div>
                                @endif
                                @if($matricula->turnoEducativo)
                                    <div class="flex items-center gap-[6px] text-sm text-gray-600 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[16px] text-gray-400">{{ $matricula->turnoEducativo->icono ?? 'schedule' }}</i>
                                        {{ $matricula->turnoEducativo->nombre }}
                                    </div>
                                @endif
                                @if(!$matricula->nivelEducativo && !$matricula->turnoEducativo)
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </div>
                        </div>

                        {{-- Año + Grado --}}
                        <div>
                            <span class="block text-xs text-gray-400 mb-[6px]">Año lectivo / Grado</span>
                            <span class="text-sm font-medium text-black dark:text-white">
                                {{ implode(' — ', array_filter([$matricula->año_lectivo, $matricula->grado_ciclo])) ?: '—' }}
                            </span>
                        </div>

                        {{-- Fechas --}}
                        <div>
                            <span class="block text-xs text-gray-400 mb-[6px]">Período</span>
                            <div class="flex items-center gap-[6px] text-sm text-black dark:text-white">
                                <i class="material-symbols-outlined !text-[15px] text-gray-400">date_range</i>
                                {{ $matricula->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                <span class="text-gray-400">→</span>
                                {{ $matricula->fecha_fin?->format('d/m/Y') ?? '—' }}
                            </div>
                        </div>

                        {{-- Costo --}}
                        <div>
                            <span class="block text-xs text-gray-400 mb-[6px]">Costo mensual</span>
                            @if($matricula->costo_mensual !== null)
                                <span class="text-sm font-semibold text-black dark:text-white">
                                    {{ $matricula->moneda?->simbolo ?? '' }} {{ number_format($matricula->costo_mensual, 2) }}
                                    <span class="text-xs text-gray-400 font-normal"> / mes</span>
                                </span>
                                @if($matricula->dia_vencimiento_pago)
                                    <span class="block text-xs text-gray-400 mt-[2px]">Vence el día {{ $matricula->dia_vencimiento_pago }} de cada mes</span>
                                @endif
                            @else
                                <span class="text-sm text-gray-400">—</span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            {{-- SECCIÓN INFERIOR: 2 columnas --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                {{-- Panel izquierdo: datos adicionales --}}
                <div class="lg:col-span-1 flex flex-col gap-[20px]">

                    {{-- Tutor --}}
                    @if($matricula->nombre_tutor || $matricula->telefono_tutor)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[14px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[6px] text-sm">
                            <i class="material-symbols-outlined !text-[16px] text-primary-500">supervisor_account</i>
                            Tutor
                        </h6>
                        @if($matricula->nombre_tutor)
                            <div class="flex items-center gap-[8px] mb-[8px]">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400">person</i>
                                <span class="text-sm text-black dark:text-white">{{ $matricula->nombre_tutor }}</span>
                            </div>
                        @endif
                        @if($matricula->telefono_tutor)
                            <div class="flex items-center gap-[8px]">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400">phone</i>
                                <span class="text-sm text-black dark:text-white">{{ $matricula->telefono_tutor }}</span>
                            </div>
                        @endif
                    </div>
                    @endif

                    {{-- Notas --}}
                    @if($matricula->notas)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[10px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[6px] text-sm">
                            <i class="material-symbols-outlined !text-[16px] text-primary-500">notes</i>
                            Notas
                        </h6>
                        <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $matricula->notas }}</p>
                    </div>
                    @endif

                    {{-- Empresa de la institución --}}
                    @if($inst?->empresa)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[12px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[6px] text-sm">
                            <i class="material-symbols-outlined !text-[16px] text-primary-500">business</i>
                            Empresa
                        </h6>
                        <div class="flex items-center gap-[10px] mb-[10px]">
                            @if($inst->empresa->logo_url)
                                <img src="{{ Storage::url($inst->empresa->logo_url) }}" class="w-[36px] h-[36px] rounded-md object-contain" alt="">
                            @else
                                <div class="w-[36px] h-[36px] rounded-md bg-gray-100 dark:bg-[#15203c] flex items-center justify-center font-bold text-gray-500">
                                    {{ mb_strtoupper(mb_substr($inst->empresa->razon_social, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('dashboard.empresas.show', $inst->empresa) }}" class="text-sm font-medium text-primary-500 hover:underline">{{ $inst->empresa->razon_social }}</a>
                                @if($inst->empresa->ruc)
                                    <span class="block text-xs text-gray-400 font-mono">RUC: {{ $inst->empresa->ruc }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Volver --}}
                    <a href="{{ route('dashboard.matriculas.index') }}"
                       class="inline-flex items-center gap-[6px] text-sm text-gray-500 dark:text-gray-400 hover:text-primary-500 transition-all">
                        <i class="material-symbols-outlined !text-[16px]">arrow_back</i>
                        Volver al listado
                    </a>
                </div>

                {{-- Panel derecho: Tabs Pagos / Documentos --}}
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">

                        {{-- Tab buttons --}}
                        <div class="trezo-tabs mb-[20px]">
                            <ul class="flex gap-[4px] border-b border-gray-100 dark:border-[#172036] pb-0">
                                <li>
                                    <button type="button" data-tab="tabPagos"
                                        class="nav-link active px-[16px] py-[10px] text-sm font-medium rounded-t-md transition-all">
                                        <span class="inline-flex items-center gap-[5px]">
                                            <i class="material-symbols-outlined !text-[15px]">payments</i>
                                            Pagos <span class="ml-[4px] bg-primary-100 text-primary-700 text-[10px] font-bold px-[5px] py-[1px] rounded-full">{{ $pagos->count() }}</span>
                                        </span>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" data-tab="tabDocs"
                                        class="nav-link px-[16px] py-[10px] text-sm font-medium rounded-t-md transition-all">
                                        <span class="inline-flex items-center gap-[5px]">
                                            <i class="material-symbols-outlined !text-[15px]">folder_open</i>
                                            Documentos <span class="ml-[4px] bg-gray-100 dark:bg-[#15203c] text-gray-600 dark:text-gray-400 text-[10px] font-bold px-[5px] py-[1px] rounded-full">{{ $matricula->documentosEducativos->count() }}</span>
                                        </span>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        {{-- TAB: Pagos --}}
                        <div id="tabPagos" class="tab-pane active">

                            {{-- Mini cards resumen --}}
                            <div class="grid grid-cols-3 gap-[14px] mb-[20px]">
                                <div class="bg-success-50 dark:bg-[#0a1a10] rounded-md p-[14px] text-center">
                                    <span class="block text-[11px] text-success-600 font-medium uppercase tracking-wide mb-[4px]">Pagado</span>
                                    <span class="block text-lg font-bold text-success-700">
                                        {{ $matricula->moneda?->simbolo ?? '' }} {{ number_format($totalPagado, 2) }}
                                    </span>
                                </div>
                                <div class="bg-warning-50 dark:bg-[#1a1500] rounded-md p-[14px] text-center">
                                    <span class="block text-[11px] text-warning-600 font-medium uppercase tracking-wide mb-[4px]">Pendiente</span>
                                    <span class="block text-lg font-bold text-warning-700">
                                        {{ $matricula->moneda?->simbolo ?? '' }} {{ number_format($totalPendiente, 2) }}
                                    </span>
                                </div>
                                <div class="bg-danger-50 dark:bg-[#1a0505] rounded-md p-[14px] text-center">
                                    <span class="block text-[11px] text-danger-600 font-medium uppercase tracking-wide mb-[4px]">Vencido</span>
                                    <span class="block text-lg font-bold text-danger-700">
                                        {{ $matricula->moneda?->simbolo ?? '' }} {{ number_format($totalVencido, 2) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Header + botón --}}
                            <div class="flex items-center justify-between mb-[14px]">
                                <h6 class="font-semibold text-black dark:text-white !mb-0 text-sm">Historial de pagos</h6>
                                <a href="/dashboard/pagos-educativos/create?matricula_id={{ $matricula->id }}"
                                   class="inline-flex items-center gap-[5px] px-[12px] py-[6px] bg-success-500 text-white rounded-md hover:bg-success-400 transition-all text-xs font-medium">
                                    <i class="material-symbols-outlined !text-[14px]">add</i> Registrar pago
                                </a>
                            </div>

                            @if($pagos->isEmpty())
                                <div class="text-center py-[30px]">
                                    <i class="material-symbols-outlined !text-[40px] text-gray-300 dark:text-gray-600 block mb-[8px]">receipt_long</i>
                                    <p class="text-sm text-gray-400">No hay pagos registrados.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto -mx-[20px] md:-mx-[25px]">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-[#15203c]">
                                                <th class="text-left px-[20px] md:px-[25px] py-[10px] font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Concepto</th>
                                                <th class="text-left px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Mes</th>
                                                <th class="text-right px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Monto</th>
                                                <th class="text-left px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Vencimiento</th>
                                                <th class="text-left px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Pagado</th>
                                                <th class="text-center px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-[#172036]">
                                            @foreach($pagos->sortByDesc('fecha_vencimiento') as $pago)
                                                @php
                                                    $estadoPago = match($pago->estado) {
                                                        'pagado'    => 'bg-success-100 text-success-700 border border-success-300',
                                                        'vencido'   => 'bg-danger-100 text-danger-700 border border-danger-300',
                                                        default     => 'bg-warning-100 text-warning-700 border border-warning-300',
                                                    };
                                                @endphp
                                                <tr class="text-black dark:text-white hover:bg-gray-50 dark:hover:bg-[#0a1020]">
                                                    <td class="px-[20px] md:px-[25px] py-[12px] text-gray-700 dark:text-gray-300 max-w-[160px] truncate">{{ $pago->concepto ?? '—' }}</td>
                                                    <td class="px-[12px] py-[12px] text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $pago->mes_correspondiente ?? '—' }}</td>
                                                    <td class="px-[12px] py-[12px] text-right font-medium whitespace-nowrap">{{ $pago->moneda?->simbolo ?? '' }} {{ number_format($pago->monto, 2) }}</td>
                                                    <td class="px-[12px] py-[12px] text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $pago->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                                                    <td class="px-[12px] py-[12px] text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}</td>
                                                    <td class="px-[12px] py-[12px] text-center">
                                                        <span class="inline-block text-[11px] font-medium px-[8px] py-[2px] rounded-[100px] {{ $estadoPago }}">
                                                            {{ ucfirst($pago->estado) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- TAB: Documentos --}}
                        <div id="tabDocs" class="tab-pane">

                            <div class="flex items-center justify-between mb-[14px]">
                                <h6 class="font-semibold text-black dark:text-white !mb-0 text-sm">Documentos adjuntos</h6>
                                <a href="/dashboard/documentos-educativos/create?matricula_id={{ $matricula->id }}&hogar_miembro_id={{ $matricula->hogar_miembro_id }}"
                                   class="inline-flex items-center gap-[5px] px-[12px] py-[6px] bg-primary-500 text-white rounded-md hover:bg-primary-400 transition-all text-xs font-medium">
                                    <i class="material-symbols-outlined !text-[14px]">upload_file</i> Subir documento
                                </a>
                            </div>

                            @if($matricula->documentosEducativos->isEmpty())
                                <div class="text-center py-[30px]">
                                    <i class="material-symbols-outlined !text-[40px] text-gray-300 dark:text-gray-600 block mb-[8px]">folder_open</i>
                                    <p class="text-sm text-gray-400">No hay documentos adjuntos.</p>
                                </div>
                            @else
                                <div class="flex flex-col gap-[10px]">
                                    @foreach($matricula->documentosEducativos->sortByDesc('fecha_documento') as $doc)
                                        <div class="flex items-center gap-[12px] p-[12px] bg-gray-50 dark:bg-[#15203c] rounded-md">
                                            <div class="w-[40px] h-[40px] rounded-md bg-primary-100 dark:bg-[#1a2744] flex items-center justify-center flex-shrink-0">
                                                <i class="material-symbols-outlined !text-[20px] text-primary-500">{{ $doc->tipoDocumentoEducativo?->icono ?? 'description' }}</i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-black dark:text-white truncate">{{ $doc->titulo }}</p>
                                                <p class="text-xs text-gray-400">
                                                    @if($doc->tipoDocumentoEducativo) {{ $doc->tipoDocumentoEducativo->nombre }} · @endif
                                                    {{ $doc->fecha_documento?->format('d/m/Y') ?? '—' }}
                                                </p>
                                            </div>
                                            @if($doc->archivo_path)
                                                <a href="{{ Storage::url($doc->archivo_path) }}" target="_blank"
                                                   class="flex-shrink-0 w-[32px] h-[32px] rounded-md bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] flex items-center justify-center hover:bg-primary-50 transition-all"
                                                   title="Ver/Descargar">
                                                    <i class="material-symbols-outlined !text-[16px] text-primary-500">download</i>
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('[data-tab]');
            tabs.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    tabs.forEach(function (b) {
                        b.classList.remove('active');
                        document.getElementById(b.dataset.tab)?.classList.remove('active');
                    });
                    btn.classList.add('active');
                    document.getElementById(btn.dataset.tab)?.classList.add('active');
                });
            });
        });
        </script>
    </body>
</html>
