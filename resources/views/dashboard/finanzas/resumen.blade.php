<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Resumen Financiero</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        @php
            // Computa patrimonio neto por moneda (activos - pasivos)
            $monedas = $activosPorMoneda->pluck('codigo')
                ->merge($pasivosPorMoneda->pluck('codigo'))
                ->unique()->values();

            $patrimonioPorMoneda = $monedas->map(function ($codigo) use ($activosPorMoneda, $pasivosPorMoneda) {
                $activo  = (float) ($activosPorMoneda->firstWhere('codigo', $codigo)?->total ?? 0);
                $pasivo  = (float) ($pasivosPorMoneda->firstWhere('codigo', $codigo)?->total ?? 0);
                $simbolo = $activosPorMoneda->firstWhere('codigo', $codigo)?->simbolo
                         ?? $pasivosPorMoneda->firstWhere('codigo', $codigo)?->simbolo
                         ?? $codigo;
                return (object) [
                    'codigo'  => $codigo,
                    'simbolo' => $simbolo,
                    'total'   => $activo - $pasivo,
                ];
            })->sortByDesc('total')->values();

            function colorEstadoClases(?string $color): array {
                return match($color) {
                    'green'  => ['bg' => 'bg-success-100', 'text' => 'text-success-600'],
                    'red'    => ['bg' => 'bg-danger-100',  'text' => 'text-danger-600'],
                    'orange' => ['bg' => 'bg-orange-100',  'text' => 'text-orange-600'],
                    'amber'  => ['bg' => 'bg-warning-100', 'text' => 'text-warning-600'],
                    'gray'   => ['bg' => 'bg-gray-100',    'text' => 'text-gray-500'],
                    default  => ['bg' => 'bg-gray-100',    'text' => 'text-gray-500'],
                };
            }
        @endphp

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Encabezado + breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Resumen Financiero</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Finanzas</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Resumen</li>
                </ol>
            </div>

            <!-- ════════════════════════════════════════════════════════════
                 FILA DE KPIs
            ═══════════════════════════════════════════════════════════════ -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-[25px] mb-[25px]">

                <!-- KPI 1: Total en cuentas (activos) -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="flex items-start gap-[15px]">
                        <div class="w-[52px] h-[52px] rounded-full flex-shrink-0 flex items-center justify-center bg-primary-50 dark:bg-[#15203c]">
                            <i class="material-symbols-outlined !text-[26px] text-primary-500">account_balance_wallet</i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-[6px] uppercase tracking-wide font-medium">Total en cuentas</p>
                            @forelse($activosPorMoneda as $item)
                                <p class="{{ $loop->first ? 'text-[22px] font-bold text-black dark:text-white leading-tight' : 'text-sm font-semibold text-gray-500 dark:text-gray-400' }} mb-[2px]">
                                    {{ $item->simbolo }} {{ number_format((float) $item->total, 2) }}
                                    @if(!$loop->first)
                                        <span class="text-xs font-normal ml-[3px]">{{ $item->codigo }}</span>
                                    @endif
                                </p>
                            @empty
                                <p class="text-[22px] font-bold text-gray-300 dark:text-gray-600">—</p>
                            @endforelse
                            <p class="text-xs text-gray-400 mt-[5px]">Cuentas, CTS, AFP, plazos y fondos</p>
                        </div>
                    </div>
                </div>

                <!-- KPI 2: Deuda en productos (pasivos) -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="flex items-start gap-[15px]">
                        <div class="w-[52px] h-[52px] rounded-full flex-shrink-0 flex items-center justify-center bg-danger-50 dark:bg-[#15203c]">
                            <i class="material-symbols-outlined !text-[26px] text-danger-500">credit_card</i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-[6px] uppercase tracking-wide font-medium">Deuda en productos</p>
                            @forelse($pasivosPorMoneda as $item)
                                <p class="{{ $loop->first ? 'text-[22px] font-bold text-danger-600 leading-tight' : 'text-sm font-semibold text-gray-500 dark:text-gray-400' }} mb-[2px]">
                                    {{ $item->simbolo }} {{ number_format((float) $item->total, 2) }}
                                    @if(!$loop->first)
                                        <span class="text-xs font-normal ml-[3px]">{{ $item->codigo }}</span>
                                    @endif
                                </p>
                            @empty
                                <p class="text-[22px] font-bold text-gray-300 dark:text-gray-600">—</p>
                            @endforelse
                            <p class="text-xs text-gray-400 mt-[5px]">Tarjetas de crédito y préstamos</p>
                        </div>
                    </div>
                </div>

                <!-- KPI 3: Patrimonio neto (activos − pasivos) -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="flex items-start gap-[15px]">
                        <div class="w-[52px] h-[52px] rounded-full flex-shrink-0 flex items-center justify-center bg-success-50 dark:bg-[#15203c]">
                            <i class="material-symbols-outlined !text-[26px] text-success-500">trending_up</i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-[6px] uppercase tracking-wide font-medium">Patrimonio neto</p>
                            @forelse($patrimonioPorMoneda as $item)
                                @php $positivo = $item->total >= 0; @endphp
                                <p class="{{ $loop->first ? 'text-[22px] font-bold leading-tight' : 'text-sm font-semibold' }} mb-[2px] {{ $positivo ? 'text-success-600' : 'text-danger-600' }}">
                                    {{ $positivo ? '' : '−' }}{{ $item->simbolo }} {{ number_format(abs((float) $item->total), 2) }}
                                    @if(!$loop->first)
                                        <span class="text-xs font-normal ml-[3px]">{{ $item->codigo }}</span>
                                    @endif
                                </p>
                            @empty
                                <p class="text-[22px] font-bold text-gray-300 dark:text-gray-600">—</p>
                            @endforelse
                            <p class="text-xs text-gray-400 mt-[5px]">Activos menos pasivos financieros</p>
                        </div>
                    </div>
                </div>

                <!-- KPI 4: Te deben (préstamos personales) -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="flex items-start gap-[15px]">
                        <div class="w-[52px] h-[52px] rounded-full flex-shrink-0 flex items-center justify-center bg-warning-50 dark:bg-[#15203c]">
                            <i class="material-symbols-outlined !text-[26px] text-warning-500">person_pin</i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-[6px] uppercase tracking-wide font-medium">Te deben</p>
                            @forelse($prestamosPorMoneda as $item)
                                <p class="{{ $loop->first ? 'text-[22px] font-bold text-warning-600 leading-tight' : 'text-sm font-semibold text-gray-500 dark:text-gray-400' }} mb-[2px]">
                                    {{ $item->simbolo }} {{ number_format((float) $item->total, 2) }}
                                    @if(!$loop->first)
                                        <span class="text-xs font-normal ml-[3px]">{{ $item->codigo }}</span>
                                    @endif
                                </p>
                            @empty
                                <p class="text-[22px] font-bold text-gray-300 dark:text-gray-600">S/ 0.00</p>
                            @endforelse
                            <p class="text-xs text-gray-400 mt-[5px]">Saldo pendiente de prestatarios</p>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /FILA DE KPIs -->

            <!-- ════════════════════════════════════════════════════════════
                 TABLA: Productos por saldo
            ═══════════════════════════════════════════════════════════════ -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] rounded-md overflow-hidden">
                <div class="trezo-card-header flex items-center justify-between p-[20px] md:p-[25px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 !text-base">Productos por saldo</h5>
                    @if($totalProductos > 10)
                        <a href="{{ route('dashboard.productos-financieros.index') }}"
                            class="text-xs text-primary-500 hover:underline inline-flex items-center gap-[4px]">
                            Ver todos ({{ $totalProductos }})
                            <i class="material-symbols-outlined !text-[15px]">arrow_forward</i>
                        </a>
                    @endif
                </div>
                <div class="trezo-card-content">
                    @if($topProductos->isEmpty())
                        <div class="text-center py-[50px] text-gray-400">
                            <i class="material-symbols-outlined !text-[56px] mb-[10px] block text-gray-300 dark:text-gray-600">savings</i>
                            No hay productos financieros registrados aún.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-[#172036]">
                                        <th class="text-left font-semibold text-xs text-gray-400 uppercase tracking-wide px-[20px] md:px-[25px] py-[13px] whitespace-nowrap">Entidad</th>
                                        <th class="text-left font-semibold text-xs text-gray-400 uppercase tracking-wide px-[12px] py-[13px] whitespace-nowrap">Alias</th>
                                        <th class="text-left font-semibold text-xs text-gray-400 uppercase tracking-wide px-[12px] py-[13px] whitespace-nowrap">Tipo</th>
                                        <th class="text-left font-semibold text-xs text-gray-400 uppercase tracking-wide px-[12px] py-[13px] whitespace-nowrap">Estado</th>
                                        <th class="text-right font-semibold text-xs text-gray-400 uppercase tracking-wide px-[20px] md:px-[25px] py-[13px] whitespace-nowrap">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProductos as $producto)
                                    @php
                                        $entidad   = $producto->entidadFinanciera;
                                        $nombreEnt = $entidad?->nombre_comercial_resuelto ?? '—';
                                        $colEs     = colorEstadoClases($producto->estadoProducto?->color);
                                    @endphp
                                    <tr class="border-b border-gray-50 dark:border-[#172036] hover:bg-gray-50/50 dark:hover:bg-[#15203c]/50 transition-colors">
                                        <!-- Entidad -->
                                        <td class="px-[20px] md:px-[25px] py-[14px]">
                                            <div class="flex items-center gap-[10px]">
                                                @if($entidad?->logo_resuelto)
                                                    <img src="{{ $entidad->logo_resuelto }}"
                                                         class="w-[32px] h-[32px] object-contain rounded-md flex-shrink-0"
                                                         alt="{{ $nombreEnt }}">
                                                @else
                                                    <div class="w-[32px] h-[32px] rounded-md bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                                        <i class="material-symbols-outlined !text-[16px] text-primary-400">account_balance</i>
                                                    </div>
                                                @endif
                                                <span class="text-sm font-medium text-black dark:text-white truncate max-w-[140px]" title="{{ $nombreEnt }}">
                                                    {{ $nombreEnt }}
                                                </span>
                                            </div>
                                        </td>
                                        <!-- Alias -->
                                        <td class="px-[12px] py-[14px]">
                                            <a href="{{ route('dashboard.productos-financieros.show', $producto) }}"
                                               class="text-sm text-primary-500 hover:underline font-medium">
                                                {{ $producto->alias }}
                                            </a>
                                        </td>
                                        <!-- Tipo -->
                                        <td class="px-[12px] py-[14px]">
                                            <span class="inline-flex items-center gap-[5px] text-sm text-gray-600 dark:text-gray-400">
                                                <i class="material-symbols-outlined !text-[16px] text-gray-400">{{ $producto->tipoProductoFinanciero?->icono ?? 'category' }}</i>
                                                {{ $producto->tipoProductoFinanciero?->nombre ?? '—' }}
                                            </span>
                                        </td>
                                        <!-- Estado -->
                                        <td class="px-[12px] py-[14px]">
                                            <span class="inline-flex items-center gap-[4px] px-[9px] py-[3px] rounded-full text-xs font-medium {{ $colEs['bg'] }} {{ $colEs['text'] }}">
                                                <i class="material-symbols-outlined !text-[12px]">{{ $producto->estadoProducto?->icono ?? 'circle' }}</i>
                                                {{ $producto->estadoProducto?->nombre ?? '—' }}
                                            </span>
                                        </td>
                                        <!-- Saldo -->
                                        <td class="px-[20px] md:px-[25px] py-[14px] text-right">
                                            <span class="text-sm font-bold text-black dark:text-white whitespace-nowrap">
                                                {{ $producto->moneda?->simbolo }} {{ number_format((float) $producto->saldo_actual, 2) }}
                                            </span>
                                            @if($producto->moneda && !$producto->moneda->moneda_local)
                                                <span class="block text-xs text-gray-400">{{ $producto->moneda->codigo }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            <!-- /TABLA productos por saldo -->

            <!-- ════════════════════════════════════════════════════════════
                 PANEL: Próximos vencimientos
            ═══════════════════════════════════════════════════════════════ -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] rounded-md overflow-hidden">
                <div class="trezo-card-header flex items-center justify-between p-[20px] md:p-[25px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 !text-base">Próximos vencimientos</h5>
                    <span class="text-xs text-gray-400">Ordenados por fecha</span>
                </div>
                <div class="trezo-card-content p-[20px] md:p-[25px]">
                    @if($vencimientos->isEmpty())
                        <div class="text-center py-[40px] text-gray-400">
                            <i class="material-symbols-outlined !text-[52px] mb-[10px] block text-gray-300 dark:text-gray-600">event_available</i>
                            No hay vencimientos próximos registrados.
                        </div>
                    @else
                        <ul class="space-y-[12px]">
                            @foreach($vencimientos as $item)
                            @php
                                if ($item->dias <= 7) {
                                    $urgenciaClase = 'bg-danger-50 border-danger-200 dark:border-danger-900';
                                    $diasClase     = 'bg-danger-100 text-danger-700';
                                } elseif ($item->dias <= 30) {
                                    $urgenciaClase = 'bg-warning-50 border-warning-200 dark:border-warning-900';
                                    $diasClase     = 'bg-warning-100 text-warning-700';
                                } else {
                                    $urgenciaClase = 'bg-gray-50 dark:bg-[#15203c] border-gray-100 dark:border-[#172036]';
                                    $diasClase     = 'bg-gray-100 text-gray-600';
                                }
                            @endphp
                            <li class="flex items-center gap-[14px] p-[14px] rounded-md border {{ $urgenciaClase }}">
                                <!-- Icono -->
                                <div class="w-[40px] h-[40px] rounded-full flex-shrink-0 flex items-center justify-center bg-white dark:bg-[#0c1427] shadow-sm">
                                    <i class="material-symbols-outlined !text-[20px] text-gray-500">{{ $item->icono }}</i>
                                </div>
                                <!-- Descripción + tipo -->
                                <div class="flex-1 min-w-0">
                                    @if($item->enlace)
                                        <a href="{{ $item->enlace }}"
                                           class="block text-sm font-semibold text-black dark:text-white truncate hover:text-primary-500 transition-colors">
                                            {{ $item->descripcion }}
                                        </a>
                                    @else
                                        <span class="block text-sm font-semibold text-black dark:text-white truncate">
                                            {{ $item->descripcion }}
                                        </span>
                                    @endif
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item->tipo }}</span>
                                </div>
                                <!-- Fecha + días -->
                                <div class="text-right flex-shrink-0">
                                    <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">
                                        {{ $item->fecha->format('d/m/Y') }}
                                    </span>
                                    <span class="inline-block text-xs font-semibold px-[8px] py-[2px] rounded-full {{ $diasClase }}">
                                        {{ $item->dias === 0 ? 'Hoy' : 'en ' . $item->dias . ' d' }}
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <!-- /PANEL vencimientos -->

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
