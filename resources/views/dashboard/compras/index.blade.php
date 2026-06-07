<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Compras</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Compras</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Compras</li>
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

            @if(session('error'))
            <div class="mb-[25px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-danger-700 hover:text-danger-900">
                    <i class="material-symbols-outlined !text-lg">close</i>
                </button>
            </div>
            @endif

            <!-- Filtros -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <form method="GET" action="{{ route('dashboard.compras.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-[14px]">
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-[5px]">Desde</label>
                        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                            class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-[5px]">Hasta</label>
                        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                            class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm transition-all focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-[5px]">Categoría</label>
                        <select name="categoria_id"
                            class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm cursor-pointer transition-all focus:border-primary-500">
                            <option value="">Todas</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-[5px]">Comercio</label>
                        <select name="comercio_id"
                            class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm cursor-pointer transition-all focus:border-primary-500">
                            <option value="">Todos</option>
                            @foreach($comercios as $com)
                                <option value="{{ $com->id }}" {{ request('comercio_id') == $com->id ? 'selected' : '' }}>
                                    {{ $com->nombre_referencial ?: $com->empresa?->razon_social ?: '—' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-[5px]">Miembro</label>
                        <select name="miembro_id"
                            class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm cursor-pointer transition-all focus:border-primary-500">
                            <option value="">Todos</option>
                            @foreach($miembros as $m)
                                <option value="{{ $m->id }}" {{ request('miembro_id') == $m->id ? 'selected' : '' }}>
                                    {{ trim(($m->user?->persona?->nombres ?? '') . ' ' . ($m->user?->persona?->apellido_paterno ?? '')) ?: $m->apodo ?: '—' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-[5px]">Método de pago</label>
                        <select name="metodo_pago_id"
                            class="h-[42px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 text-sm cursor-pointer transition-all focus:border-primary-500">
                            <option value="">Todos</option>
                            @foreach($metodosPago as $mp)
                                <option value="{{ $mp->id }}" {{ request('metodo_pago_id') == $mp->id ? 'selected' : '' }}>{{ $mp->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="xl:col-span-6 flex items-center justify-between gap-[10px]">
                        <div class="flex gap-[10px]">
                            <button type="submit"
                                class="inline-flex items-center gap-[6px] h-[38px] px-[16px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                <i class="material-symbols-outlined !text-[16px]">filter_list</i>
                                Filtrar
                            </button>
                            <a href="{{ route('dashboard.compras.index') }}"
                                class="inline-flex items-center gap-[6px] h-[38px] px-[16px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 text-sm font-medium hover:border-primary-500 hover:text-primary-500 transition-all">
                                <i class="material-symbols-outlined !text-[16px]">clear</i>
                                Limpiar
                            </a>
                        </div>
                        <a href="{{ route('dashboard.compras.create') }}"
                            class="inline-flex items-center gap-[6px] h-[38px] px-[16px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            <i class="material-symbols-outlined !text-[16px]">add</i>
                            Nueva Compra
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabla de compras -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                    <h5 class="!mb-0">
                        <i class="material-symbols-outlined !text-[20px] align-middle mr-[6px] text-primary-500">shopping_cart</i>
                        Registro de Compras
                    </h5>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $compras->total() }} registro(s)</span>
                </div>

                @if($compras->isEmpty())
                    <div class="text-center py-[60px]">
                        <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[16px]">shopping_bag</i>
                        <p class="text-gray-500 dark:text-gray-400 mb-[20px]">No hay compras registradas.</p>
                        <a href="{{ route('dashboard.compras.create') }}"
                            class="inline-block bg-primary-500 text-white py-[10px] px-[20px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">add</i>
                                Registrar primera compra
                            </span>
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-[#172036]">
                                    <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Fecha</th>
                                    <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Comercio</th>
                                    <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Categoría</th>
                                    <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Concepto</th>
                                    <th class="text-right py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Total</th>
                                    <th class="text-center py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Pago</th>
                                    <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Miembro</th>
                                    <th class="text-center py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Docs</th>
                                    <th class="text-center py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-[#172036]">
                                @foreach($compras as $compra)
                                @php
                                    $hoy = now()->startOfDay();
                                    $garantiaVenciendo = $compra->documentos->contains(function ($d) use ($hoy) {
                                        return strtolower($d->tipoDocumentoCompra?->nombre ?? '') === 'garantía'
                                            && $d->fecha_vencimiento
                                            && $d->fecha_vencimiento->gte($hoy)
                                            && $d->fecha_vencimiento->diffInDays($hoy) <= 30;
                                    });
                                    $garantiaVencida = !$garantiaVenciendo && $compra->documentos->contains(function ($d) use ($hoy) {
                                        return strtolower($d->tipoDocumentoCompra?->nombre ?? '') === 'garantía'
                                            && $d->fecha_vencimiento
                                            && $d->fecha_vencimiento->lt($hoy);
                                    });
                                    $nombreMiembro = trim(
                                        ($compra->miembro?->user?->persona?->nombres ?? '') . ' ' .
                                        ($compra->miembro?->user?->persona?->apellido_paterno ?? '')
                                    ) ?: ($compra->miembro?->apodo ?? '—');
                                    $logoComercio = $compra->comercio?->logo_efectivo;
                                    $nombreComercio = $compra->comercio?->nombre_referencial
                                        ?: $compra->comercio?->empresa?->razon_social
                                        ?: '—';
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                    <!-- Fecha -->
                                    <td class="py-[12px] px-[12px] whitespace-nowrap text-gray-600 dark:text-gray-400 text-xs">
                                        {{ $compra->fecha_compra?->format('d/m/Y') }}
                                    </td>
                                    <!-- Comercio -->
                                    <td class="py-[12px] px-[12px]">
                                        <div class="flex items-center gap-[8px]">
                                            @if($logoComercio)
                                                <img src="{{ $logoComercio }}" class="w-[28px] h-[28px] object-cover rounded-full flex-shrink-0" alt="">
                                            @else
                                                <span class="w-[28px] h-[28px] rounded-full bg-gray-100 dark:bg-[#172036] flex items-center justify-center text-xs font-bold text-gray-500 flex-shrink-0">
                                                    {{ mb_strtoupper(mb_substr($nombreComercio, 0, 1)) }}
                                                </span>
                                            @endif
                                            <span class="text-black dark:text-white font-medium text-xs truncate max-w-[120px]" title="{{ $nombreComercio }}">
                                                {{ $nombreComercio }}
                                            </span>
                                        </div>
                                    </td>
                                    <!-- Categoría -->
                                    <td class="py-[12px] px-[12px]">
                                        @if($compra->categoriaCompra)
                                            <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[3px] px-[8px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400 whitespace-nowrap">
                                                <i class="material-symbols-outlined !text-[13px]">{{ $compra->categoriaCompra->icono ?? 'label' }}</i>
                                                {{ $compra->categoriaCompra->nombre }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                    <!-- Concepto -->
                                    <td class="py-[12px] px-[12px] max-w-[180px]">
                                        <span class="text-black dark:text-white text-sm truncate block" title="{{ $compra->concepto }}">
                                            {{ $compra->concepto }}
                                        </span>
                                        @if($garantiaVenciendo)
                                            <span class="inline-flex items-center gap-[3px] text-xs text-warning-600 mt-[2px]">
                                                <i class="material-symbols-outlined !text-[12px]">warning</i>
                                                Garantía por vencer
                                            </span>
                                        @elseif($garantiaVencida)
                                            <span class="inline-flex items-center gap-[3px] text-xs text-danger-500 mt-[2px]">
                                                <i class="material-symbols-outlined !text-[12px]">error</i>
                                                Garantía vencida
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Total -->
                                    <td class="py-[12px] px-[12px] text-right whitespace-nowrap">
                                        <span class="font-mono font-semibold text-black dark:text-white text-sm">
                                            {{ $compra->moneda?->simbolo }} {{ number_format($compra->total, 2) }}
                                        </span>
                                    </td>
                                    <!-- Método de pago -->
                                    <td class="py-[12px] px-[12px] text-center">
                                        @if($compra->metodoPago)
                                            <span class="inline-flex items-center gap-[4px] text-xs text-gray-600 dark:text-gray-400" title="{{ $compra->metodoPago->nombre }}">
                                                <i class="material-symbols-outlined !text-[16px] text-primary-500">{{ $compra->metodoPago->icono ?? 'payment' }}</i>
                                                <span class="hidden sm:inline">{{ $compra->metodoPago->nombre }}</span>
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                    <!-- Miembro -->
                                    <td class="py-[12px] px-[12px]">
                                        <span class="text-xs text-gray-600 dark:text-gray-400 truncate block max-w-[100px]" title="{{ $nombreMiembro }}">
                                            {{ $nombreMiembro }}
                                        </span>
                                    </td>
                                    <!-- Documentos -->
                                    <td class="py-[12px] px-[12px] text-center">
                                        @php $countDocs = $compra->documentos->count(); @endphp
                                        @if($countDocs > 0)
                                            <span class="inline-flex items-center justify-center w-[22px] h-[22px] rounded-full bg-primary-100 text-primary-700 text-xs font-bold">
                                                {{ $countDocs }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">
                                                <i class="material-symbols-outlined !text-[16px]">attach_file</i>
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Acciones -->
                                    <td class="py-[12px] px-[12px] text-center">
                                        <div class="flex items-center justify-center gap-[4px]">
                                            <a href="{{ route('dashboard.compras.show', $compra) }}"
                                               class="w-[28px] h-[28px] rounded flex items-center justify-center bg-primary-50 dark:bg-[#15203c] hover:bg-primary-100 transition-all text-primary-500"
                                               title="Ver">
                                                <i class="material-symbols-outlined !text-[15px]">visibility</i>
                                            </a>
                                            <a href="{{ route('dashboard.compras.edit', $compra) }}"
                                               class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-50 dark:bg-[#172036] hover:bg-gray-100 transition-all text-gray-500 dark:text-gray-400"
                                               title="Editar">
                                                <i class="material-symbols-outlined !text-[15px]">edit</i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.compras.destroy', $compra) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('¿Eliminar la compra «{{ addslashes($compra->concepto) }}»?')"
                                                    class="w-[28px] h-[28px] rounded flex items-center justify-center bg-danger-50 dark:bg-[#172036] hover:bg-danger-100 transition-all text-danger-500"
                                                    title="Eliminar">
                                                    <i class="material-symbols-outlined !text-[15px]">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <!-- Pie: totales -->
                            <tfoot>
                                <tr class="border-t-2 border-gray-200 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c]">
                                    <td colspan="4" class="py-[12px] px-[12px] text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Total (filtrado)
                                    </td>
                                    <td class="py-[12px] px-[12px] text-right font-mono font-bold text-primary-600 dark:text-primary-400 text-sm whitespace-nowrap">
                                        {{ number_format($totalFiltrado, 2) }}
                                    </td>
                                    <td colspan="4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($compras->hasPages())
                    <div class="sm:flex sm:items-center justify-between mt-[25px] pt-[14px] border-t border-gray-100 dark:border-[#172036]">
                        <p class="!mb-0 text-sm">
                            Mostrando {{ $compras->firstItem() }}–{{ $compras->lastItem() }} de {{ $compras->total() }} resultados
                        </p>
                        <ol class="mt-[10px] sm:mt-0">
                            <li class="inline-block mx-[1px]">
                                @if($compras->onFirstPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </span>
                                @else
                                    <a href="{{ $compras->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </a>
                                @endif
                            </li>
                            @foreach($compras->getUrlRange(max(1, $compras->currentPage()-2), min($compras->lastPage(), $compras->currentPage()+2)) as $page => $url)
                            <li class="inline-block mx-[1px]">
                                @if($page == $compras->currentPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                                @endif
                            </li>
                            @endforeach
                            <li class="inline-block mx-[1px]">
                                @if($compras->hasMorePages())
                                    <a href="{{ $compras->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i>
                                    </a>
                                @else
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i>
                                    </span>
                                @endif
                            </li>
                        </ol>
                    </div>
                    @endif
                @endif
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
