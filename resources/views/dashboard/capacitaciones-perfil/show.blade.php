<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Capacitaciones — {{ trim(($miembro->user?->persona?->nombres ?? '') . ' ' . ($miembro->user?->persona?->apellido_paterno ?? '')) ?: $miembro->apodo ?: 'Miembro' }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        @php
            $nombre  = trim(($miembro->user?->persona?->nombres ?? '') . ' ' . ($miembro->user?->persona?->apellido_paterno ?? '')) ?: $miembro->apodo ?: '—';
            $avatar  = $miembro->user?->persona?->foto_url;
            $inicial = mb_strtoupper(mb_substr($nombre, 0, 1));
            $hoy     = now()->startOfDay();
        @endphp

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Capacitaciones — {{ $nombre }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.capacitaciones-perfil.index') }}" class="transition-all hover:text-primary-500">Capacitaciones</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">{{ $nombre }}</li>
                </ol>
            </div>

            <!-- Cabecera del miembro -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex flex-wrap items-center gap-[20px]">

                    <!-- Avatar -->
                    @if($avatar)
                        <img src="{{ $avatar }}" alt="{{ $nombre }}"
                             class="w-[72px] h-[72px] rounded-full object-cover border-[3px] border-white dark:border-[#172036] shadow flex-shrink-0">
                    @else
                        <span class="w-[72px] h-[72px] rounded-full bg-primary-100 dark:bg-[#1a2d4d] flex items-center justify-center text-2xl font-bold text-primary-700 dark:text-primary-400 flex-shrink-0 shadow">
                            {{ $inicial }}
                        </span>
                    @endif

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <h5 class="!mb-[6px]">{{ $nombre }}</h5>
                        <div class="flex flex-wrap gap-[16px] text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-[5px]">
                                <i class="material-symbols-outlined !text-[16px] text-primary-500">workspace_premium</i>
                                <strong class="text-black dark:text-white">{{ $capacitaciones->count() }}</strong>
                                {{ $capacitaciones->count() === 1 ? 'capacitación' : 'capacitaciones' }}
                            </span>
                            @if($totalHoras > 0)
                            <span class="flex items-center gap-[5px]">
                                <i class="material-symbols-outlined !text-[16px] text-primary-500">schedule</i>
                                <strong class="text-black dark:text-white">{{ number_format($totalHoras) }}</strong>
                                horas académicas
                            </span>
                            @endif
                            <span class="flex items-center gap-[5px]">
                                <i class="material-symbols-outlined !text-[16px] text-success-500">verified</i>
                                <strong class="text-black dark:text-white">{{ $capacitaciones->whereNotNull('file_path')->count() }}</strong>
                                con certificado
                            </span>
                        </div>
                    </div>

                    <!-- Enlace a gestión -->
                    <a href="{{ route('dashboard.capacitaciones.index') }}"
                       class="inline-flex items-center gap-[6px] h-[38px] px-[14px] rounded-md border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-300 text-sm font-medium hover:border-primary-400 hover:text-primary-500 transition-all flex-shrink-0">
                        <i class="material-symbols-outlined !text-[16px]">edit</i>
                        Gestionar
                    </a>
                </div>
            </div>

            <!-- Filtros de vínculo -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[16px] md:p-[20px] rounded-md">
                <div class="flex items-center gap-[8px] flex-wrap">
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Mostrar:</span>
                    <button type="button" data-filter="all"
                            class="filter-btn active inline-flex items-center gap-[5px] px-[12px] py-[6px] rounded-full text-xs font-medium transition-all border bg-primary-500 text-white border-primary-500">
                        <i class="material-symbols-outlined !text-[13px]">select_all</i>
                        Todas ({{ $capacitaciones->count() }})
                    </button>
                    <button type="button" data-filter="libre"
                            class="filter-btn inline-flex items-center gap-[5px] px-[12px] py-[6px] rounded-full text-xs font-medium transition-all border bg-white dark:bg-[#0c1427] text-gray-600 dark:text-gray-300 border-gray-200 dark:border-[#172036] hover:border-primary-400">
                        <i class="material-symbols-outlined !text-[13px]">school</i>
                        Libres ({{ $capacitaciones->whereNull('empleo_id')->count() }})
                    </button>
                    <button type="button" data-filter="empleo"
                            class="filter-btn inline-flex items-center gap-[5px] px-[12px] py-[6px] rounded-full text-xs font-medium transition-all border bg-white dark:bg-[#0c1427] text-gray-600 dark:text-gray-300 border-gray-200 dark:border-[#172036] hover:border-primary-400">
                        <i class="material-symbols-outlined !text-[13px]">work</i>
                        De empleo ({{ $capacitaciones->whereNotNull('empleo_id')->count() }})
                    </button>
                </div>
            </div>

            <!-- Timeline de capacitaciones -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">

                @if($capacitaciones->isEmpty())
                    <div class="text-center py-[60px]">
                        <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[14px]">workspace_premium</i>
                        <h6 class="text-gray-400 dark:text-gray-500 !mb-[8px]">Sin capacitaciones registradas</h6>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mb-[20px]">
                            Registra la formación de {{ $nombre }} desde el módulo de gestión.
                        </p>
                        <a href="{{ route('dashboard.capacitaciones.create') }}"
                           class="inline-flex items-center gap-[6px] px-[18px] py-[10px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            <i class="material-symbols-outlined !text-[18px]">add</i>
                            Agregar capacitación
                        </a>
                    </div>
                @else
                    <div class="relative" id="timeline-container">

                        <!-- Eje vertical -->
                        <span class="block absolute top-0 bottom-0 ltr:left-[6px] rtl:right-[6px] ltr:md:left-[160px] rtl:md:right-[160px] mt-[5px] ltr:border-l rtl:border-r border-dashed border-gray-200 dark:border-[#172036]"></span>

                        @foreach($capacitaciones as $cap)
                        @php
                            $tipo       = $cap->tipoCapacitacion;
                            $icono      = $tipo?->icono ?? 'school';
                            $dotColor   = '#4f88e4'; // primary-500

                            $institucion = $cap->institucionEducativa?->nombre_referencial ?? $cap->institucion_nombre;

                            $periodoIni = $cap->fecha_inicio ? $cap->fecha_inicio->translatedFormat('M Y') : null;
                            $periodoFin = $cap->fecha_fin    ? $cap->fecha_fin->translatedFormat('M Y')    : null;
                            $periodo    = match(true) {
                                $periodoIni && $periodoFin => "$periodoIni – $periodoFin",
                                $periodoIni                => "Desde $periodoIni",
                                $periodoFin                => "Hasta $periodoFin",
                                default                    => null,
                            };

                            $vinculo = $cap->empleo_id ? 'empleo' : 'libre';

                            // Vencimiento
                            $vencBadge = null;
                            $vencClass = '';
                            if ($cap->fecha_vencimiento) {
                                $diff = $hoy->diffInDays($cap->fecha_vencimiento, false);
                                if ($diff < 0) {
                                    $vencBadge = 'Vencido';
                                    $vencClass = 'bg-danger-50 dark:bg-[#2a1a1a] text-danger-500';
                                    $dotColor  = '#ef4444';
                                } elseif ($diff <= 30) {
                                    $vencBadge = 'Vence pronto';
                                    $vencClass = 'bg-warning-50 dark:bg-[#2a2010] text-warning-600';
                                    $dotColor  = '#f97316';
                                } else {
                                    $vencBadge = 'Vigente hasta ' . $cap->fecha_vencimiento->format('d/m/Y');
                                    $vencClass = 'bg-success-50 dark:bg-[#0f2a1a] text-success-600';
                                }
                            }

                            $empleo     = $cap->empleo;
                            $empNombre  = $empleo?->empleador?->empresa?->razon_social ?? $empleo?->empleador?->nombre;
                        @endphp

                        <div class="timeline-item relative ltr:pl-[25px] rtl:pr-[25px] ltr:md:pl-[190px] rtl:md:pr-[190px] mb-[28px] last:mb-0"
                             data-vinculo="{{ $vinculo }}">

                            <!-- Dot -->
                            <span class="block absolute top-[14px] ltr:left-0 rtl:right-0 ltr:md:left-[154px] rtl:md:right-[154px] w-[14px] h-[14px] rounded-full border-[3px] border-white dark:border-[#0c1427] shadow"
                                  style="background-color: {{ $dotColor }}"></span>

                            <!-- Fecha en eje (md+) -->
                            @if($periodoFin || $periodoIni)
                            <span class="md:absolute md:top-[10px] ltr:md:left-0 rtl:md:right-0 text-xs text-gray-400 dark:text-gray-500 block mb-[8px] md:mb-0 w-[140px] ltr:md:text-right rtl:md:text-left leading-[1.4]">
                                {{ $periodoFin ?? $periodoIni }}
                            </span>
                            @endif

                            <!-- Card -->
                            <div class="rounded-md border border-gray-100 dark:border-[#172036] bg-white dark:bg-[#0c1427] hover:shadow-md transition-all">
                                <div class="p-[16px] md:p-[20px]">

                                    <!-- Cabecera card -->
                                    <div class="flex items-start gap-[12px]">

                                        <!-- Icono tipo -->
                                        <span class="w-[44px] h-[44px] rounded-md bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                            <i class="material-symbols-outlined !text-[22px] text-primary-500">{{ $icono }}</i>
                                        </span>

                                        <div class="flex-1 min-w-0">
                                            <!-- Nombre + badges -->
                                            <div class="flex flex-wrap items-start gap-[6px] mb-[4px]">
                                                <h6 class="!mb-0 text-black dark:text-white font-semibold leading-tight flex-1">{{ $cap->nombre }}</h6>

                                                @if(!$cap->activo)
                                                    <span class="inline-flex items-center gap-[3px] text-[10px] font-bold py-[2px] px-[8px] rounded-full bg-gray-100 dark:bg-[#172036] text-gray-500 uppercase tracking-wide">
                                                        Inactivo
                                                    </span>
                                                @endif

                                                @if($cap->empleo_id)
                                                    <span class="inline-flex items-center gap-[3px] text-[10px] font-medium py-[2px] px-[8px] rounded-full bg-orange-50 dark:bg-[#2a1a0a] text-orange-600 dark:text-orange-400">
                                                        <i class="material-symbols-outlined !text-[11px]">work</i>
                                                        Empleo{{ $empleo ? ': ' . $empleo->cargo : '' }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Tipo + institución -->
                                            <div class="flex flex-wrap items-center gap-[12px] text-xs text-gray-500 dark:text-gray-400 mb-[6px]">
                                                @if($tipo)
                                                    <span class="flex items-center gap-[4px]">
                                                        <i class="material-symbols-outlined !text-[13px]">{{ $icono }}</i>
                                                        {{ $tipo->nombre }}
                                                    </span>
                                                @endif
                                                @if($institucion)
                                                    <span class="flex items-center gap-[4px]">
                                                        <i class="material-symbols-outlined !text-[13px]">business</i>
                                                        {{ $institucion }}
                                                    </span>
                                                @endif
                                                @if($periodo)
                                                    <span class="flex items-center gap-[4px]">
                                                        <i class="material-symbols-outlined !text-[13px]">calendar_today</i>
                                                        {{ $periodo }}
                                                    </span>
                                                @endif
                                                @if($cap->horas_academicas)
                                                    <span class="flex items-center gap-[4px]">
                                                        <i class="material-symbols-outlined !text-[13px]">schedule</i>
                                                        {{ $cap->horas_academicas }} h
                                                    </span>
                                                @endif
                                            </div>

                                            @if($empleo && $empNombre)
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mb-[6px]">
                                                    <i class="material-symbols-outlined !text-[12px] align-middle">corporate_fare</i>
                                                    {{ $empNombre }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Badges inferiores + acciones -->
                                    @php $hasBadges = $vencBadge || $cap->file_path || $cap->url_verificacion || $cap->codigo_certificado; @endphp
                                    @if($hasBadges)
                                    <div class="flex flex-wrap items-center gap-[8px] mt-[12px] pt-[12px] border-t border-gray-100 dark:border-[#172036]">

                                        <!-- Vencimiento -->
                                        @if($vencBadge)
                                            <span class="inline-flex items-center gap-[4px] text-[11px] font-medium py-[3px] px-[10px] rounded-full {{ $vencClass }}">
                                                <i class="material-symbols-outlined !text-[12px]">event</i>
                                                {{ $vencBadge }}
                                            </span>
                                        @endif

                                        <!-- Código certificado -->
                                        @if($cap->codigo_certificado)
                                            <span class="inline-flex items-center gap-[4px] text-[11px] font-medium py-[3px] px-[10px] rounded-full bg-gray-100 dark:bg-[#172036] text-gray-600 dark:text-gray-300">
                                                <i class="material-symbols-outlined !text-[12px]">tag</i>
                                                {{ $cap->codigo_certificado }}
                                            </span>
                                        @endif

                                        <!-- Certificado archivo -->
                                        @if($cap->file_path)
                                            <a href="{{ asset('storage/' . $cap->file_path) }}" target="_blank"
                                               class="inline-flex items-center gap-[4px] text-[11px] font-medium py-[3px] px-[10px] rounded-full bg-success-50 dark:bg-[#0f2a1a] text-success-600 dark:text-success-400 hover:bg-success-100 transition-all">
                                                <i class="material-symbols-outlined !text-[12px]">picture_as_pdf</i>
                                                Ver certificado
                                            </a>
                                        @endif

                                        <!-- URL verificación -->
                                        @if($cap->url_verificacion)
                                            <a href="{{ $cap->url_verificacion }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-[4px] text-[11px] font-medium py-[3px] px-[10px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400 hover:bg-primary-100 transition-all">
                                                <i class="material-symbols-outlined !text-[12px]">verified</i>
                                                Verificar credencial
                                            </a>
                                        @endif

                                        <!-- Spacer + enlace a gestión -->
                                        <div class="flex-1"></div>
                                        <a href="{{ route('dashboard.capacitaciones.show', $cap) }}"
                                           class="inline-flex items-center gap-[4px] text-[11px] font-medium py-[3px] px-[10px] rounded-full border border-gray-200 dark:border-[#172036] text-gray-500 dark:text-gray-400 hover:border-primary-400 hover:text-primary-500 transition-all">
                                            <i class="material-symbols-outlined !text-[12px]">open_in_new</i>
                                            Ver detalle
                                        </a>
                                    </div>
                                    @else
                                    <div class="flex justify-end mt-[10px]">
                                        <a href="{{ route('dashboard.capacitaciones.show', $cap) }}"
                                           class="inline-flex items-center gap-[4px] text-[11px] font-medium py-[3px] px-[10px] rounded-full border border-gray-200 dark:border-[#172036] text-gray-500 dark:text-gray-400 hover:border-primary-400 hover:text-primary-500 transition-all">
                                            <i class="material-symbols-outlined !text-[12px]">open_in_new</i>
                                            Ver detalle
                                        </a>
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Mensaje vacío al filtrar (oculto por defecto) -->
                        <div id="empty-filter-msg" class="hidden text-center py-[40px]">
                            <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[10px]">filter_list_off</i>
                            <p class="text-sm text-gray-400 dark:text-gray-500">No hay capacitaciones en esta categoría.</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script>
        (function () {
            const buttons  = document.querySelectorAll('.filter-btn');
            const items    = document.querySelectorAll('.timeline-item');
            const emptyMsg = document.getElementById('empty-filter-msg');

            const inactiveClass = 'bg-white dark:bg-[#0c1427] text-gray-600 dark:text-gray-300 border-gray-200 dark:border-[#172036] hover:border-primary-400'.split(' ');
            const activeClass   = 'bg-primary-500 text-white border-primary-500'.split(' ');

            buttons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const filter = this.dataset.filter;

                    // Toggle button styles
                    buttons.forEach(b => {
                        b.classList.remove(...activeClass);
                        b.classList.add(...inactiveClass);
                    });
                    this.classList.remove(...inactiveClass);
                    this.classList.add(...activeClass);

                    // Filter items
                    let visible = 0;
                    items.forEach(item => {
                        const match = filter === 'all' || item.dataset.vinculo === filter;
                        item.style.display = match ? '' : 'none';
                        if (match) visible++;
                    });

                    if (emptyMsg) emptyMsg.classList.toggle('hidden', visible > 0);
                });
            });
        })();
        </script>
    </body>
</html>
