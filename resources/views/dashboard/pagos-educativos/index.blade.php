<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Pagos Educativos</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Pagos Educativos</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Educación</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Pagos</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            {{-- FILA 1: Filtro por miembro --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[20px] p-[20px] md:p-[25px] rounded-md">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-[12px] uppercase tracking-wide">Filtrar por miembro</p>
                <div class="flex flex-wrap gap-[8px]">
                    <a href="{{ route('dashboard.pagos-educativos.index', array_filter(['matricula_id' => $matriculaId, 'mes_correspondiente' => $mes, 'estado' => $estado])) }}"
                       class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-[8px] border text-sm font-medium transition-all
                           {{ !$miembroId ? 'bg-primary-500 border-primary-500 text-white' : 'border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500' }}">
                        <i class="material-symbols-outlined !text-[16px]">groups</i>
                        Todos
                    </a>
                    @foreach($miembros as $m)
                        @php
                            $nombreM = trim(implode(' ', array_filter([$m->user?->persona?->nombres, $m->user?->persona?->apellido_paterno, $m->user?->persona?->apellido_materno]))) ?: ($m->user?->name ?? 'Sin nombre');
                            $avatarM = $m->user?->persona?->avatar_url ?? null;
                        @endphp
                        <a href="{{ route('dashboard.pagos-educativos.index', array_filter(['miembro_id' => $m->id, 'matricula_id' => $matriculaId, 'mes_correspondiente' => $mes, 'estado' => $estado])) }}"
                           class="inline-flex items-center gap-[7px] px-[14px] py-[6px] rounded-[8px] border text-sm font-medium transition-all
                               {{ $miembroId === $m->id ? 'bg-primary-500 border-primary-500 text-white' : 'border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500' }}">
                            @if($avatarM)
                                <img src="{{ $avatarM }}" class="w-[20px] h-[20px] rounded-full object-cover flex-shrink-0" alt="">
                            @else
                                <span class="w-[20px] h-[20px] rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-[10px] font-bold flex-shrink-0">{{ mb_strtoupper(mb_substr($nombreM, 0, 1)) }}</span>
                            @endif
                            <span class="max-w-[140px] truncate">{{ $nombreM }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- FILA 2: Filtros secundarios --}}
            <form method="GET" action="{{ route('dashboard.pagos-educativos.index') }}" class="trezo-card bg-white dark:bg-[#0c1427] mb-[20px] p-[20px] md:p-[25px] rounded-md">
                @if($miembroId)<input type="hidden" name="miembro_id" value="{{ $miembroId }}">@endif
                <div class="flex flex-wrap items-end gap-[14px]">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-[6px] font-medium uppercase tracking-wide">Matrícula</label>
                        <select name="matricula_id" class="h-[42px] rounded-md text-sm text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 focus:border-primary-500">
                            <option value="">Todas las matrículas</option>
                            @foreach($matriculas as $mat)
                                @php
                                    $matNombreM = trim(implode(' ', array_filter([$mat->hogarMiembro?->user?->persona?->nombres, $mat->hogarMiembro?->user?->persona?->apellido_paterno]))) ?: ($mat->hogarMiembro?->user?->name ?? '');
                                    $matLabel = ($mat->institucionEducativa?->nombre_referencial ?? '(Sin inst.)') . ($matNombreM ? ' — ' . $matNombreM : '') . ($mat->año_lectivo ? ' · ' . $mat->año_lectivo : '');
                                @endphp
                                <option value="{{ $mat->id }}" {{ $matriculaId === $mat->id ? 'selected' : '' }}>{{ $matLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-[6px] font-medium uppercase tracking-wide">Mes</label>
                        <input type="month" name="mes_correspondiente" value="{{ $mes }}"
                            class="h-[42px] rounded-md text-sm text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 focus:border-primary-500">
                    </div>
                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-[6px] font-medium uppercase tracking-wide">Estado</label>
                        <select name="estado" class="h-[42px] rounded-md text-sm text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[12px] block w-full outline-0 focus:border-primary-500">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="pagado"    {{ $estado === 'pagado'    ? 'selected' : '' }}>Pagado</option>
                            <option value="vencido"   {{ $estado === 'vencido'   ? 'selected' : '' }}>Vencido</option>
                        </select>
                    </div>
                    <div class="flex gap-[8px]">
                        <button type="submit" class="h-[42px] px-[18px] bg-primary-500 text-white rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                            <span class="inline-flex items-center gap-[5px]"><i class="material-symbols-outlined !text-[16px]">filter_list</i> Filtrar</span>
                        </button>
                        <a href="{{ route('dashboard.pagos-educativos.index') }}" class="h-[42px] px-[14px] flex items-center border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 rounded-md hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all text-sm">
                            Limpiar
                        </a>
                    </div>
                    <a href="{{ route('dashboard.pagos-educativos.create') }}"
                       class="h-[42px] px-[18px] flex items-center gap-[6px] bg-success-500 text-white rounded-md hover:bg-success-400 transition-all text-sm font-medium ml-auto">
                        <i class="material-symbols-outlined !text-[16px]">add</i> Registrar pago
                    </a>
                </div>
            </form>

            {{-- FILA 3: Cards resumen --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px] mb-[20px]">
                <div class="trezo-card bg-success-50 dark:bg-[#0a1a10] p-[20px] md:p-[25px] rounded-md text-center border border-success-200 dark:border-success-900">
                    <div class="w-[48px] h-[48px] rounded-full bg-success-100 dark:bg-success-900/30 flex items-center justify-center mx-auto mb-[10px]">
                        <i class="material-symbols-outlined !text-[22px] text-success-600">check_circle</i>
                    </div>
                    <span class="block text-xs text-success-600 font-medium uppercase tracking-wide mb-[4px]">Total Pagado</span>
                    <span class="block text-2xl font-bold text-success-700">S/ {{ number_format($totalPagado, 2) }}</span>
                </div>
                <div class="trezo-card bg-warning-50 dark:bg-[#1a1500] p-[20px] md:p-[25px] rounded-md text-center border border-warning-200 dark:border-warning-900">
                    <div class="w-[48px] h-[48px] rounded-full bg-warning-100 dark:bg-warning-900/30 flex items-center justify-center mx-auto mb-[10px]">
                        <i class="material-symbols-outlined !text-[22px] text-warning-600">schedule</i>
                    </div>
                    <span class="block text-xs text-warning-600 font-medium uppercase tracking-wide mb-[4px]">Pendiente</span>
                    <span class="block text-2xl font-bold text-warning-700">S/ {{ number_format($totalPendiente, 2) }}</span>
                </div>
                <div class="trezo-card bg-danger-50 dark:bg-[#1a0505] p-[20px] md:p-[25px] rounded-md text-center border border-danger-200 dark:border-danger-900">
                    <div class="w-[48px] h-[48px] rounded-full bg-danger-100 dark:bg-danger-900/30 flex items-center justify-center mx-auto mb-[10px]">
                        <i class="material-symbols-outlined !text-[22px] text-danger-600">warning</i>
                    </div>
                    <span class="block text-xs text-danger-600 font-medium uppercase tracking-wide mb-[4px]">Vencido</span>
                    <span class="block text-2xl font-bold text-danger-700">S/ {{ number_format($totalVencido, 2) }}</span>
                </div>
            </div>

            {{-- LISTADO --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                    <h6 class="!mb-0 font-semibold text-black dark:text-white flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">payments</i>
                        Historial de Pagos
                    </h6>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-[#15203c]">
                                <th class="text-left px-[16px] py-[10px] font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Institución / Miembro</th>
                                <th class="text-left px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Concepto</th>
                                <th class="text-left px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Mes</th>
                                <th class="text-right px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Monto</th>
                                <th class="text-left px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Vencimiento</th>
                                <th class="text-left px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Pagado</th>
                                <th class="text-center px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400">Estado</th>
                                <th class="text-center px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400">Comp.</th>
                                <th class="text-center px-[12px] py-[10px] font-medium text-gray-600 dark:text-gray-400">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-[#172036]">
                            @forelse($pagos as $pago)
                                @php
                                    $miembroPago = $pago->matricula?->hogarMiembro;
                                    $nombreMiembroPago = trim(implode(' ', array_filter([
                                        $miembroPago?->user?->persona?->nombres,
                                        $miembroPago?->user?->persona?->apellido_paterno,
                                    ]))) ?: ($miembroPago?->user?->name ?? '—');
                                    $avatarPago = $miembroPago?->user?->persona?->avatar_url ?? null;
                                    $estadoClass = match($pago->estado) {
                                        'pagado'  => 'bg-success-100 text-success-700 border border-success-300',
                                        'vencido' => 'bg-danger-100 text-danger-700 border border-danger-300',
                                        default   => 'bg-warning-100 text-warning-700 border border-warning-300',
                                    };
                                @endphp
                                <tr class="text-black dark:text-white hover:bg-gray-50 dark:hover:bg-[#0a1020]">
                                    <td class="px-[16px] py-[12px]">
                                        <div class="flex items-center gap-[8px]">
                                            @if($avatarPago)
                                                <img src="{{ $avatarPago }}" class="w-[28px] h-[28px] rounded-full object-cover flex-shrink-0" alt="">
                                            @else
                                                <span class="w-[28px] h-[28px] rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-[11px] font-bold flex-shrink-0 text-gray-600 dark:text-gray-300">{{ mb_strtoupper(mb_substr($nombreMiembroPago, 0, 1)) }}</span>
                                            @endif
                                            <div class="min-w-0">
                                                <span class="block text-xs font-semibold truncate max-w-[150px]">{{ $pago->matricula?->institucionEducativa?->nombre_referencial ?? '(Sin inst.)' }}</span>
                                                <span class="block text-[11px] text-gray-400 truncate max-w-[150px]">{{ $nombreMiembroPago }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-[12px] py-[12px] text-gray-600 dark:text-gray-400 max-w-[150px] truncate">{{ $pago->concepto ?? '—' }}</td>
                                    <td class="px-[12px] py-[12px] text-gray-500 dark:text-gray-400 whitespace-nowrap font-mono text-xs">{{ $pago->mes_correspondiente ?? '—' }}</td>
                                    <td class="px-[12px] py-[12px] text-right font-semibold whitespace-nowrap">{{ $pago->moneda?->simbolo ?? 'S/' }} {{ number_format($pago->monto, 2) }}</td>
                                    <td class="px-[12px] py-[12px] text-gray-500 dark:text-gray-400 whitespace-nowrap text-xs font-mono">{{ $pago->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="px-[12px] py-[12px] text-gray-500 dark:text-gray-400 whitespace-nowrap text-xs font-mono">{{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="px-[12px] py-[12px] text-center">
                                        <span class="inline-block text-[11px] font-medium px-[8px] py-[2px] rounded-[100px] {{ $estadoClass }}">
                                            {{ ucfirst($pago->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-[12px] py-[12px] text-center">
                                        @if($pago->comprobante_path)
                                            <i class="material-symbols-outlined !text-[16px] text-success-500" title="{{ $pago->comprobante_nombre_original ?? 'Comprobante' }}">attach_file</i>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">—</span>
                                        @endif
                                    </td>
                                    <td class="px-[12px] py-[12px] text-center">
                                        <div class="flex items-center justify-center gap-[6px]">
                                            <a href="{{ route('dashboard.pagos-educativos.show', $pago) }}" class="text-primary-500 leading-none custom-tooltip" data-text="Ver">
                                                <i class="material-symbols-outlined !text-md">visibility</i>
                                            </a>
                                            <a href="{{ route('dashboard.pagos-educativos.edit', $pago) }}" class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip" data-text="Editar">
                                                <i class="material-symbols-outlined !text-md">edit</i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.pagos-educativos.destroy', $pago) }}" class="inline leading-none">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-danger-500 leading-none custom-tooltip" data-text="Eliminar"
                                                    onclick="return confirm('¿Eliminar este pago?')">
                                                    <i class="material-symbols-outlined !text-md">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center px-[20px] py-[50px] text-gray-500 dark:text-gray-400">
                                        <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">receipt_long</i>
                                        No hay pagos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pagos->hasPages())
                <div class="pt-[12px] sm:flex sm:items-center justify-between mt-[16px] border-t border-gray-100 dark:border-[#172036]">
                    <p class="!mb-0 text-xs">Mostrando {{ $pagos->firstItem() }}–{{ $pagos->lastItem() }} de {{ $pagos->total() }} resultados</p>
                    <ol class="mt-[10px] sm:mt-0">
                        <li class="inline-block mx-[1px]">
                            @if($pagos->onFirstPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i></span>
                            @else
                                <a href="{{ $pagos->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i></a>
                            @endif
                        </li>
                        @foreach($pagos->getUrlRange(max(1,$pagos->currentPage()-2), min($pagos->lastPage(),$pagos->currentPage()+2)) as $page => $url)
                        <li class="inline-block mx-[1px]">
                            @if($page == $pagos->currentPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                            @endif
                        </li>
                        @endforeach
                        <li class="inline-block mx-[1px]">
                            @if($pagos->hasMorePages())
                                <a href="{{ $pagos->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i></a>
                            @else
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i></span>
                            @endif
                        </li>
                    </ol>
                </div>
                @endif
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
