<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Documentos de Viajero</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        @php
        function badgeVencDoc(string $estado): array {
            return match($estado) {
                'vencido'         => ['bg' => 'bg-danger-100 dark:bg-[#2a1a1a]',   'text' => 'text-danger-600',  'border' => 'border-danger-300',  'icon' => 'cancel',        'label' => 'Vencido'],
                'por_vencer'      => ['bg' => 'bg-warning-100 dark:bg-[#2a2200]',  'text' => 'text-warning-600', 'border' => 'border-warning-300', 'icon' => 'schedule',      'label' => 'Por vencer'],
                'vigente'         => ['bg' => 'bg-success-100 dark:bg-[#0a2a15]',  'text' => 'text-success-600', 'border' => 'border-success-300', 'icon' => 'check_circle',  'label' => 'Vigente'],
                default           => ['bg' => 'bg-gray-100 dark:bg-[#15203c]',     'text' => 'text-gray-500',    'border' => 'border-gray-200',    'icon' => 'info',          'label' => 'Sin vencimiento'],
            };
        }
        @endphp

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            {{-- Breadcrumb --}}
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 flex items-center gap-[8px]">
                    <i class="material-symbols-outlined !text-[24px] text-primary-500">badge</i>
                    Documentos de Viajero
                </h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.viajes.index') }}" class="transition-all hover:text-primary-500">Viajes</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Documentos de Viajero</li>
                </ol>
            </div>

            {{-- ── Alertas de resumen ───────────────────────────────────────────── --}}
            @if($totalVencidos > 0 || $totalPorVencer > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px] mb-[25px]">
                @if($totalVencidos > 0)
                <div class="flex items-center gap-[14px] p-[16px] rounded-md bg-danger-50 dark:bg-[#2a1a1a] border border-danger-200 dark:border-danger-800">
                    <span class="w-[42px] h-[42px] rounded-full bg-danger-100 flex items-center justify-center flex-shrink-0">
                        <i class="material-symbols-outlined !text-[22px] text-danger-600">cancel</i>
                    </span>
                    <div>
                        <span class="block text-xl font-bold text-danger-600">{{ $totalVencidos }}</span>
                        <span class="text-sm text-danger-700">Documento{{ $totalVencidos > 1 ? 's' : '' }} vencido{{ $totalVencidos > 1 ? 's' : '' }}</span>
                    </div>
                </div>
                @endif
                @if($totalPorVencer > 0)
                <div class="flex items-center gap-[14px] p-[16px] rounded-md bg-warning-50 dark:bg-[#2a2200] border border-warning-200 dark:border-warning-800">
                    <span class="w-[42px] h-[42px] rounded-full bg-warning-100 flex items-center justify-center flex-shrink-0">
                        <i class="material-symbols-outlined !text-[22px] text-warning-600">schedule</i>
                    </span>
                    <div>
                        <span class="block text-xl font-bold text-warning-600">{{ $totalPorVencer }}</span>
                        <span class="text-sm text-warning-700">Por vencer (próx. 90 días)</span>
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- ── Indicación cuando todo está OK ──────────────────────────────── --}}
            @if($totalVencidos === 0 && $totalPorVencer === 0 && $porMiembro->isNotEmpty())
            <div class="flex items-center gap-[12px] p-[14px] mb-[25px] rounded-md bg-success-50 dark:bg-[#0a2a15] border border-success-200 dark:border-success-800">
                <i class="material-symbols-outlined !text-[22px] text-success-600">verified</i>
                <span class="text-sm font-medium text-success-700 dark:text-success-400">Todos los documentos de viajero están vigentes.</span>
            </div>
            @endif

            {{-- ── Botón acceso a Legal ─────────────────────────────────────────── --}}
            <div class="flex items-center justify-between mb-[20px]">
                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-[6px]">
                    <i class="material-symbols-outlined !text-[16px]">info</i>
                    Los documentos se gestionan en el módulo Legal. Haz clic en cada uno para editarlo allá.
                </p>
                <a href="{{ route('dashboard.documentos-legales.index', ['categoria' => 'personal']) }}"
                   class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500 transition-all">
                    <i class="material-symbols-outlined !text-[16px]">open_in_new</i>
                    Ir a Legal
                </a>
            </div>

            {{-- ── Sin documentos ───────────────────────────────────────────────── --}}
            @if($porMiembro->isEmpty())
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[60px] rounded-md text-center">
                <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[16px]">badge</i>
                <p class="text-gray-500 dark:text-gray-400 mb-[8px]">No hay documentos de viajero registrados.</p>
                <p class="text-sm text-gray-400 mb-[20px]">
                    Regístralos en Legal (categoría Personal) con tipos marcados como "Relevante para viajes"<br>
                    (pasaporte, visa, permiso de menor, etc.).
                </p>
                <a href="{{ route('dashboard.documentos-legales.create') }}"
                   class="inline-flex items-center gap-[6px] px-[18px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                    <i class="material-symbols-outlined !text-[16px]">add</i>
                    Nuevo documento en Legal
                </a>
            </div>

            @else
            {{-- ── Tarjetas por miembro ─────────────────────────────────────────── --}}
            <div class="space-y-[20px]">
                @foreach($porMiembro as $miembroId => $docs)
                @php
                    $primerDoc = $docs->first();
                    $miembro   = $primerDoc->hogarMiembro;
                    $persona   = $miembro?->user?->persona;
                    $nombre    = trim(implode(' ', array_filter([
                        $persona?->nombres,
                        $persona?->apellido_paterno,
                        $persona?->apellido_materno,
                    ]))) ?: ($miembro?->user?->name ?? 'Sin nombre');
                    $fotoUrl   = $persona?->foto_url;
                    $inicial   = mb_strtoupper(mb_substr($nombre, 0, 1));

                    $hayAlerta = $docs->whereIn('_estado_venc', ['vencido', 'por_vencer'])->count() > 0;
                @endphp

                <div class="trezo-card bg-white dark:bg-[#0c1427] rounded-md overflow-hidden {{ $hayAlerta ? 'border-l-4 border-l-warning-400' : '' }}">

                    {{-- Cabecera del miembro --}}
                    <div class="flex items-center gap-[14px] px-[20px] py-[16px] border-b border-gray-100 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c]">
                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" class="w-[42px] h-[42px] rounded-full object-cover flex-shrink-0" alt="{{ $nombre }}">
                        @else
                            <span class="w-[42px] h-[42px] rounded-full bg-primary-100 dark:bg-[#1a2d4d] flex items-center justify-center text-base font-bold text-primary-700 dark:text-primary-400 flex-shrink-0">
                                {{ $inicial }}
                            </span>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-black dark:text-white text-sm truncate">{{ $nombre }}</p>
                            <p class="text-xs text-gray-400">{{ $docs->count() }} documento{{ $docs->count() > 1 ? 's' : '' }}</p>
                        </div>
                        @php
                            $nVenc   = $docs->where('_estado_venc', 'vencido')->count();
                            $nPorV   = $docs->where('_estado_venc', 'por_vencer')->count();
                        @endphp
                        @if($nVenc > 0)
                        <span class="inline-flex items-center gap-[4px] text-[11px] font-semibold px-[8px] py-[2px] rounded-full bg-danger-100 text-danger-600 border border-danger-200">
                            <i class="material-symbols-outlined !text-[12px]">cancel</i>
                            {{ $nVenc }} vencido{{ $nVenc > 1 ? 's' : '' }}
                        </span>
                        @endif
                        @if($nPorV > 0)
                        <span class="inline-flex items-center gap-[4px] text-[11px] font-semibold px-[8px] py-[2px] rounded-full bg-warning-100 text-warning-600 border border-warning-200">
                            <i class="material-symbols-outlined !text-[12px]">schedule</i>
                            {{ $nPorV }} por vencer
                        </span>
                        @endif
                    </div>

                    {{-- Lista de documentos del miembro --}}
                    <div class="divide-y divide-gray-50 dark:divide-[#172036]">
                        @foreach($docs as $doc)
                        @php $badge = badgeVencDoc($doc->_estado_venc); @endphp
                        <div class="flex items-center gap-[14px] px-[20px] py-[14px] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">

                            {{-- Ícono del tipo --}}
                            <span class="w-[36px] h-[36px] rounded-md bg-primary-50 dark:bg-[#1a2d4d] flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined !text-[18px] text-primary-500">
                                    {{ $doc->tipoDocumentoLegal?->icono ?? 'badge' }}
                                </i>
                            </span>

                            {{-- Info principal --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-black dark:text-white truncate">
                                    {{ $doc->tipoDocumentoLegal?->nombre ?? 'Documento' }}
                                    @if($doc->titulo)
                                        <span class="font-normal text-gray-500"> — {{ $doc->titulo }}</span>
                                    @endif
                                </p>
                                @if($doc->numero_documento)
                                <p class="text-xs text-gray-400 font-mono">{{ $doc->numero_documento }}</p>
                                @endif
                            </div>

                            {{-- Vencimiento --}}
                            <div class="text-right flex-shrink-0">
                                @if($doc->fecha_vencimiento)
                                <p class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    Vence: <span class="font-medium text-black dark:text-white">{{ $doc->fecha_vencimiento->format('d/m/Y') }}</span>
                                </p>
                                @if($doc->_estado_venc === 'por_vencer')
                                <p class="text-[10px] text-warning-500 mt-[2px]">
                                    en {{ now()->diffInDays($doc->fecha_vencimiento) }} días
                                </p>
                                @elseif($doc->_estado_venc === 'vencido')
                                <p class="text-[10px] text-danger-500 mt-[2px]">
                                    hace {{ $doc->fecha_vencimiento->diffInDays(now()) }} días
                                </p>
                                @endif
                                @else
                                <p class="text-xs text-gray-400">Sin vencimiento</p>
                                @endif
                            </div>

                            {{-- Badge de estado --}}
                            <span class="inline-flex items-center gap-[4px] text-[10px] font-semibold px-[8px] py-[3px] rounded-full border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }} whitespace-nowrap flex-shrink-0">
                                <i class="material-symbols-outlined !text-[11px]">{{ $badge['icon'] }}</i>
                                {{ $badge['label'] }}
                            </span>

                            {{-- Enlace a Legal --}}
                            <a href="{{ route('dashboard.documentos-legales.edit', $doc) }}"
                               title="Editar en Legal"
                               class="w-[30px] h-[30px] flex items-center justify-center rounded text-gray-400 hover:text-primary-500 hover:bg-primary-50 transition-all flex-shrink-0">
                                <i class="material-symbols-outlined !text-[16px]">open_in_new</i>
                            </a>

                        </div>
                        @endforeach
                    </div>

                </div>
                @endforeach
            </div>
            @endif

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
