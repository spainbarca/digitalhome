<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <title>{{ $prestatario->nombre_resuelto }} — Préstamo</title>
        @vite('resources/css/app.css')
        <style>
            .select2-container--default .select2-selection--single { height:44px;border-radius:6px;border-color:#e5e7eb;display:flex;align-items:center;padding:0 12px; }
            .select2-container--default .select2-selection--single .select2-selection__rendered { line-height:normal;padding:0;color:inherit;display:flex;align-items:center;gap:8px;width:100%; }
            .select2-container--default .select2-selection--single .select2-selection__arrow { height:44px;top:0; }
            .select2-container--default .select2-selection--single .select2-selection__clear { margin-right:8px; }
            .select2-dropdown { border-color:#e5e7eb;border-radius:6px;z-index:10100; }
            .select2-search--dropdown .select2-search__field { border-radius:4px;border-color:#e5e7eb;outline:none; }
            .select2-results__option { display:flex;align-items:center;gap:8px;padding:8px 12px; }
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

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $prestatario->nombre_resuelto }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.prestamos.prestatarios.index') }}" class="transition-all hover:text-primary-500">Prestatarios</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">{{ Str::limit($prestatario->nombre_resuelto, 30) }}</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif
            @if(session('error'))
            <div class="mb-[25px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            @php
                $saldo    = $prestatario->saldo;
                $simbolo  = $prestatario->moneda?->simbolo ?? '';
                $colores  = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                $bgColor  = $colores[abs(crc32($prestatario->id)) % count($colores)];
                $palabras = explode(' ', $prestatario->nombre_resuelto);
                $iniciales = mb_strtoupper(mb_substr($palabras[0] ?? '', 0, 1) . mb_substr($palabras[1] ?? '', 0, 1));
            @endphp

            {{-- ── ZONA 1: ESTADO DE CUENTA ──────────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                {{-- Panel izquierdo: perfil --}}
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] rounded-md overflow-hidden h-full flex flex-col">

                        @if($prestatario->foto_resuelta)
                            <img src="{{ $prestatario->foto_resuelta }}" class="w-full h-[160px] object-cover object-top" alt="{{ $prestatario->nombre_resuelto }}">
                        @else
                            <div class="w-full h-[120px] {{ $bgColor }} flex items-center justify-center">
                                <span class="text-white font-bold opacity-30" style="font-size:60px">{{ $iniciales ?: '?' }}</span>
                            </div>
                        @endif

                        <div class="p-[20px] flex-1 flex flex-col">
                            <div class="-mt-[28px] mb-[12px]">
                                <div class="inline-flex items-center justify-center w-[56px] h-[56px] rounded-full bg-white dark:bg-[#0c1427] ring-4 ring-white dark:ring-[#0c1427] shadow-md">
                                    <i class="material-symbols-outlined !text-[28px] text-primary-500">person</i>
                                </div>
                            </div>
                            <h4 class="!mb-[4px] font-semibold text-black dark:text-white">{{ $prestatario->nombre_resuelto }}</h4>
                            @if($prestatario->es_miembro_hogar)
                            <span class="inline-flex items-center gap-[4px] text-[11px] font-medium px-[8px] py-[2px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-500 mb-[8px] self-start">
                                <i class="material-symbols-outlined !text-[12px]">house</i> Miembro del hogar
                            </span>
                            @endif

                            <dl class="text-sm space-y-[8px] mt-[8px]">
                                <div class="flex items-center justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400 flex items-center gap-[4px]">
                                        <i class="material-symbols-outlined !text-[15px]">payments</i> Moneda
                                    </dt>
                                    <dd class="font-medium text-black dark:text-white">{{ $simbolo }} {{ $prestatario->moneda?->nombre }}</dd>
                                </div>
                                @if($prestatario->telefono)
                                <div class="flex items-center justify-between">
                                    <dt class="text-gray-500 dark:text-gray-400 flex items-center gap-[4px]">
                                        <i class="material-symbols-outlined !text-[15px]">phone</i> Teléfono
                                    </dt>
                                    <dd class="font-medium text-black dark:text-white">{{ $prestatario->telefono }}</dd>
                                </div>
                                @endif
                            </dl>

                            @if($prestatario->notas)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-[12px] bg-gray-50 dark:bg-[#15203c] rounded-md p-[10px]">{{ $prestatario->notas }}</p>
                            @endif

                            <div class="flex-1"></div>
                            <div class="flex items-center gap-[8px] mt-[16px] pt-[14px] border-t border-gray-100 dark:border-[#172036]">
                                <a href="{{ route('dashboard.prestamos.prestatarios.edit', $prestatario) }}"
                                    class="flex-1 text-center rounded-md font-medium px-[10px] py-[7px] bg-primary-500 text-white hover:bg-primary-400 text-xs transition-all">
                                    <i class="material-symbols-outlined !text-[14px] align-middle mr-[2px]">edit</i> Editar
                                </a>
                                <form method="POST" action="{{ route('dashboard.prestamos.prestatarios.destroy', $prestatario) }}" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Eliminar a «{{ addslashes($prestatario->nombre_resuelto) }}»?')"
                                        class="w-full rounded-md font-medium px-[10px] py-[7px] bg-danger-500 text-white hover:bg-danger-400 text-xs transition-all">
                                        <i class="material-symbols-outlined !text-[14px] align-middle mr-[2px]">delete</i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel derecho: Estado de cuenta --}}
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md h-full">
                        <h6 class="!mb-[18px] font-semibold flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">account_balance_wallet</i>
                            Estado de Cuenta
                        </h6>

                        {{-- Saldo principal --}}
                        <div class="rounded-md p-[20px] mb-[18px] {{ $saldo > 0 ? 'bg-danger-50 dark:bg-danger-900/20' : 'bg-success-50 dark:bg-success-900/20' }}">
                            <p class="text-xs font-semibold uppercase tracking-wide {{ $saldo > 0 ? 'text-danger-600' : 'text-success-600' }} mb-[4px]">
                                {{ $saldo > 0 ? 'Deuda pendiente' : 'Sin deuda' }}
                            </p>
                            <div class="flex items-end gap-[8px]">
                                <span class="text-3xl font-bold {{ $saldo > 0 ? 'text-danger-500' : 'text-success-500' }}">
                                    {{ $simbolo }} {{ number_format(abs($saldo), 2) }}
                                </span>
                                <span class="text-xs text-gray-400 mb-[4px]">{{ $prestatario->moneda?->codigo }}</span>
                            </div>
                        </div>

                        {{-- Mini-stats de totales --}}
                        <div class="grid grid-cols-3 gap-[12px]">
                            <div class="rounded-md border border-danger-100 dark:border-[#172036] p-[14px] text-center">
                                <div class="w-[36px] h-[36px] rounded-full bg-danger-100 flex items-center justify-center mx-auto mb-[8px]">
                                    <i class="material-symbols-outlined !text-[18px] text-danger-500">arrow_upward</i>
                                </div>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-[2px] uppercase tracking-wide">Total Prestado</p>
                                <p class="font-bold text-danger-500 text-sm">{{ $simbolo }} {{ number_format($totalPrestado, 2) }}</p>
                            </div>
                            <div class="rounded-md border border-success-100 dark:border-[#172036] p-[14px] text-center">
                                <div class="w-[36px] h-[36px] rounded-full bg-success-100 flex items-center justify-center mx-auto mb-[8px]">
                                    <i class="material-symbols-outlined !text-[18px] text-success-500">arrow_downward</i>
                                </div>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-[2px] uppercase tracking-wide">Total Pagado</p>
                                <p class="font-bold text-success-500 text-sm">{{ $simbolo }} {{ number_format($totalPagado, 2) }}</p>
                            </div>
                            <div class="rounded-md border border-orange-100 dark:border-[#172036] p-[14px] text-center">
                                <div class="w-[36px] h-[36px] rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-[8px]">
                                    <i class="material-symbols-outlined !text-[18px] text-orange-500">percent</i>
                                </div>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-[2px] uppercase tracking-wide">Descontado</p>
                                <p class="font-bold text-orange-500 text-sm">{{ $simbolo }} {{ number_format($totalDescontado, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── ZONA 2: TIMELINE DE MOVIMIENTOS ───────────────────────────── --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">

                <div class="flex items-center justify-between mb-[20px]">
                    <h6 class="!mb-0 font-semibold flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">receipt_long</i>
                        Movimientos
                        <span class="text-[11px] font-bold bg-gray-100 dark:bg-[#172036] text-gray-500 dark:text-gray-400 px-[7px] py-[1px] rounded-full">{{ $conSaldo->count() }}</span>
                    </h6>
                    <button type="button" onclick="abrirMovimiento()"
                        class="inline-flex items-center gap-[6px] h-[36px] px-[14px] rounded-md bg-primary-500 text-white text-xs font-medium hover:bg-primary-400 transition-all">
                        <i class="material-symbols-outlined !text-[16px]">add</i>
                        Nuevo Movimiento
                    </button>
                </div>

                @if($conSaldo->isEmpty())
                <div class="text-center py-[50px]">
                    <i class="material-symbols-outlined !text-[56px] text-gray-200 dark:text-gray-700 block mb-[10px]">receipt_long</i>
                    <p class="text-gray-400 dark:text-gray-500 text-sm">Aún no hay movimientos registrados.</p>
                    <button type="button" onclick="abrirMovimiento()"
                        class="mt-[14px] inline-flex items-center gap-[6px] h-[36px] px-[16px] rounded-md border border-primary-500 text-primary-500 text-xs font-medium hover:bg-primary-500 hover:text-white transition-all">
                        <i class="material-symbols-outlined !text-[15px]">add</i>
                        Registrar primer movimiento
                    </button>
                </div>
                @else

                {{-- Timeline --}}
                <div class="relative pt-[10px] pb-[10px]">
                    {{-- Línea vertical --}}
                    <span class="block absolute top-0 bottom-0 ltr:left-[6px] rtl:right-[6px] ltr:md:left-[150px] rtl:md:right-[150px] mt-[5px] ltr:border-l rtl:border-r border-dashed border-gray-100 dark:border-[#172036]"></span>

                    @foreach($conSaldo as $mov)
                    @php
                        $tipoCfg = match($mov->tipo) {
                            'cargo'     => ['dot' => 'bg-danger-500',  'badge' => 'bg-danger-100 text-danger-700',  'monto' => 'text-danger-500',  'icon' => 'arrow_upward',   'label' => 'Cargo'],
                            'abono'     => ['dot' => 'bg-success-500', 'badge' => 'bg-success-100 text-success-700','monto' => 'text-success-500', 'icon' => 'arrow_downward', 'label' => 'Abono'],
                            default     => ['dot' => 'bg-orange-500',  'badge' => 'bg-orange-100 text-orange-700',  'monto' => 'text-orange-500',  'icon' => 'percent',        'label' => 'Descuento'],
                        };
                        $conceptoNombre  = $mov->conceptoPago?->nombre ?? null;
                        $catIcono        = $mov->conceptoPago?->categoriaConcepto?->icono ?? 'payments';
                        $imagenUrl       = $mov->conceptoPago?->imagen_url ?? null;
                        $comprobanteUrl  = $mov->comprobante_path ? asset('storage/' . $mov->comprobante_path) : null;
                        $signoDisplay    = $mov->tipo === 'cargo' ? '+' : '−';
                    @endphp

                    <div class="relative ltr:pl-[25px] rtl:pr-[25px] ltr:md:pl-[180px] rtl:md:pr-[180px] mb-[24px] last:mb-0">
                        {{-- Punto del timeline --}}
                        <span class="block absolute top-[4px] ltr:left-0 rtl:right-0 ltr:md:left-[144px] rtl:md:right-[144px] w-[12px] h-[12px] rounded-full {{ $tipoCfg['dot'] }}"></span>

                        {{-- Columna fecha --}}
                        <span class="md:absolute md:top-0 ltr:md:left-0 rtl:md:right-0 text-xs block mb-[8px] md:mb-0 w-[130px] ltr:md:text-right rtl:md:text-left text-gray-500 dark:text-gray-400 leading-[1.4]">
                            {{ $mov->fecha?->format('d/m/Y') ?? '—' }}
                            <span class="block text-[10px] text-gray-400 mt-[2px]">
                                Saldo: <span class="{{ $mov->saldo_corriente > 0 ? 'text-danger-500' : 'text-success-500' }} font-semibold">{{ number_format($mov->saldo_corriente, 2) }}</span>
                            </span>
                        </span>

                        {{-- Contenido del movimiento --}}
                        <div class="flex items-start justify-between gap-[12px]">
                            <div class="min-w-0 flex-1">
                                {{-- Tipo badge + concepto --}}
                                <div class="flex items-center gap-[8px] mb-[4px] flex-wrap">
                                    <span class="inline-flex items-center gap-[3px] text-[11px] font-semibold px-[7px] py-[2px] rounded-full {{ $tipoCfg['badge'] }}">
                                        <i class="material-symbols-outlined !text-[11px]">{{ $tipoCfg['icon'] }}</i>
                                        {{ $tipoCfg['label'] }}
                                    </span>
                                    @if($conceptoNombre)
                                        <div class="flex items-center gap-[5px]">
                                            @if($imagenUrl)
                                                <img src="{{ $imagenUrl }}" class="w-[18px] h-[18px] rounded object-cover" alt="">
                                            @else
                                                <i class="material-symbols-outlined !text-[14px] text-primary-400">{{ $catIcono }}</i>
                                            @endif
                                            <span class="text-sm font-medium text-black dark:text-white">{{ $conceptoNombre }}</span>
                                        </div>
                                    @endif
                                </div>

                                @if($mov->glosa)
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-[4px]">{{ $mov->glosa }}</p>
                                @endif

                                @if($mov->notas)
                                <p class="text-xs text-gray-400 italic mb-[4px]">{{ $mov->notas }}</p>
                                @endif

                                {{-- Método de pago + comprobante --}}
                                <div class="flex items-center gap-[10px] flex-wrap mt-[4px]">
                                    @if($mov->metodoPago)
                                    <span class="inline-flex items-center gap-[4px] text-xs text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[13px]">{{ $mov->metodoPago->icono ?? 'payment' }}</i>
                                        {{ $mov->metodoPago->nombre }}
                                    </span>
                                    @endif
                                    @if($comprobanteUrl)
                                    <a href="{{ $comprobanteUrl }}" target="_blank"
                                        class="inline-flex items-center gap-[4px] text-xs text-primary-500 hover:text-primary-400 transition-all">
                                        <i class="material-symbols-outlined !text-[13px]">attach_file</i>
                                        Ver comprobante
                                    </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Monto + acciones --}}
                            <div class="flex-shrink-0 text-right">
                                <span class="text-lg font-bold {{ $tipoCfg['monto'] }}">
                                    {{ $signoDisplay }} {{ $simbolo }} {{ number_format($mov->monto, 2) }}
                                </span>
                                <div class="flex items-center gap-[6px] justify-end mt-[8px]">
                                    <button type="button"
                                        onclick="abrirMovimientoEditar({{ json_encode([
                                            'id'               => $mov->id,
                                            'tipo'             => $mov->tipo,
                                            'concepto_pago_id' => $mov->concepto_pago_id,
                                            'monto'            => (float) $mov->monto,
                                            'glosa'            => $mov->glosa,
                                            'fecha'            => $mov->fecha?->format('Y-m-d'),
                                            'metodo_pago_id'   => $mov->metodo_pago_id,
                                            'notas'            => $mov->notas,
                                            'comprobante_url'  => $comprobanteUrl,
                                        ]) }})"
                                        class="h-[28px] w-[28px] inline-flex items-center justify-center rounded-md bg-gray-50 dark:bg-[#172036] text-gray-500 hover:bg-gray-100 transition-all" title="Editar">
                                        <i class="material-symbols-outlined !text-[13px]">edit</i>
                                    </button>
                                    <form method="POST" action="{{ route('dashboard.prestamos.prestatarios.movimientos.destroy', [$prestatario, $mov]) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Eliminar este movimiento?')"
                                            class="h-[28px] w-[28px] inline-flex items-center justify-center rounded-md bg-danger-50 dark:bg-[#172036] text-danger-500 hover:bg-danger-100 transition-all" title="Eliminar">
                                            <i class="material-symbols-outlined !text-[13px]">delete</i>
                                        </button>
                                    </form>
                                </div>
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

        {{-- ─── MODAL Movimiento ───────────────────────────────────────────────── --}}
        <div id="modalMov" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarMovimiento()"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[560px] max-h-[90vh] overflow-y-auto bg-white dark:bg-[#0c1427] rounded-md shadow-xl mx-[16px]">
                <div class="sticky top-0 z-10 bg-gray-50 dark:bg-[#15203c] flex items-center justify-between p-[18px] border-b border-gray-100 dark:border-[#172036]">
                    <h5 class="!mb-0 text-base flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">receipt_long</i>
                        <span id="modalMovTitulo">Nuevo Movimiento</span>
                    </h5>
                    <button type="button" onclick="cerrarMovimiento()" class="text-gray-400 hover:text-gray-600 transition-all">
                        <i class="material-symbols-outlined !text-[20px]">close</i>
                    </button>
                </div>
                <form id="formMov" method="POST" enctype="multipart/form-data" class="p-[18px]">
                    @csrf
                    <span id="movMethodSpoof"></span>

                    <div class="grid grid-cols-1 gap-[14px]">

                        {{-- Tipo --}}
                        <div>
                            <label class="mb-[8px] text-black dark:text-white font-medium block text-sm">Tipo <span class="text-danger-500">*</span></label>
                            <div class="grid grid-cols-3 gap-[8px]">
                                <button type="button" data-tipo="cargo" class="tipo-btn h-[44px] rounded-md border flex items-center justify-center gap-[5px] text-sm font-medium transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">arrow_upward</i> Cargo
                                </button>
                                <button type="button" data-tipo="abono" class="tipo-btn h-[44px] rounded-md border flex items-center justify-center gap-[5px] text-sm font-medium transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">arrow_downward</i> Abono
                                </button>
                                <button type="button" data-tipo="descuento" class="tipo-btn h-[44px] rounded-md border flex items-center justify-center gap-[5px] text-sm font-medium transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">percent</i> Descuento
                                </button>
                            </div>
                            <input type="hidden" name="tipo" id="movTipo" value="cargo">
                        </div>

                        {{-- Concepto de pago --}}
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">
                                Concepto de Pago
                                <span class="text-xs text-gray-400 font-normal ml-[4px]">Opcional — auto-completa el monto</span>
                            </label>
                            <select name="concepto_pago_id" id="movConcepto">
                                <option value="">Sin concepto...</option>
                                @foreach($conceptosPago as $cp)
                                <option value="{{ $cp->id }}"
                                    data-icono="{{ $cp->categoriaConcepto?->icono ?? 'payments' }}"
                                    data-imagen="{{ $cp->imagen_url ?? '' }}"
                                    data-precio="{{ $cp->precio_referencial ?? '' }}">
                                    {{ $cp->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Monto y Fecha --}}
                        <div class="grid grid-cols-2 gap-[14px]">
                            <div>
                                <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Monto <span class="text-danger-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute ltr:left-[12px] rtl:right-[12px] top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-400">{{ $simbolo }}</span>
                                    <input type="number" name="monto" id="movMonto" step="0.01" min="0.01" placeholder="0.00"
                                        class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] ltr:pl-[32px] rtl:pr-[32px] pr-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                                </div>
                            </div>
                            <div>
                                <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Fecha <span class="text-danger-500">*</span></label>
                                <input type="date" name="fecha" id="movFecha"
                                    class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                            </div>
                        </div>

                        {{-- Glosa --}}
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Glosa</label>
                            <input type="text" name="glosa" id="movGlosa" maxlength="255" placeholder="Ej: yapeo deuda enero..."
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                        </div>

                        {{-- Método de pago --}}
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Método de Pago</label>
                            <select name="metodo_pago_id" id="movMetodo">
                                <option value="">Sin especificar...</option>
                                @foreach($metodosPago as $mp)
                                <option value="{{ $mp->id }}"
                                    data-icono="{{ $mp->icono ?? 'payment' }}"
                                    data-logo="{{ $mp->logo ? asset('storage/' . $mp->logo) : '' }}">
                                    {{ $mp->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Comprobante --}}
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">
                                Comprobante <span class="text-xs text-gray-400 font-normal">(JPG/PNG/PDF — máx. 5 MB)</span>
                            </label>
                            <div id="movComprobanteActualWrap" class="hidden mb-[8px] flex items-center gap-[8px]">
                                <a id="movComprobanteActualLink" href="#" target="_blank"
                                    class="inline-flex items-center gap-[4px] text-xs text-primary-500 hover:text-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[14px]">open_in_new</i>
                                    Ver comprobante actual
                                </a>
                                <span class="text-xs text-gray-400">— Sube uno nuevo para reemplazarlo.</span>
                            </div>
                            <div id="movComprobantePreviewWrap" class="hidden mb-[8px]">
                                <img id="movComprobantePreview" src="" alt="Preview"
                                    class="max-w-[180px] h-[100px] object-contain rounded-md border border-gray-200 dark:border-[#172036]">
                            </div>
                            <input type="file" name="comprobante" id="movComprobante"
                                accept="image/*,.pdf"
                                onchange="previewComprobante(this)"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-[10px] file:py-[7px] file:px-[12px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-600 dark:file:bg-[#15203c] dark:file:text-primary-400 hover:file:bg-primary-100 transition-all cursor-pointer">
                        </div>

                        {{-- Notas --}}
                        <div>
                            <label class="mb-[6px] text-black dark:text-white font-medium block text-sm">Notas</label>
                            <textarea name="notas" id="movNotas" rows="2" placeholder="Opcional..."
                                class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] py-[10px] block w-full outline-0 text-sm transition-all focus:border-primary-500 resize-none"></textarea>
                        </div>

                    </div>

                    <div class="mt-[16px] flex justify-end gap-[10px]">
                        <button type="button" onclick="cerrarMovimiento()"
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

        @include('partials.front.scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        const STORE_MOV  = "{{ route('dashboard.prestamos.prestatarios.movimientos.store', $prestatario) }}";
        const BASE_MOV   = "{{ url('/dashboard/finanzas/prestamos/prestatarios/' . $prestatario->id . '/movimientos') }}/";
        const HOY        = "{{ date('Y-m-d') }}";

        // ── Tipo selector ─────────────────────────────────────────────────────
        const TIPO_CFG = {
            cargo:     { border: 'border-danger-500',  bg: 'bg-danger-50',  text: 'text-danger-600'  },
            abono:     { border: 'border-success-500', bg: 'bg-success-50', text: 'text-success-600' },
            descuento: { border: 'border-orange-500',  bg: 'bg-orange-50',  text: 'text-orange-600'  },
        };

        function selectTipo(tipo) {
            document.getElementById('movTipo').value = tipo;
            const base = 'tipo-btn h-[44px] rounded-md border flex items-center justify-center gap-[5px] text-sm font-medium transition-all';
            document.querySelectorAll('.tipo-btn').forEach(btn => {
                const t = btn.dataset.tipo;
                btn.className = t === tipo
                    ? base + ' ' + TIPO_CFG[t].border + ' ' + TIPO_CFG[t].bg + ' ' + TIPO_CFG[t].text
                    : base + ' border-gray-200 text-gray-500 hover:border-gray-400';
            });
        }

        document.querySelectorAll('.tipo-btn').forEach(btn => {
            btn.addEventListener('click', () => selectTipo(btn.dataset.tipo));
        });

        // ── Modal helpers ─────────────────────────────────────────────────────
        function abrirMovimiento() {
            resetModalMov();
            document.getElementById('modalMovTitulo').textContent = 'Nuevo Movimiento';
            document.getElementById('formMov').action = STORE_MOV;
            document.getElementById('movMethodSpoof').innerHTML = '';
            document.getElementById('modalMov').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function abrirMovimientoEditar(data) {
            resetModalMov();
            document.getElementById('modalMovTitulo').textContent = 'Editar Movimiento';
            document.getElementById('formMov').action = BASE_MOV + data.id;
            document.getElementById('movMethodSpoof').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            selectTipo(data.tipo || 'cargo');
            document.getElementById('movMonto').value = data.monto || '';
            document.getElementById('movFecha').value = data.fecha || '';
            document.getElementById('movGlosa').value = data.glosa || '';
            document.getElementById('movNotas').value = data.notas || '';

            if (data.concepto_pago_id) {
                $('#movConcepto').val(data.concepto_pago_id).trigger('change');
            }
            if (data.metodo_pago_id) {
                $('#movMetodo').val(data.metodo_pago_id).trigger('change');
            }

            if (data.comprobante_url) {
                const wrap = document.getElementById('movComprobanteActualWrap');
                const link = document.getElementById('movComprobanteActualLink');
                link.href = data.comprobante_url;
                wrap.classList.remove('hidden');
            }

            document.getElementById('modalMov').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarMovimiento() {
            document.getElementById('modalMov').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function resetModalMov() {
            document.getElementById('formMov').reset();
            document.getElementById('movTipo').value = 'cargo';
            document.getElementById('movFecha').value = HOY;
            document.getElementById('movComprobanteActualWrap').classList.add('hidden');
            document.getElementById('movComprobantePreviewWrap').classList.add('hidden');
            if (typeof $ !== 'undefined') {
                $('#movConcepto').val(null).trigger('change');
                $('#movMetodo').val(null).trigger('change');
            }
            selectTipo('cargo');
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && !document.getElementById('modalMov').classList.contains('hidden')) {
                cerrarMovimiento();
            }
        });

        // ── Preview comprobante ───────────────────────────────────────────────
        function previewComprobante(input) {
            const wrap    = document.getElementById('movComprobantePreviewWrap');
            const preview = document.getElementById('movComprobantePreview');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = e => { preview.src = e.target.result; wrap.classList.remove('hidden'); };
                    reader.readAsDataURL(file);
                } else {
                    wrap.classList.add('hidden');
                }
            } else {
                wrap.classList.add('hidden');
            }
        }

        // ── Select2 ───────────────────────────────────────────────────────────
        $(function () {
            function conceptoTpl(opt) {
                if (!opt.id) return opt.text;
                const img   = opt.element?.dataset?.imagen;
                const icono = opt.element?.dataset?.icono || 'payments';
                const thumb = img
                    ? `<img src="${img}" style="width:22px;height:22px;border-radius:3px;object-fit:cover;">`
                    : `<i class="material-symbols-outlined" style="font-size:16px;color:#4f88e4;">${icono}</i>`;
                return $(`<span style="display:flex;align-items:center;gap:8px;">${thumb}<span>${opt.text}</span></span>`);
            }

            $('#movConcepto').select2({
                placeholder: 'Sin concepto...',
                allowClear: true,
                dropdownParent: $('#modalMov'),
                templateResult: conceptoTpl,
                templateSelection: conceptoTpl,
                width: '100%',
            }).on('select2:select', function (e) {
                const precio = e.params.data.element?.dataset?.precio;
                if (precio && precio !== '' && precio !== 'null') {
                    document.getElementById('movMonto').value = parseFloat(precio).toFixed(2);
                }
            });

            function metodoPagoTpl(opt) {
                if (!opt.id) return opt.text;
                const logo  = opt.element?.dataset?.logo;
                const icono = opt.element?.dataset?.icono || 'payment';
                const thumb = logo
                    ? `<img src="${logo}" style="width:20px;height:20px;border-radius:2px;object-fit:contain;">`
                    : `<i class="material-symbols-outlined" style="font-size:16px;color:#9ca3af;">${icono}</i>`;
                return $(`<span style="display:flex;align-items:center;gap:8px;">${thumb}<span>${opt.text}</span></span>`);
            }

            $('#movMetodo').select2({
                placeholder: 'Sin especificar...',
                allowClear: true,
                dropdownParent: $('#modalMov'),
                templateResult: metodoPagoTpl,
                templateSelection: metodoPagoTpl,
                width: '100%',
            });

            // Init estado inicial
            resetModalMov();
        });
        </script>
    </body>
</html>
