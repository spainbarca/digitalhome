<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $empresa->razon_social }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $empresa->razon_social }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.empresas.index') }}" class="transition-all hover:text-primary-500">Empresas</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Detalle
                    </li>
                </ol>
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
                $colores = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'];
                $bgColor = $colores[abs(crc32($empresa->id)) % count($colores)];
                $estado  = strtoupper($empresa->estado_sunat ?? '');
                $estadoBadgeClass = match(true) {
                    str_contains($estado, 'ACTIVO') => 'text-success-600 border border-success-600 bg-success-100',
                    str_contains($estado, 'BAJA')   => 'text-danger-600 border border-danger-600 bg-danger-100',
                    $estado !== ''                  => 'text-warning-600 border border-warning-600 bg-warning-100',
                    default                         => 'text-gray-500 border border-gray-300 bg-gray-50',
                };
                $condicion = strtoupper($empresa->condicion_sunat ?? '');
                $condBadgeClass = match(true) {
                    str_contains($condicion, 'HABIDO') && !str_contains($condicion, 'NO') => 'text-success-600 border border-success-600 bg-success-100',
                    $condicion !== '' => 'text-warning-600 border border-warning-600 bg-warning-100',
                    default           => 'text-gray-500 border border-gray-300 bg-gray-50',
                };
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Columna izquierda: identidad -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md text-center">
                        <!-- Logo o avatar -->
                        <div class="flex justify-center mb-[16px]">
                            @if($empresa->logo_url)
                                <img src="{{ Storage::url($empresa->logo_url) }}"
                                    class="w-[96px] h-[96px] rounded-full object-cover ring-4 ring-primary-100 dark:ring-primary-900/30"
                                    alt="{{ $empresa->razon_social }}">
                            @else
                                <div class="w-[96px] h-[96px] rounded-full {{ $bgColor }} flex items-center justify-center text-white font-bold text-[36px] select-none ring-4 ring-primary-100 dark:ring-primary-900/30">
                                    {{ strtoupper(mb_substr($empresa->razon_social, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <h5 class="font-semibold text-black dark:text-white mb-[4px]">{{ $empresa->razon_social }}</h5>

                        @if($empresa->nombre_comercial)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-[12px]">{{ $empresa->nombre_comercial }}</p>
                        @endif

                        <p class="text-xs text-gray-400 mb-[12px] font-mono">RUC: {{ $empresa->ruc }}</p>

                        <div class="flex flex-wrap justify-center gap-[6px] mb-[16px]">
                            @if($empresa->estado_sunat)
                                <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] {{ $estadoBadgeClass }}">
                                    {{ $empresa->estado_sunat }}
                                </span>
                            @endif
                            @if($empresa->condicion_sunat)
                                <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] {{ $condBadgeClass }}">
                                    {{ $empresa->condicion_sunat }}
                                </span>
                            @endif
                            <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] {{ $empresa->activo ? 'text-success-600 border border-success-600 bg-success-100' : 'text-danger-600 border border-danger-600 bg-danger-100' }}">
                                {{ $empresa->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        @if($empresa->sector)
                        <div class="flex flex-wrap justify-center gap-[6px] mt-[4px]">
                            <span class="inline-flex items-center gap-[5px] text-xs font-medium px-[10px] py-[4px] rounded-md bg-primary-50 dark:bg-[#15203c] text-primary-500">
                                <i class="{{ $empresa->sector->icono ?? 'ri-building-line' }} text-[14px]"></i>
                                {{ $empresa->sector->nombre }}
                            </span>
                        </div>
                        @endif

                        <!-- Acciones -->
                        <div class="mt-[20px] pt-[16px] border-t border-gray-100 dark:border-[#172036] flex gap-[10px] justify-center">
                            <a href="{{ route('dashboard.empresas.edit', $empresa) }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">edit</i>
                                    Editar
                                </span>
                            </a>
                            <a href="{{ route('dashboard.empresas.index') }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] text-sm">
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: datos -->
                <div class="lg:col-span-2 space-y-[25px]">

                    <!-- Datos SUNAT -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">verified</i>
                            Datos SUNAT
                            @if($empresa->sunat_sincronizado_en)
                                <span class="text-xs font-normal text-gray-400 ml-auto">
                                    Sincronizado: {{ $empresa->sunat_sincronizado_en->format('d/m/Y H:i') }}
                                </span>
                            @endif
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[12px]">
                            @php
                            $datos = [
                                ['RUC',                        $empresa->ruc],
                                ['Tipo de contribuyente',      $empresa->tipoContribuyente?->nombre],
                                ['Estado',                     $empresa->estado_sunat],
                                ['Condición',                  $empresa->condicion_sunat],
                                ['Inicio de actividades',      $empresa->fecha_inicio_actividades?->format('d/m/Y')],
                            ];
                            @endphp
                            @foreach($datos as [$label, $val])
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">{{ $label }}</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $val ?: '—' }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">contact_phone</i>
                            Datos de Contacto
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-[12px]">
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Teléfono</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $empresa->telefono ?: '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Email</span>
                                @if($empresa->email)
                                    <a href="mailto:{{ $empresa->email }}" class="block text-sm font-medium text-primary-500 hover:underline truncate">{{ $empresa->email }}</a>
                                @else
                                    <span class="block text-sm font-medium text-black dark:text-white">—</span>
                                @endif
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Sitio web</span>
                                @if($empresa->sitio_web)
                                    <a href="{{ $empresa->sitio_web }}" target="_blank" rel="noopener" class="block text-sm font-medium text-primary-500 hover:underline truncate">{{ $empresa->sitio_web }}</a>
                                @else
                                    <span class="block text-sm font-medium text-black dark:text-white">—</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Ubicación -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">location_on</i>
                            Ubicación
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[12px]">
                            <div class="sm:col-span-2">
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Dirección fiscal</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $empresa->direccion_fiscal ?: '—' }}</span>
                            </div>
                            @if($empresa->distrito)
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Distrito</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $empresa->distrito->distrito }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Provincia / Departamento</span>
                                <span class="block text-sm font-medium text-black dark:text-white">
                                    {{ $empresa->distrito->provincia }} / {{ $empresa->distrito->departamento }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <!-- Proveedores de servicio -->
            @if($empresa->proveedoresServicio->isNotEmpty())
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                    <i class="material-symbols-outlined !text-[18px] text-primary-500">electrical_services</i>
                    Proveedores de Servicio vinculados
                    <span class="ml-[6px] text-xs font-normal text-gray-400">({{ $empresa->proveedoresServicio->count() }})</span>
                </h6>
                <div class="table-responsive overflow-x-auto">
                    <table class="w-full">
                        <thead class="text-black dark:text-white">
                            <tr>
                                <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md">Nombre comercial</th>
                                <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md">Tipo de servicio</th>
                                <th class="font-medium ltr:text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-black dark:text-white">
                            @foreach($empresa->proveedoresServicio as $proveedor)
                            <tr>
                                <td class="ltr:text-left whitespace-nowrap px-[20px] py-[13px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r text-sm font-medium">
                                    {{ $proveedor->nombre_comercial }}
                                </td>
                                <td class="ltr:text-left whitespace-nowrap px-[20px] py-[13px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r text-sm">
                                    @if($proveedor->tipoServicio)
                                        <span class="flex items-center gap-[6px]">
                                            <i class="material-symbols-outlined !text-[16px] text-primary-500">{{ $proveedor->tipoServicio->icono ?? 'category' }}</i>
                                            {{ $proveedor->tipoServicio->nombre }}
                                        </span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="ltr:text-left whitespace-nowrap px-[20px] py-[13px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r">
                                    @if($proveedor->activo)
                                        <span class="px-[8px] py-[3px] inline-block bg-success-50 dark:bg-[#15203c] text-success-600 rounded-sm font-medium text-xs">Activo</span>
                                    @else
                                        <span class="px-[8px] py-[3px] inline-block bg-danger-50 dark:bg-[#15203c] text-danger-500 rounded-sm font-medium text-xs">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
