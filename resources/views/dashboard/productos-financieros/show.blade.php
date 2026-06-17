<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $productoFinanciero->alias }} — Producto Financiero</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        @php
            if (!function_exists('colorEstadoProductoClases')) {
                function colorEstadoProductoClases(?string $color): array {
                    return match($color) {
                        'green'  => ['bg' => 'bg-success-100', 'text' => 'text-success-600'],
                        'red'    => ['bg' => 'bg-danger-100',  'text' => 'text-danger-600'],
                        'orange' => ['bg' => 'bg-orange-100',  'text' => 'text-orange-600'],
                        'amber'  => ['bg' => 'bg-warning-100', 'text' => 'text-warning-600'],
                        'gray'   => ['bg' => 'bg-gray-100',    'text' => 'text-gray-500'],
                        default  => ['bg' => 'bg-gray-100',    'text' => 'text-gray-500'],
                    };
                }
            }

            if (!function_exists('grupoNaturalezaProducto')) {
                function grupoNaturalezaProducto(?string $nombre): string {
                    $n = mb_strtolower($nombre ?? '');
                    if (str_contains($n, 'tarjeta')) return 'tarjeta';
                    if (str_contains($n, 'préstamo') || str_contains($n, 'prestamo')) return 'prestamo';
                    if (str_contains($n, 'plazo fijo')) return 'plazo_fijo';
                    if (str_contains($n, 'cts') || str_contains($n, 'afp')) return 'cts_afp';
                    if (str_contains($n, 'cuenta')) return 'cuenta';
                    return 'otro';
                }
            }
        @endphp

        @php
            $p        = $productoFinanciero;
            $entidad  = $p->entidadFinanciera;
            $tipo     = $p->tipoProductoFinanciero;
            $estado   = $p->estadoProducto;
            $moneda   = $p->moneda;
            $padre    = $p->productoPadre;
            $persona  = $p->miembro?->user?->persona;
            $nombreM  = trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '')) ?: ($p->miembro?->apodo ?: '—');
            $avatarM  = $persona?->foto_url;
            $inicialM = mb_strtoupper(mb_substr($nombreM, 0, 1));

            $nombreEnt = $entidad?->nombre_comercial_resuelto ?? '—';
            $colEs     = colorEstadoProductoClases($estado?->color);
            $grupo     = grupoNaturalezaProducto($tipo?->nombre);

            $colores  = ['from-primary-400 to-primary-600','from-orange-400 to-orange-600','from-purple-400 to-purple-600','from-success-400 to-success-600','from-pink-400 to-pink-600'];
            $gradiente = $colores[abs(crc32($p->id)) % count($colores)];

            $disponibleTarjeta = ($grupo === 'tarjeta' && $p->linea_credito !== null)
                ? ((float) $p->linea_credito - (float) ($p->saldo_actual ?? 0))
                : null;

            $detalles = array_filter([
                ['Número de Cuenta', $p->numero_cuenta, 'text', 'numero_cuenta'],
                ['CCI', $p->cci, 'text', 'cci'],
                ['Últimos Dígitos', $p->ultimos_digitos ? '•••• ' . $p->ultimos_digitos : null, 'text', null],
                ['Línea de Crédito', $p->linea_credito !== null ? ($moneda?->simbolo . ' ' . number_format((float) $p->linea_credito, 2)) : null, 'text', null],
                ['Cuota', $p->cuota !== null ? ($moneda?->simbolo . ' ' . number_format((float) $p->cuota, 2)) : null, 'text', null],
                ['Plazo (meses)', $p->plazo_meses, 'text', null],
                ['TEA', $p->tasa_tea !== null ? number_format((float) $p->tasa_tea, 3) . '%' : null, 'text', null],
                ['TCEA', $p->tasa_tcea !== null ? number_format((float) $p->tasa_tcea, 3) . '%' : null, 'text', null],
                ['Día de Corte', $p->dia_corte, 'text', null],
                ['Día de Pago', $p->dia_pago, 'text', null],
                ['Fecha de Apertura', $p->fecha_apertura?->format('d/m/Y'), 'text', null],
                ['Fecha de Vencimiento', $p->fecha_vencimiento?->format('d/m/Y'), 'text', null],
            ], fn ($row) => $row[1] !== null && $row[1] !== '');
        @endphp

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $p->alias }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.productos-financieros.index') }}" class="transition-all hover:text-primary-500">Productos Financieros</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Detalle</li>
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

            <!-- ── Cabecera (hero) ───────────────────────────────────────────── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Panel izquierdo: entidad + estado + titular + acciones -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md h-full flex flex-col">
                        <div class="text-center py-[24px] px-[16px] rounded-md mb-[20px] bg-gray-50 dark:bg-[#15203c]">
                            @if($entidad?->logo_resuelto)
                                <img src="{{ $entidad->logo_resuelto }}" class="w-[72px] h-[72px] rounded-md object-cover mx-auto mb-[12px] border border-gray-100 dark:border-[#172036]" alt="{{ $nombreEnt }}">
                            @else
                                <span class="w-[72px] h-[72px] rounded-md bg-gradient-to-br {{ $gradiente }} flex items-center justify-center text-2xl font-bold text-white mx-auto mb-[12px]">
                                    <i class="material-symbols-outlined !text-[32px]">account_balance</i>
                                </span>
                            @endif

                            <h6 class="!mb-[4px] font-semibold text-black dark:text-white">{{ $p->alias }}</h6>
                            <p class="text-xs text-gray-400 mb-[10px]">{{ $nombreEnt }}</p>

                            @if($tipo)
                            <div class="mb-[8px]">
                                <span class="inline-flex items-center gap-[4px] text-[11px] font-medium py-[3px] px-[10px] rounded-full bg-primary-50 dark:bg-[#1a2d4d] text-primary-600 dark:text-primary-400">
                                    <i class="material-symbols-outlined !text-[13px]">{{ $tipo->icono ?? 'category' }}</i>
                                    {{ $tipo->nombre }}
                                </span>
                            </div>
                            @endif

                            @if($estado)
                            <div>
                                <span class="inline-flex items-center gap-[4px] text-[12px] font-medium py-[3px] px-[10px] rounded-full {{ $colEs['bg'] }} {{ $colEs['text'] }}">
                                    <i class="material-symbols-outlined !text-[13px]">{{ $estado->icono ?? 'circle' }}</i>
                                    {{ $estado->nombre }}
                                </span>
                            </div>
                            @endif

                            @if($p->es_mancomunada)
                            <div class="mt-[8px]">
                                <span class="inline-flex items-center gap-[4px] text-[11px] font-semibold py-[2px] px-[8px] rounded-full bg-gray-100 dark:bg-[#1a2d4d] text-gray-500 dark:text-gray-400">
                                    <i class="material-symbols-outlined !text-[12px]">group</i>
                                    Mancomunada
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Titular -->
                        <div class="flex items-center gap-[10px] mb-[14px] p-[12px] bg-gray-50 dark:bg-[#15203c] rounded-md">
                            @if($avatarM)
                                <img src="{{ $avatarM }}" class="w-[36px] h-[36px] rounded-full object-cover flex-shrink-0" alt="">
                            @else
                                <span class="w-[36px] h-[36px] rounded-full bg-primary-100 flex items-center justify-center text-sm font-bold text-primary-700 flex-shrink-0">{{ $inicialM }}</span>
                            @endif
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400 mb-0">Titular</p>
                                <p class="text-sm font-medium text-black dark:text-white truncate">{{ $nombreM }}</p>
                            </div>
                        </div>

                        <div class="flex-1"></div>

                        <!-- Acciones -->
                        <div class="flex items-center gap-[10px] mt-[16px]">
                            <a href="{{ route('dashboard.productos-financieros.edit', $p) }}"
                                class="flex-1 text-center inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <i class="material-symbols-outlined !text-[16px] align-middle mr-[4px]">edit</i>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('dashboard.productos-financieros.destroy', $p) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('¿Eliminar el producto «{{ addslashes($p->alias) }}»?')"
                                    class="w-full inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-danger-500 text-white hover:bg-danger-400 text-sm">
                                    <i class="material-symbols-outlined !text-[16px] align-middle mr-[4px]">delete</i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panel derecho: saldo destacado + cabecera adaptada por naturaleza -->
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">

                        <!-- Saldo destacado -->
                        <div class="text-center sm:text-left mb-[20px] pb-[20px] border-b border-gray-100 dark:border-[#172036]">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-[4px]">
                                {{ $grupo === 'tarjeta' ? 'Deuda Actual' : ($grupo === 'prestamo' ? 'Capital Pendiente' : 'Saldo Actual') }}
                            </p>
                            <span class="block text-3xl md:text-4xl font-bold {{ $colEs['text'] }}">
                                {{ $moneda?->simbolo }} {{ number_format((float) ($p->saldo_actual ?? 0), 2) }}
                            </span>
                        </div>

                        <!-- Cabecera adaptada por naturaleza -->
                        @if($grupo === 'cuenta')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px]">
                                @if($p->numero_cuenta)
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">Número de Cuenta</label>
                                    <div class="relative">
                                        <input type="text" id="copyNumeroCuenta" readonly value="{{ $p->numero_cuenta }}"
                                            class="h-[44px] w-full rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c] px-[14px] pr-[40px] text-sm font-mono outline-0">
                                        <button type="button" data-input="copyNumeroCuenta"
                                            class="copyClipboardButton absolute ltr:right-[10px] rtl:left-[10px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-500 transition-all">
                                            <i class="ri-file-copy-line"></i>
                                        </button>
                                    </div>
                                </div>
                                @endif
                                @if($p->cci)
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">CCI</label>
                                    <div class="relative">
                                        <input type="text" id="copyCci" readonly value="{{ $p->cci }}"
                                            class="h-[44px] w-full rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c] px-[14px] pr-[40px] text-sm font-mono outline-0">
                                        <button type="button" data-input="copyCci"
                                            class="copyClipboardButton absolute ltr:right-[10px] rtl:left-[10px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-500 transition-all">
                                            <i class="ri-file-copy-line"></i>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @elseif($grupo === 'tarjeta')
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-[16px]">
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">Tarjeta</label>
                                    <span class="block text-lg font-mono font-semibold text-black dark:text-white">
                                        •••• {{ $p->ultimos_digitos ?: '----' }}
                                    </span>
                                </div>
                                @if($p->linea_credito !== null)
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">Línea de Crédito</label>
                                    <span class="block text-lg font-semibold text-black dark:text-white">
                                        {{ $moneda?->simbolo }} {{ number_format((float) $p->linea_credito, 2) }}
                                    </span>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">Disponible</label>
                                    <span class="block text-lg font-semibold text-success-600">
                                        {{ $moneda?->simbolo }} {{ number_format((float) $disponibleTarjeta, 2) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        @elseif($grupo === 'prestamo')
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-[16px]">
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">Cuota</label>
                                    <span class="block text-base font-semibold text-black dark:text-white">
                                        {{ $p->cuota !== null ? $moneda?->simbolo . ' ' . number_format((float) $p->cuota, 2) : '—' }}
                                    </span>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">Plazo</label>
                                    <span class="block text-base font-semibold text-black dark:text-white">
                                        {{ $p->plazo_meses ? $p->plazo_meses . ' meses' : '—' }}
                                    </span>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">TCEA</label>
                                    <span class="block text-base font-semibold text-black dark:text-white">
                                        {{ $p->tasa_tcea !== null ? number_format((float) $p->tasa_tcea, 3) . '%' : '—' }}
                                    </span>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">Fecha de Apertura</label>
                                    <span class="block text-base font-semibold text-black dark:text-white">
                                        {{ $p->fecha_apertura?->format('d/m/Y') ?? '—' }}
                                    </span>
                                </div>
                            </div>
                        @elseif($grupo === 'plazo_fijo')
                            @php
                                $diasRestantes = $p->fecha_vencimiento ? now()->startOfDay()->diffInDays($p->fecha_vencimiento, false) : null;
                            @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-[16px]">
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">TEA</label>
                                    <span class="block text-base font-semibold text-black dark:text-white">
                                        {{ $p->tasa_tea !== null ? number_format((float) $p->tasa_tea, 3) . '%' : '—' }}
                                    </span>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">Fecha de Vencimiento</label>
                                    <span class="block text-base font-semibold text-black dark:text-white">
                                        {{ $p->fecha_vencimiento?->format('d/m/Y') ?? '—' }}
                                    </span>
                                </div>
                                @if($diasRestantes !== null)
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">Días Restantes</label>
                                    <span class="block text-base font-semibold {{ $diasRestantes < 0 ? 'text-danger-500' : ($diasRestantes <= 30 ? 'text-warning-500' : 'text-success-600') }}">
                                        {{ $diasRestantes < 0 ? 'Vencido' : $diasRestantes . ' días' }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        @elseif($grupo === 'cts_afp')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px]">
                                <div>
                                    <label class="text-xs text-gray-400 block mb-[6px]">Fecha de Apertura</label>
                                    <span class="block text-base font-semibold text-black dark:text-white">
                                        {{ $p->fecha_apertura?->format('d/m/Y') ?? '—' }}
                                    </span>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <!-- ── Tabs ──────────────────────────────────────────────────────── -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-tabs" id="trezo-tabs">
                    <ul class="navs mb-[20px] border-b border-gray-100 dark:border-[#172036] flex flex-wrap gap-y-[4px]">
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabResumen" class="nav-link active flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">summarize</i>
                                Resumen
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabMovimientos" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">receipt_long</i>
                                Movimientos
                                @if($transacciones->count() > 0)
                                <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-primary-500 text-white rounded-full">{{ $transacciones->count() }}</span>
                                @endif
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabDocumentos" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">description</i>
                                Documentos
                                @if($productoFinanciero->documentosFinancieros->count() > 0)
                                <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-primary-500 text-white rounded-full">{{ $productoFinanciero->documentosFinancieros->count() }}</span>
                                @endif
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabBeneficiarios" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">groups</i>
                                Beneficiarios
                                @if($productoFinanciero->beneficiarios->count() > 0)
                                <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-primary-500 text-white rounded-full">{{ $productoFinanciero->beneficiarios->count() }}</span>
                                @endif
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- ─── TAB 1: Resumen ─────────────────────────────────────── --}}
                        <div class="tab-pane active" id="tabResumen">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px]">

                                <!-- Entidad -->
                                <div class="lg:col-span-1">
                                    <h6 class="font-semibold text-black dark:text-white mb-[14px] flex items-center gap-[8px]">
                                        <i class="material-symbols-outlined !text-[18px] text-primary-500">account_balance</i>
                                        Entidad
                                    </h6>
                                    <div class="flex items-center gap-[12px] p-[14px] bg-gray-50 dark:bg-[#15203c] rounded-md mb-[16px]">
                                        @if($entidad?->logo_resuelto)
                                            <img src="{{ $entidad->logo_resuelto }}" class="w-[44px] h-[44px] rounded-md object-cover flex-shrink-0" alt="{{ $nombreEnt }}">
                                        @else
                                            <span class="w-[44px] h-[44px] rounded-md bg-gradient-to-br {{ $gradiente }} flex items-center justify-center text-white flex-shrink-0">
                                                <i class="material-symbols-outlined !text-[20px]">account_balance</i>
                                            </span>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('dashboard.entidades-financieras.show', $entidad) }}"
                                                class="block text-sm font-semibold text-black dark:text-white hover:text-primary-500 truncate">
                                                {{ $nombreEnt }}
                                            </a>
                                            @if($entidad?->empresa?->ruc)
                                            <span class="text-xs text-gray-400 font-mono">RUC: {{ $entidad->empresa->ruc }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($padre)
                                    <h6 class="font-semibold text-black dark:text-white mb-[14px] flex items-center gap-[8px]">
                                        <i class="material-symbols-outlined !text-[18px] text-primary-500">link</i>
                                        Producto Padre
                                    </h6>
                                    <a href="{{ route('dashboard.productos-financieros.show', $padre) }}"
                                        class="flex items-center gap-[12px] p-[14px] bg-gray-50 dark:bg-[#15203c] rounded-md mb-[16px] hover:bg-primary-50 dark:hover:bg-[#172036] transition-colors">
                                        <i class="material-symbols-outlined !text-[20px] text-primary-500">savings</i>
                                        <div class="min-w-0">
                                            <span class="block text-sm font-semibold text-black dark:text-white truncate">{{ $padre->alias }}</span>
                                            <span class="text-xs text-gray-400">{{ $padre->entidadFinanciera?->nombre_comercial_resuelto }}</span>
                                        </div>
                                    </a>
                                    @endif

                                    @if($p->notas)
                                    <h6 class="font-semibold text-black dark:text-white mb-[14px] flex items-center gap-[8px]">
                                        <i class="material-symbols-outlined !text-[18px] text-primary-500">notes</i>
                                        Notas
                                    </h6>
                                    <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[14px]">
                                        <p class="text-sm text-black dark:text-white leading-[1.6] whitespace-pre-line">{{ $p->notas }}</p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Datos del producto -->
                                <div class="lg:col-span-2">
                                    <h6 class="font-semibold text-black dark:text-white mb-[14px] flex items-center gap-[8px]">
                                        <i class="material-symbols-outlined !text-[18px] text-primary-500">list_alt</i>
                                        Datos del Producto
                                    </h6>
                                    <ul class="grid grid-cols-1 sm:grid-cols-2">
                                        <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                            <span class="block text-gray-500 dark:text-gray-400 text-sm">Alias</span>
                                            <span class="block text-black dark:text-white font-medium text-sm">{{ $p->alias }}</span>
                                        </li>
                                        <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                            <span class="block text-gray-500 dark:text-gray-400 text-sm">Tipo</span>
                                            <span class="block text-black dark:text-white font-medium text-sm">{{ $tipo?->nombre ?? '—' }}</span>
                                        </li>
                                        <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                            <span class="block text-gray-500 dark:text-gray-400 text-sm">Estado</span>
                                            <span class="block text-black dark:text-white font-medium text-sm">{{ $estado?->nombre ?? '—' }}</span>
                                        </li>
                                        <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                            <span class="block text-gray-500 dark:text-gray-400 text-sm">Moneda</span>
                                            <span class="block text-black dark:text-white font-medium text-sm">{{ $moneda?->simbolo }} {{ $moneda?->nombre ?? '—' }}</span>
                                        </li>
                                        <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                            <span class="block text-gray-500 dark:text-gray-400 text-sm">Titular</span>
                                            <span class="block text-black dark:text-white font-medium text-sm">{{ $nombreM }}</span>
                                        </li>
                                        <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                            <span class="block text-gray-500 dark:text-gray-400 text-sm">¿Mancomunada?</span>
                                            <span class="block text-black dark:text-white font-medium text-sm">{{ $p->es_mancomunada ? 'Sí' : 'No' }}</span>
                                        </li>
                                        @foreach($detalles as [$label, $valor])
                                        <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                            <span class="block text-gray-500 dark:text-gray-400 text-sm">{{ $label }}</span>
                                            <span class="block text-black dark:text-white font-medium text-sm">{{ $valor }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- ─── TAB 2: Movimientos ─────────────────────────────────── --}}
                        <div class="tab-pane" id="tabMovimientos">
                            @php
                                if (!function_exists('colorTipoTxn')) {
                                    function colorTipoTxn(?string $c): array {
                                        return match($c) {
                                            'green'  => ['bg'=>'bg-success-100 dark:bg-[#0c2a1e]','text'=>'text-success-700 dark:text-success-400'],
                                            'red'    => ['bg'=>'bg-danger-100 dark:bg-[#2a0c0c]', 'text'=>'text-danger-700 dark:text-danger-400'],
                                            'orange' => ['bg'=>'bg-orange-100 dark:bg-[#2a1a0c]', 'text'=>'text-orange-700 dark:text-orange-400'],
                                            'amber'  => ['bg'=>'bg-warning-100 dark:bg-[#2a220c]','text'=>'text-warning-700 dark:text-warning-400'],
                                            'blue'   => ['bg'=>'bg-primary-100 dark:bg-[#0c1a2a]','text'=>'text-primary-700 dark:text-primary-400'],
                                            'purple' => ['bg'=>'bg-purple-100 dark:bg-[#1a0c2a]', 'text'=>'text-purple-700 dark:text-purple-400'],
                                            default  => ['bg'=>'bg-gray-100 dark:bg-[#172036]',   'text'=>'text-gray-600 dark:text-gray-400'],
                                        };
                                    }
                                }
                            @endphp

                            <div class="flex items-center justify-between mb-[20px]">
                                <div class="flex items-center gap-[8px]">
                                    <i class="material-symbols-outlined !text-[20px] text-primary-500">receipt_long</i>
                                    <h6 class="!mb-0 text-sm font-semibold text-black dark:text-white">Movimientos</h6>
                                    <span class="text-xs text-gray-400">({{ $transacciones->count() }})</span>
                                </div>
                                <button type="button" onclick="abrirModalTxn()"
                                    class="inline-flex items-center gap-[6px] h-[36px] px-[14px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i>
                                    Movimiento
                                </button>
                            </div>

                            @if($transacciones->isEmpty())
                                <div class="text-center py-[50px]">
                                    <i class="material-symbols-outlined !text-[56px] text-gray-300 dark:text-gray-600 block mb-[12px]">receipt_long</i>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mb-[16px]">No hay movimientos registrados.</p>
                                    <button type="button" onclick="abrirModalTxn()"
                                        class="inline-flex items-center gap-[6px] px-[16px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                        <i class="material-symbols-outlined !text-[16px]">add</i>
                                        Registrar primer movimiento
                                    </button>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full whitespace-nowrap">
                                        <thead>
                                            <tr class="border-b border-gray-100 dark:border-[#172036]">
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px] first:ltr:pl-0 first:rtl:pr-0">Tipo</th>
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">Glosa</th>
                                                <th class="text-right font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">Monto</th>
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">Fecha</th>
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">Método</th>
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">Nro. Op.</th>
                                                <th class="text-right font-medium text-black dark:text-white text-sm py-[12px] px-[10px] last:ltr:pr-0 last:rtl:pl-0">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-[#172036]">
                                            @foreach($transacciones as $txn)
                                            @php
                                                $esOrigen  = $txn->producto_financiero_id === $productoFinanciero->id;
                                                $esDestino = $txn->producto_destino_id    === $productoFinanciero->id;
                                                $naturaleza = $txn->tipoTransaccion?->naturaleza;
                                                $colTipo    = colorTipoTxn($txn->tipoTransaccion?->color);

                                                // Signo del monto según perspectiva
                                                if ($esDestino && !$esOrigen) {
                                                    $montoClass  = 'text-success-600 dark:text-success-400 font-semibold';
                                                    $montoPrefix = '+';
                                                } elseif ($naturaleza === 'ingreso') {
                                                    $montoClass  = 'text-success-600 dark:text-success-400 font-semibold';
                                                    $montoPrefix = '+';
                                                } elseif ($naturaleza === 'egreso') {
                                                    $montoClass  = 'text-danger-600 dark:text-danger-400 font-semibold';
                                                    $montoPrefix = '−';
                                                } else {
                                                    $montoClass  = 'text-gray-700 dark:text-gray-300 font-semibold';
                                                    $montoPrefix = '';
                                                }

                                                $esTransferencia = $txn->producto_destino_id !== null;
                                                $aliasDestino = $txn->productoDestino?->alias;
                                                $aliasOrigen  = $txn->producto?->alias;
                                            @endphp
                                            <tr>
                                                <td class="py-[11px] px-[10px] first:ltr:pl-0 first:rtl:pr-0">
                                                    <div>
                                                        <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[2px] px-[8px] rounded-full {{ $colTipo['bg'] }} {{ $colTipo['text'] }}">
                                                            @if($txn->tipoTransaccion?->icono)
                                                                <i class="material-symbols-outlined !text-[11px]">{{ $txn->tipoTransaccion->icono }}</i>
                                                            @endif
                                                            {{ $txn->tipoTransaccion?->nombre ?? '—' }}
                                                        </span>
                                                        @if($esTransferencia)
                                                        <div class="flex items-center gap-[4px] mt-[4px] text-[10px] text-gray-400">
                                                            <span class="truncate max-w-[80px]">{{ $aliasOrigen }}</span>
                                                            <i class="material-symbols-outlined !text-[11px]">arrow_forward</i>
                                                            <span class="truncate max-w-[80px]">{{ $aliasDestino }}</span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="py-[11px] px-[10px] text-sm text-gray-600 dark:text-gray-400 max-w-[160px]">
                                                    <span class="block truncate" title="{{ $txn->glosa }}">{{ $txn->glosa ?: '—' }}</span>
                                                </td>
                                                <td class="py-[11px] px-[10px] text-right">
                                                    <span class="text-sm {{ $montoClass }}">
                                                        {{ $montoPrefix }} {{ $txn->moneda?->simbolo }} {{ number_format((float) $txn->monto, 2) }}
                                                    </span>
                                                </td>
                                                <td class="py-[11px] px-[10px] text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $txn->fecha->format('d/m/Y') }}
                                                </td>
                                                <td class="py-[11px] px-[10px] text-sm text-gray-500 dark:text-gray-400">
                                                    @if($txn->metodoPago)
                                                        <span class="inline-flex items-center gap-[4px]">
                                                            @if($txn->metodoPago->icono)
                                                                <i class="material-symbols-outlined !text-[13px]">{{ $txn->metodoPago->icono }}</i>
                                                            @endif
                                                            {{ $txn->metodoPago->nombre }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-300 dark:text-gray-600">—</span>
                                                    @endif
                                                </td>
                                                <td class="py-[11px] px-[10px] text-sm text-gray-500 dark:text-gray-400 font-mono">
                                                    {{ $txn->nro_operacion ?: '—' }}
                                                </td>
                                                <td class="py-[11px] px-[10px] last:ltr:pr-0 last:rtl:pl-0">
                                                    <div class="flex items-center justify-end gap-[6px]">
                                                        <button type="button"
                                                            onclick="abrirModalEditTxn({{ json_encode([
                                                                'id'                  => $txn->id,
                                                                'tipo_transaccion_id' => $txn->tipo_transaccion_id,
                                                                'monto'               => (float) $txn->monto,
                                                                'moneda_id'           => $txn->moneda_id,
                                                                'fecha'               => $txn->fecha->format('Y-m-d'),
                                                                'glosa'               => $txn->glosa,
                                                                'nro_operacion'       => $txn->nro_operacion,
                                                                'metodo_pago_id'      => $txn->metodo_pago_id,
                                                                'producto_destino_id' => $txn->producto_destino_id,
                                                            ]) }})"
                                                            class="h-[30px] w-[30px] inline-flex items-center justify-center rounded-md bg-gray-50 dark:bg-[#172036] text-gray-500 dark:text-gray-400 hover:bg-primary-50 hover:text-primary-600 transition-all">
                                                            <i class="material-symbols-outlined !text-[14px]">edit</i>
                                                        </button>
                                                        <form method="POST" action="{{ route('dashboard.productos-financieros.transacciones.destroy', $txn) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                onclick="return confirm('¿Eliminar el movimiento del {{ $txn->fecha->format('d/m/Y') }}?')"
                                                                class="h-[30px] w-[30px] inline-flex items-center justify-center rounded-md bg-danger-50 dark:bg-[#172036] text-danger-500 hover:bg-danger-100 transition-all">
                                                                <i class="material-symbols-outlined !text-[14px]">delete</i>
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

                        {{-- ─── TAB 3: Documentos ─────────────────────────────────────── --}}
                        <div class="tab-pane" id="tabDocumentos">
                            @php $hoy = now()->startOfDay(); @endphp

                            <div class="flex items-center justify-between mb-[20px]">
                                <div class="flex items-center gap-[8px]">
                                    <i class="material-symbols-outlined !text-[20px] text-primary-500">attach_file</i>
                                    <h6 class="!mb-0 text-sm font-semibold text-black dark:text-white">Documentos</h6>
                                    <span class="text-xs text-gray-400">({{ $productoFinanciero->documentosFinancieros->count() }})</span>
                                </div>
                                <button type="button" onclick="abrirModalDoc()"
                                    class="inline-flex items-center gap-[6px] h-[36px] px-[14px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i>
                                    Documento
                                </button>
                            </div>

                            @if($productoFinanciero->documentosFinancieros->isEmpty())
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
                                <div class="overflow-x-auto">
                                    <table class="w-full whitespace-nowrap">
                                        <thead>
                                            <tr class="border-b border-gray-100 dark:border-[#172036]">
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px] first:ltr:pl-0 first:rtl:pr-0">Tipo</th>
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">Título</th>
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">Período</th>
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">F. Emisión</th>
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">F. Vencimiento</th>
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">Archivo</th>
                                                <th class="text-right font-medium text-black dark:text-white text-sm py-[12px] px-[10px] last:ltr:pr-0 last:rtl:pl-0">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-[#172036]">
                                            @foreach($productoFinanciero->documentosFinancieros as $doc)
                                            @php
                                                $icono = $doc->tipoDocumentoFinanciero?->icono ?? 'description';
                                                $tipo  = $doc->tipoDocumentoFinanciero?->nombre ?? '—';
                                                $vencColor = '';
                                                $vencIcon  = '';
                                                if ($doc->fecha_vencimiento) {
                                                    if ($doc->fecha_vencimiento->lt($hoy)) {
                                                        $vencColor = 'text-danger-600 dark:text-danger-400';
                                                        $vencIcon  = 'error';
                                                    } elseif ($doc->fecha_vencimiento->diffInDays($hoy) <= 30) {
                                                        $vencColor = 'text-warning-600 dark:text-warning-400';
                                                        $vencIcon  = 'warning';
                                                    }
                                                }
                                            @endphp
                                            <tr>
                                                <td class="py-[12px] px-[10px] first:ltr:pl-0 first:rtl:pr-0">
                                                    <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[2px] px-[8px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400">
                                                        <i class="material-symbols-outlined !text-[12px]">{{ $icono }}</i>
                                                        {{ $tipo }}
                                                    </span>
                                                </td>
                                                <td class="py-[12px] px-[10px] text-sm text-black dark:text-white max-w-[200px]">
                                                    <span class="block truncate" title="{{ $doc->titulo }}">{{ $doc->titulo }}</span>
                                                </td>
                                                <td class="py-[12px] px-[10px] text-sm text-gray-500 dark:text-gray-400 font-mono">
                                                    {{ $doc->periodo ?? '—' }}
                                                </td>
                                                <td class="py-[12px] px-[10px] text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $doc->fecha_emision?->format('d/m/Y') ?? '—' }}
                                                </td>
                                                <td class="py-[12px] px-[10px]">
                                                    @if($doc->fecha_vencimiento)
                                                        <span class="inline-flex items-center gap-[4px] text-sm {{ $vencColor ?: 'text-gray-500 dark:text-gray-400' }}">
                                                            @if($vencIcon)
                                                                <i class="material-symbols-outlined !text-[14px]">{{ $vencIcon }}</i>
                                                            @endif
                                                            {{ $doc->fecha_vencimiento->format('d/m/Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-sm text-gray-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="py-[12px] px-[10px]">
                                                    @if($doc->archivo_path)
                                                        <a href="{{ asset('storage/' . $doc->archivo_path) }}" target="_blank"
                                                            class="inline-flex items-center gap-[4px] text-xs text-primary-600 dark:text-primary-400 hover:text-primary-400 transition-all">
                                                            <i class="material-symbols-outlined !text-[14px]">open_in_new</i>
                                                            Ver
                                                        </a>
                                                    @else
                                                        <span class="text-xs text-gray-400">Sin archivo</span>
                                                    @endif
                                                </td>
                                                <td class="py-[12px] px-[10px] last:ltr:pr-0 last:rtl:pl-0">
                                                    <div class="flex items-center justify-end gap-[6px]">
                                                        <button type="button"
                                                            onclick="abrirModalEditDoc({{ json_encode([
                                                                'id'                           => $doc->id,
                                                                'tipo_documento_financiero_id' => $doc->tipo_documento_financiero_id,
                                                                'titulo'                       => $doc->titulo,
                                                                'periodo'                      => $doc->periodo,
                                                                'fecha_emision'                => $doc->fecha_emision?->format('Y-m-d'),
                                                                'fecha_vencimiento'            => $doc->fecha_vencimiento?->format('Y-m-d'),
                                                                'notas'                        => $doc->notas,
                                                            ]) }})"
                                                            class="h-[30px] w-[30px] inline-flex items-center justify-center rounded-md bg-gray-50 dark:bg-[#172036] text-gray-500 dark:text-gray-400 hover:bg-primary-50 hover:text-primary-600 transition-all">
                                                            <i class="material-symbols-outlined !text-[14px]">edit</i>
                                                        </button>
                                                        <form method="POST" action="{{ route('dashboard.productos-financieros.documentos.destroy', $doc) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                onclick="return confirm('¿Eliminar el documento «{{ addslashes($doc->titulo) }}»?')"
                                                                class="h-[30px] w-[30px] inline-flex items-center justify-center rounded-md bg-danger-50 dark:bg-[#172036] text-danger-500 hover:bg-danger-100 transition-all">
                                                                <i class="material-symbols-outlined !text-[14px]">delete</i>
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

                        {{-- ─── TAB 4: Beneficiarios ──────────────────────────────────── --}}
                        <div class="tab-pane" id="tabBeneficiarios">
                            @php
                                $sumaPct = $productoFinanciero->beneficiarios->sum('porcentaje');
                                $pctCompleto = abs($sumaPct - 100) < 0.01;
                            @endphp

                            <div class="flex items-center justify-between mb-[20px]">
                                <div class="flex items-center gap-[8px] flex-wrap">
                                    <i class="material-symbols-outlined !text-[20px] text-primary-500">groups</i>
                                    <h6 class="!mb-0 text-sm font-semibold text-black dark:text-white">Beneficiarios</h6>
                                    <span class="text-xs text-gray-400">({{ $productoFinanciero->beneficiarios->count() }})</span>
                                    @if($productoFinanciero->beneficiarios->count() > 0)
                                        @if($pctCompleto)
                                            <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[2px] px-[8px] rounded-full bg-success-100 dark:bg-[#0c2a1e] text-success-700 dark:text-success-400">
                                                <i class="material-symbols-outlined !text-[12px]">check_circle</i>
                                                100% asignado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[2px] px-[8px] rounded-full bg-warning-100 dark:bg-[#2a220c] text-warning-700 dark:text-warning-400">
                                                <i class="material-symbols-outlined !text-[12px]">warning</i>
                                                Suma: {{ number_format($sumaPct, 0) }}% — debería ser 100%
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <button type="button" onclick="abrirModalBenef()"
                                    class="inline-flex items-center gap-[6px] h-[36px] px-[14px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i>
                                    Beneficiario
                                </button>
                            </div>

                            @if($productoFinanciero->beneficiarios->isEmpty())
                                <div class="text-center py-[50px]">
                                    <i class="material-symbols-outlined !text-[56px] text-gray-300 dark:text-gray-600 block mb-[12px]">groups</i>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mb-[16px]">No hay beneficiarios registrados.</p>
                                    <button type="button" onclick="abrirModalBenef()"
                                        class="inline-flex items-center gap-[6px] px-[16px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                        <i class="material-symbols-outlined !text-[16px]">add</i>
                                        Agregar primer beneficiario
                                    </button>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full whitespace-nowrap">
                                        <thead>
                                            <tr class="border-b border-gray-100 dark:border-[#172036]">
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px] first:ltr:pl-0 first:rtl:pr-0">Nombre</th>
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">Parentesco</th>
                                                <th class="text-left font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">Doc. Identidad</th>
                                                <th class="text-right font-medium text-black dark:text-white text-sm py-[12px] px-[10px]">Porcentaje</th>
                                                <th class="text-right font-medium text-black dark:text-white text-sm py-[12px] px-[10px] last:ltr:pr-0 last:rtl:pl-0">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-[#172036]">
                                            @foreach($productoFinanciero->beneficiarios as $benef)
                                            @php
                                                $persona   = $benef->hogarMiembro?->user?->persona;
                                                $nombreResuelto = $persona
                                                    ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? ''))
                                                    : ($benef->nombre ?: '—');
                                                $inicial   = mb_strtoupper(mb_substr($nombreResuelto, 0, 1));
                                                $foto      = $persona?->foto_url;
                                            @endphp
                                            <tr>
                                                <td class="py-[11px] px-[10px] first:ltr:pl-0 first:rtl:pr-0">
                                                    <div class="flex items-center gap-[8px]">
                                                        @if($foto)
                                                            <img src="{{ $foto }}" class="w-[30px] h-[30px] rounded-full object-cover flex-shrink-0" alt="">
                                                        @else
                                                            <span class="w-[30px] h-[30px] rounded-full bg-primary-100 flex items-center justify-center text-xs font-bold text-primary-700 flex-shrink-0">{{ $inicial }}</span>
                                                        @endif
                                                        <div class="min-w-0">
                                                            <p class="text-sm font-medium text-black dark:text-white truncate">{{ $nombreResuelto }}</p>
                                                            @if($benef->hogarMiembro)
                                                                <p class="text-[11px] text-primary-500">miembro del hogar</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-[11px] px-[10px] text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $benef->parentesco ?: '—' }}
                                                </td>
                                                <td class="py-[11px] px-[10px] text-sm text-gray-500 dark:text-gray-400 font-mono">
                                                    {{ $benef->documento_identidad ?: '—' }}
                                                </td>
                                                <td class="py-[11px] px-[10px] text-right">
                                                    <span class="text-sm font-semibold text-black dark:text-white">{{ number_format((float) $benef->porcentaje, 1) }}%</span>
                                                </td>
                                                <td class="py-[11px] px-[10px] last:ltr:pr-0 last:rtl:pl-0">
                                                    <div class="flex items-center justify-end gap-[6px]">
                                                        <button type="button"
                                                            onclick="abrirModalEditBenef({{ json_encode([
                                                                'id'                  => $benef->id,
                                                                'hogar_miembro_id'    => $benef->hogar_miembro_id,
                                                                'nombre'              => $benef->nombre,
                                                                'parentesco'          => $benef->parentesco,
                                                                'documento_identidad' => $benef->documento_identidad,
                                                                'porcentaje'          => (float) $benef->porcentaje,
                                                            ]) }})"
                                                            class="h-[30px] w-[30px] inline-flex items-center justify-center rounded-md bg-gray-50 dark:bg-[#172036] text-gray-500 dark:text-gray-400 hover:bg-primary-50 hover:text-primary-600 transition-all">
                                                            <i class="material-symbols-outlined !text-[14px]">edit</i>
                                                        </button>
                                                        <form method="POST" action="{{ route('dashboard.productos-financieros.beneficiarios.destroy', $benef) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                onclick="return confirm('¿Eliminar el beneficiario «{{ addslashes($benef->nombre) }}»?')"
                                                                class="h-[30px] w-[30px] inline-flex items-center justify-center rounded-md bg-danger-50 dark:bg-[#172036] text-danger-500 hover:bg-danger-100 transition-all">
                                                                <i class="material-symbols-outlined !text-[14px]">delete</i>
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

                    </div>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        <!-- ─── MODAL Transacción ───────────────────────────────────────────── -->
        <div id="modalTransaccion" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalTxn()"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[580px] bg-white dark:bg-[#0c1427] rounded-md shadow-xl overflow-hidden mx-[16px]">
                <div class="bg-gray-50 dark:bg-[#15203c] flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-base" id="modalTxnTitulo">Agregar Movimiento</h5>
                    <button type="button" onclick="cerrarModalTxn()" class="text-gray-400 hover:text-gray-600 transition-all">
                        <i class="material-symbols-outlined !text-[22px]">close</i>
                    </button>
                </div>

                <form id="formTransaccion" method="POST" class="p-[20px] max-h-[80vh] overflow-y-auto">
                    @csrf
                    <span id="methodSpoofTxn"></span>

                    @if($errors->any())
                    <div class="mb-[14px] bg-danger-100 border border-danger-400 text-danger-700 px-[14px] py-[10px] rounded-md text-sm">
                        <ul class="list-disc ltr:pl-[16px] rtl:pr-[16px]">
                            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px]">

                        <!-- Tipo de transacción -->
                        <div class="sm:col-span-2">
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Tipo <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative" id="tipoTxnWrapper">
                                <div id="tipoTxnTrigger"
                                    class="h-[48px] flex items-center rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] cursor-pointer select-none transition-all hover:border-primary-500">
                                    <span class="w-[8px] h-[8px] rounded-full bg-gray-300 mr-[8px] flex-shrink-0" id="tipoTxnColorDot"></span>
                                    <span id="tipoTxnLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Selecciona tipo...</span>
                                    <i class="material-symbols-outlined !text-[18px] text-gray-400 transition-transform duration-200" id="tipoTxnChevron">expand_more</i>
                                </div>
                                <input type="hidden" name="tipo_transaccion_id" id="tipo_transaccion_id" value="">
                                <div id="tipoTxnDropdown" class="hidden absolute z-[9999] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[2px]">
                                    <ul class="max-h-[220px] overflow-y-auto py-[4px]">
                                        @foreach($tiposTransaccion as $tt)
                                        <li class="tipo-txn-opcion flex items-center gap-[8px] px-[12px] py-[8px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                            data-id="{{ $tt->id }}"
                                            data-nombre="{{ $tt->nombre }}"
                                            data-color="{{ $tt->color ?? '' }}"
                                            data-es-transferencia="{{ str_contains(strtolower($tt->nombre), 'transfer') ? '1' : '0' }}">
                                            <span class="w-[8px] h-[8px] rounded-full flex-shrink-0" style="background-color: {{ match($tt->color) { 'green'=>'#16a34a','red'=>'#dc2626','orange'=>'#ea580c','amber'=>'#d97706','blue'=>'#3b82f6','purple'=>'#9333ea', default=>'#9ca3af' } }}"></span>
                                            @if($tt->icono)
                                            <i class="material-symbols-outlined !text-[14px] text-gray-500">{{ $tt->icono }}</i>
                                            @endif
                                            <span class="text-sm text-black dark:text-white">{{ $tt->nombre }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Monto + Moneda -->
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Monto <span class="text-danger-500">*</span>
                            </label>
                            <input type="number" name="monto" id="txnMonto" step="0.01" min="0.01" placeholder="0.00"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all placeholder:text-gray-500 focus:border-primary-500">
                        </div>

                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Moneda <span class="text-danger-500">*</span>
                            </label>
                            <select name="moneda_id" id="txnMonedaId"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                                @foreach($monedas as $mon)
                                <option value="{{ $mon->id }}">{{ $mon->simbolo }} {{ $mon->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fecha -->
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Fecha <span class="text-danger-500">*</span>
                            </label>
                            <input type="date" name="fecha" id="txnFecha"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>

                        <!-- Método de pago -->
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Método de pago</label>
                            <select name="metodo_pago_id" id="txnMetodoPagoId"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                                <option value="">Sin especificar</option>
                                @foreach($metodosPago as $mp)
                                <option value="{{ $mp->id }}">{{ $mp->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Glosa -->
                        <div class="sm:col-span-2">
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Glosa</label>
                            <input type="text" name="glosa" id="txnGlosa" placeholder="Descripción del movimiento..."
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                        </div>

                        <!-- Nro. Operación -->
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Nro. de Operación</label>
                            <input type="text" name="nro_operacion" id="txnNroOp" placeholder="Ej: 00123456"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500 font-mono">
                        </div>

                        <!-- Cuenta destino (solo transferencia) -->
                        <div id="destinoWrapper" class="hidden sm:col-span-2">
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Cuenta destino
                                <span class="text-warning-500 text-xs font-normal ml-[4px]">(transferencia)</span>
                            </label>
                            <select name="producto_destino_id" id="txnProductoDestino"
                                class="h-[48px] rounded-md text-black dark:text-white border border-warning-300 bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all focus:border-warning-400">
                                <option value="">Sin cuenta destino</option>
                                @foreach($productosDestino as $pd)
                                <option value="{{ $pd->id }}">{{ $pd->alias }} — {{ $pd->entidadFinanciera?->nombre_comercial_resuelto }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="mt-[20px] flex justify-end gap-[12px]">
                        <button type="button" onclick="cerrarModalTxn()"
                            class="px-[20px] py-[10px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 text-sm font-medium hover:border-primary-500 hover:text-primary-500 transition-all">
                            Cancelar
                        </button>
                        <button type="submit"
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

        <!-- ─── MODAL Beneficiario ────────────────────────────────────────────── -->
        <div id="modalBeneficiario" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalBenef()"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[520px] bg-white dark:bg-[#0c1427] rounded-md shadow-xl overflow-hidden mx-[16px]">
                <div class="bg-gray-50 dark:bg-[#15203c] flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-base" id="modalBenefTitulo">Agregar Beneficiario</h5>
                    <button type="button" onclick="cerrarModalBenef()" class="text-gray-400 hover:text-gray-600 transition-all">
                        <i class="material-symbols-outlined !text-[22px]">close</i>
                    </button>
                </div>

                <form id="formBeneficiario" method="POST" class="p-[20px] max-h-[80vh] overflow-y-auto">
                    @csrf
                    <span id="methodSpoofBenef"></span>

                    @if($errors->any())
                    <div class="mb-[14px] bg-danger-100 border border-danger-400 text-danger-700 px-[14px] py-[10px] rounded-md text-sm">
                        <ul class="list-disc ltr:pl-[16px] rtl:pr-[16px]">
                            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px]">

                        <!-- Toggle miembro del hogar -->
                        <div class="sm:col-span-2 flex items-center gap-[10px]">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="esMiembroToggle" class="sr-only peer" onchange="toggleMiembroHogar(this.checked)">
                                <div class="w-[44px] h-[24px] bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all dark:border-gray-600 peer-checked:bg-primary-500"></div>
                            </label>
                            <span class="text-sm font-medium text-black dark:text-white">Miembro del hogar</span>
                        </div>

                        <!-- Select miembro (condicional) -->
                        <div id="miembroSelectWrapper" class="sm:col-span-2 hidden">
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Miembro</label>
                            <div class="relative" id="miembroWrapper">
                                <div id="miembroTrigger"
                                    class="h-[48px] flex items-center rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] cursor-pointer select-none transition-all hover:border-primary-500">
                                    <span class="w-[24px] h-[24px] rounded-full bg-primary-100 flex items-center justify-center text-xs font-bold text-primary-700 mr-[8px] flex-shrink-0" id="miembroAvatar">?</span>
                                    <span id="miembroLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Selecciona miembro...</span>
                                    <i class="material-symbols-outlined !text-[18px] text-gray-400 transition-transform duration-200" id="miembroChevron">expand_more</i>
                                </div>
                                <input type="hidden" name="hogar_miembro_id" id="hogar_miembro_id" value="">
                                <div id="miembroDropdown" class="hidden absolute z-[9999] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[2px]">
                                    <ul class="max-h-[200px] overflow-y-auto py-[4px]">
                                        @foreach($miembros as $m)
                                        @php
                                            $pM = $m->user?->persona;
                                            $nombreM = trim(($pM?->nombres ?? '') . ' ' . ($pM?->apellido_paterno ?? '')) ?: ($m->apodo ?: 'Miembro');
                                            $inicialM = mb_strtoupper(mb_substr($nombreM, 0, 1));
                                            $fotoM = $pM?->foto_url ?? '';
                                        @endphp
                                        <li class="miembro-benef-opcion flex items-center gap-[8px] px-[12px] py-[8px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                            data-id="{{ $m->id }}" data-nombre="{{ $nombreM }}" data-inicial="{{ $inicialM }}" data-foto="{{ $fotoM }}">
                                            @if($fotoM)
                                                <img src="{{ $fotoM }}" class="w-[24px] h-[24px] rounded-full object-cover flex-shrink-0" alt="">
                                            @else
                                                <span class="w-[24px] h-[24px] rounded-full bg-primary-100 flex items-center justify-center text-xs font-bold text-primary-700 flex-shrink-0">{{ $inicialM }}</span>
                                            @endif
                                            <span class="text-sm text-black dark:text-white">{{ $nombreM }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Nombre (siempre visible) -->
                        <div class="sm:col-span-2">
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Nombre <span class="text-danger-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="benefNombre" placeholder="Nombre completo del beneficiario"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                        </div>

                        <!-- Parentesco -->
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Parentesco</label>
                            <select name="parentesco" id="benefParentesco"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                                <option value="">Sin especificar</option>
                                <option value="cónyuge">Cónyuge</option>
                                <option value="hijo">Hijo/a</option>
                                <option value="padre">Padre/Madre</option>
                                <option value="hermano">Hermano/a</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>

                        <!-- Documento identidad -->
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Doc. de Identidad</label>
                            <input type="text" name="documento_identidad" id="benefDocId" placeholder="DNI / CE"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500 font-mono">
                        </div>

                        <!-- Porcentaje -->
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Porcentaje <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="porcentaje" id="benefPorcentaje" step="0.01" min="0" max="100" value="100"
                                    class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] pl-[14px] pr-[36px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                                <span class="absolute ltr:right-[12px] rtl:left-[12px] top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">%</span>
                            </div>
                        </div>

                    </div>

                    <div class="mt-[20px] flex justify-end gap-[12px]">
                        <button type="button" onclick="cerrarModalBenef()"
                            class="px-[20px] py-[10px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 text-sm font-medium hover:border-primary-500 hover:text-primary-500 transition-all">
                            Cancelar
                        </button>
                        <button type="submit"
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

        <!-- ─── MODAL Documento Financiero ──────────────────────────────────── -->
        <div id="modalDocFinanciero" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalDoc()"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[560px] bg-white dark:bg-[#0c1427] rounded-md shadow-xl overflow-hidden mx-[16px]">
                <div class="bg-gray-50 dark:bg-[#15203c] flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-base" id="modalDocTitulo">Agregar Documento</h5>
                    <button type="button" onclick="cerrarModalDoc()" class="text-gray-400 hover:text-gray-600 transition-all">
                        <i class="material-symbols-outlined !text-[22px]">close</i>
                    </button>
                </div>

                <form id="formDocFinanciero" method="POST" enctype="multipart/form-data" class="p-[20px] max-h-[80vh] overflow-y-auto">
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
                                <input type="hidden" name="tipo_documento_financiero_id" id="tipo_documento_financiero_id" value="">
                                <div id="tipoDocDropdown" class="hidden absolute z-[9999] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[2px]">
                                    <ul class="max-h-[200px] overflow-y-auto py-[4px]">
                                        @foreach($tiposDocumento as $td)
                                        <li class="tipo-doc-fin-opcion flex items-center gap-[8px] px-[12px] py-[8px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                            data-id="{{ $td->id }}" data-nombre="{{ $td->nombre }}" data-icono="{{ $td->icono ?? 'description' }}">
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
                            <input type="text" name="titulo" id="docTitulo" placeholder="Ej: Estado de cuenta mayo 2026"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                        </div>

                        <!-- Período -->
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Período
                                <span class="text-gray-400 text-xs font-normal ml-[4px]">Ej: 2026-05</span>
                            </label>
                            <input type="text" name="periodo" id="docPeriodo" placeholder="2026-05"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500 font-mono">
                        </div>

                        <!-- Fecha de emisión -->
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Fecha de emisión</label>
                            <input type="date" name="fecha_emision" id="docFechaEmision"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>

                        <!-- Fecha de vencimiento -->
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Fecha de vencimiento</label>
                            <input type="date" name="fecha_vencimiento" id="docFechaVencimiento"
                                class="h-[48px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>

                        <!-- Archivo -->
                        <div class="sm:col-span-2">
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">
                                Archivo
                                <span id="archivoOpcional" class="text-gray-400 text-xs font-normal ml-[4px]">(opcional — deja vacío para mantener el actual)</span>
                                <span id="archivoRecomendado" class="text-gray-400 text-xs font-normal ml-[4px]">(PDF o imagen, máx. 20 MB)</span>
                            </label>
                            <input type="file" name="archivo" id="docArchivo" accept=".pdf,.jpg,.jpeg,.png,.gif,.webp"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-[10px] file:py-[8px] file:px-[14px] file:rounded-md
                                    file:border-0 file:text-sm file:font-medium
                                    file:bg-primary-50 file:text-primary-600
                                    dark:file:bg-[#15203c] dark:file:text-primary-400
                                    hover:file:bg-primary-100 transition-all cursor-pointer">
                        </div>

                        <!-- Notas -->
                        <div class="sm:col-span-2">
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Notas</label>
                            <textarea name="notas" id="docNotas" rows="3" placeholder="Observaciones opcionales..."
                                class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] py-[10px] block w-full outline-0 text-sm transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500 resize-none"></textarea>
                        </div>

                    </div>

                    <div class="mt-[20px] flex justify-end gap-[12px]">
                        <button type="button" onclick="cerrarModalDoc()"
                            class="px-[20px] py-[10px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 text-sm font-medium hover:border-primary-500 hover:text-primary-500 transition-all">
                            Cancelar
                        </button>
                        <button type="submit"
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
        const STORE_URL_DOC   = "{{ route('dashboard.productos-financieros.documentos.store', $p) }}";
        const UPDATE_BASE_DOC = "{{ url('/dashboard/finanzas/documentos-financieros') }}/";

        // ── Dropdown tipo documento ────────────────────────────────────────────
        (function () {
            const wrapper  = document.getElementById('tipoDocWrapper');
            const trigger  = document.getElementById('tipoDocTrigger');
            const dropdown = document.getElementById('tipoDocDropdown');
            const chevron  = document.getElementById('tipoDocChevron');
            const hidden   = document.getElementById('tipo_documento_financiero_id');
            const label    = document.getElementById('tipoDocLabel');
            const iconPrev = document.getElementById('tipoDocIconPreview');
            const opciones = document.querySelectorAll('.tipo-doc-fin-opcion');

            function open()  { dropdown.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; }
            function close() { dropdown.classList.add('hidden');    chevron.style.transform = ''; }

            trigger.addEventListener('click', () => dropdown.classList.contains('hidden') ? open() : close());
            opciones.forEach(li => {
                li.addEventListener('click', () => {
                    hidden.value         = li.dataset.id;
                    label.textContent    = li.dataset.nombre;
                    label.className      = 'text-black dark:text-white text-sm flex-1 truncate';
                    iconPrev.textContent = li.dataset.icono;
                    iconPrev.className   = 'material-symbols-outlined !text-[18px] mr-[8px] text-primary-500';
                    close();
                });
            });
            document.addEventListener('click', e => { if (!wrapper.contains(e.target)) close(); });
        })();

        // ── Modal ──────────────────────────────────────────────────────────────
        function resetDropdownTipo() {
            document.getElementById('tipo_documento_financiero_id').value = '';
            document.getElementById('tipoDocLabel').textContent  = 'Selecciona tipo...';
            document.getElementById('tipoDocLabel').className    = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
            document.getElementById('tipoDocIconPreview').textContent = 'description';
            document.getElementById('tipoDocIconPreview').className   = 'material-symbols-outlined !text-[18px] mr-[8px] text-gray-400';
        }

        function abrirModalDoc() {
            const modal = document.getElementById('modalDocFinanciero');
            const form  = document.getElementById('formDocFinanciero');

            document.getElementById('modalDocTitulo').textContent = 'Agregar Documento';
            form.action = STORE_URL_DOC;
            document.getElementById('methodSpoof').innerHTML = '';
            document.getElementById('docTitulo').value         = '';
            document.getElementById('docPeriodo').value        = '';
            document.getElementById('docFechaEmision').value   = '';
            document.getElementById('docFechaVencimiento').value = '';
            document.getElementById('docArchivo').value        = '';
            document.getElementById('docNotas').value          = '';
            document.getElementById('archivoOpcional').classList.add('hidden');
            document.getElementById('archivoRecomendado').classList.remove('hidden');
            resetDropdownTipo();

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function abrirModalEditDoc(data) {
            const modal = document.getElementById('modalDocFinanciero');
            const form  = document.getElementById('formDocFinanciero');

            document.getElementById('modalDocTitulo').textContent = 'Editar Documento';
            form.action = UPDATE_BASE_DOC + data.id;
            document.getElementById('methodSpoof').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('docTitulo').value          = data.titulo        || '';
            document.getElementById('docPeriodo').value         = data.periodo       || '';
            document.getElementById('docFechaEmision').value    = data.fecha_emision || '';
            document.getElementById('docFechaVencimiento').value = data.fecha_vencimiento || '';
            document.getElementById('docArchivo').value         = '';
            document.getElementById('docNotas').value           = data.notas         || '';
            document.getElementById('archivoOpcional').classList.remove('hidden');
            document.getElementById('archivoRecomendado').classList.add('hidden');

            const opcion = [...document.querySelectorAll('.tipo-doc-fin-opcion')]
                .find(li => li.dataset.id === data.tipo_documento_financiero_id);
            if (opcion) opcion.click();

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalDoc() {
            document.getElementById('modalDocFinanciero').classList.add('hidden');
            document.body.style.overflow = '';
        }

        @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            const docFields = ['tipo_documento_financiero_id','titulo','periodo','fecha_emision','fecha_vencimiento','archivo','notas'];
            const hasDocErrors = {{ json_encode(collect($errors->keys())->intersect(collect(['tipo_documento_financiero_id','titulo','periodo','fecha_emision','fecha_vencimiento','archivo','notas']))->isNotEmpty()) }};
            if (hasDocErrors) {
                // Cambiar al tab de documentos
                const tabBtn = document.querySelector('[data-tab="tabDocumentos"]');
                if (tabBtn) tabBtn.click();
                abrirModalDoc();
            }
        });
        @endif

        document.addEventListener('keydown', e => { if (e.key === 'Escape') { cerrarModalTxn(); cerrarModalBenef(); cerrarModalDoc(); } });
        </script>

        <script>
        // ════════════════════════════════════════════════════════════════════════
        // MODAL TRANSACCIÓN
        // ════════════════════════════════════════════════════════════════════════
        const STORE_URL_TXN   = "{{ route('dashboard.productos-financieros.transacciones.store', $p) }}";
        const UPDATE_BASE_TXN = "{{ url('/dashboard/finanzas/transacciones') }}/";
        const PRODUCTO_MONEDA_ID = {{ $p->moneda_id ?? 'null' }};
        const COLOR_HEX = { green:'#16a34a', red:'#dc2626', orange:'#ea580c', amber:'#d97706', blue:'#3b82f6', purple:'#9333ea' };

        // Dropdown tipo transacción
        (function () {
            const wrapper  = document.getElementById('tipoTxnWrapper');
            const trigger  = document.getElementById('tipoTxnTrigger');
            const dropdown = document.getElementById('tipoTxnDropdown');
            const chevron  = document.getElementById('tipoTxnChevron');
            const hidden   = document.getElementById('tipo_transaccion_id');
            const label    = document.getElementById('tipoTxnLabel');
            const dot      = document.getElementById('tipoTxnColorDot');
            const opciones = document.querySelectorAll('.tipo-txn-opcion');

            function open()  { dropdown.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; }
            function close() { dropdown.classList.add('hidden');    chevron.style.transform = ''; }

            trigger.addEventListener('click', () => dropdown.classList.contains('hidden') ? open() : close());
            opciones.forEach(li => {
                li.addEventListener('click', () => {
                    hidden.value   = li.dataset.id;
                    label.textContent = li.dataset.nombre;
                    label.className   = 'text-black dark:text-white text-sm flex-1 truncate';
                    dot.style.backgroundColor = COLOR_HEX[li.dataset.color] || '#9ca3af';
                    const esTrans = li.dataset.esTransferencia === '1';
                    document.getElementById('destinoWrapper').classList.toggle('hidden', !esTrans);
                    if (!esTrans) document.getElementById('txnProductoDestino').value = '';
                    close();
                });
            });
            document.addEventListener('click', e => { if (!wrapper.contains(e.target)) close(); });
        })();

        function resetTipoTxn() {
            document.getElementById('tipo_transaccion_id').value = '';
            document.getElementById('tipoTxnLabel').textContent  = 'Selecciona tipo...';
            document.getElementById('tipoTxnLabel').className    = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
            document.getElementById('tipoTxnColorDot').style.backgroundColor = '#d1d5db';
            document.getElementById('destinoWrapper').classList.add('hidden');
            document.getElementById('txnProductoDestino').value = '';
        }

        function abrirModalTxn() {
            const modal = document.getElementById('modalTransaccion');
            const form  = document.getElementById('formTransaccion');
            document.getElementById('modalTxnTitulo').textContent = 'Agregar Movimiento';
            form.action = STORE_URL_TXN;
            document.getElementById('methodSpoofTxn').innerHTML = '';
            document.getElementById('txnMonto').value    = '';
            document.getElementById('txnGlosa').value    = '';
            document.getElementById('txnNroOp').value    = '';
            document.getElementById('txnFecha').value    = new Date().toISOString().split('T')[0];
            document.getElementById('txnMetodoPagoId').value = '';
            if (PRODUCTO_MONEDA_ID) document.getElementById('txnMonedaId').value = PRODUCTO_MONEDA_ID;
            resetTipoTxn();
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function abrirModalEditTxn(data) {
            const modal = document.getElementById('modalTransaccion');
            const form  = document.getElementById('formTransaccion');
            document.getElementById('modalTxnTitulo').textContent = 'Editar Movimiento';
            form.action = UPDATE_BASE_TXN + data.id;
            document.getElementById('methodSpoofTxn').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('txnMonto').value    = data.monto   || '';
            document.getElementById('txnGlosa').value    = data.glosa   || '';
            document.getElementById('txnNroOp').value    = data.nro_operacion || '';
            document.getElementById('txnFecha').value    = data.fecha   || '';
            document.getElementById('txnMetodoPagoId').value = data.metodo_pago_id || '';
            document.getElementById('txnMonedaId').value = data.moneda_id || '';
            // Seleccionar tipo
            const opTipo = [...document.querySelectorAll('.tipo-txn-opcion')].find(li => li.dataset.id === data.tipo_transaccion_id);
            if (opTipo) opTipo.click();
            // Cuenta destino
            if (data.producto_destino_id) {
                document.getElementById('destinoWrapper').classList.remove('hidden');
                document.getElementById('txnProductoDestino').value = data.producto_destino_id;
            }
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalTxn() {
            document.getElementById('modalTransaccion').classList.add('hidden');
            document.body.style.overflow = '';
        }

        @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            const txnFields = ['tipo_transaccion_id','monto','moneda_id','fecha','glosa','nro_operacion','metodo_pago_id','producto_destino_id'];
            const hasTxnErrors = {{ json_encode(collect($errors->keys())->intersect(collect(['tipo_transaccion_id','monto','moneda_id','fecha','glosa','nro_operacion','metodo_pago_id','producto_destino_id']))->isNotEmpty()) }};
            if (hasTxnErrors) {
                const tabBtn = document.querySelector('[data-tab="tabMovimientos"]');
                if (tabBtn) tabBtn.click();
                abrirModalTxn();
            }
        });
        @endif

        // ════════════════════════════════════════════════════════════════════════
        // MODAL BENEFICIARIO
        // ════════════════════════════════════════════════════════════════════════
        const STORE_URL_BENEF   = "{{ route('dashboard.productos-financieros.beneficiarios.store', $p) }}";
        const UPDATE_BASE_BENEF = "{{ url('/dashboard/finanzas/beneficiarios') }}/";

        // Dropdown miembro hogar
        (function () {
            const wrapper  = document.getElementById('miembroWrapper');
            const trigger  = document.getElementById('miembroTrigger');
            const dropdown = document.getElementById('miembroDropdown');
            const chevron  = document.getElementById('miembroChevron');
            const hidden   = document.getElementById('hogar_miembro_id');
            const label    = document.getElementById('miembroLabel');
            const avatar   = document.getElementById('miembroAvatar');
            const opciones = document.querySelectorAll('.miembro-benef-opcion');

            function open()  { dropdown.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; }
            function close() { dropdown.classList.add('hidden');    chevron.style.transform = ''; }

            trigger.addEventListener('click', () => dropdown.classList.contains('hidden') ? open() : close());
            opciones.forEach(li => {
                li.addEventListener('click', () => {
                    hidden.value      = li.dataset.id;
                    label.textContent = li.dataset.nombre;
                    label.className   = 'text-black dark:text-white text-sm flex-1 truncate';
                    // Actualiza avatar y nombre libre
                    if (li.dataset.foto) {
                        avatar.outerHTML = `<img src="${li.dataset.foto}" id="miembroAvatar" class="w-[24px] h-[24px] rounded-full object-cover mr-[8px] flex-shrink-0" alt="">`;
                    } else {
                        avatar.textContent = li.dataset.inicial;
                    }
                    document.getElementById('benefNombre').value = li.dataset.nombre;
                    close();
                });
            });
            document.addEventListener('click', e => { if (wrapper && !wrapper.contains(e.target)) close(); });
        })();

        function toggleMiembroHogar(activo) {
            document.getElementById('miembroSelectWrapper').classList.toggle('hidden', !activo);
            if (!activo) {
                document.getElementById('hogar_miembro_id').value = '';
                document.getElementById('miembroLabel').textContent = 'Selecciona miembro...';
                document.getElementById('miembroLabel').className = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
            }
        }

        function abrirModalBenef() {
            const modal = document.getElementById('modalBeneficiario');
            const form  = document.getElementById('formBeneficiario');
            document.getElementById('modalBenefTitulo').textContent = 'Agregar Beneficiario';
            form.action = STORE_URL_BENEF;
            document.getElementById('methodSpoofBenef').innerHTML = '';
            document.getElementById('esMiembroToggle').checked = false;
            toggleMiembroHogar(false);
            document.getElementById('hogar_miembro_id').value = '';
            document.getElementById('benefNombre').value      = '';
            document.getElementById('benefParentesco').value  = '';
            document.getElementById('benefDocId').value       = '';
            document.getElementById('benefPorcentaje').value  = '100';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function abrirModalEditBenef(data) {
            const modal = document.getElementById('modalBeneficiario');
            const form  = document.getElementById('formBeneficiario');
            document.getElementById('modalBenefTitulo').textContent = 'Editar Beneficiario';
            form.action = UPDATE_BASE_BENEF + data.id;
            document.getElementById('methodSpoofBenef').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('benefNombre').value      = data.nombre       || '';
            document.getElementById('benefParentesco').value  = data.parentesco   || '';
            document.getElementById('benefDocId').value       = data.documento_identidad || '';
            document.getElementById('benefPorcentaje').value  = data.porcentaje   || '100';
            const esMiembro = !!(data.hogar_miembro_id);
            document.getElementById('esMiembroToggle').checked = esMiembro;
            toggleMiembroHogar(esMiembro);
            if (esMiembro && data.hogar_miembro_id) {
                document.getElementById('hogar_miembro_id').value = data.hogar_miembro_id;
                const opMiembro = [...document.querySelectorAll('.miembro-benef-opcion')].find(li => li.dataset.id === data.hogar_miembro_id);
                if (opMiembro) opMiembro.click();
            }
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalBenef() {
            document.getElementById('modalBeneficiario').classList.add('hidden');
            document.body.style.overflow = '';
        }

        @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            const benefFields = ['hogar_miembro_id','nombre','parentesco','documento_identidad','porcentaje'];
            const hasBenefErrors = {{ json_encode(collect($errors->keys())->intersect(collect(['hogar_miembro_id','nombre','parentesco','documento_identidad','porcentaje']))->isNotEmpty()) }};
            if (hasBenefErrors) {
                const tabBtn = document.querySelector('[data-tab="tabBeneficiarios"]');
                if (tabBtn) tabBtn.click();
                abrirModalBenef();
            }
        });
        @endif
        </script>
    </body>
</html>
