<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Visor de Viajes</title>
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
            .grupo-header[aria-expanded="false"] .grupo-chevron{transform:rotate(-90deg)}
            .grupo-header .grupo-chevron{transition:transform .2s}
        </style>
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            {{-- Breadcrumb --}}
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 flex items-center gap-[8px]">
                    <i class="material-symbols-outlined !text-[24px] text-primary-500">map</i>
                    Visor de Viajes
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
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Visor</li>
                </ol>
            </div>

            {{-- ── Filtros ──────────────────────────────────────────────────────────── --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <form method="GET" action="{{ route('dashboard.visor-viajes.index') }}" id="formFiltros">
                    <div class="flex flex-wrap items-end gap-[12px]">

                        {{-- Operador --}}
                        <div class="min-w-[200px] flex-1">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Operador</label>
                            <select name="operador_viaje_id" id="filtroOperador">
                                <option value="">Todos los operadores</option>
                                @foreach($operadoresSelect as $op)
                                <option value="{{ $op->id }}"
                                    data-logo="{{ $op->logo_resuelto }}"
                                    data-icono="{{ $op->tipoOperadorViaje?->icono }}"
                                    data-ruc="{{ $op->empresa?->ruc }}"
                                    {{ request('operador_viaje_id') == $op->id ? 'selected' : '' }}>
                                    {{ $op->nombre_comercial_resuelto ?? '—' }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tipo de transporte --}}
                        <div class="min-w-[180px] flex-1">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Tipo de transporte</label>
                            <select name="tipo_transporte_id" id="filtroTransporte">
                                <option value="">Todos</option>
                                @foreach($tiposTransporte as $tt)
                                <option value="{{ $tt->id }}"
                                    data-icono="{{ $tt->icono }}"
                                    {{ request('tipo_transporte_id') == $tt->id ? 'selected' : '' }}>
                                    {{ $tt->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Estado de reserva --}}
                        <div class="min-w-[180px] flex-1">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Estado</label>
                            <select name="estado_reserva_id" id="filtroEstado">
                                <option value="">Todos</option>
                                @foreach($estadosReserva as $er)
                                <option value="{{ $er->id }}"
                                    data-icono="{{ $er->icono }}"
                                    data-color="{{ $er->color }}"
                                    {{ request('estado_reserva_id') == $er->id ? 'selected' : '' }}>
                                    {{ $er->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Desde --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Desde</label>
                            <input type="date" name="desde" value="{{ request('desde') }}"
                                class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] outline-0 text-sm transition-all focus:border-primary-500">
                        </div>

                        {{-- Hasta --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Hasta</label>
                            <input type="date" name="hasta" value="{{ request('hasta') }}"
                                class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] outline-0 text-sm transition-all focus:border-primary-500">
                        </div>

                        {{-- Agrupación --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Agrupar por</label>
                            <select name="agrupar"
                                class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] outline-0 text-sm cursor-pointer transition-all focus:border-primary-500">
                                <option value="operador"   {{ $agrupar === 'operador'   ? 'selected' : '' }}>Por operador</option>
                                <option value="transporte" {{ $agrupar === 'transporte' ? 'selected' : '' }}>Por transporte</option>
                                <option value="ninguno"    {{ $agrupar === 'ninguno'    ? 'selected' : '' }}>Lista plana</option>
                            </select>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex items-center gap-[8px] self-end">
                            <button type="submit"
                                class="inline-flex items-center gap-[6px] h-[42px] px-[18px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                <i class="material-symbols-outlined !text-[18px]">filter_list</i>
                                Filtrar
                            </button>
                            @if(request()->hasAny(['operador_viaje_id','tipo_transporte_id','estado_reserva_id','desde','hasta']))
                            <a href="{{ route('dashboard.visor-viajes.index', ['agrupar' => $agrupar]) }}"
                                class="inline-flex items-center gap-[4px] h-[42px] px-[14px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-500 text-sm hover:border-primary-500 hover:text-primary-500 transition-all">
                                <i class="material-symbols-outlined !text-[16px]">clear</i>
                                Limpiar
                            </a>
                            @endif
                        </div>

                    </div>
                </form>
            </div>

            {{-- ── Cards de resumen ─────────────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-[25px] mb-[25px]">
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] rounded-md flex items-center gap-[16px]">
                    <span class="w-[50px] h-[50px] rounded-full bg-orange-100 dark:bg-[#1a2d4d] flex items-center justify-center flex-shrink-0">
                        <i class="material-symbols-outlined !text-[26px] text-orange-500">confirmation_number</i>
                    </span>
                    <div>
                        <span class="block text-2xl font-bold text-black dark:text-white">{{ $totalReservas }}</span>
                        <span class="text-xs text-gray-400">Reservas</span>
                    </div>
                </div>
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] rounded-md flex items-center gap-[16px]">
                    <span class="w-[50px] h-[50px] rounded-full bg-primary-100 dark:bg-[#1a2d4d] flex items-center justify-center flex-shrink-0">
                        <i class="material-symbols-outlined !text-[26px] text-primary-500">flight</i>
                    </span>
                    <div>
                        <span class="block text-2xl font-bold text-black dark:text-white">{{ $totalOperadores }}</span>
                        <span class="text-xs text-gray-400">Operadores</span>
                    </div>
                </div>
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] rounded-md flex items-center gap-[16px]">
                    <span class="w-[50px] h-[50px] rounded-full bg-success-100 dark:bg-[#1a2d4d] flex items-center justify-center flex-shrink-0">
                        <i class="material-symbols-outlined !text-[26px] text-success-500">luggage</i>
                    </span>
                    <div>
                        <span class="block text-2xl font-bold text-black dark:text-white">{{ $totalViajes }}</span>
                        <span class="text-xs text-gray-400">Viajes</span>
                    </div>
                </div>
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] rounded-md">
                    <div class="flex items-center gap-[10px] mb-[8px]">
                        <span class="w-[36px] h-[36px] rounded-full bg-purple-100 dark:bg-[#1a2d4d] flex items-center justify-center flex-shrink-0">
                            <i class="material-symbols-outlined !text-[20px] text-purple-500">payments</i>
                        </span>
                        <span class="text-xs text-gray-400 font-medium">Total gastado</span>
                    </div>
                    @forelse($totalPorMoneda as $row)
                    <div class="flex items-baseline gap-[4px]">
                        <span class="text-lg font-bold text-black dark:text-white">
                            {{ $row['moneda']?->simbolo }} {{ number_format($row['total'], 2) }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $row['moneda']?->codigo }}</span>
                    </div>
                    @empty
                    <span class="text-gray-300 dark:text-gray-600 text-sm">—</span>
                    @endforelse
                </div>
            </div>

            {{-- ── Resultados ───────────────────────────────────────────────────────── --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">

                <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                    <h5 class="!mb-0 flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[20px] text-primary-500">table_rows</i>
                        Reservas
                        @if($agrupar === 'operador') <span class="text-sm font-normal text-gray-400">agrupadas por operador</span>
                        @elseif($agrupar === 'transporte') <span class="text-sm font-normal text-gray-400">agrupadas por transporte</span>
                        @else <span class="text-sm font-normal text-gray-400">lista plana</span>
                        @endif
                    </h5>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $totalReservas }} reserva(s)</span>
                </div>

                @if($reservas->isEmpty())
                    <div class="text-center py-[60px]">
                        <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[16px]">confirmation_number</i>
                        <p class="text-gray-500 dark:text-gray-400">No hay reservas con los filtros aplicados.</p>
                    </div>

                @elseif($agrupar === 'ninguno')
                    {{-- ── Lista plana ──────────────────────────────────────────── --}}
                    <div class="overflow-x-auto">
                        @include('dashboard.visor-viajes._tabla', ['reservasList' => $reservas])
                    </div>

                @else
                    {{-- ── Secciones colapsables ────────────────────────────────── --}}
                    <div class="space-y-[16px]">
                        @foreach($grupos as $grupo)
                        @php $grupoId = 'grupo_' . md5($loop->index); @endphp
                        <div class="border border-gray-100 dark:border-[#172036] rounded-md overflow-hidden">

                            {{-- Cabecera del grupo --}}
                            <button type="button"
                                class="grupo-header w-full flex items-center justify-between px-[16px] py-[12px] bg-gray-50 dark:bg-[#15203c] hover:bg-gray-100 dark:hover:bg-[#172036] transition-all text-left"
                                aria-expanded="true"
                                onclick="toggleGrupo('{{ $grupoId }}', this)">
                                <div class="flex items-center gap-[10px]">
                                    @if($agrupar === 'operador' && ($grupo['logo'] ?? null))
                                        <img src="{{ $grupo['logo'] }}" class="w-[28px] h-[28px] rounded object-cover flex-shrink-0" alt="">
                                    @else
                                        <span class="w-[28px] h-[28px] rounded bg-white dark:bg-[#0c1427] flex items-center justify-center flex-shrink-0">
                                            <i class="material-symbols-outlined !text-[18px] text-gray-400">{{ $grupo['icono'] ?? 'folder' }}</i>
                                        </span>
                                    @endif
                                    <span class="font-semibold text-black dark:text-white text-sm">{{ $grupo['label'] }}</span>
                                    <span class="inline-flex items-center justify-center min-w-[20px] h-[20px] px-[5px] text-[10px] font-bold bg-primary-500 text-white rounded-full">
                                        {{ count($grupo['reservas']) }}
                                    </span>
                                    @foreach($grupo['subtotales'] as $sub)
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 hidden sm:inline">
                                        {{ $sub['moneda']?->simbolo }} {{ number_format($sub['total'], 2) }} {{ $sub['moneda']?->codigo }}
                                    </span>
                                    @endforeach
                                </div>
                                <i class="material-symbols-outlined !text-[20px] text-gray-400 grupo-chevron">expand_more</i>
                            </button>

                            {{-- Filas del grupo --}}
                            <div id="{{ $grupoId }}" class="overflow-x-auto">
                                @include('dashboard.visor-viajes._tabla', ['reservasList' => collect($grupo['reservas'])])
                            </div>

                        </div>
                        @endforeach
                    </div>
                @endif

            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        // ── Select2 templates ─────────────────────────────────────────────────
        function fmtIcono(o) {
            if (!o.id) return o.text;
            const ic = $(o.element).data('icono'), co = $(o.element).data('color');
            const dot = co ? `<span class="inline-block w-[10px] h-[10px] rounded-full mr-1 flex-shrink-0" style="background:${co}"></span>` : '';
            const i   = ic ? `<i class="material-symbols-outlined !text-[18px] mr-1 align-middle flex-shrink-0">${ic}</i>` : '';
            return $(`<span class="flex items-center">${dot}${i}<span>${o.text}</span></span>`);
        }
        function fmtOperador(o) {
            if (!o.id) return o.text;
            const lo = $(o.element).data('logo'), ic = $(o.element).data('icono'), ruc = $(o.element).data('ruc');
            const media = lo
                ? `<img src="${lo}" class="w-[22px] h-[22px] rounded object-cover mr-2 flex-shrink-0">`
                : (ic ? `<i class="material-symbols-outlined !text-[18px] mr-2 flex-shrink-0">${ic}</i>` : '');
            const sub = ruc ? `<span class="text-xs text-gray-500 ml-1">${ruc}</span>` : '';
            return $(`<span class="flex items-center">${media}<span>${o.text}</span>${sub}</span>`);
        }

        // ── Inicializar Select2 de filtros ────────────────────────────────────
        $(function () {
            $('#filtroOperador').select2({
                width: '100%',
                placeholder: 'Todos los operadores',
                allowClear: true,
                templateResult: fmtOperador,
                templateSelection: fmtOperador,
                escapeMarkup: function(m) { return m; },
            });
            $('#filtroTransporte').select2({
                width: '100%',
                placeholder: 'Todos',
                allowClear: true,
                templateResult: fmtIcono,
                templateSelection: fmtIcono,
                escapeMarkup: function(m) { return m; },
            });
            $('#filtroEstado').select2({
                width: '100%',
                placeholder: 'Todos',
                allowClear: true,
                templateResult: fmtIcono,
                templateSelection: fmtIcono,
                escapeMarkup: function(m) { return m; },
            });
        });

        // ── Toggle grupos colapsables ─────────────────────────────────────────
        function toggleGrupo(id, btn) {
            const panel = document.getElementById(id);
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            if (expanded) {
                panel.style.display = 'none';
                btn.setAttribute('aria-expanded', 'false');
            } else {
                panel.style.display = '';
                btn.setAttribute('aria-expanded', 'true');
            }
        }
        </script>
    </body>
</html>
