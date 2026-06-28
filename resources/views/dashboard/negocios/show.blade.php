<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $negocio->nombre ?? $negocio->empresa?->razon_social ?? 'Negocio' }} — Negocio</title>
        @vite('resources/css/app.css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
        <style>
            .select2-container--default .select2-selection--single{height:42px;border:1px solid #e5e7eb;border-radius:6px;display:flex;align-items:center;padding:0 10px}
            .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:42px;color:inherit}
            .select2-container--default .select2-selection--single .select2-selection__arrow{height:42px;top:0}
            .dark .select2-container--default .select2-selection--single{background:#0c1427;border-color:#172036;color:#fff}
            .dark .select2-container--default .select2-results__option{background:#0c1427;color:#fff}
            .dark .select2-dropdown{background:#0c1427;border-color:#172036}
            .dark .select2-search--dropdown .select2-search__field{background:#15203c;border-color:#172036;color:#fff}
        </style>
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        @php
            $gradients = ['from-primary-400 to-primary-600','from-orange-400 to-orange-600','from-purple-400 to-purple-600','from-success-400 to-success-600','from-pink-400 to-pink-600'];
            $grad      = $gradients[abs(crc32($negocio->id)) % count($gradients)];
            $evColor   = $negocio->estadoNegocio?->color  ?? '#6b7280';
            $evIcono   = $negocio->estadoNegocio?->icono  ?? 'store';
            $evNombre  = $negocio->estadoNegocio?->nombre ?? 'Sin estado';
            $nombreNegocio = $negocio->nombre ?? $negocio->empresa?->razon_social ?? '—';
            $logoEmpresa   = $negocio->empresa?->logo_url ? asset('storage/'.$negocio->empresa->logo_url) : null;

            // Panel cumplimiento: merge docs con vencimiento
            $itemsCumplimiento = collect();
            foreach ($todosLosDocumentos->filter(fn($d) => $d->fecha_vencimiento) as $d) {
                $itemsCumplimiento->push([
                    'nombre'     => $d->nombre,
                    'subtipo'    => $d->tipoDocumentoNegocio?->nombre,
                    'icono'      => $d->tipoDocumentoNegocio?->icono ?? 'description',
                    'vencimiento'=> $d->fecha_vencimiento,
                ]);
            }
            foreach ($documentosLegalesRelevantes->filter(fn($d) => $d->fecha_vencimiento) as $d) {
                $itemsCumplimiento->push([
                    'nombre'     => $d->titulo,
                    'subtipo'    => $d->tipoDocumentoLegal?->nombre,
                    'icono'      => 'gavel',
                    'vencimiento'=> $d->fecha_vencimiento,
                ]);
            }
            $itemsCumplimiento = $itemsCumplimiento->sortBy('vencimiento')->values();
        @endphp

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $nombreNegocio }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.negocios.index') }}" class="transition-all hover:text-primary-500">Negocios</a>
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
            @if($errors->any())
            <div class="mb-[25px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                </ul>
            </div>
            @endif

            {{-- ── 2-col layout ─────────────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                {{-- Panel izquierdo: identidad --}}
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md h-full flex flex-col">

                        {{-- Banner --}}
                        <div class="relative rounded-md overflow-hidden mb-[20px] h-[140px] flex items-center justify-center bg-gradient-to-br {{ $grad }}">
                            @if($logoEmpresa)
                                <img src="{{ $logoEmpresa }}" class="w-[72px] h-[72px] rounded-full object-cover border-2 border-white shadow" alt="">
                            @else
                                <i class="material-symbols-outlined !text-[56px] text-white opacity-70">store</i>
                            @endif
                            <span class="absolute top-[10px] right-[10px] inline-flex items-center gap-[4px] text-[10px] font-semibold py-[2px] px-[8px] rounded-full shadow"
                                  style="background-color:{{ $evColor }}; color:#fff;">
                                <i class="material-symbols-outlined !text-[10px]">{{ $evIcono }}</i>
                                {{ $evNombre }}
                            </span>
                        </div>

                        <h6 class="font-semibold text-black dark:text-white !mb-[4px] text-center">{{ $nombreNegocio }}</h6>
                        @if($negocio->empresa?->ruc)
                        <p class="text-xs text-center text-gray-400 mb-[12px] font-mono">RUC {{ $negocio->empresa->ruc }}</p>
                        @endif

                        <div class="flex flex-wrap justify-center gap-[6px] mb-[16px]">
                            @if($negocio->regimenTributario)
                            <span class="inline-flex items-center gap-[3px] text-[10px] font-medium py-[2px] px-[8px] rounded-full bg-primary-50 dark:bg-[#1a2d4d] text-primary-600 dark:text-primary-400">
                                <i class="material-symbols-outlined !text-[11px]">receipt_long</i>
                                {{ $negocio->regimenTributario->codigo ?? $negocio->regimenTributario->nombre }}
                            </span>
                            @endif
                            @if($negocio->tipoSociedad)
                            <span class="inline-flex items-center gap-[3px] text-[10px] font-medium py-[2px] px-[8px] rounded-full bg-gray-100 dark:bg-[#15203c] text-gray-600 dark:text-gray-300">
                                {{ $negocio->tipoSociedad->sigla ?? $negocio->tipoSociedad->nombre }}
                            </span>
                            @endif
                            @if($negocio->empresa?->sector)
                            <span class="inline-flex items-center gap-[3px] text-[10px] font-medium py-[2px] px-[8px] rounded-full bg-orange-50 dark:bg-[#2a1a00] text-orange-600 dark:text-orange-300">
                                {{ $negocio->empresa->sector->nombre }}
                            </span>
                            @endif
                        </div>

                        @if($representante)
                        <div class="flex items-center gap-[10px] mb-[14px] p-[12px] bg-gray-50 dark:bg-[#15203c] rounded-md">
                            @if(isset($representante->foto_url) && $representante->foto_url)
                                <img src="{{ asset('storage/'.$representante->foto_url) }}" class="w-[36px] h-[36px] rounded-full object-cover flex-shrink-0" alt="">
                            @else
                                <span class="w-[36px] h-[36px] rounded-full bg-gray-200 dark:bg-[#172036] flex items-center justify-center flex-shrink-0">
                                    <i class="material-symbols-outlined !text-[18px] text-gray-400">person</i>
                                </span>
                            @endif
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400 mb-[1px]">Representante legal</p>
                                <p class="text-sm font-medium text-black dark:text-white truncate">
                                    {{ trim(implode(' ', array_filter([$representante->nombres ?? '', $representante->apellido_paterno ?? '']))) }}
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($negocio->empresa)
                        <div class="p-[12px] bg-gray-50 dark:bg-[#15203c] rounded-md mb-[14px]">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wide mb-[4px]">Empresa vinculada</p>
                            <a href="{{ route('dashboard.empresas.show', $negocio->empresa) }}" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
                                {{ $negocio->empresa->razon_social }}
                            </a>
                        </div>
                        @endif

                        <div class="flex-1"></div>

                        <div class="flex items-center gap-[10px] mt-[16px]">
                            <a href="{{ route('dashboard.negocios.edit', $negocio) }}"
                                class="flex-1 text-center inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <i class="material-symbols-outlined !text-[16px] align-middle mr-[4px]">edit</i>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('dashboard.negocios.destroy', $negocio) }}" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('¿Eliminar el negocio «{{ addslashes($nombreNegocio) }}»?')"
                                    class="w-full transition-all rounded-md font-medium px-[13px] py-[8px] bg-danger-500 text-white hover:bg-danger-400 text-sm">
                                    <i class="material-symbols-outlined !text-[16px] align-middle mr-[4px]">delete</i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Panel derecho: stats + cumplimiento --}}
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md h-full">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-[12px]">
                            <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[14px] text-center">
                                <i class="material-symbols-outlined !text-[24px] text-primary-500 block mb-[4px]">shopping_cart</i>
                                <span class="block text-xl font-bold text-black dark:text-white">{{ $negocio->pedidos->count() }}</span>
                                <span class="text-xs text-gray-400">Pedidos</span>
                            </div>
                            <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[14px] text-center">
                                <i class="material-symbols-outlined !text-[24px] text-orange-500 block mb-[4px]">payments</i>
                                <span class="block text-xl font-bold text-black dark:text-white">{{ $negocio->pagosNegocio->count() }}</span>
                                <span class="text-xs text-gray-400">Pagos</span>
                            </div>
                            <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[14px] text-center">
                                <i class="material-symbols-outlined !text-[24px] text-purple-500 block mb-[4px]">description</i>
                                <span class="block text-xl font-bold text-black dark:text-white">{{ $todosLosDocumentos->count() }}</span>
                                <span class="text-xs text-gray-400">Documentos</span>
                            </div>
                            <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[14px] text-center">
                                <i class="material-symbols-outlined !text-[24px] text-success-500 block mb-[4px]">account_balance</i>
                                <span class="block text-xl font-bold text-black dark:text-white">{{ $negocio->productosFinancieros->count() }}</span>
                                <span class="text-xs text-gray-400">Prod. financieros</span>
                            </div>
                        </div>

                        {{-- Panel Cumplimiento --}}
                        @if($itemsCumplimiento->isNotEmpty())
                        <div class="mt-[20px] pt-[20px] border-t border-gray-100 dark:border-[#172036]">
                            <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[6px] text-sm">
                                <i class="material-symbols-outlined !text-[18px] text-orange-500">task_alt</i>
                                Panel Cumplimiento — próximos vencimientos
                            </h6>
                            <div class="space-y-[8px]">
                                @foreach($itemsCumplimiento->take(6) as $ci)
                                @php
                                    $fv   = $ci['vencimiento'];
                                    $dias = (int) now()->startOfDay()->diffInDays($fv, false);
                                    if ($dias < 0)      { $bc = 'bg-danger-100 text-danger-700 dark:bg-danger-900/30 dark:text-danger-400'; $bt = 'Vencido';   $dc = 'bg-danger-500'; }
                                    elseif ($dias <= 30){ $bc = 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400'; $bt = 'Por vencer'; $dc = 'bg-orange-500'; }
                                    else                { $bc = 'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400'; $bt = 'Vigente';    $dc = 'bg-success-500'; }
                                @endphp
                                <div class="flex items-center gap-[10px] p-[10px] rounded-md bg-gray-50 dark:bg-[#15203c]">
                                    <span class="w-[8px] h-[8px] rounded-full flex-shrink-0 {{ $dc }}"></span>
                                    <i class="material-symbols-outlined !text-[16px] text-gray-400 flex-shrink-0">{{ $ci['icono'] }}</i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-black dark:text-white truncate">{{ $ci['nombre'] }}</p>
                                        @if($ci['subtipo']) <p class="text-[10px] text-gray-400">{{ $ci['subtipo'] }}</p> @endif
                                    </div>
                                    <div class="flex items-center gap-[8px] flex-shrink-0">
                                        <span class="text-xs text-gray-400">{{ $fv->format('d/m/Y') }}</span>
                                        <span class="inline-flex items-center text-[10px] font-semibold py-[2px] px-[7px] rounded-full {{ $bc }}">{{ $bt }}</span>
                                    </div>
                                </div>
                                @endforeach
                                @if($itemsCumplimiento->count() > 6)
                                <p class="text-xs text-gray-400 text-center pt-[4px]">y {{ $itemsCumplimiento->count() - 6 }} más — ver tab Documentos</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ── Tabs ─────────────────────────────────────────────────────────── --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-tabs" id="trezo-tabs">
                    <ul class="navs mb-[20px] border-b border-gray-100 dark:border-[#172036] flex flex-wrap gap-y-[4px]">
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabResumen" class="nav-link active flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">summarize</i> Resumen
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabPedidos" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">shopping_cart</i> Pedidos
                                @if($negocio->pedidos->count()) <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-primary-500 text-white rounded-full">{{ $negocio->pedidos->count() }}</span> @endif
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabPagos" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">payments</i> Pagos
                                @if($negocio->pagosNegocio->count()) <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-orange-500 text-white rounded-full">{{ $negocio->pagosNegocio->count() }}</span> @endif
                            </button>
                        </li>
                        <li class="nav-item inline-block ltr:mr-[24px] rtl:ml-[24px]">
                            <button type="button" data-tab="tabDocumentos" class="nav-link flex items-center gap-[6px] pb-[10px] transition-all relative font-medium text-sm">
                                <i class="material-symbols-outlined !text-[18px]">description</i> Documentos
                                @if($todosLosDocumentos->count()) <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-purple-500 text-white rounded-full">{{ $todosLosDocumentos->count() }}</span> @endif
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- ─── TAB: Resumen ──────────────────────────────────────────── --}}
                        <div class="tab-pane active" id="tabResumen">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-[20px]">
                                <div>
                                    <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[6px] text-sm">
                                        <i class="material-symbols-outlined !text-[18px] text-primary-500">info</i> Datos del negocio
                                    </h6>
                                    <div class="space-y-[8px]">
                                        <div class="flex items-center justify-between py-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <span class="text-xs text-gray-400">Empresa</span>
                                            <span class="text-sm font-medium text-black dark:text-white">{{ $negocio->empresa?->razon_social ?? '—' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between py-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <span class="text-xs text-gray-400">Régimen tributario</span>
                                            <span class="text-sm font-medium text-black dark:text-white">{{ $negocio->regimenTributario?->nombre ?? '—' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between py-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <span class="text-xs text-gray-400">Tipo de sociedad</span>
                                            <span class="text-sm font-medium text-black dark:text-white">{{ $negocio->tipoSociedad?->nombre ?? '—' }}</span>
                                        </div>
                                        @if($negocio->ciiu)
                                        <div class="flex items-center justify-between py-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <span class="text-xs text-gray-400">CIIU</span>
                                            <span class="text-sm font-mono text-black dark:text-white">{{ $negocio->ciiu }}</span>
                                        </div>
                                        @endif
                                        @if($negocio->fecha_constitucion)
                                        <div class="flex items-center justify-between py-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <span class="text-xs text-gray-400">Constitución</span>
                                            <span class="text-sm text-black dark:text-white">{{ $negocio->fecha_constitucion->format('d/m/Y') }}</span>
                                        </div>
                                        @endif
                                        @if($negocio->fecha_inicio_actividades)
                                        <div class="flex items-center justify-between py-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <span class="text-xs text-gray-400">Inicio de actividades</span>
                                            <span class="text-sm text-black dark:text-white">{{ $negocio->fecha_inicio_actividades->format('d/m/Y') }}</span>
                                        </div>
                                        @endif
                                        @if($negocio->partida_registral)
                                        <div class="flex items-center justify-between py-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <span class="text-xs text-gray-400">Partida registral</span>
                                            <span class="text-sm font-mono text-black dark:text-white">{{ $negocio->partida_registral }}</span>
                                        </div>
                                        @endif
                                        @if($negocio->oficina_registral)
                                        <div class="flex items-center justify-between py-[8px]">
                                            <span class="text-xs text-gray-400">Oficina registral</span>
                                            <span class="text-sm text-black dark:text-white">{{ $negocio->oficina_registral }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @if($negocio->observaciones)
                                    <div class="mt-[16px] p-[14px] bg-gray-50 dark:bg-[#15203c] rounded-md">
                                        <p class="text-xs text-gray-400 mb-[6px]">Observaciones</p>
                                        <p class="text-sm text-black dark:text-white whitespace-pre-line leading-[1.7]">{{ $negocio->observaciones }}</p>
                                    </div>
                                    @endif
                                </div>

                                <div>
                                    <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[6px] text-sm">
                                        <i class="material-symbols-outlined !text-[18px] text-success-500">account_balance</i>
                                        Productos financieros ({{ $negocio->productosFinancieros->count() }})
                                    </h6>
                                    @forelse($negocio->productosFinancieros as $pf)
                                    <div class="flex items-center justify-between p-[12px] rounded-md bg-gray-50 dark:bg-[#15203c] mb-[8px]">
                                        <div class="flex items-center gap-[10px] min-w-0">
                                            <i class="material-symbols-outlined !text-[20px] text-primary-500 flex-shrink-0">{{ $pf->tipoProductoFinanciero?->icono ?? 'credit_card' }}</i>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-black dark:text-white truncate">{{ $pf->nombre }}</p>
                                                <p class="text-[10px] text-gray-400">{{ $pf->entidadFinanciera?->nombre ?? '—' }} · {{ $pf->tipoProductoFinanciero?->nombre ?? '—' }}</p>
                                            </div>
                                        </div>
                                        @if(isset($pf->saldo_actual) && $pf->saldo_actual !== null)
                                        <span class="text-sm font-semibold text-black dark:text-white flex-shrink-0 ml-[8px]">
                                            {{ $pf->moneda?->simbolo }} {{ number_format((float)$pf->saldo_actual, 2) }}
                                        </span>
                                        @endif
                                    </div>
                                    @empty
                                    <p class="text-sm text-gray-400">Sin productos financieros vinculados.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- ─── TAB: Pedidos ──────────────────────────────────────────── --}}
                        <div class="tab-pane hidden" id="tabPedidos">
                            <div class="flex items-center justify-between mb-[20px]">
                                <h6 class="font-semibold text-black dark:text-white flex items-center gap-[8px] !mb-0">
                                    <i class="material-symbols-outlined !text-[18px] text-primary-500">shopping_cart</i>
                                    Pedidos ({{ $negocio->pedidos->count() }})
                                </h6>
                                <button type="button" onclick="abrirModalPedido()"
                                    class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i> Nuevo pedido
                                </button>
                            </div>

                            @if($negocio->pedidos->isEmpty())
                            <div class="text-center py-[40px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">shopping_cart</i>
                                <p class="text-sm text-gray-400">Sin pedidos registrados.</p>
                            </div>
                            @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-[#172036]">
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">Proveedor</th>
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">N°</th>
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">Fecha</th>
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">Descripción</th>
                                            <th class="text-right text-xs text-gray-400 py-[10px] pr-[12px]">Total</th>
                                            <th class="text-center text-xs text-gray-400 py-[10px] pr-[12px]">Docs</th>
                                            <th class="text-center text-xs text-gray-400 py-[10px]">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($negocio->pedidos as $pedido)
                                        <tr class="border-b border-gray-50 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                            <td class="py-[10px] pr-[12px]">
                                                @php $prov = $pedido->proveedorNegocio; @endphp
                                                @if($prov)
                                                <div class="flex items-center gap-[6px]">
                                                    @if($prov->logo_resuelto)
                                                        <img src="{{ $prov->logo_resuelto }}" class="w-[24px] h-[24px] rounded object-cover flex-shrink-0" alt="">
                                                    @else
                                                        <span class="w-[24px] h-[24px] rounded bg-gray-200 dark:bg-[#172036] flex items-center justify-center flex-shrink-0">
                                                            <i class="material-symbols-outlined !text-[12px] text-gray-400">local_shipping</i>
                                                        </span>
                                                    @endif
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-medium text-black dark:text-white truncate max-w-[100px]">{{ $prov->sigla_resuelta ?? $prov->nombre }}</p>
                                                    </div>
                                                </div>
                                                @else
                                                <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="py-[10px] pr-[12px] text-xs font-mono text-gray-500">{{ $pedido->numero ?? '—' }}</td>
                                            <td class="py-[10px] pr-[12px] text-xs text-gray-500">{{ $pedido->fecha?->format('d/m/Y') ?? '—' }}</td>
                                            <td class="py-[10px] pr-[12px]">
                                                <p class="text-xs text-black dark:text-white max-w-[160px] truncate" title="{{ $pedido->descripcion }}">
                                                    {{ $pedido->descripcion ? Str::limit($pedido->descripcion, 50) : '—' }}
                                                </p>
                                            </td>
                                            <td class="py-[10px] pr-[12px] text-right">
                                                @if($pedido->total !== null)
                                                <span class="text-sm font-semibold text-black dark:text-white">
                                                    {{ $pedido->moneda?->simbolo }} {{ number_format((float)$pedido->total, 2) }}
                                                </span>
                                                @else
                                                <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="py-[10px] pr-[12px] text-center">
                                                @php $ndoc = $pedido->documentosNegocio?->count() ?? 0; @endphp
                                                <div class="flex items-center justify-center gap-[4px]">
                                                    @if($ndoc)
                                                    <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-purple-500 text-white rounded-full">{{ $ndoc }}</span>
                                                    @endif
                                                    <button type="button" onclick="abrirBoletaPedido('{{ $pedido->id }}')"
                                                        class="text-gray-400 hover:text-purple-500 transition-all p-[4px]" title="Adjuntar boleta">
                                                        <i class="material-symbols-outlined !text-[16px]">attach_file</i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="py-[10px] text-center">
                                                <form method="POST" action="{{ route('dashboard.pedidos.destroy', $pedido) }}" class="inline"
                                                    onsubmit="return confirm('¿Eliminar este pedido?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-danger-500 transition-all p-[4px]">
                                                        <i class="material-symbols-outlined !text-[16px]">delete</i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>

                        {{-- ─── TAB: Pagos ────────────────────────────────────────────── --}}
                        <div class="tab-pane hidden" id="tabPagos">
                            <div class="flex items-center justify-between mb-[20px]">
                                <h6 class="font-semibold text-black dark:text-white flex items-center gap-[8px] !mb-0">
                                    <i class="material-symbols-outlined !text-[18px] text-orange-500">payments</i>
                                    Pagos ({{ $negocio->pagosNegocio->count() }})
                                </h6>
                                <button type="button" onclick="abrirModalPago()"
                                    class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-md bg-orange-500 text-white text-sm font-medium hover:bg-orange-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i> Nuevo pago
                                </button>
                            </div>

                            @if($negocio->pagosNegocio->isEmpty())
                            <div class="text-center py-[40px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">payments</i>
                                <p class="text-sm text-gray-400">Sin pagos registrados.</p>
                            </div>
                            @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-[#172036]">
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">Entidad receptora</th>
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">Concepto</th>
                                            <th class="text-right text-xs text-gray-400 py-[10px] pr-[12px]">Monto</th>
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">Fecha</th>
                                            <th class="text-left text-xs text-gray-400 py-[10px] pr-[12px]">Período</th>
                                            <th class="text-center text-xs text-gray-400 py-[10px] pr-[12px]">Docs</th>
                                            <th class="text-center text-xs text-gray-400 py-[10px]">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($negocio->pagosNegocio as $pago)
                                        <tr class="border-b border-gray-50 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                            <td class="py-[10px] pr-[12px]">
                                                <span class="text-xs font-medium text-black dark:text-white">{{ $pago->entidadReceptora?->razon_social ?? '—' }}</span>
                                            </td>
                                            <td class="py-[10px] pr-[12px]">
                                                @if($pago->tipoPagoNegocio)
                                                <span class="inline-flex items-center gap-[3px] text-xs text-gray-500">
                                                    <i class="material-symbols-outlined !text-[13px]">{{ $pago->tipoPagoNegocio->icono ?? 'label' }}</i>
                                                    {{ $pago->tipoPagoNegocio->nombre }}
                                                </span>
                                                @else
                                                <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="py-[10px] pr-[12px] text-right">
                                                <span class="text-sm font-semibold text-black dark:text-white">
                                                    {{ $pago->moneda?->simbolo }} {{ number_format((float)$pago->monto, 2) }}
                                                </span>
                                            </td>
                                            <td class="py-[10px] pr-[12px] text-xs text-gray-500">{{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}</td>
                                            <td class="py-[10px] pr-[12px] text-xs font-mono text-gray-500">{{ $pago->periodo ?? '—' }}</td>
                                            <td class="py-[10px] pr-[12px] text-center">
                                                @php $ndocP = $pago->documentosNegocio?->count() ?? 0; @endphp
                                                <div class="flex items-center justify-center gap-[4px]">
                                                    @if($ndocP)
                                                    <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-[4px] text-[10px] font-bold bg-purple-500 text-white rounded-full">{{ $ndocP }}</span>
                                                    @endif
                                                    <button type="button" onclick="abrirDocumentoPago('{{ $pago->id }}')"
                                                        class="text-gray-400 hover:text-purple-500 transition-all p-[4px]" title="Adjuntar documento">
                                                        <i class="material-symbols-outlined !text-[16px]">attach_file</i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="py-[10px] text-center">
                                                <form method="POST" action="{{ route('dashboard.pagos-negocio.destroy', $pago) }}" class="inline"
                                                    onsubmit="return confirm('¿Eliminar este pago?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-danger-500 transition-all p-[4px]">
                                                        <i class="material-symbols-outlined !text-[16px]">delete</i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>

                        {{-- ─── TAB: Documentos ──────────────────────────────────────── --}}
                        <div class="tab-pane hidden" id="tabDocumentos">
                            <div class="flex items-center justify-between mb-[20px]">
                                <h6 class="font-semibold text-black dark:text-white flex items-center gap-[8px] !mb-0">
                                    <i class="material-symbols-outlined !text-[18px] text-purple-500">description</i>
                                    Documentos ({{ $todosLosDocumentos->count() }})
                                </h6>
                                <button type="button" onclick="abrirModalDocumento()"
                                    class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-md bg-purple-500 text-white text-sm font-medium hover:bg-purple-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">add</i> Nuevo documento
                                </button>
                            </div>

                            @if($todosLosDocumentos->isEmpty())
                            <div class="text-center py-[40px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 block mb-[10px]">folder_open</i>
                                <p class="text-sm text-gray-400">Sin documentos registrados.</p>
                            </div>
                            @else
                            <div class="overflow-x-auto mb-[24px]">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-[#172036]">
                                            <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Tipo</th>
                                            <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Nombre</th>
                                            <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Contexto</th>
                                            <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Emisión</th>
                                            <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Vencimiento</th>
                                            <th class="text-center text-xs text-gray-400 py-[8px] pr-[12px]">Archivo</th>
                                            <th class="text-center text-xs text-gray-400 py-[8px]">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todosLosDocumentos as $doc)
                                        @php
                                            $docBadge = '';
                                            if ($doc->fecha_vencimiento) {
                                                $dd = (int) now()->startOfDay()->diffInDays($doc->fecha_vencimiento, false);
                                                if ($dd < 0)       $docBadge = '<span class="inline-flex text-[9px] font-semibold py-[1px] px-[6px] rounded-full bg-danger-100 text-danger-700">Vencido</span>';
                                                elseif ($dd <= 30) $docBadge = '<span class="inline-flex text-[9px] font-semibold py-[1px] px-[6px] rounded-full bg-orange-100 text-orange-700">Por vencer</span>';
                                                else               $docBadge = '<span class="inline-flex text-[9px] font-semibold py-[1px] px-[6px] rounded-full bg-success-100 text-success-700">Vigente</span>';
                                            }
                                            if ($doc->pedido_id)           $ctx = 'Pedido: ' . ($doc->pedido?->numero ?? substr($doc->pedido_id, 0, 8));
                                            elseif ($doc->pago_negocio_id) $ctx = 'Pago ' . ($doc->pagoNegocio?->fecha_pago?->format('d/m/Y') ?? '');
                                            else                           $ctx = 'Negocio';
                                        @endphp
                                        <tr class="border-b border-gray-50 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                            <td class="py-[8px] pr-[12px]">
                                                @if($doc->tipoDocumentoNegocio)
                                                <span class="inline-flex items-center gap-[3px] text-xs text-gray-500">
                                                    <i class="material-symbols-outlined !text-[13px]">{{ $doc->tipoDocumentoNegocio->icono ?? 'description' }}</i>
                                                    {{ $doc->tipoDocumentoNegocio->nombre }}
                                                </span>
                                                @else
                                                <span class="text-gray-300">—</span>
                                                @endif
                                            </td>
                                            <td class="py-[8px] pr-[12px] text-sm font-medium text-black dark:text-white">{{ $doc->nombre }}</td>
                                            <td class="py-[8px] pr-[12px] text-xs text-gray-500">{{ $ctx }}</td>
                                            <td class="py-[8px] pr-[12px] text-xs text-gray-500">{{ $doc->fecha_emision?->format('d/m/Y') ?? '—' }}</td>
                                            <td class="py-[8px] pr-[12px]">
                                                @if($doc->fecha_vencimiento)
                                                <div class="flex items-center gap-[4px]">
                                                    <span class="text-xs text-gray-500">{{ $doc->fecha_vencimiento->format('d/m/Y') }}</span>
                                                    {!! $docBadge !!}
                                                </div>
                                                @else
                                                <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="py-[8px] pr-[12px] text-center">
                                                @if($doc->archivo_url)
                                                <a href="{{ $doc->archivo_url }}" target="_blank" class="text-primary-500 hover:text-primary-400">
                                                    <i class="material-symbols-outlined !text-[18px]">open_in_new</i>
                                                </a>
                                                @else
                                                <span class="text-gray-300">—</span>
                                                @endif
                                            </td>
                                            <td class="py-[8px] text-center">
                                                <form method="POST" action="{{ route('dashboard.documentos-negocio.destroy', $doc) }}" class="inline"
                                                    onsubmit="return confirm('¿Eliminar este documento?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-danger-500 p-[4px]">
                                                        <i class="material-symbols-outlined !text-[16px]">delete</i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif

                            {{-- Documentos legales relevantes (read-only) --}}
                            @if($documentosLegalesRelevantes->isNotEmpty())
                            <div class="border-t border-gray-100 dark:border-[#172036] pt-[20px]">
                                <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[6px] text-sm">
                                    <i class="material-symbols-outlined !text-[18px] text-orange-500">gavel</i>
                                    Documentos legales relevantes
                                    <span class="text-xs font-normal text-gray-400 ml-[4px]">(solo lectura)</span>
                                </h6>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-gray-100 dark:border-[#172036]">
                                                <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Tipo</th>
                                                <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Título</th>
                                                <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Número</th>
                                                <th class="text-left text-xs text-gray-400 py-[8px] pr-[12px]">Vencimiento</th>
                                                <th class="text-center text-xs text-gray-400 py-[8px]">Ver</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($documentosLegalesRelevantes as $dl)
                                            @php
                                                $dlBadge = '';
                                                if ($dl->fecha_vencimiento) {
                                                    $dd2 = (int) now()->startOfDay()->diffInDays($dl->fecha_vencimiento, false);
                                                    if ($dd2 < 0)       $dlBadge = '<span class="inline-flex text-[9px] font-semibold py-[1px] px-[6px] rounded-full bg-danger-100 text-danger-700">Vencido</span>';
                                                    elseif ($dd2 <= 30) $dlBadge = '<span class="inline-flex text-[9px] font-semibold py-[1px] px-[6px] rounded-full bg-orange-100 text-orange-700">Por vencer</span>';
                                                    else                $dlBadge = '<span class="inline-flex text-[9px] font-semibold py-[1px] px-[6px] rounded-full bg-success-100 text-success-700">Vigente</span>';
                                                }
                                            @endphp
                                            <tr class="border-b border-gray-50 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                                <td class="py-[8px] pr-[12px] text-xs text-gray-500">{{ $dl->tipoDocumentoLegal?->nombre ?? '—' }}</td>
                                                <td class="py-[8px] pr-[12px] text-sm text-black dark:text-white">{{ $dl->titulo }}</td>
                                                <td class="py-[8px] pr-[12px] text-xs font-mono text-gray-500">{{ $dl->numero_documento ?? '—' }}</td>
                                                <td class="py-[8px] pr-[12px]">
                                                    @if($dl->fecha_vencimiento)
                                                    <div class="flex items-center gap-[4px]">
                                                        <span class="text-xs text-gray-500">{{ $dl->fecha_vencimiento->format('d/m/Y') }}</span>
                                                        {!! $dlBadge !!}
                                                    </div>
                                                    @else
                                                    <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="py-[8px] text-center">
                                                    <a href="{{ route('dashboard.documentos-legales.show', $dl) }}" class="text-gray-400 hover:text-primary-500 transition-all">
                                                        <i class="material-symbols-outlined !text-[16px]">open_in_new</i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════
             MODAL: Nuevo Pedido
        ════════════════════════════════════════════════════════════════════════ --}}
        <div id="modalPedido" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalPedido()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-[16px]">
                <div class="bg-white dark:bg-[#0c1427] rounded-md shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                        <h6 class="font-semibold text-black dark:text-white !mb-0">Nuevo pedido</h6>
                        <button type="button" onclick="cerrarModalPedido()" class="text-gray-400 hover:text-gray-600 transition-all">
                            <i class="material-symbols-outlined">close</i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('dashboard.negocios.pedidos.store', $negocio) }}">
                        @csrf
                        <div class="p-[20px] space-y-[14px]">

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">
                                    Proveedor <span class="text-danger-500">*</span>
                                </label>
                                <select name="proveedor_negocio_id" id="pedidoProveedor" required
                                    class="select2-proveedor h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] block w-full outline-0">
                                    <option value="">Selecciona proveedor…</option>
                                    @foreach($proveedores as $prov)
                                    <option value="{{ $prov->id }}"
                                        data-logo="{{ $prov->logo_resuelto }}"
                                        data-sigla="{{ $prov->sigla_resuelta }}">
                                        {{ $prov->sigla_resuelta ? $prov->sigla_resuelta . ' — ' : '' }}{{ $prov->nombre ?? $prov->empresa?->razon_social }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">N° de pedido / factura</label>
                                    <input type="text" name="numero" placeholder="Ej: F001-0001234"
                                        class="h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Fecha <span class="text-danger-500">*</span></label>
                                    <input type="date" name="fecha" required
                                        class="h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Descripción <span class="text-danger-500">*</span></label>
                                <textarea name="descripcion" rows="2" required placeholder="Detalle de los bienes o servicios…"
                                    class="w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] py-[10px] text-sm outline-0 focus:border-primary-500"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Moneda</label>
                                    <select name="moneda_id"
                                        class="h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] block w-full outline-0 text-sm text-black dark:text-white px-[14px]">
                                        <option value="">— moneda —</option>
                                        @foreach($monedas as $mon)
                                        <option value="{{ $mon->id }}" {{ $mon->moneda_local ? 'selected' : '' }}>
                                            {{ $mon->simbolo }} {{ $mon->codigo }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Total</label>
                                    <input type="number" name="total" step="0.01" min="0" placeholder="0.00"
                                        class="h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Observaciones</label>
                                <textarea name="observaciones" rows="2"
                                    class="w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] py-[10px] text-sm outline-0 focus:border-primary-500"></textarea>
                            </div>

                        </div>
                        <div class="flex items-center justify-end gap-[10px] px-[20px] pb-[20px]">
                            <button type="button" onclick="cerrarModalPedido()"
                                class="px-[16px] py-[8px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-500 hover:border-primary-500 hover:text-primary-500 transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-[16px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                Guardar pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════
             MODAL: Nuevo Pago
        ════════════════════════════════════════════════════════════════════════ --}}
        <div id="modalPago" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalPago()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-[16px]">
                <div class="bg-white dark:bg-[#0c1427] rounded-md shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                        <h6 class="font-semibold text-black dark:text-white !mb-0">Nuevo pago</h6>
                        <button type="button" onclick="cerrarModalPago()" class="text-gray-400 hover:text-gray-600 transition-all">
                            <i class="material-symbols-outlined">close</i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('dashboard.negocios.pagos.store', $negocio) }}">
                        @csrf
                        <div class="p-[20px] space-y-[14px]">

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Entidad receptora</label>
                                <select name="entidad_receptora_id" id="pagoEmpresa"
                                    class="select2-empresa h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] block w-full outline-0">
                                    <option value="">— opcional —</option>
                                    @foreach($empresas as $emp)
                                    <option value="{{ $emp->id }}"
                                        data-logo="{{ $emp->logo_url ? asset('storage/'.$emp->logo_url) : '' }}">
                                        {{ $emp->razon_social }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Tipo de pago / concepto</label>
                                <select name="tipo_pago_negocio_id" id="pagoTipo"
                                    class="select2-icono h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] block w-full outline-0">
                                    <option value="">— opcional —</option>
                                    @foreach($tiposPagoNegocio as $tp)
                                    <option value="{{ $tp->id }}" data-icono="{{ $tp->icono ?? 'label' }}">
                                        {{ $tp->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Monto <span class="text-danger-500">*</span></label>
                                    <input type="number" name="monto" step="0.01" min="0" placeholder="0.00" required
                                        class="h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Moneda</label>
                                    <select name="moneda_id"
                                        class="h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] block w-full outline-0 text-sm text-black dark:text-white px-[14px]">
                                        <option value="">— moneda —</option>
                                        @foreach($monedas as $mon)
                                        <option value="{{ $mon->id }}" {{ $mon->moneda_local ? 'selected' : '' }}>
                                            {{ $mon->simbolo }} {{ $mon->codigo }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Fecha de pago <span class="text-danger-500">*</span></label>
                                    <input type="date" name="fecha_pago" required
                                        class="h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Período <span class="text-xs font-normal text-gray-400">(AAAA-MM)</span></label>
                                    <input type="text" name="periodo" placeholder="Ej: 2026-06"
                                        class="h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm font-mono">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Observaciones</label>
                                <textarea name="observaciones" rows="2"
                                    class="w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] py-[10px] text-sm outline-0 focus:border-primary-500"></textarea>
                            </div>

                        </div>
                        <div class="flex items-center justify-end gap-[10px] px-[20px] pb-[20px]">
                            <button type="button" onclick="cerrarModalPago()"
                                class="px-[16px] py-[8px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-500 hover:border-primary-500 hover:text-primary-500 transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-[16px] py-[8px] rounded-md bg-orange-500 text-white text-sm font-medium hover:bg-orange-400 transition-all">
                                Guardar pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════
             MODAL: Documento (compartido — negocio / pedido / pago)
        ════════════════════════════════════════════════════════════════════════ --}}
        <div id="modalDocumento" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalDocumento()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-[16px]">
                <div class="bg-white dark:bg-[#0c1427] rounded-md shadow-xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                        <h6 class="font-semibold text-black dark:text-white !mb-0" id="modalDocumentoTitle">Adjuntar documento</h6>
                        <button type="button" onclick="cerrarModalDocumento()" class="text-gray-400 hover:text-gray-600 transition-all">
                            <i class="material-symbols-outlined">close</i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('dashboard.negocios.documentos.store', $negocio) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="negocio_id"     id="docNegocioId" value="">
                        <input type="hidden" name="pedido_id"       id="docPedidoId"  value="">
                        <input type="hidden" name="pago_negocio_id" id="docPagoId"    value="">

                        <div class="p-[20px] space-y-[14px]">

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Tipo de documento</label>
                                <select name="tipo_documento_negocio_id" id="docTipo"
                                    class="select2-icono-doc h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] block w-full outline-0">
                                    <option value="">— opcional —</option>
                                    @foreach($tiposDocumentoNegocio as $td)
                                    <option value="{{ $td->id }}" data-icono="{{ $td->icono ?? 'description' }}">
                                        {{ $td->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Nombre <span class="text-danger-500">*</span></label>
                                <input type="text" name="nombre" required placeholder="Ej: Contrato de suministro 2026"
                                    class="h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Archivo</label>
                                <input type="file" name="archivo" accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx"
                                    class="w-full text-sm text-gray-500 file:mr-[12px] file:py-[8px] file:px-[14px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                            </div>

                            <div class="grid grid-cols-2 gap-[12px]">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Fecha de emisión</label>
                                    <input type="date" name="fecha_emision"
                                        class="h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Fecha de vencimiento</label>
                                    <input type="date" name="fecha_vencimiento"
                                        class="h-[42px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Observaciones</label>
                                <textarea name="observaciones" rows="2"
                                    class="w-full rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] py-[10px] text-sm outline-0 focus:border-primary-500"></textarea>
                            </div>

                        </div>
                        <div class="flex items-center justify-end gap-[10px] px-[20px] pb-[20px]">
                            <button type="button" onclick="cerrarModalDocumento()"
                                class="px-[16px] py-[8px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-500 hover:border-primary-500 hover:text-primary-500 transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-[16px] py-[8px] rounded-md bg-purple-500 text-white text-sm font-medium hover:bg-purple-400 transition-all">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('partials.front.scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        // ── Tabs ─────────────────────────────────────────────────────────────────
        document.querySelectorAll('#trezo-tabs .nav-link').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#trezo-tabs .nav-link').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('#trezo-tabs .tab-pane').forEach(p => { p.classList.add('hidden'); p.classList.remove('active'); });
                btn.classList.add('active');
                const pane = document.getElementById(btn.dataset.tab);
                if (pane) { pane.classList.remove('hidden'); pane.classList.add('active'); }
            });
        });

        const _initialTab = '{{ session('activeTab', '') }}';
        if (_initialTab) {
            const _btn = document.querySelector(`[data-tab="${_initialTab}"]`);
            if (_btn) _btn.click();
        }

        // ── Select2 template functions ────────────────────────────────────────
        function fmtProveedor(o) {
            if (!o.id) return o.text;
            const lo = $(o.element).data('logo');
            const media = lo
                ? `<img src="${lo}" class="w-[22px] h-[22px] rounded object-cover mr-2 flex-shrink-0" alt="">`
                : `<i class="material-symbols-outlined !text-[18px] mr-2 flex-shrink-0">local_shipping</i>`;
            return $(`<span class="flex items-center">${media}<span>${o.text}</span></span>`);
        }
        function fmtEmpresa(o) {
            if (!o.id) return o.text;
            const lo = $(o.element).data('logo');
            const media = lo
                ? `<img src="${lo}" class="w-[22px] h-[22px] rounded object-cover mr-2 flex-shrink-0" alt="">`
                : `<i class="material-symbols-outlined !text-[18px] mr-2 flex-shrink-0">business</i>`;
            return $(`<span class="flex items-center">${media}<span>${o.text}</span></span>`);
        }
        function fmtIcono(o) {
            if (!o.id) return o.text;
            const ic = $(o.element).data('icono');
            const i  = ic ? `<i class="material-symbols-outlined !text-[18px] mr-1 align-middle flex-shrink-0">${ic}</i>` : '';
            return $(`<span class="flex items-center">${i}<span>${o.text}</span></span>`);
        }

        // ── Select2 lazy init ─────────────────────────────────────────────────
        function initPedidoS2() {
            if (!$('#pedidoProveedor').data('select2')) {
                $('#modalPedido .select2-proveedor').select2({
                    dropdownParent: $('#modalPedido'),
                    templateResult: fmtProveedor,
                    templateSelection: fmtProveedor,
                    escapeMarkup: m => m,
                    width: '100%',
                    placeholder: 'Selecciona proveedor…',
                });
            }
        }
        function initPagoS2() {
            if (!$('#pagoEmpresa').data('select2')) {
                $('#modalPago .select2-empresa').select2({
                    dropdownParent: $('#modalPago'),
                    templateResult: fmtEmpresa,
                    templateSelection: fmtEmpresa,
                    escapeMarkup: m => m,
                    width: '100%',
                    placeholder: '— opcional —',
                    allowClear: true,
                });
                $('#modalPago .select2-icono').select2({
                    dropdownParent: $('#modalPago'),
                    templateResult: fmtIcono,
                    templateSelection: fmtIcono,
                    escapeMarkup: m => m,
                    width: '100%',
                    placeholder: '— opcional —',
                    allowClear: true,
                });
            }
        }
        function initDocumentoS2() {
            if (!$('#docTipo').data('select2')) {
                $('#modalDocumento .select2-icono-doc').select2({
                    dropdownParent: $('#modalDocumento'),
                    templateResult: fmtIcono,
                    templateSelection: fmtIcono,
                    escapeMarkup: m => m,
                    width: '100%',
                    placeholder: '— opcional —',
                    allowClear: true,
                });
            }
        }

        // ── Modal: Pedido ─────────────────────────────────────────────────────
        function abrirModalPedido() {
            document.getElementById('modalPedido').classList.remove('hidden');
            initPedidoS2();
        }
        function cerrarModalPedido() {
            document.getElementById('modalPedido').classList.add('hidden');
        }

        // ── Modal: Pago ───────────────────────────────────────────────────────
        function abrirModalPago() {
            document.getElementById('modalPago').classList.remove('hidden');
            initPagoS2();
        }
        function cerrarModalPago() {
            document.getElementById('modalPago').classList.add('hidden');
        }

        // ── Modal: Documento (shared) ─────────────────────────────────────────
        function _abrirDoc(titulo, negocioId, pedidoId, pagoId) {
            document.getElementById('modalDocumentoTitle').textContent = titulo;
            document.getElementById('docNegocioId').value = negocioId || '';
            document.getElementById('docPedidoId').value  = pedidoId  || '';
            document.getElementById('docPagoId').value    = pagoId    || '';
            document.getElementById('modalDocumento').classList.remove('hidden');
            initDocumentoS2();
        }
        function abrirModalDocumento() {
            _abrirDoc('Adjuntar documento al negocio', '{{ $negocio->id }}', '', '');
        }
        function abrirBoletaPedido(pedidoId) {
            _abrirDoc('Adjuntar boleta al pedido', '', pedidoId, '');
        }
        function abrirDocumentoPago(pagoId) {
            _abrirDoc('Adjuntar documento al pago', '', '', pagoId);
        }
        function cerrarModalDocumento() {
            document.getElementById('modalDocumento').classList.add('hidden');
        }
        </script>
    </body>
</html>
