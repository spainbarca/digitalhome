<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('partials.front.styles')
        <title>Recordatorios</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb + Header -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Recordatorios</h5>
                <div class="flex items-center gap-[12px] mt-[12px] md:mt-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                            <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                                <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Recordatorios</li>
                    </ol>
                    <a href="{{ route('dashboard.recordatorios.create') }}"
                       class="inline-block bg-primary-500 text-white py-[7px] px-[14px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                        <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                            <i class="material-symbols-outlined !text-[15px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">add</i>
                            Nuevo Recordatorio
                        </span>
                    </a>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-success-700 hover:text-success-900">
                    <i class="material-symbols-outlined !text-lg">close</i>
                </button>
            </div>
            @endif

            @php
            $totalPendientes = $vencidos->count() + $proximos->count();
            $hoy = today();
            @endphp

            <!-- Mini-stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-[16px] mb-[25px]">
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[16px] rounded-md flex items-center gap-[14px]">
                    <div class="w-[48px] h-[48px] rounded-md bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                        <i class="material-symbols-outlined !text-[24px] text-primary-500">notifications</i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-[2px]">Total pendientes</p>
                        <p class="text-2xl font-bold text-black dark:text-white">{{ $totalPendientes }}</p>
                    </div>
                </div>
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[16px] rounded-md flex items-center gap-[14px]">
                    <div class="w-[48px] h-[48px] rounded-md bg-danger-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                        <i class="material-symbols-outlined !text-[24px] text-danger-500">warning</i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-[2px]">Vencidos</p>
                        <p class="text-2xl font-bold text-danger-600">{{ $vencidos->count() }}</p>
                    </div>
                </div>
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[16px] rounded-md flex items-center gap-[14px]">
                    <div class="w-[48px] h-[48px] rounded-md bg-orange-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                        <i class="material-symbols-outlined !text-[24px] text-orange-500">schedule</i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-[2px]">Próximos 30 días</p>
                        <p class="text-2xl font-bold text-orange-500">{{ $proximos->count() }}</p>
                    </div>
                </div>
            </div>

            @if($vencidos->isEmpty() && $proximos->isEmpty() && $futuros->isEmpty() && $descartados->isEmpty())
            <!-- Estado vacío -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[25px] rounded-md">
                <div class="text-center py-[60px]">
                    <i class="material-symbols-outlined !text-[72px] text-gray-300 dark:text-gray-600 block mb-[16px]">notifications_off</i>
                    <p class="text-gray-500 dark:text-gray-400 text-lg font-medium mb-[8px]">No tienes recordatorios pendientes</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mb-[24px]">Crea tu primer recordatorio para fechas importantes.</p>
                    <a href="{{ route('dashboard.recordatorios.create') }}"
                       class="inline-block bg-primary-500 text-white py-[10px] px-[24px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                        <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                            <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">add</i>
                            Crear primer recordatorio
                        </span>
                    </a>
                </div>
            </div>
            @else

            {{-- ─── SECCIÓN: Vencidos ──────────────────────────────────────── --}}
            @if($vencidos->isNotEmpty())
            <div class="mb-[25px]">
                <div class="flex items-center gap-[8px] mb-[14px]">
                    <i class="material-symbols-outlined !text-[20px] text-danger-500">warning</i>
                    <h6 class="!mb-0 text-danger-600">Vencidos <span class="text-gray-400 font-normal text-sm">({{ $vencidos->count() }})</span></h6>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[14px]">
                    @foreach($vencidos as $rec)
                    @php $diasVencido = $rec->fecha_vencimiento->diffInDays($hoy); @endphp
                    <div class="bg-white dark:bg-[#0c1427] rounded-md border-l-4 border-danger-500 shadow-sm p-[16px]">
                        <div class="flex items-center gap-[6px] flex-wrap mb-[10px]">
                            <span class="inline-block text-xs font-medium py-[2px] px-[8px] rounded-full text-white bg-danger-500">VENCIDO</span>
                            <span class="text-xs text-danger-500">Hace {{ $diasVencido }} {{ $diasVencido === 1 ? 'día' : 'días' }}</span>
                            @if($rec->repetir)
                            <span class="inline-flex items-center gap-[3px] text-xs text-primary-500 ml-auto">
                                <i class="material-symbols-outlined !text-[13px]">autorenew</i>Anual
                            </span>
                            @endif
                        </div>
                        <h6 class="font-semibold text-black dark:text-white text-sm mb-[6px] line-clamp-1">{{ $rec->titulo }}</h6>
                        @if($rec->descripcion)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-[10px] line-clamp-2">{{ $rec->descripcion }}</p>
                        @endif
                        <div class="flex items-center gap-[4px] text-xs text-danger-500 mb-[14px]">
                            <i class="material-symbols-outlined !text-[13px]">calendar_today</i>
                            {{ $rec->fecha_vencimiento?->format('d/m/Y') }}
                        </div>
                        <div class="flex items-center gap-[4px] flex-wrap">
                            <a href="{{ route('dashboard.recordatorios.show', $rec) }}"
                               class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-primary-100 transition-all text-gray-500 hover:text-primary-600" title="Ver">
                                <i class="material-symbols-outlined !text-[14px]">visibility</i>
                            </a>
                            <a href="{{ route('dashboard.recordatorios.edit', $rec) }}"
                               class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-warning-100 transition-all text-gray-500 hover:text-warning-600" title="Editar">
                                <i class="material-symbols-outlined !text-[14px]">edit</i>
                            </a>
                            @if($rec->repetir)
                            <button onclick="accionRecordatorio('renovar','{{ route('dashboard.recordatorios.renovar', $rec) }}')"
                                class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-primary-100 transition-all text-gray-500 hover:text-primary-600" title="Renovar un año">
                                <i class="material-symbols-outlined !text-[14px]">autorenew</i>
                            </button>
                            @endif
                            <button onclick="accionRecordatorio('descartar','{{ route('dashboard.recordatorios.descartar', $rec) }}')"
                                class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-danger-100 transition-all text-gray-500 hover:text-danger-600" title="Descartar">
                                <i class="material-symbols-outlined !text-[14px]">archive</i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ─── SECCIÓN: Próximos 30 días ─────────────────────────────── --}}
            @if($proximos->isNotEmpty())
            <div class="mb-[25px]">
                <div class="flex items-center gap-[8px] mb-[14px]">
                    <i class="material-symbols-outlined !text-[20px] text-orange-500">schedule</i>
                    <h6 class="!mb-0 text-orange-600">Próximos 30 días <span class="text-gray-400 font-normal text-sm">({{ $proximos->count() }})</span></h6>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[14px]">
                    @foreach($proximos as $rec)
                    @php
                    $diasRestantes = $hoy->diffInDays($rec->fecha_vencimiento);
                    @endphp
                    <div class="bg-white dark:bg-[#0c1427] rounded-md border-l-4 border-orange-400 shadow-sm p-[16px]">
                        <div class="flex items-center gap-[6px] flex-wrap mb-[10px]">
                            @if($diasRestantes === 0)
                                <span class="inline-block text-xs font-medium py-[2px] px-[8px] rounded-full text-white bg-danger-500">¡Hoy!</span>
                            @elseif($diasRestantes === 1)
                                <span class="inline-block text-xs font-medium py-[2px] px-[8px] rounded-full text-white bg-orange-500">¡Mañana!</span>
                            @else
                                <span class="inline-block text-xs font-medium py-[2px] px-[8px] rounded-full bg-orange-100 text-orange-700">En {{ $diasRestantes }} días</span>
                            @endif
                            @if($rec->repetir)
                            <span class="inline-flex items-center gap-[3px] text-xs text-primary-500 ml-auto">
                                <i class="material-symbols-outlined !text-[13px]">autorenew</i>Anual
                            </span>
                            @endif
                        </div>
                        <h6 class="font-semibold text-black dark:text-white text-sm mb-[6px] line-clamp-1">{{ $rec->titulo }}</h6>
                        @if($rec->descripcion)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-[10px] line-clamp-2">{{ $rec->descripcion }}</p>
                        @endif
                        <div class="flex items-center gap-[4px] text-xs text-gray-500 mb-[14px]">
                            <i class="material-symbols-outlined !text-[13px]">calendar_today</i>
                            {{ $rec->fecha_vencimiento?->format('d/m/Y') }}
                        </div>
                        <div class="flex items-center gap-[4px] flex-wrap">
                            <a href="{{ route('dashboard.recordatorios.show', $rec) }}"
                               class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-primary-100 transition-all text-gray-500 hover:text-primary-600" title="Ver">
                                <i class="material-symbols-outlined !text-[14px]">visibility</i>
                            </a>
                            <a href="{{ route('dashboard.recordatorios.edit', $rec) }}"
                               class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-warning-100 transition-all text-gray-500 hover:text-warning-600" title="Editar">
                                <i class="material-symbols-outlined !text-[14px]">edit</i>
                            </a>
                            <button onclick="accionRecordatorio('descartar','{{ route('dashboard.recordatorios.descartar', $rec) }}')"
                                class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-danger-100 transition-all text-gray-500 hover:text-danger-600" title="Descartar">
                                <i class="material-symbols-outlined !text-[14px]">archive</i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ─── SECCIÓN: Futuros ──────────────────────────────────────── --}}
            @if($futuros->isNotEmpty())
            <div class="mb-[25px]">
                <div class="flex items-center gap-[8px] mb-[14px]">
                    <i class="material-symbols-outlined !text-[20px] text-gray-400">calendar_month</i>
                    <h6 class="!mb-0 text-gray-600 dark:text-gray-400">Futuros <span class="text-gray-400 font-normal text-sm">({{ $futuros->count() }})</span></h6>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[14px]">
                    @foreach($futuros as $rec)
                    <div class="bg-white dark:bg-[#0c1427] rounded-md border-l-4 border-gray-300 dark:border-gray-600 shadow-sm p-[16px]">
                        <div class="flex items-center gap-[6px] flex-wrap mb-[10px]">
                            <span class="inline-block text-xs font-medium py-[2px] px-[8px] rounded-full bg-gray-100 text-gray-500 dark:bg-[#15203c] dark:text-gray-400">
                                {{ $rec->fecha_vencimiento?->format('d/m/Y') }}
                            </span>
                            @if($rec->repetir)
                            <span class="inline-flex items-center gap-[3px] text-xs text-primary-500 ml-auto">
                                <i class="material-symbols-outlined !text-[13px]">autorenew</i>Anual
                            </span>
                            @endif
                        </div>
                        <h6 class="font-semibold text-black dark:text-white text-sm mb-[6px] line-clamp-1">{{ $rec->titulo }}</h6>
                        @if($rec->descripcion)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-[10px] line-clamp-2">{{ $rec->descripcion }}</p>
                        @endif
                        <div class="flex items-center gap-[4px] flex-wrap">
                            <a href="{{ route('dashboard.recordatorios.show', $rec) }}"
                               class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-primary-100 transition-all text-gray-500 hover:text-primary-600" title="Ver">
                                <i class="material-symbols-outlined !text-[14px]">visibility</i>
                            </a>
                            <a href="{{ route('dashboard.recordatorios.edit', $rec) }}"
                               class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-warning-100 transition-all text-gray-500 hover:text-warning-600" title="Editar">
                                <i class="material-symbols-outlined !text-[14px]">edit</i>
                            </a>
                            <button onclick="accionRecordatorio('descartar','{{ route('dashboard.recordatorios.descartar', $rec) }}')"
                                class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-100 dark:bg-[#15203c] hover:bg-danger-100 transition-all text-gray-500 hover:text-danger-600" title="Descartar">
                                <i class="material-symbols-outlined !text-[14px]">archive</i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ─── SECCIÓN: Descartados (colapsable) ─────────────────────── --}}
            @if($descartados->isNotEmpty())
            <div class="mb-[25px]">
                <button id="btnDescartados"
                    onclick="toggleDescartados()"
                    class="flex items-center gap-[8px] text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-all mb-[14px]">
                    <i class="material-symbols-outlined !text-[18px]">archive</i>
                    Ver descartados ({{ $descartados->count() }})
                    <i class="material-symbols-outlined !text-[18px] transition-transform duration-200" id="iconDescartados">expand_more</i>
                </button>
                <div id="seccionDescartados" class="hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[14px]">
                        @foreach($descartados as $rec)
                        <div class="bg-gray-50 dark:bg-[#0c1427] rounded-md border-l-4 border-gray-300 dark:border-gray-700 p-[16px] opacity-70">
                            <div class="flex items-center gap-[6px] mb-[10px]">
                                <span class="inline-block text-xs font-medium py-[2px] px-[8px] rounded-full bg-gray-200 text-gray-500 dark:bg-[#15203c] dark:text-gray-500">Descartado</span>
                            </div>
                            <h6 class="font-semibold text-gray-600 dark:text-gray-400 text-sm mb-[6px] line-clamp-1">{{ $rec->titulo }}</h6>
                            <div class="flex items-center gap-[4px] text-xs text-gray-400 mb-[12px]">
                                <i class="material-symbols-outlined !text-[13px]">calendar_today</i>
                                {{ $rec->fecha_vencimiento?->format('d/m/Y') }}
                            </div>
                            <div class="flex items-center gap-[4px]">
                                <a href="{{ route('dashboard.recordatorios.show', $rec) }}"
                                   class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-200 dark:bg-[#15203c] hover:bg-primary-100 transition-all text-gray-500 hover:text-primary-600" title="Ver">
                                    <i class="material-symbols-outlined !text-[14px]">visibility</i>
                                </a>
                                <button onclick="accionRecordatorio('restaurar','{{ route('dashboard.recordatorios.restaurar', $rec) }}')"
                                    class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-200 dark:bg-[#15203c] hover:bg-success-100 transition-all text-gray-500 hover:text-success-600" title="Restaurar">
                                    <i class="material-symbols-outlined !text-[14px]">restore</i>
                                </button>
                                <form method="POST" action="{{ route('dashboard.recordatorios.destroy', $rec) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Eliminar permanentemente?')"
                                        class="w-[28px] h-[28px] rounded flex items-center justify-center bg-gray-200 dark:bg-[#15203c] hover:bg-danger-100 transition-all text-gray-500 hover:text-danger-600" title="Eliminar">
                                        <i class="material-symbols-outlined !text-[14px]">delete</i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @endif {{-- fin empty check --}}

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script>
        function accionRecordatorio(accion, url) {
            var mensajes = { descartar: '¿Descartar este recordatorio?', renovar: '¿Renovar este recordatorio un año más?', restaurar: '¿Restaurar este recordatorio como pendiente?' };
            if (!confirm(mensajes[accion] || '¿Continuar?')) return;
            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) { if (data.success) location.reload(); else alert('Error al procesar la acción.'); })
            .catch(function() { alert('Error de conexión.'); });
        }

        function toggleDescartados() {
            var sec  = document.getElementById('seccionDescartados');
            var icon = document.getElementById('iconDescartados');
            sec.classList.toggle('hidden');
            icon.style.transform = sec.classList.contains('hidden') ? '' : 'rotate(180deg)';
        }
        </script>
    </body>
</html>
