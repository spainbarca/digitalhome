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
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabDocumentos" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">description</i>
                                Documentos
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabBeneficiarios" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">groups</i>
                                Beneficiarios
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

                        {{-- ─── TAB 2: Movimientos (vacío, tolerante) ─────────────────── --}}
                        <div class="tab-pane" id="tabMovimientos">
                            <div class="flex items-center justify-between mb-[16px]">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Transacciones e historial de movimientos del producto.</p>
                                <button type="button" disabled title="Disponible próximamente"
                                    class="inline-flex items-center gap-[6px] h-[36px] px-[14px] rounded-md bg-gray-100 dark:bg-[#172036] text-gray-400 text-xs font-medium cursor-not-allowed">
                                    <i class="material-symbols-outlined !text-[16px]">add</i>
                                    Agregar
                                </button>
                            </div>
                            <div class="text-center py-[40px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[8px]">receipt_long</i>
                                <p class="text-gray-400 dark:text-gray-500 text-sm">Aún no hay registros.</p>
                                <p class="text-gray-400 dark:text-gray-500 text-xs mt-[4px]">Disponible próximamente.</p>
                            </div>
                        </div>

                        {{-- ─── TAB 3: Documentos (vacío, tolerante) ──────────────────── --}}
                        <div class="tab-pane" id="tabDocumentos">
                            <div class="flex items-center justify-between mb-[16px]">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Documentos asociados a este producto (extractos, vouchers, etc.).</p>
                                <button type="button" disabled title="Disponible próximamente"
                                    class="inline-flex items-center gap-[6px] h-[36px] px-[14px] rounded-md bg-gray-100 dark:bg-[#172036] text-gray-400 text-xs font-medium cursor-not-allowed">
                                    <i class="material-symbols-outlined !text-[16px]">add</i>
                                    Agregar
                                </button>
                            </div>
                            <div class="text-center py-[40px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[8px]">description</i>
                                <p class="text-gray-400 dark:text-gray-500 text-sm">Aún no hay registros.</p>
                                <p class="text-gray-400 dark:text-gray-500 text-xs mt-[4px]">Disponible próximamente.</p>
                            </div>
                        </div>

                        {{-- ─── TAB 4: Beneficiarios (vacío, tolerante) ───────────────── --}}
                        <div class="tab-pane" id="tabBeneficiarios">
                            <div class="flex items-center justify-between mb-[16px]">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Beneficiarios designados para este producto.</p>
                                <button type="button" disabled title="Disponible próximamente"
                                    class="inline-flex items-center gap-[6px] h-[36px] px-[14px] rounded-md bg-gray-100 dark:bg-[#172036] text-gray-400 text-xs font-medium cursor-not-allowed">
                                    <i class="material-symbols-outlined !text-[16px]">add</i>
                                    Agregar
                                </button>
                            </div>
                            <div class="text-center py-[40px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[8px]">groups</i>
                                <p class="text-gray-400 dark:text-gray-500 text-sm">Aún no hay registros.</p>
                                <p class="text-gray-400 dark:text-gray-500 text-xs mt-[4px]">Disponible próximamente.</p>
                            </div>
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
