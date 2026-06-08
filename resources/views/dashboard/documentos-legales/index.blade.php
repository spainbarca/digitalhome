<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Documentos Legales</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Documentos Legales</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Legal</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Documentos</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            <!-- Filtros -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <form method="GET" action="{{ route('dashboard.documentos-legales.index') }}" class="flex flex-wrap items-end gap-[12px]">

                    <div class="min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Categoría</label>
                        <select name="categoria"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0 cursor-pointer w-full">
                            <option value="">Todas</option>
                            @foreach($categorias as $val => $lbl)
                                <option value="{{ $val }}" {{ $categoria === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="min-w-[160px]">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Estado</label>
                        <select name="estado_documento_legal_id"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0 cursor-pointer w-full">
                            <option value="">Todos</option>
                            @foreach($estados as $e)
                                <option value="{{ $e->id }}" {{ $estadoId == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="min-w-[150px]">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Miembro</label>
                        <select name="hogar_miembro_id"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0 cursor-pointer w-full">
                            <option value="">Todos</option>
                            @foreach($miembros as $m)
                                @php $lbl = trim(implode(' ', array_filter([$m->user?->persona?->nombres, $m->user?->persona?->apellido_paterno]))) ?: ($m->user?->name ?? 'Sin nombre'); @endphp
                                <option value="{{ $m->id }}" {{ $miembroId == $m->id ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="min-w-[160px]">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Propiedad</label>
                        <select name="propiedad_inmueble_id"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0 cursor-pointer w-full">
                            <option value="">Todas</option>
                            @foreach($propiedades as $prop)
                                <option value="{{ $prop->id }}" {{ $propiedadId == $prop->id ? 'selected' : '' }}>{{ $prop->alias }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-[8px]">
                        <button type="submit" class="h-[36px] px-[14px] bg-primary-500 text-white rounded-md text-xs hover:bg-primary-400 transition-all">Filtrar</button>
                        @if($categoria || $estadoId || $miembroId || $propiedadId)
                        <a href="{{ route('dashboard.documentos-legales.index') }}"
                            class="h-[36px] px-[14px] border border-gray-200 dark:border-[#172036] text-gray-500 dark:text-gray-400 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all flex items-center">
                            Limpiar
                        </a>
                        @endif
                    </div>

                </form>
            </div>

            <!-- Tabla -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                    <h6 class="!mb-0 font-semibold text-black dark:text-white flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">gavel</i>
                        Documentos del Hogar
                    </h6>
                    <a href="{{ route('dashboard.documentos-legales.create') }}"
                        class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white text-sm">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">upload_file</i>
                            Nuevo Documento
                        </span>
                    </a>
                </div>

                <!-- Leyenda -->
                <div class="flex items-center gap-[16px] mb-[16px] text-xs">
                    <span class="flex items-center gap-[5px]">
                        <span class="inline-block w-[12px] h-[12px] rounded-sm bg-danger-100 border border-danger-300"></span>
                        Vencido
                    </span>
                    <span class="flex items-center gap-[5px]">
                        <span class="inline-block w-[12px] h-[12px] rounded-sm bg-orange-100 border border-orange-300"></span>
                        Por vencer (≤30 días)
                    </span>
                </div>

                <div class="trezo-card-content">
                    <div class="table-responsive overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12px] px-[14px] text-gray-500 dark:text-gray-400 ltr:first:pl-0">Título</th>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12px] px-[14px] text-gray-500 dark:text-gray-400">Tipo / Categoría</th>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12px] px-[14px] text-gray-500 dark:text-gray-400">Vinculado a</th>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12px] px-[14px] text-gray-500 dark:text-gray-400">Estado</th>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12px] px-[14px] text-gray-500 dark:text-gray-400">Vencimiento</th>
                                    <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12px] px-[14px] text-gray-500 dark:text-gray-400 ltr:last:pr-0">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-black dark:text-white">
                                @forelse($documentos as $doc)
                                @php
                                    $hoy = now()->startOfDay();
                                    $vence = $doc->fecha_vencimiento;
                                    if ($vence && $vence->lt($hoy)) {
                                        $rowBg = 'bg-danger-50 dark:bg-danger-900/10';
                                    } elseif ($vence && $vence->lte($hoy->copy()->addDays(30))) {
                                        $rowBg = 'bg-orange-50 dark:bg-orange-900/10';
                                    } else {
                                        $rowBg = '';
                                    }

                                    $estadoColor = $doc->estadoDocumentoLegal?->color ?? 'gray';
                                    $badgeCls = match($estadoColor) {
                                        'green'  => 'text-success-600 bg-success-100 border-success-200',
                                        'orange' => 'text-orange-600 bg-orange-100 border-orange-200',
                                        'red'    => 'text-danger-600 bg-danger-100 border-danger-200',
                                        'blue'   => 'text-primary-600 bg-primary-100 border-primary-200',
                                        default  => 'text-gray-600 bg-gray-100 border-gray-200',
                                    };

                                    $tipoIcono = $doc->tipoDocumentoLegal?->icono ?? 'description';
                                    $categoriasMap = ['personal'=>'Personal','propiedad'=>'Propiedad','seguro'=>'Seguro','contrato'=>'Contrato','denuncia'=>'Denuncia','otro'=>'Otro'];
                                    $catLabel = $categoriasMap[$doc->tipoDocumentoLegal?->categoria ?? ''] ?? '—';

                                    $miembroNombre = '';
                                    if ($doc->hogarMiembro) {
                                        $miembroNombre = trim(implode(' ', array_filter([
                                            $doc->hogarMiembro?->user?->persona?->nombres,
                                            $doc->hogarMiembro?->user?->persona?->apellido_paterno,
                                        ]))) ?: ($doc->hogarMiembro?->user?->name ?? '');
                                    }
                                @endphp
                                <tr class="{{ $rowBg }} transition-colors">
                                    <td class="ltr:text-left px-[14px] py-[11px] ltr:first:pl-0 border-b border-primary-50 dark:border-[#172036]">
                                        <div class="flex items-center gap-[8px]">
                                            @if($doc->file_path)
                                            <i class="material-symbols-outlined !text-[16px] text-gray-400 flex-shrink-0">attach_file</i>
                                            @endif
                                            <a href="{{ route('dashboard.documentos-legales.show', $doc) }}"
                                                class="text-sm font-medium hover:text-primary-500 transition-all">
                                                {{ $doc->titulo }}
                                            </a>
                                        </div>
                                        @if($doc->numero_documento)
                                        <span class="block text-xs text-gray-400 font-mono mt-[2px]">{{ $doc->numero_documento }}</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left px-[14px] py-[11px] border-b border-primary-50 dark:border-[#172036] whitespace-nowrap">
                                        <div class="flex items-center gap-[5px] text-sm">
                                            <i class="material-symbols-outlined !text-[15px] text-primary-500">{{ $tipoIcono }}</i>
                                            <span class="truncate max-w-[120px]">{{ $doc->tipoDocumentoLegal?->nombre ?? '—' }}</span>
                                        </div>
                                        <span class="block text-xs text-gray-400 mt-[2px]">{{ $catLabel }}</span>
                                    </td>
                                    <td class="ltr:text-left px-[14px] py-[11px] border-b border-primary-50 dark:border-[#172036] text-sm">
                                        @if($miembroNombre)
                                        <div class="flex items-center gap-[4px]">
                                            <i class="material-symbols-outlined !text-[13px] text-gray-400">person</i>
                                            <span>{{ $miembroNombre }}</span>
                                        </div>
                                        @elseif($doc->propiedadInmueble)
                                        <div class="flex items-center gap-[4px]">
                                            <i class="material-symbols-outlined !text-[13px] text-gray-400">{{ $doc->propiedadInmueble->tipoInmueble?->icono ?? 'home' }}</i>
                                            <span>{{ $doc->propiedadInmueble->alias }}</span>
                                        </div>
                                        @else
                                        <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left px-[14px] py-[11px] border-b border-primary-50 dark:border-[#172036] whitespace-nowrap">
                                        @if($doc->estadoDocumentoLegal)
                                        <span class="text-xs font-medium border rounded-full px-[8px] py-[2px] {{ $badgeCls }}">
                                            {{ $doc->estadoDocumentoLegal->nombre }}
                                        </span>
                                        @else
                                        <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left px-[14px] py-[11px] border-b border-primary-50 dark:border-[#172036] whitespace-nowrap text-sm font-mono">
                                        @if($vence)
                                            <span class="{{ $vence->lt($hoy) ? 'text-danger-600 font-semibold' : ($vence->lte($hoy->copy()->addDays(30)) ? 'text-orange-600 font-semibold' : '') }}">
                                                {{ $vence->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="ltr:text-left px-[14px] py-[11px] ltr:last:pr-0 border-b border-primary-50 dark:border-[#172036]">
                                        <div class="flex items-center gap-[4px]">
                                            <a href="{{ route('dashboard.documentos-legales.show', $doc) }}"
                                                class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-50 dark:bg-[#15203c] hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all text-gray-500 hover:text-primary-500"
                                                title="Ver">
                                                <i class="material-symbols-outlined !text-[15px]">visibility</i>
                                            </a>
                                            <a href="{{ route('dashboard.documentos-legales.edit', $doc) }}"
                                                class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-50 dark:bg-[#15203c] hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all text-gray-500 hover:text-primary-500"
                                                title="Editar">
                                                <i class="material-symbols-outlined !text-[15px]">edit</i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.documentos-legales.destroy', $doc) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('¿Eliminar «{{ addslashes($doc->titulo) }}»?')"
                                                    class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-50 dark:bg-[#15203c] hover:bg-danger-50 dark:hover:bg-danger-900/20 transition-all text-danger-500"
                                                    title="Eliminar">
                                                    <i class="material-symbols-outlined !text-[15px]">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-[40px] text-gray-400">
                                        <i class="material-symbols-outlined !text-[40px] block mb-[8px]">gavel</i>
                                        No se encontraron documentos legales.
                                        @if(!$categoria && !$estadoId && !$miembroId && !$propiedadId)
                                        <div class="mt-[12px]">
                                            <a href="{{ route('dashboard.documentos-legales.create') }}"
                                                class="inline-block bg-primary-500 text-white px-[16px] py-[8px] rounded-md text-sm hover:bg-primary-400 transition-all">
                                                Registrar primer documento
                                            </a>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($documentos->hasPages())
                    <div class="sm:flex sm:items-center justify-between mt-[20px] pt-[14px] border-t border-gray-100 dark:border-[#172036]">
                        <p class="!mb-0 text-sm text-gray-500">
                            Mostrando {{ $documentos->firstItem() }}–{{ $documentos->lastItem() }} de {{ $documentos->total() }}
                        </p>
                        <ol class="mt-[10px] sm:mt-0">
                            <li class="inline-block mx-[1px]">
                                @if($documentos->onFirstPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i></span>
                                @else
                                    <a href="{{ $documentos->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i></a>
                                @endif
                            </li>
                            @foreach($documentos->getUrlRange(max(1, $documentos->currentPage()-2), min($documentos->lastPage(), $documentos->currentPage()+2)) as $page => $url)
                            <li class="inline-block mx-[1px]">
                                @if($page == $documentos->currentPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                                @endif
                            </li>
                            @endforeach
                            <li class="inline-block mx-[1px]">
                                @if($documentos->hasMorePages())
                                    <a href="{{ $documentos->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i></a>
                                @else
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i></span>
                                @endif
                            </li>
                        </ol>
                    </div>
                    @endif
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
