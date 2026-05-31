<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('partials.front.styles')
        <title>{{ $recordatorio->titulo }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Detalle del Recordatorio</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.recordatorios.index') }}" class="transition-all hover:text-primary-500">Recordatorios</a>
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

            @php
            $hoy = today();
            $esPendiente  = $recordatorio->estado === 'pendiente';
            $esDescartado = $recordatorio->estado === 'descartado';
            $diasDiff     = $recordatorio->fecha_vencimiento ? $hoy->diffInDays($recordatorio->fecha_vencimiento, false) : null;
            @endphp

            <!-- Card 1: Encabezado -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex items-start justify-between gap-[16px] flex-wrap">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-[8px] flex-wrap mb-[10px]">
                            @if($recordatorio->estado === 'pendiente')
                                <span class="inline-block text-xs font-medium py-[3px] px-[10px] border border-warning-300 bg-warning-100 text-warning-700 rounded-xl">Pendiente</span>
                            @elseif($recordatorio->estado === 'enviado')
                                <span class="inline-block text-xs font-medium py-[3px] px-[10px] border border-success-300 bg-success-100 text-success-700 rounded-xl">Enviado</span>
                            @else
                                <span class="inline-block text-xs font-medium py-[3px] px-[10px] border border-gray-300 bg-gray-100 text-gray-600 rounded-xl">Descartado</span>
                            @endif
                            @if($recordatorio->repetir)
                                <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[3px] px-[10px] border border-primary-300 bg-primary-50 text-primary-600 rounded-xl">
                                    <i class="material-symbols-outlined !text-[13px]">autorenew</i>Se repite anualmente
                                </span>
                            @endif
                        </div>
                        <h4 class="!text-[22px] !mb-0 text-black dark:text-white">{{ $recordatorio->titulo }}</h4>
                    </div>
                    <div class="flex items-center gap-[8px] flex-wrap">
                        <a href="{{ route('dashboard.recordatorios.edit', $recordatorio) }}"
                           class="inline-flex items-center gap-[6px] bg-primary-500 text-white py-[7px] px-[14px] rounded-md hover:bg-primary-400 transition-all text-sm">
                            <i class="material-symbols-outlined !text-[16px]">edit</i>Editar
                        </a>
                        @if($esPendiente)
                        <button onclick="accion('descartar','{{ route('dashboard.recordatorios.descartar', $recordatorio) }}')"
                            class="inline-flex items-center gap-[6px] bg-gray-100 dark:bg-[#15203c] text-gray-600 dark:text-gray-400 py-[7px] px-[14px] rounded-md hover:bg-gray-200 transition-all text-sm">
                            <i class="material-symbols-outlined !text-[16px]">archive</i>Descartar
                        </button>
                        @endif
                        @if($recordatorio->repetir && $esPendiente)
                        <button onclick="accion('renovar','{{ route('dashboard.recordatorios.renovar', $recordatorio) }}')"
                            class="inline-flex items-center gap-[6px] bg-success-500 text-white py-[7px] px-[14px] rounded-md hover:bg-success-400 transition-all text-sm">
                            <i class="material-symbols-outlined !text-[16px]">autorenew</i>Renovar
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card 2: Fechas -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                    <i class="material-symbols-outlined !text-[18px] text-primary-500">calendar_today</i>
                    Fechas
                </h6>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px]">
                    <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[16px]">
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-[4px]">Fecha de vencimiento</p>
                        <p class="text-lg font-bold text-black dark:text-white">
                            {{ $recordatorio->fecha_vencimiento?->format('d/m/Y') ?? '—' }}
                        </p>
                        @if($diasDiff !== null && $esPendiente)
                        @php
                        if ($diasDiff < 0) {
                            $textoD = 'Venció hace ' . abs($diasDiff) . ' días';
                            $clsD   = 'text-danger-600';
                        } elseif ($diasDiff === 0) {
                            $textoD = '¡Vence hoy!';
                            $clsD   = 'text-danger-600';
                        } else {
                            $textoD = 'Faltan ' . $diasDiff . ' días';
                            $clsD   = $diasDiff <= 7 ? 'text-orange-500' : ($diasDiff <= 30 ? 'text-warning-600' : 'text-success-600');
                        }
                        @endphp
                        <p class="text-xs {{ $clsD }} mt-[4px] font-medium">{{ $textoD }}</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 dark:bg-[#15203c] rounded-md p-[16px]">
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-[4px]">Fecha de aviso</p>
                        @if($recordatorio->fecha_aviso)
                        <p class="text-lg font-bold text-black dark:text-white">{{ $recordatorio->fecha_aviso->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500 mt-[2px]">{{ $recordatorio->fecha_aviso->format('H:i') }}h</p>
                        @else
                        <p class="text-gray-400 dark:text-gray-500">—</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card 3: Descripción -->
            @if($recordatorio->descripcion)
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[8px]">
                    <i class="material-symbols-outlined !text-[18px] text-primary-500">notes</i>
                    Descripción
                </h6>
                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $recordatorio->descripcion }}</p>
            </div>
            @endif

            <!-- Card 4: Vinculación -->
            @if($recordatorio->recordable_id)
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[8px]">
                    <i class="material-symbols-outlined !text-[18px] text-primary-500">link</i>
                    Vinculado a
                </h6>
                @php $tipo = class_basename($recordatorio->recordable_type); @endphp
                <div class="flex items-center gap-[10px]">
                    <div class="w-[40px] h-[40px] rounded-md bg-primary-50 flex items-center justify-center">
                        <i class="material-symbols-outlined !text-[20px] text-primary-500">description</i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-black dark:text-white">{{ $tipo }}</p>
                        @if($tipo === 'DocumentoServicio')
                        <a href="{{ route('dashboard.documentos-servicio.show', $recordatorio->recordable_id) }}"
                           class="text-xs text-primary-500 hover:underline flex items-center gap-[3px]">
                            <i class="material-symbols-outlined !text-[12px]">open_in_new</i>
                            Ver documento
                        </a>
                        @else
                        <p class="text-xs text-gray-400">ID: {{ Str::limit($recordatorio->recordable_id, 20) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Botones -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex items-center justify-between flex-wrap gap-[12px]">
                    <a href="{{ route('dashboard.recordatorios.index') }}"
                       class="inline-flex items-center gap-[6px] text-sm text-gray-500 dark:text-gray-400 hover:text-primary-500 transition-all">
                        <i class="material-symbols-outlined !text-[18px]">arrow_back</i>
                        Volver a Recordatorios
                    </a>
                    <form method="POST" action="{{ route('dashboard.recordatorios.destroy', $recordatorio) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('¿Eliminar este recordatorio permanentemente?')"
                            class="inline-flex items-center gap-[6px] text-sm text-danger-500 hover:text-danger-700 transition-all">
                            <i class="material-symbols-outlined !text-[18px]">delete</i>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script>
        function accion(tipo, url) {
            var msgs = { descartar: '¿Descartar este recordatorio?', renovar: '¿Renovar un año más?' };
            if (!confirm(msgs[tipo] || '¿Continuar?')) return;
            fetch(url, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            })
            .then(function(r) { return r.json(); })
            .then(function(d) { if (d.success) window.location.href = '{{ route('dashboard.recordatorios.index') }}'; })
            .catch(function() { alert('Error de conexión.'); });
        }
        </script>
    </body>
</html>
