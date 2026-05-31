<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Cuenta — {{ $cuentaServicio->numero_cuenta }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Detalle de Cuenta de Servicio</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.cuentas-servicio.index') }}" class="transition-all hover:text-primary-500">Cuentas de Servicio</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Detalle
                    </li>
                </ol>
            </div>

            @php
            $iconMap = [
                'electricidad' => 'bolt',
                'agua'         => 'water_drop',
                'gas'          => 'local_fire_department',
                'internet'     => 'wifi',
                'telefon'      => 'phone',
                'móvil'        => 'phone_android',
                'movil'        => 'phone_android',
                'cable'        => 'tv',
                'tv'           => 'tv',
                'seguridad'    => 'security',
            ];
            $nombreTipo = strtolower($cuentaServicio->proveedor?->tipoServicio?->nombre ?? '');
            $icon = 'home_repair_service';
            foreach ($iconMap as $key => $val) {
                if (str_contains($nombreTipo, $key)) { $icon = $val; break; }
            }
            $empresa = $cuentaServicio->proveedor?->empresa;
            $inicialEmpresa = mb_strtoupper(mb_substr($empresa?->razon_social ?? $cuentaServicio->proveedor?->nombre_comercial ?? '?', 0, 1));
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Tarjeta visual -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">

                        <div class="bg-primary-50 dark:bg-[#15203c] rounded-md py-[30px] px-[25px] text-center mb-[20px]">
                            <i class="material-symbols-outlined !text-[72px] text-primary-500 block mb-[10px]">{{ $icon }}</i>
                            <h4 class="!text-[18px] !mb-[4px]">{{ $cuentaServicio->proveedor?->tipoServicio?->nombre ?? 'Servicio' }}</h4>
                            <p class="text-gray-500 dark:text-gray-400 font-mono text-sm">{{ $cuentaServicio->numero_cuenta }}</p>
                            <div class="mt-[12px]">
                                @if($cuentaServicio->estado === 'activa')
                                    <span class="inline-block text-xs font-medium py-[3px] px-[10px] border border-success-300 bg-success-100 dark:bg-dark dark:border-dark text-success-700 rounded-xl">Activa</span>
                                @elseif($cuentaServicio->estado === 'suspendida')
                                    <span class="inline-block text-xs font-medium py-[3px] px-[10px] border border-warning-300 bg-warning-100 dark:bg-dark dark:border-dark text-warning-700 rounded-xl">Suspendida</span>
                                @else
                                    <span class="inline-block text-xs font-medium py-[3px] px-[10px] border border-danger-300 bg-danger-100 dark:bg-dark dark:border-dark text-danger-700 rounded-xl">Cancelada</span>
                                @endif
                            </div>
                        </div>

                        <!-- Logo empresa -->
                        @if($empresa)
                        <div class="flex items-center gap-[10px] mb-[16px] p-[12px] bg-gray-50 dark:bg-[#15203c] rounded-md">
                            @if($empresa->logo_url)
                                <img src="{{ Storage::url($empresa->logo_url) }}" class="w-[36px] h-[36px] object-cover rounded-full flex-shrink-0" alt="">
                            @else
                                <span class="w-[36px] h-[36px] rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 bg-primary-100 text-primary-700">{{ $inicialEmpresa }}</span>
                            @endif
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400 mb-0">Empresa</p>
                                <p class="text-sm font-medium text-black dark:text-white truncate">{{ $empresa->razon_social }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center gap-[10px]">
                            <a href="{{ route('dashboard.cuentas-servicio.edit', $cuentaServicio) }}"
                                class="flex-1 text-center inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-primary-500 text-white hover:bg-primary-400">
                                <i class="material-symbols-outlined !text-[18px] align-middle mr-[4px]">edit</i>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('dashboard.cuentas-servicio.destroy', $cuentaServicio) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('¿Eliminar la cuenta «{{ addslashes($cuentaServicio->numero_cuenta) }}»?')"
                                    class="w-full inline-block transition-all rounded-md font-medium px-[13px] py-[8px] bg-danger-500 text-white hover:bg-danger-400">
                                    <i class="material-symbols-outlined !text-[18px] align-middle mr-[4px]">delete</i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h4 class="!text-lg !mb-[16px]">Información de la Cuenta</h4>

                        <ul class="mb-[20px] md:mb-[25px] grid grid-cols-1 sm:grid-cols-2">
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Tipo de Servicio</span>
                                <span class="block text-black dark:text-white font-medium">
                                    {{ $cuentaServicio->proveedor?->tipoServicio?->nombre ?? '—' }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Proveedor</span>
                                <span class="block text-black dark:text-white font-medium">
                                    {{ $cuentaServicio->proveedor?->nombre_comercial ?? '—' }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Número de Cuenta</span>
                                <span class="block text-black dark:text-white font-mono">{{ $cuentaServicio->numero_cuenta }}</span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Estado</span>
                                <span class="block font-medium
                                    @if($cuentaServicio->estado === 'activa') text-success-600
                                    @elseif($cuentaServicio->estado === 'suspendida') text-warning-600
                                    @else text-danger-600 @endif">
                                    {{ ucfirst($cuentaServicio->estado) }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Titular</span>
                                <span class="block text-black dark:text-white">
                                    @if($cuentaServicio->titular)
                                        {{ trim(($cuentaServicio->titular->persona?->nombres ?? '') . ' ' . ($cuentaServicio->titular->persona?->apellido_paterno ?? '')) ?: $cuentaServicio->titular->name }}
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Fecha de Inicio</span>
                                <span class="block text-black dark:text-white">{{ $cuentaServicio->fecha_inicio?->format('d/m/Y') ?? '—' }}</span>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Propiedad</span>
                                <a href="{{ route('dashboard.propiedades.show', $cuentaServicio->propiedad) }}"
                                   class="block text-primary-500 hover:text-primary-400 font-medium transition-all">
                                    {{ $cuentaServicio->propiedad?->alias ?? '—' }}
                                </a>
                            </li>
                            <li class="flex items-center justify-between py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400">Registrado</span>
                                <span class="block text-black dark:text-white">{{ $cuentaServicio->created_at?->format('d M, Y') }}</span>
                            </li>
                            @if($cuentaServicio->notas)
                            <li class="sm:col-span-2 py-[10px] px-[20px] border border-gray-100 dark:border-[#15203c]">
                                <span class="block text-gray-500 dark:text-gray-400 mb-[4px]">Notas</span>
                                <p class="text-black dark:text-white text-sm">{{ $cuentaServicio->notas }}</p>
                            </li>
                            @endif
                        </ul>

                        <div class="mt-[16px]">
                            <a href="{{ route('dashboard.cuentas-servicio.index', ['propiedad' => $cuentaServicio->propiedad_id]) }}"
                               class="inline-flex items-center gap-[6px] text-primary-500 hover:text-primary-400 text-sm font-medium transition-all">
                                <i class="material-symbols-outlined !text-[16px]">arrow_back</i>
                                Volver a Cuentas de Servicio
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            @php
            $unidadSimbolo = $cuentaServicio->proveedor?->tipoServicio?->unidadMedida?->simbolo ?? '';
            @endphp

            {{-- ─── Historial de Consumo ─────────────────────────────────── --}}
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex items-center gap-[8px] mb-[20px]">
                    <i class="material-symbols-outlined !text-[22px] text-primary-500">bar_chart</i>
                    <h6 class="!mb-0">Historial de Consumo</h6>
                    @if($unidadSimbolo)
                    <span class="text-xs text-gray-400 ml-[2px]">({{ $unidadSimbolo }})</span>
                    @endif
                </div>

                @if($lecturas->count() > 0)
                    <canvas id="graficaConsumo" height="100" class="mb-[25px]"></canvas>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-[#172036]">
                                    <th class="text-left py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Período</th>
                                    <th class="text-right py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Lect. anterior</th>
                                    <th class="text-right py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Lect. actual</th>
                                    <th class="text-right py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Consumo</th>
                                    <th class="text-left py-[8px] px-[12px] text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-[#172036]">
                                @foreach($lecturas as $lec)
                                <tr class="hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                    <td class="py-[10px] px-[12px] font-medium text-black dark:text-white">{{ $lec['periodo'] }}</td>
                                    <td class="py-[10px] px-[12px] text-right font-mono text-gray-600 dark:text-gray-400">{{ number_format($lec['anterior'], 2) }}</td>
                                    <td class="py-[10px] px-[12px] text-right font-mono text-gray-600 dark:text-gray-400">{{ number_format($lec['actual'], 2) }}</td>
                                    <td class="py-[10px] px-[12px] text-right font-mono font-semibold text-primary-600">
                                        {{ number_format($lec['consumo'], 2) }}
                                        @if($unidadSimbolo)<span class="text-xs font-normal text-gray-400 ml-[2px]">{{ $unidadSimbolo }}</span>@endif
                                    </td>
                                    <td class="py-[10px] px-[12px]">
                                        @if($lec['estado'])
                                        <span class="inline-block text-xs font-medium py-[2px] px-[8px] rounded-full text-white whitespace-nowrap"
                                              style="background-color: {{ $lec['color'] }}">
                                            {{ $lec['estado'] }}
                                        </span>
                                        @else
                                        <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-[40px]">
                        <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">bar_chart</i>
                        <p class="text-gray-400 dark:text-gray-500 text-sm">Sin lecturas de consumo registradas aún.</p>
                    </div>
                @endif
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')

        @if($lecturas->count() > 0)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
        <script>
        (function () {
            var lecturas = @json($lecturas);
            if (!lecturas.length) return;

            var ctx = document.getElementById('graficaConsumo').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: lecturas.map(function (l) { return l.periodo; }),
                    datasets: [{
                        label: 'Consumo',
                        data: lecturas.map(function (l) { return l.consumo; }),
                        backgroundColor: 'rgba(99, 102, 241, 0.75)',
                        borderColor:     'rgba(99, 102, 241, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    return ctx.parsed.y + ' {{ $unidadSimbolo }}';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(156, 163, 175, 0.15)' },
                            ticks: { font: { size: 11 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        })();
        </script>
        @endif
    </body>
</html>
