<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Conceptos de Pago</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Conceptos de Pago</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Préstamos Personales</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Conceptos de Pago</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            <!-- Filtros + botón nuevo -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex flex-wrap items-center gap-[12px] justify-between">
                    <form method="GET" action="{{ route('dashboard.prestamos.conceptos-pago.index') }}" class="flex flex-wrap items-center gap-[10px]">
                        <div class="relative sm:w-[220px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Buscar por nombre..."
                                class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </div>
                        <button type="submit"
                            class="h-[36px] px-[14px] bg-primary-500 text-white rounded-md text-xs hover:bg-primary-400 transition-all">
                            Filtrar
                        </button>
                        @if(request('q') || request('categoria_id'))
                        <a href="{{ route('dashboard.prestamos.conceptos-pago.index') }}"
                            class="h-[36px] px-[14px] border border-gray-200 dark:border-[#172036] text-gray-500 dark:text-gray-400 rounded-md text-xs hover:bg-gray-50 dark:hover:bg-[#15203c] transition-all flex items-center">
                            Limpiar
                        </a>
                        @endif
                    </form>

                    <a href="{{ route('dashboard.prestamos.conceptos-pago.create') }}"
                        class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                            Nuevo Concepto
                        </span>
                    </a>
                </div>

                {{-- Pills de categoría --}}
                @if($categorias->isNotEmpty())
                <div class="flex flex-wrap items-center gap-[8px] mt-[14px] pt-[14px] border-t border-gray-100 dark:border-[#172036]">
                    <a href="{{ route('dashboard.prestamos.conceptos-pago.index', array_filter(['q' => request('q')])) }}"
                        class="inline-flex items-center gap-[5px] px-[12px] py-[5px] rounded-full text-xs font-medium transition-all
                            {{ !request('categoria_id') ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-[#15203c] text-gray-600 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-[#172036]' }}">
                        <i class="material-symbols-outlined !text-[14px]">payments</i>
                        Todas
                    </a>
                    @foreach($categorias as $cat)
                    <a href="{{ route('dashboard.prestamos.conceptos-pago.index', array_filter(['q' => request('q'), 'categoria_id' => $cat->id])) }}"
                        class="inline-flex items-center gap-[5px] px-[12px] py-[5px] rounded-full text-xs font-medium transition-all
                            {{ request('categoria_id') == $cat->id ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-[#15203c] text-gray-600 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-[#172036]' }}">
                        <i class="material-symbols-outlined !text-[14px]">{{ $cat->icono ?? 'label' }}</i>
                        {{ $cat->nombre }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Cards grid -->
            <div class="trezo-card mb-[25px]">
                <div class="trezo-card-content">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-[25px]">
                        @forelse($conceptos as $concepto)
                        @php
                            $colores  = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                            $bgColor  = $colores[abs(crc32($concepto->id)) % count($colores)];
                            $catIcono = $concepto->categoriaConcepto?->icono ?? 'payments';
                        @endphp
                        <div class="bg-white dark:bg-[#0c1427] p-[10px] rounded-md" id="card-{{ $concepto->id }}">
                            <!-- Banner / imagen -->
                            <div class="relative h-[160px] rounded-md overflow-hidden">
                                @if($concepto->imagen_url)
                                    <img src="{{ $concepto->imagen_url }}" class="w-full h-full object-cover object-top" alt="{{ $concepto->nombre }}">
                                @else
                                    <div class="w-full h-full {{ $bgColor }} flex items-center justify-center">
                                        <i class="material-symbols-outlined !text-[56px] text-white opacity-25">{{ $catIcono }}</i>
                                    </div>
                                @endif
                            </div>
                            <!-- Info -->
                            <div class="text-center -mt-[26px]">
                                <div class="relative mb-[10px] inline-flex items-center justify-center w-[52px] h-[52px] rounded-full bg-white dark:bg-[#0c1427] ring-2 ring-white dark:ring-[#0c1427] shadow-sm">
                                    <i class="material-symbols-outlined !text-[26px] text-primary-500">{{ $catIcono }}</i>
                                </div>
                                <h6 class="!text-sm !font-semibold !mb-[3px] truncate px-[8px]" title="{{ $concepto->nombre }}">
                                    {{ $concepto->nombre }}
                                </h6>
                                <span class="block text-xs text-gray-400 mb-[4px] truncate px-[8px]">
                                    {{ $concepto->categoriaConcepto?->nombre ?? 'Sin categoría' }}
                                </span>
                                @if($concepto->precio_referencial)
                                <span class="block text-xs font-semibold text-primary-500 mb-[10px]">
                                    S/ {{ number_format($concepto->precio_referencial, 2) }}
                                </span>
                                @else
                                <span class="block text-xs text-gray-300 dark:text-gray-600 mb-[10px]">—</span>
                                @endif

                                <div class="h-[1px] bg-primary-50 dark:bg-[#172036] mb-[12px]"></div>

                                <div class="flex items-center justify-between gap-[6px] px-[4px]">
                                    <!-- Toggle activo -->
                                    <label class="relative cursor-pointer" title="{{ $concepto->activo ? 'Activo — clic para desactivar' : 'Inactivo — clic para activar' }}">
                                        <input type="checkbox"
                                            class="sr-only peer concepto-toggle"
                                            data-id="{{ $concepto->id }}"
                                            data-url="{{ route('dashboard.prestamos.conceptos-pago.toggle-activo', $concepto) }}"
                                            {{ $concepto->activo ? 'checked' : '' }}>
                                        <div class="w-[36px] h-[20px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all peer-checked:bg-success-500"></div>
                                    </label>
                                    <a href="{{ route('dashboard.prestamos.conceptos-pago.show', $concepto) }}"
                                        class="inline-block rounded-[7px] py-[5px] px-[13px] font-medium text-sm bg-primary-500 text-white transition-all hover:bg-primary-400">
                                        Ver detalle
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-1 sm:col-span-2 md:col-span-3 xl:col-span-4 text-center py-[50px] text-gray-400">
                            <i class="material-symbols-outlined !text-[56px] mb-[10px] block text-gray-300 dark:text-gray-600">payments</i>
                            @if(request('q') || request('categoria_id'))
                                No se encontraron conceptos con ese criterio.
                            @else
                                No hay conceptos de pago registrados aún.
                            @endif
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            @if($conceptos->hasPages())
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="sm:flex sm:items-center justify-between">
                    <p class="!mb-0 text-sm">
                        Mostrando {{ $conceptos->firstItem() }}–{{ $conceptos->lastItem() }} de {{ $conceptos->total() }} resultados
                    </p>
                    <ol class="mt-[10px] sm:mt-0">
                        <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0">
                            @if($conceptos->onFirstPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                </span>
                            @else
                                <a href="{{ $conceptos->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                    <span class="opacity-0">0</span>
                                    <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                </a>
                            @endif
                        </li>
                        @foreach($conceptos->getUrlRange(max(1,$conceptos->currentPage()-2), min($conceptos->lastPage(),$conceptos->currentPage()+2)) as $page => $url)
                        <li class="inline-block mx-[1px]">
                            @if($page == $conceptos->currentPage())
                                <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">{{ $page }}</a>
                            @endif
                        </li>
                        @endforeach
                        <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0">
                            @if($conceptos->hasMorePages())
                                <a href="{{ $conceptos->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
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
            </div>
            @endif

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script>
            document.querySelectorAll('.concepto-toggle').forEach(function (toggle) {
                toggle.addEventListener('change', function () {
                    const url   = this.dataset.url;
                    const check = this;
                    fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                ?? '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(r => r.json())
                    .then(data => {
                        check.checked = data.activo;
                    })
                    .catch(() => {
                        check.checked = !check.checked;
                    });
                });
            });
        </script>
    </body>
</html>
