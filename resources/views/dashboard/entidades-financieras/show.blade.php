<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $entidadFinanciera->nombre_comercial_resuelto ?? 'Entidad Financiera' }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        @php
            $e          = $entidadFinanciera;
            $empresa    = $e->empresa;
            $tipo       = $e->tipoEntidadFinanciera;
            $nombre     = $e->nombre_comercial_resuelto ?? '—';
            $inicial    = strtoupper(mb_substr($nombre, 0, 1));
            $colores    = ['from-primary-400 to-primary-600','from-orange-400 to-orange-600','from-purple-400 to-purple-600','from-success-400 to-success-600','from-pink-400 to-pink-600'];
            $gradiente  = $colores[abs(crc32($e->id)) % count($colores)];

            $logoSrc = $e->logo_resuelto;

            $estadoSunat = strtoupper($empresa?->estado_sunat ?? '');
            $estadoBadge = match(true) {
                str_contains($estadoSunat, 'ACTIVO') => 'text-success-600 border border-success-600 bg-success-100',
                str_contains($estadoSunat, 'BAJA')   => 'text-danger-600 border border-danger-600 bg-danger-100',
                $estadoSunat !== ''                  => 'text-warning-600 border border-warning-600 bg-warning-100',
                default                              => '',
            };
        @endphp

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $nombre }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.entidades-financieras.index') }}" class="transition-all hover:text-primary-500">Entidades Financieras</a>
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

            <!-- Banner / hero -->
            <div class="rounded-md overflow-hidden h-[180px] md:h-[220px] relative z-0 mb-0">
                @if($e->banner_url)
                    <img src="{{ $e->banner_url }}" alt="{{ $nombre }}"
                        class="w-full h-full object-cover"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                    <div class="w-full h-full bg-gradient-to-br {{ $gradiente }} hidden absolute inset-0"></div>
                @else
                    <div class="w-full h-full bg-gradient-to-br {{ $gradiente }}"></div>
                @endif
                <!-- Acciones en banner -->
                <div class="absolute top-[12px] right-[12px] flex gap-[8px]">
                    <a href="{{ route('dashboard.entidades-financieras.edit', $e) }}"
                        class="inline-flex items-center gap-[6px] px-[12px] py-[7px] rounded-md bg-white/90 dark:bg-[#0c1427]/90 text-black dark:text-white text-sm font-medium hover:bg-white transition-all shadow-sm">
                        <i class="material-symbols-outlined !text-[16px]">edit</i>
                        Editar
                    </a>
                    <form method="POST" action="{{ route('dashboard.entidades-financieras.destroy', $e) }}"
                        onsubmit="return confirm('¿Eliminar esta entidad financiera?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-[6px] px-[12px] py-[7px] rounded-md bg-danger-500/90 text-white text-sm font-medium hover:bg-danger-500 transition-all shadow-sm">
                            <i class="material-symbols-outlined !text-[16px]">delete</i>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mt-[25px] mb-[25px]">

                <!-- Columna izquierda -->
                <div class="lg:col-span-1 space-y-[25px]">

                    <!-- Card identidad -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md text-center">
                        <div class="relative z-10 flex justify-center -mt-[60px] mb-[16px]">
                            @if($logoSrc)
                                <img src="{{ $logoSrc }}"
                                    class="w-[96px] h-[96px] rounded-full object-cover ring-4 ring-white dark:ring-[#0c1427] shadow-md"
                                    alt="{{ $nombre }}"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                <div class="w-[96px] h-[96px] rounded-full bg-gradient-to-br {{ $gradiente }} items-center justify-center text-white font-bold text-[36px] ring-4 ring-white dark:ring-[#0c1427] shadow-md select-none hidden">
                                    {{ $inicial }}
                                </div>
                            @else
                                <div class="w-[96px] h-[96px] rounded-full bg-gradient-to-br {{ $gradiente }} flex items-center justify-center text-white font-bold text-[36px] ring-4 ring-white dark:ring-[#0c1427] shadow-md select-none">
                                    {{ $inicial }}
                                </div>
                            @endif
                        </div>

                        <h5 class="font-semibold text-black dark:text-white mb-[4px]">{{ $nombre }}</h5>

                        @if($e->sigla_resuelta)
                            <span class="inline-block text-xs font-mono font-semibold px-[8px] py-[2px] rounded bg-gray-100 dark:bg-[#15203c] text-gray-500 dark:text-gray-400 mb-[8px]">{{ $e->sigla_resuelta }}</span>
                        @endif

                        @if($tipo)
                        <div class="flex justify-center mb-[12px]">
                            <span class="inline-flex items-center gap-[5px] text-xs font-medium px-[10px] py-[4px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400">
                                <i class="material-symbols-outlined !text-[13px]">{{ $tipo->icono ?? 'account_balance' }}</i>
                                {{ $tipo->nombre }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Card identificadores -->
                    @if($e->codigo_sbs || $e->swift)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">badge</i>
                            Identificadores
                        </h6>
                        <ul class="space-y-[12px]">
                            @if($e->codigo_sbs)
                            <li class="flex items-start gap-[10px] text-sm">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400 mt-[1px] flex-shrink-0">assured_workload</i>
                                <div>
                                    <p class="text-xs text-gray-400 mb-[2px]">Código SBS</p>
                                    <span class="text-black dark:text-white font-mono font-medium">{{ $e->codigo_sbs }}</span>
                                </div>
                            </li>
                            @endif
                            @if($e->swift)
                            <li class="flex items-start gap-[10px] text-sm">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400 mt-[1px] flex-shrink-0">swap_horiz</i>
                                <div>
                                    <p class="text-xs text-gray-400 mb-[2px]">SWIFT/BIC</p>
                                    <span class="text-black dark:text-white font-mono font-medium">{{ $e->swift }}</span>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                    @endif

                    <!-- Card empresa vinculada -->
                    @if($empresa)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">business</i>
                            Empresa Vinculada
                        </h6>
                        <div class="flex items-center gap-[12px] mb-[12px]">
                            @if($empresa->logo_url)
                                <img src="{{ Storage::url($empresa->logo_url) }}"
                                    class="w-[48px] h-[48px] rounded-md object-cover flex-shrink-0" alt="{{ $empresa->razon_social }}">
                            @else
                                @php $bgEmp = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'][abs(crc32($empresa->id)) % 5]; @endphp
                                <div class="w-[48px] h-[48px] rounded-md {{ $bgEmp }} flex items-center justify-center text-white font-bold text-[20px] flex-shrink-0 select-none">
                                    {{ strtoupper(mb_substr($empresa->razon_social, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <a href="{{ route('dashboard.empresas.show', $empresa) }}"
                                    class="block text-sm font-semibold text-black dark:text-white hover:text-primary-500 truncate">
                                    {{ $empresa->razon_social }}
                                </a>
                                @if($empresa->ruc)
                                <span class="text-xs text-gray-400 font-mono">RUC: {{ $empresa->ruc }}</span>
                                @endif
                            </div>
                        </div>
                        @if($empresa->estado_sunat || $empresa->condicion_sunat)
                        <div class="flex flex-wrap gap-[6px]">
                            @if($empresa->estado_sunat && $estadoBadge)
                            <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] {{ $estadoBadge }}">
                                {{ $empresa->estado_sunat }}
                            </span>
                            @endif
                            @if($empresa->condicion_sunat)
                            <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] text-gray-600 border border-gray-300 bg-gray-50 dark:bg-[#15203c]">
                                {{ $empresa->condicion_sunat }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif

                </div>

                <!-- Columna derecha: productos financieros -->
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                            <h5 class="!mb-0 flex items-center gap-[8px]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">savings</i>
                                Productos Financieros
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                    ({{ $e->productosFinancieros->count() }})
                                </span>
                            </h5>
                        </div>

                        @if($e->productosFinancieros->isEmpty())
                        <div class="text-center py-[40px] text-gray-400 dark:text-gray-500">
                            <i class="material-symbols-outlined !text-[40px] block mb-[8px]">savings</i>
                            <p class="text-sm">Sin productos financieros registrados para esta entidad.</p>
                        </div>
                        @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-[#172036]">
                                        <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Producto</th>
                                        <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Tipo</th>
                                        <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Estado</th>
                                        <th class="text-left py-[10px] px-[12px] text-xs font-semibold text-gray-500 dark:text-gray-400">Miembro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($e->productosFinancieros as $prod)
                                    <tr class="border-b border-gray-100 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                        <td class="py-[10px] px-[12px]">
                                            <span class="text-sm text-black dark:text-white font-medium">{{ $prod->nombre ?? '—' }}</span>
                                            @if($prod->numero_cuenta ?? null)
                                            <span class="block text-xs text-gray-400 font-mono">{{ $prod->numero_cuenta }}</span>
                                            @endif
                                        </td>
                                        <td class="py-[10px] px-[12px] text-sm text-gray-600 dark:text-gray-400">
                                            {{ $prod->tipoProductoFinanciero?->nombre ?? '—' }}
                                        </td>
                                        <td class="py-[10px] px-[12px] text-sm text-gray-600 dark:text-gray-400">
                                            {{ $prod->estadoProducto?->nombre ?? '—' }}
                                        </td>
                                        <td class="py-[10px] px-[12px] text-sm text-gray-600 dark:text-gray-400">
                                            @php
                                                $persona = $prod->hogarMiembro?->user?->persona;
                                                $nombreM = $persona
                                                    ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? ''))
                                                    : '—';
                                            @endphp
                                            {{ $nombreM }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
