<table class="w-full text-sm">
    <thead>
        <tr class="border-b border-gray-100 dark:border-[#172036]">
            <th class="text-left py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Viaje</th>
            <th class="text-left py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Tipo</th>
            <th class="text-left py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Operador</th>
            <th class="text-left py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Transporte</th>
            <th class="text-left py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Código</th>
            <th class="text-left py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Fechas</th>
            <th class="text-right py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Monto</th>
            <th class="text-center py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide whitespace-nowrap">Estado</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-50 dark:divide-[#172036]">
        @foreach($reservasList as $r)
        <tr class="hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">

            {{-- Viaje --}}
            <td class="py-[10px] px-[12px]">
                @if($r->viaje)
                <a href="{{ route('dashboard.viajes.show', $r->viaje) }}?tab=tabReservas"
                   class="text-xs font-medium text-primary-500 hover:text-primary-400 hover:underline transition-all truncate block max-w-[130px]"
                   title="{{ $r->viaje->nombre }}">
                    {{ $r->viaje->nombre }}
                </a>
                @else
                <span class="text-gray-300 dark:text-gray-600">—</span>
                @endif
            </td>

            {{-- Tipo de reserva --}}
            <td class="py-[10px] px-[12px]">
                @if($r->tipoReserva)
                <span class="inline-flex items-center gap-[4px] text-xs text-gray-600 dark:text-gray-300">
                    <i class="material-symbols-outlined !text-[13px] text-orange-500">{{ $r->tipoReserva->icono ?? 'confirmation_number' }}</i>
                    {{ $r->tipoReserva->nombre }}
                </span>
                @else
                <span class="text-gray-300 dark:text-gray-600">—</span>
                @endif
            </td>

            {{-- Operador --}}
            <td class="py-[10px] px-[12px]">
                @php $op = $r->operadorViaje; @endphp
                @if($op)
                <a href="{{ route('dashboard.operadores-viaje.show', $op) }}"
                   class="flex items-center gap-[6px] hover:opacity-80 transition-all group max-w-[140px]">
                    @if($op->logo_resuelto)
                        <img src="{{ $op->logo_resuelto }}" class="w-[22px] h-[22px] rounded object-cover flex-shrink-0" alt="">
                    @else
                        <span class="w-[22px] h-[22px] rounded bg-gray-100 dark:bg-[#172036] flex items-center justify-center flex-shrink-0">
                            <i class="material-symbols-outlined !text-[12px] text-gray-400">business</i>
                        </span>
                    @endif
                    <span class="text-xs text-black dark:text-white group-hover:text-primary-500 truncate">{{ $op->nombre_comercial_resuelto }}</span>
                </a>
                @else
                <span class="text-gray-300 dark:text-gray-600 text-xs">—</span>
                @endif
            </td>

            {{-- Transporte --}}
            <td class="py-[10px] px-[12px]">
                @if($r->tipoTransporte)
                <span class="inline-flex items-center gap-[4px] text-xs text-gray-600 dark:text-gray-300">
                    <i class="material-symbols-outlined !text-[13px] text-primary-500">{{ $r->tipoTransporte->icono ?? 'directions_car' }}</i>
                    {{ $r->tipoTransporte->nombre }}
                </span>
                @else
                <span class="text-gray-300 dark:text-gray-600 text-xs">—</span>
                @endif
            </td>

            {{-- Código --}}
            <td class="py-[10px] px-[12px]">
                <span class="block font-medium text-black dark:text-white text-xs truncate max-w-[120px]" title="{{ $r->titulo ?? '' }}">
                    {{ $r->titulo ?? '' }}
                </span>
                @if($r->codigo_reserva)
                <span class="text-[10px] text-gray-400 font-mono">{{ $r->codigo_reserva }}</span>
                @endif
            </td>

            {{-- Fechas --}}
            <td class="py-[10px] px-[12px] text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                {{ $r->fecha_inicio?->format('d/m/Y') ?? '—' }}
                @if($r->fecha_fin)
                <span class="block text-[10px]">{{ $r->fecha_fin->format('d/m/Y') }}</span>
                @endif
            </td>

            {{-- Monto --}}
            <td class="py-[10px] px-[12px] text-right whitespace-nowrap">
                @if($r->monto !== null)
                <span class="text-sm font-semibold text-black dark:text-white">
                    {{ $r->moneda?->simbolo }} {{ number_format((float)$r->monto, 2) }}
                </span>
                @if($r->moneda)
                <span class="block text-[10px] text-gray-400">{{ $r->moneda->codigo }}</span>
                @endif
                @else
                <span class="text-gray-300 dark:text-gray-600">—</span>
                @endif
            </td>

            {{-- Estado --}}
            <td class="py-[10px] px-[12px] text-center">
                @if($r->estadoReserva)
                <span class="inline-flex items-center gap-[3px] text-[10px] font-semibold py-[3px] px-[8px] rounded-full whitespace-nowrap"
                    style="background-color:{{ $r->estadoReserva->color ?? '#e5e7eb' }}22;color:{{ $r->estadoReserva->color ?? '#6b7280' }}">
                    @if($r->estadoReserva->icono)
                    <i class="material-symbols-outlined !text-[11px]">{{ $r->estadoReserva->icono }}</i>
                    @endif
                    {{ $r->estadoReserva->nombre }}
                </span>
                @else
                <span class="text-gray-300 dark:text-gray-600">—</span>
                @endif
            </td>

        </tr>
        @endforeach
    </tbody>
</table>
