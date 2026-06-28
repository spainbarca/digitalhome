<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Pedidos</title>
        @vite('resources/css/app.css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
        <style>
            .select2-container--default .select2-selection--single{height:36px;border:1px solid #e5e7eb;border-radius:6px;display:flex;align-items:center;padding:0 10px}
            .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:36px;color:inherit;font-size:.75rem}
            .select2-container--default .select2-selection--single .select2-selection__arrow{height:36px;top:0}
            .dark .select2-container--default .select2-selection--single{background:#0c1427;border-color:#172036;color:#fff}
            .dark .select2-container--default .select2-results__option{background:#0c1427;color:#fff}
            .dark .select2-dropdown{background:#0c1427;border-color:#172036}
            .dark .select2-search--dropdown .select2-search__field{background:#15203c;border-color:#172036;color:#fff}
        </style>
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Pedidos</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Pedidos</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px]">
                    <div class="flex flex-wrap items-center gap-[10px]">
                        {{-- Búsqueda de texto --}}
                        <div class="relative">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" id="buscar" oninput="filtrarTabla()" placeholder="Buscar por número, descripción…"
                                class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-[230px] block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </div>

                        {{-- Filtro por Negocio --}}
                        <select id="filtroNegocio" onchange="filtrarTabla()"
                            class="select2-filtro-negocio h-[36px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white text-xs px-[10px] outline-0 min-w-[180px]">
                            <option value="">Todos los negocios</option>
                            @foreach($negocios as $neg)
                            <option value="{{ $neg->id }}">{{ $neg->nombre ?? $neg->empresa?->razon_social }}</option>
                            @endforeach
                        </select>

                        {{-- Filtro por Proveedor --}}
                        <select id="filtroProveedor" onchange="filtrarTabla()"
                            class="select2-filtro-proveedor h-[36px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] text-black dark:text-white text-xs px-[10px] outline-0 min-w-[180px]">
                            <option value="">Todos los proveedores</option>
                            @foreach($proveedores as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->sigla_resuelta ?? $prov->nombre ?? $prov->empresa?->razon_social }}</option>
                            @endforeach
                        </select>

                        <div class="ml-auto">
                            <a href="{{ route('dashboard.pedidos.create') }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                                <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                    <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                                    Nuevo Pedido
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="trezo-card-content">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-[#172036]">
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400">Negocio</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400">Proveedor</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[110px]">N°</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[100px]">Fecha</th>
                                    <th class="text-left py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400">Descripción</th>
                                    <th class="text-right py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[120px]">Total</th>
                                    <th class="text-center py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[70px]">Docs</th>
                                    <th class="text-right py-[13px] px-[16px] text-sm font-semibold text-gray-500 dark:text-gray-400 w-[100px]">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaBody">
                                @forelse($pedidos as $pedido)
                                @php
                                    $prov  = $pedido->proveedorNegocio;
                                    $ndoc  = $pedido->documentosNegocio?->count() ?? 0;
                                    $searchStr = strtolower(
                                        ($pedido->numero ?? '') . ' ' .
                                        ($pedido->descripcion ?? '') . ' ' .
                                        ($pedido->negocio?->nombre ?? $pedido->negocio?->empresa?->razon_social ?? '') . ' ' .
                                        ($prov?->sigla_resuelta ?? $prov?->nombre ?? '')
                                    );
                                @endphp
                                <tr class="border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors"
                                    data-id="{{ $pedido->id }}"
                                    data-negocio-id="{{ $pedido->negocio_id }}"
                                    data-proveedor-id="{{ $pedido->proveedor_negocio_id }}"
                                    data-search="{{ $searchStr }}">
                                    <td class="py-[13px] px-[16px] text-sm text-black dark:text-white font-medium">
                                        <a href="{{ route('dashboard.negocios.show', $pedido->negocio_id) }}" class="hover:text-primary-500 transition-colors">
                                            {{ $pedido->negocio?->nombre ?? $pedido->negocio?->empresa?->razon_social ?? '—' }}
                                        </a>
                                    </td>
                                    <td class="py-[13px] px-[16px]">
                                        @if($prov)
                                        <div class="flex items-center gap-[6px]">
                                            @if($prov->logo_resuelto)
                                                <img src="{{ $prov->logo_resuelto }}" class="w-[24px] h-[24px] rounded object-cover flex-shrink-0" alt="">
                                            @else
                                                <span class="w-[24px] h-[24px] rounded bg-gray-100 dark:bg-[#172036] flex items-center justify-center flex-shrink-0">
                                                    <i class="material-symbols-outlined !text-[12px] text-gray-400">local_shipping</i>
                                                </span>
                                            @endif
                                            <span class="text-sm text-black dark:text-white truncate max-w-[140px]">{{ $prov->sigla_resuelta ?? $prov->nombre ?? '—' }}</span>
                                        </div>
                                        @else
                                        <span class="text-gray-400 dark:text-gray-600 text-sm">—</span>
                                        @endif
                                    </td>
                                    <td class="py-[13px] px-[16px] text-xs font-mono text-gray-500 dark:text-gray-400">{{ $pedido->numero ?? '—' }}</td>
                                    <td class="py-[13px] px-[16px] text-sm text-gray-500 dark:text-gray-400">{{ $pedido->fecha?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="py-[13px] px-[16px]">
                                        <p class="text-sm text-black dark:text-white max-w-[200px] truncate" title="{{ $pedido->descripcion }}">
                                            {{ $pedido->descripcion ? Str::limit($pedido->descripcion, 60) : '—' }}
                                        </p>
                                    </td>
                                    <td class="py-[13px] px-[16px] text-right">
                                        @if($pedido->total !== null)
                                        <span class="text-sm font-semibold text-black dark:text-white">
                                            {{ $pedido->moneda?->simbolo }} {{ number_format((float)$pedido->total, 2) }}
                                        </span>
                                        @else
                                        <span class="text-gray-400 text-sm">—</span>
                                        @endif
                                    </td>
                                    <td class="py-[13px] px-[16px] text-center">
                                        @if($ndoc)
                                        <span class="inline-flex items-center justify-center min-w-[20px] h-[20px] px-[5px] text-[10px] font-bold bg-purple-500 text-white rounded-full">{{ $ndoc }}</span>
                                        @else
                                        <span class="text-gray-300 dark:text-gray-600 text-sm">—</span>
                                        @endif
                                    </td>
                                    <td class="py-[13px] px-[16px] text-right">
                                        <a href="{{ route('dashboard.pedidos.show', $pedido) }}"
                                            class="text-gray-500 dark:text-gray-400 hover:text-primary-500 transition-colors mr-[8px]" title="Ver">
                                            <i class="material-symbols-outlined !text-[18px]">visibility</i>
                                        </a>
                                        <a href="{{ route('dashboard.pedidos.edit', $pedido) }}"
                                            class="text-gray-500 dark:text-gray-400 hover:text-primary-500 transition-colors mr-[8px]" title="Editar">
                                            <i class="material-symbols-outlined !text-[18px]">edit</i>
                                        </a>
                                        <form method="POST" action="{{ route('dashboard.pedidos.destroy', $pedido) }}" class="inline"
                                            onsubmit="return confirm('¿Eliminar este pedido?')">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="_from" value="modulo">
                                            <button type="submit" class="text-gray-500 dark:text-gray-400 hover:text-danger-500 transition-colors" title="Eliminar">
                                                <i class="material-symbols-outlined !text-[18px]">delete</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr id="filaVacia">
                                    <td colspan="8" class="text-center py-[50px] text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">receipt_long</i>
                                        No hay pedidos registrados.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p id="sinResultados" class="hidden text-center py-[30px] text-sm text-gray-500 dark:text-gray-400">No se encontraron coincidencias.</p>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(function () {
                $('#filtroNegocio').select2({
                    placeholder: 'Todos los negocios',
                    allowClear: true,
                    width: '200px',
                }).on('change', filtrarTabla);

                $('#filtroProveedor').select2({
                    placeholder: 'Todos los proveedores',
                    allowClear: true,
                    width: '200px',
                }).on('change', filtrarTabla);
            });

            function filtrarTabla() {
                const q      = (document.getElementById('buscar').value || '').toLowerCase().trim();
                const negId  = document.getElementById('filtroNegocio').value;
                const prvId  = document.getElementById('filtroProveedor').value;
                const rows   = document.querySelectorAll('#tablaBody tr[data-id]');
                let   vis    = 0;

                rows.forEach(row => {
                    const matchQ   = !q     || (row.dataset.search || '').includes(q);
                    const matchNeg = !negId || row.dataset.negocioId   === negId;
                    const matchPrv = !prvId || row.dataset.proveedorId === prvId;
                    const ok       = matchQ && matchNeg && matchPrv;
                    row.style.display = ok ? '' : 'none';
                    if (ok) vis++;
                });

                const noFiltro = !q && !negId && !prvId;
                document.getElementById('sinResultados').classList.toggle('hidden', vis > 0 || noFiltro);
                const vacia = document.getElementById('filaVacia');
                if (vacia) vacia.style.display = noFiltro ? '' : 'none';
            }

            document.getElementById('buscar').addEventListener('input', filtrarTabla);
        </script>
    </body>
</html>
