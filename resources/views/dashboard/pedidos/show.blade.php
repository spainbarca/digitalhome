<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Pedido {{ $pedido->numero ?? 'S/N' }}</title>
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

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Pedido {{ $pedido->numero ? '# '.$pedido->numero : 'S/N' }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.pedidos.index') }}" class="hover:text-primary-500 transition-colors">Pedidos</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        {{ $pedido->numero ?? 'S/N' }}
                    </li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            @php
                $prov = $pedido->proveedorNegocio;
                $negocio = $pedido->negocio;
            @endphp

            {{-- ── Cabecera ──────────────────────────────────────────────────────── --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex flex-wrap items-start gap-[20px] justify-between">
                    <div class="flex-1 min-w-0">
                        {{-- Negocio --}}
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Negocio</p>
                        <a href="{{ route('dashboard.negocios.show', $negocio) }}"
                            class="text-base font-semibold text-primary-500 hover:text-primary-400 transition-colors block mb-[12px]">
                            {{ $negocio?->nombre ?? $negocio?->empresa?->razon_social ?? '—' }}
                        </a>

                        {{-- Proveedor --}}
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-[6px]">Proveedor</p>
                        @if($prov)
                        <div class="flex items-center gap-[8px] mb-[12px]">
                            @if($prov->logo_resuelto)
                                <img src="{{ $prov->logo_resuelto }}" class="w-[36px] h-[36px] rounded object-cover flex-shrink-0" alt="">
                            @else
                                <span class="w-[36px] h-[36px] rounded bg-gray-100 dark:bg-[#172036] flex items-center justify-center flex-shrink-0">
                                    <i class="material-symbols-outlined !text-[18px] text-gray-400">local_shipping</i>
                                </span>
                            @endif
                            <span class="text-base font-medium text-black dark:text-white">{{ $prov->sigla_resuelta ?? $prov->nombre ?? '—' }}</span>
                        </div>
                        @else
                        <p class="text-sm text-gray-400 mb-[12px]">—</p>
                        @endif

                        {{-- Fecha y número --}}
                        <div class="flex flex-wrap gap-[16px]">
                            @if($pedido->numero)
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">N° pedido</p>
                                <p class="text-sm font-mono font-medium text-black dark:text-white">{{ $pedido->numero }}</p>
                            </div>
                            @endif
                            @if($pedido->fecha)
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Fecha</p>
                                <p class="text-sm font-medium text-black dark:text-white">{{ $pedido->fecha->format('d/m/Y') }}</p>
                            </div>
                            @endif
                            @if($pedido->total !== null)
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                                <p class="text-xl font-bold text-black dark:text-white">
                                    {{ $pedido->moneda?->simbolo }} {{ number_format((float)$pedido->total, 2) }}
                                    @if($pedido->moneda) <span class="text-xs font-normal text-gray-400">{{ $pedido->moneda->codigo }}</span> @endif
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex items-center gap-[8px] flex-shrink-0">
                        <a href="{{ route('dashboard.pedidos.edit', $pedido) }}"
                            class="inline-flex items-center gap-[6px] px-[14px] py-[8px] rounded-md border border-primary-500 text-primary-500 text-sm font-medium hover:bg-primary-500 hover:text-white transition-all">
                            <i class="material-symbols-outlined !text-[18px]">edit</i>
                            Editar
                        </a>
                        <form method="POST" action="{{ route('dashboard.pedidos.destroy', $pedido) }}"
                            onsubmit="return confirm('¿Eliminar este pedido? Esta acción no se puede deshacer.')">
                            @csrf @method('DELETE')
                            <input type="hidden" name="_from" value="modulo">
                            <button type="submit"
                                class="inline-flex items-center gap-[6px] px-[14px] py-[8px] rounded-md border border-danger-500 text-danger-500 text-sm font-medium hover:bg-danger-500 hover:text-white transition-all">
                                <i class="material-symbols-outlined !text-[18px]">delete</i>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ── Descripción ───────────────────────────────────────────────────── --}}
            @if($pedido->descripcion)
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <h6 class="font-semibold text-black dark:text-white mb-[12px]">Descripción</h6>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $pedido->descripcion }}</p>
                @if($pedido->observaciones)
                <div class="mt-[12px] pt-[12px] border-t border-gray-100 dark:border-[#172036]">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Observaciones</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $pedido->observaciones }}</p>
                </div>
                @endif
            </div>
            @endif

            {{-- ── Documentos del pedido ─────────────────────────────────────────── --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex items-center justify-between mb-[16px]">
                    <h6 class="font-semibold text-black dark:text-white !mb-0">Documentos adjuntos</h6>
                    <button type="button" onclick="abrirModalDocumento()"
                        class="inline-flex items-center gap-[6px] px-[12px] py-[6px] rounded-md bg-purple-500 text-white text-sm font-medium hover:bg-purple-400 transition-all">
                        <i class="material-symbols-outlined !text-[16px]">attach_file</i>
                        Adjuntar documento
                    </button>
                </div>

                @if($pedido->documentosNegocio->isEmpty())
                <div class="text-center py-[30px]">
                    <i class="material-symbols-outlined !text-[40px] text-gray-300 dark:text-gray-600 block mb-[8px]">description</i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No hay documentos adjuntos.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-[#172036]">
                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Nombre</th>
                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Tipo</th>
                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Emisión</th>
                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Vencimiento</th>
                                <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Estado</th>
                                <th class="text-right py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Archivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->documentosNegocio as $doc)
                            @php
                                $hoy     = now()->startOfDay();
                                $vence   = $doc->fecha_vencimiento ? \Carbon\Carbon::parse($doc->fecha_vencimiento)->startOfDay() : null;
                                $diasRest = $vence ? $hoy->diffInDays($vence, false) : null;
                                $badgeClass = '';
                                $badgeText  = '';
                                if ($vence) {
                                    if ($diasRest < 0)       { $badgeClass = 'text-danger-600 border-danger-600 bg-danger-100';   $badgeText = 'Vencido'; }
                                    elseif ($diasRest <= 30) { $badgeClass = 'text-warning-600 border-warning-600 bg-warning-100'; $badgeText = 'Por vencer'; }
                                    else                     { $badgeClass = 'text-success-600 border-success-600 bg-success-100'; $badgeText = 'Vigente'; }
                                }
                            @endphp
                            <tr class="border-b border-gray-50 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                <td class="py-[10px] px-[12px] text-sm font-medium text-black dark:text-white">{{ $doc->nombre }}</td>
                                <td class="py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400">
                                    @if($doc->tipoDocumentoNegocio)
                                        <span class="flex items-center gap-[4px]">
                                            <i class="material-symbols-outlined !text-[14px] text-primary-500">{{ $doc->tipoDocumentoNegocio->icono ?? 'description' }}</i>
                                            {{ $doc->tipoDocumentoNegocio->nombre }}
                                        </span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400">{{ $doc->fecha_emision?->format('d/m/Y') ?? '—' }}</td>
                                <td class="py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400">{{ $doc->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                                <td class="py-[10px] px-[12px]">
                                    @if($badgeText)
                                    <span class="inline-block text-[10px] font-medium px-[6px] py-[2px] rounded-[100px] border {{ $badgeClass }}">{{ $badgeText }}</span>
                                    @else
                                    <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="py-[10px] px-[12px] text-right">
                                    @if($doc->archivo_path)
                                    <a href="{{ $doc->archivo_url }}" target="_blank"
                                        class="inline-flex items-center gap-[4px] text-xs text-primary-500 hover:text-primary-400 transition-colors">
                                        <i class="material-symbols-outlined !text-[14px]">open_in_new</i>
                                        Ver
                                    </a>
                                    @else
                                    <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        {{-- ══ MODAL: Adjuntar documento al pedido ═══════════════════════════════ --}}
        <div id="modalDocumento" class="fixed inset-0 z-[999] hidden">
            <div class="absolute inset-0 bg-black/50" onclick="cerrarModalDocumento()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-[16px]">
                <div class="bg-white dark:bg-[#0c1427] rounded-md shadow-xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-[20px] border-b border-gray-100 dark:border-[#172036]">
                        <h6 class="font-semibold text-black dark:text-white !mb-0">Adjuntar documento al pedido</h6>
                        <button type="button" onclick="cerrarModalDocumento()" class="text-gray-400 hover:text-gray-600 transition-all">
                            <i class="material-symbols-outlined">close</i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('dashboard.negocios.documentos.store', $pedido->negocio) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="negocio_id"      value="">
                        <input type="hidden" name="pedido_id"        value="{{ $pedido->id }}">
                        <input type="hidden" name="pago_negocio_id"  value="">

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
                                <input type="text" name="nombre" required placeholder="Ej: Boleta de pago 001"
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
            function fmtIcono(o) {
                if (!o.id) return o.text;
                const ic = $(o.element).data('icono');
                const i  = ic ? `<i class="material-symbols-outlined !text-[18px] mr-1 align-middle flex-shrink-0">${ic}</i>` : '';
                return $(`<span class="flex items-center">${i}<span>${o.text}</span></span>`);
            }

            function abrirModalDocumento() {
                document.getElementById('modalDocumento').classList.remove('hidden');
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

            function cerrarModalDocumento() {
                document.getElementById('modalDocumento').classList.add('hidden');
            }

            document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModalDocumento(); });
        </script>
    </body>
</html>
