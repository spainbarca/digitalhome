<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Documentos Educativos</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Documentos Educativos</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Educación</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Documentos</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            {{-- Filtros --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <form method="GET" action="{{ route('dashboard.documentos-educativos.index') }}" class="flex flex-wrap items-end gap-[12px]">
                    <div class="min-w-[150px]">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Miembro</label>
                        <select name="hogar_miembro_id"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0 cursor-pointer w-full">
                            <option value="">Todos</option>
                            @foreach($miembros as $m)
                                @php $lbl = trim(implode(' ', array_filter([$m->user?->persona?->nombres, $m->user?->persona?->apellido_paterno, $m->user?->persona?->apellido_materno]))) ?: ($m->user?->name ?? 'Sin nombre'); @endphp
                                <option value="{{ $m->id }}" {{ $miembroId == $m->id ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-[160px]">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Tipo de documento</label>
                        <select name="tipo_documento_educativo_id"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0 cursor-pointer w-full">
                            <option value="">Todos</option>
                            @foreach($tiposDocumento as $td)
                                <option value="{{ $td->id }}" {{ $tipoId == $td->id ? 'selected' : '' }}>{{ $td->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Matrícula</label>
                        <select name="matricula_id"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0 cursor-pointer w-full">
                            <option value="">Todas</option>
                            @foreach($matriculas as $mat)
                                @php
                                    $matNM = trim(implode(' ', array_filter([$mat->hogarMiembro?->user?->persona?->nombres, $mat->hogarMiembro?->user?->persona?->apellido_paterno]))) ?: '';
                                    $matLbl = ($mat->institucionEducativa?->nombre_referencial ?? '(Sin inst.)')
                                        . ($mat->año_lectivo ? ' · ' . $mat->año_lectivo : '')
                                        . ($matNM ? ' — ' . $matNM : '');
                                @endphp
                                <option value="{{ $mat->id }}" {{ $matriculaId == $mat->id ? 'selected' : '' }}>{{ $matLbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Desde</label>
                        <input type="date" name="fecha_desde" value="{{ $fechaDesde }}"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-[6px]">Hasta</label>
                        <input type="date" name="fecha_hasta" value="{{ $fechaHasta }}"
                            class="h-[36px] text-xs rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#15203c] text-black dark:text-white px-[10px] outline-0">
                    </div>
                    <div class="flex gap-[8px]">
                        <button type="submit" class="h-[36px] px-[14px] bg-primary-500 text-white rounded-md text-xs hover:bg-primary-400 transition-all">Filtrar</button>
                        @if($miembroId || $tipoId || $matriculaId || $fechaDesde || $fechaHasta)
                        <a href="{{ route('dashboard.documentos-educativos.index') }}"
                            class="h-[36px] px-[14px] border border-gray-200 dark:border-[#172036] text-gray-500 dark:text-gray-400 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all flex items-center">
                            Limpiar
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabla --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <h6 class="!mb-0 font-semibold text-black dark:text-white flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">folder_open</i>
                        Documentos Educativos del Hogar
                    </h6>
                    <a href="{{ route('dashboard.documentos-educativos.create') }}"
                        class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">upload_file</i>
                            Subir Documento
                        </span>
                    </a>
                </div>

                <div class="table-responsive overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400 ltr:first:pl-0">Tipo</th>
                                <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400">Título</th>
                                <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400">Miembro</th>
                                <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400">Matrícula</th>
                                <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400">Fecha</th>
                                <th class="whitespace-nowrap uppercase text-[10px] font-bold tracking-[1px] ltr:text-left pt-0 pb-[12.5px] px-[20px] text-gray-500 dark:text-gray-400 ltr:last:pr-0">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-black dark:text-white">
                            @forelse($documentos as $doc)
                            @php
                                $docIcono   = $doc->tipoDocumentoEducativo?->icono ?? 'description';
                                $docMiembro = trim(implode(' ', array_filter([$doc->hogarMiembro?->user?->persona?->nombres, $doc->hogarMiembro?->user?->persona?->apellido_paterno, $doc->hogarMiembro?->user?->persona?->apellido_materno]))) ?: ($doc->hogarMiembro?->user?->name ?? '—');
                                $esImagen   = $doc->archivo_path && str_starts_with($doc->archivo_mime ?? '', 'image/');
                            @endphp
                            <tr>
                                <td class="ltr:text-left whitespace-nowrap px-[20px] py-[12.5px] ltr:first:pl-0 border-b border-primary-50 dark:border-[#172036]">
                                    <div class="w-[36px] h-[36px] rounded-md bg-primary-50 dark:bg-[#15203c] flex items-center justify-center">
                                        <i class="material-symbols-outlined !text-[18px] text-primary-500">{{ $docIcono }}</i>
                                    </div>
                                </td>
                                <td class="ltr:text-left px-[20px] py-[12.5px] border-b border-primary-50 dark:border-[#172036] max-w-[220px]">
                                    <a href="{{ route('dashboard.documentos-educativos.show', $doc) }}"
                                        class="block text-sm font-semibold text-black dark:text-white hover:text-primary-500 transition-all truncate">
                                        {{ $doc->titulo }}
                                    </a>
                                    @if($doc->tipoDocumentoEducativo)
                                    <span class="block text-xs text-gray-400 mt-[2px]">{{ $doc->tipoDocumentoEducativo->nombre }}</span>
                                    @endif
                                    @if($esImagen)
                                    <span class="inline-flex items-center gap-[3px] text-[10px] text-success-600 mt-[2px]">
                                        <i class="material-symbols-outlined !text-[11px]">image</i> imagen
                                    </span>
                                    @elseif($doc->archivo_mime === 'application/pdf')
                                    <span class="inline-flex items-center gap-[3px] text-[10px] text-danger-600 mt-[2px]">
                                        <i class="material-symbols-outlined !text-[11px]">picture_as_pdf</i> PDF
                                    </span>
                                    @elseif($doc->archivo_path)
                                    <span class="inline-flex items-center gap-[3px] text-[10px] text-gray-400 mt-[2px]">
                                        <i class="material-symbols-outlined !text-[11px]">attach_file</i> archivo
                                    </span>
                                    @endif
                                </td>
                                <td class="ltr:text-left whitespace-nowrap px-[20px] py-[12.5px] border-b border-primary-50 dark:border-[#172036]">
                                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-400">{{ $docMiembro }}</span>
                                </td>
                                <td class="ltr:text-left px-[20px] py-[12.5px] border-b border-primary-50 dark:border-[#172036] max-w-[180px]">
                                    @if($doc->matricula)
                                    <span class="block text-xs text-gray-600 dark:text-gray-400 truncate">{{ $doc->matricula->institucionEducativa?->nombre_referencial ?? '(Sin inst.)' }}</span>
                                    @if($doc->matricula->año_lectivo)
                                        <span class="block text-[10px] text-gray-400">{{ $doc->matricula->año_lectivo }}</span>
                                    @endif
                                    @else
                                    <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="ltr:text-left whitespace-nowrap px-[20px] py-[12.5px] border-b border-primary-50 dark:border-[#172036]">
                                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 font-mono">
                                        {{ $doc->fecha_documento?->format('d/m/Y') ?? '—' }}
                                    </span>
                                </td>
                                <td class="ltr:text-left whitespace-nowrap px-[20px] py-[12.5px] border-b border-primary-50 dark:border-[#172036] ltr:last:pr-0">
                                    <div class="flex items-center gap-[9px]">
                                        <a href="{{ route('dashboard.documentos-educativos.show', $doc) }}"
                                            class="text-primary-500 leading-none custom-tooltip" data-text="Ver">
                                            <i class="material-symbols-outlined !text-md">visibility</i>
                                        </a>
                                        <a href="{{ route('dashboard.documentos-educativos.edit', $doc) }}"
                                            class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip" data-text="Editar">
                                            <i class="material-symbols-outlined !text-md">edit</i>
                                        </a>
                                        <form method="POST" action="{{ route('dashboard.documentos-educativos.destroy', $doc) }}" class="inline leading-none">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-danger-500 leading-none custom-tooltip" data-text="Eliminar"
                                                onclick="return confirm('¿Eliminar «{{ addslashes($doc->titulo) }}»?')">
                                                <i class="material-symbols-outlined !text-md">delete</i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center px-[20px] py-[50px] text-gray-500 dark:text-gray-400">
                                    <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">folder_open</i>
                                    No hay documentos educativos registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($documentos->hasPages())
                <div class="pt-[12.5px] sm:flex sm:items-center justify-between">
                    <p class="!mb-0 text-xs">Mostrando {{ $documentos->firstItem() }}–{{ $documentos->lastItem() }} de {{ $documentos->total() }} resultados</p>
                    <ol class="mt-[10px] sm:mt-0">
                        <li class="inline-block mx-[1px]">
                            @if($documentos->onFirstPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i></span>
                            @else
                                <a href="{{ $documentos->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"><span class="opacity-0">0</span><i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i></a>
                            @endif
                        </li>
                        @foreach($documentos->getUrlRange(max(1,$documentos->currentPage()-2), min($documentos->lastPage(),$documentos->currentPage()+2)) as $page => $url)
                        <li class="inline-block mx-[1px]">
                            @if($page == $documentos->currentPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
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

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
