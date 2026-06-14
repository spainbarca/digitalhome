<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $entidadLegal->nombre }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $entidadLegal->nombre }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.entidades-legales.index') }}" class="transition-all hover:text-primary-500">Entidades</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Detalle</li>
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
                $colores = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500','bg-cyan-500'];
                $bg = $colores[abs(crc32($entidadLegal->id)) % count($colores)];
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Columna izquierda: identidad -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] rounded-md overflow-hidden">
                        <!-- Banner -->
                        @if($entidadLegal->banner_path)
                            <img src="{{ Storage::url($entidadLegal->banner_path) }}"
                                class="w-full h-[120px] object-cover" alt="banner">
                        @elseif($entidadLegal->imagen_path)
                            <img src="{{ Storage::url($entidadLegal->imagen_path) }}"
                                class="w-full h-[120px] object-cover" alt="imagen">
                        @else
                            <div class="{{ $bg }} h-[120px] flex items-center justify-center">
                                <i class="material-symbols-outlined !text-[48px] text-white opacity-30">
                                    {{ $entidadLegal->tipoEntidadLegal->icono ?? 'gavel' }}
                                </i>
                            </div>
                        @endif

                        <div class="p-[20px] md:p-[25px] -mt-[42px]">
                            <!-- Logo superpuesto -->
                            <div class="mb-[16px]">
                                @if($entidadLegal->logo_path)
                                    <img src="{{ Storage::url($entidadLegal->logo_path) }}"
                                        class="w-[84px] h-[84px] rounded-full object-cover ring-4 ring-white dark:ring-[#0c1427]"
                                        alt="{{ $entidadLegal->nombre }}">
                                @else
                                    <div class="w-[84px] h-[84px] rounded-full {{ $bg }} flex items-center justify-center text-white font-bold text-[32px] ring-4 ring-white dark:ring-[#0c1427]">
                                        {{ strtoupper(mb_substr($entidadLegal->nombre, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <h4 class="font-semibold text-black dark:text-white mb-[4px]">{{ $entidadLegal->nombre }}</h4>

                            @if($entidadLegal->sigla_resuelta)
                                <span class="inline-block text-xs font-mono font-semibold px-[8px] py-[2px] rounded bg-gray-100 dark:bg-[#15203c] text-gray-500 dark:text-gray-400 mb-[8px]">{{ $entidadLegal->sigla_resuelta }}</span>
                            @endif

                            <div class="flex items-center gap-[6px] mb-[12px]">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400">{{ $entidadLegal->tipoEntidadLegal->icono ?? 'gavel' }}</i>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $entidadLegal->tipoEntidadLegal->nombre }}</span>
                            </div>

                            @if($entidadLegal->activo)
                                <span class="inline-flex items-center gap-[4px] text-xs font-medium text-success-600 bg-success-100 border border-success-200 rounded-full px-[10px] py-[3px] mb-[16px]">
                                    <span class="w-[6px] h-[6px] rounded-full bg-success-500 inline-block"></span>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-[4px] text-xs font-medium text-gray-500 bg-gray-100 border border-gray-200 rounded-full px-[10px] py-[3px] mb-[16px]">
                                    <span class="w-[6px] h-[6px] rounded-full bg-gray-400 inline-block"></span>
                                    Inactivo
                                </span>
                            @endif

                            @if($entidadLegal->descripcion)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-[16px]">{{ $entidadLegal->descripcion }}</p>
                            @endif

                            <!-- Botones de acción -->
                            <div class="flex gap-[10px] mt-[16px] pt-[16px] border-t border-gray-100 dark:border-[#172036]">
                                <a href="{{ route('dashboard.entidades-legales.edit', $entidadLegal) }}"
                                    class="flex-1 text-center inline-flex items-center justify-center gap-[6px] bg-primary-500 text-white py-[8px] px-[14px] rounded-md hover:bg-primary-400 transition-all text-sm font-medium">
                                    <i class="material-symbols-outlined !text-[16px]">edit</i>
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('dashboard.entidades-legales.destroy', $entidadLegal) }}"
                                    onsubmit="return confirm('¿Eliminar esta entidad legal?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center gap-[6px] bg-danger-500 text-white py-[8px] px-[14px] rounded-md hover:bg-danger-400 transition-all text-sm font-medium">
                                        <i class="material-symbols-outlined !text-[16px]">delete</i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: datos -->
                <div class="lg:col-span-2 flex flex-col gap-[25px]">
                    <!-- Datos de contacto y ubicación -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] flex items-center gap-[8px] pb-[16px] border-b border-gray-100 dark:border-[#172036]">
                            <i class="material-symbols-outlined !text-[20px] text-primary-500">info</i>
                            <h5 class="!mb-0">Información</h5>
                        </div>
                        <div class="space-y-[14px]">

                            @if($entidadLegal->empresa)
                            <div class="flex items-start gap-[12px]">
                                <i class="material-symbols-outlined !text-[18px] text-gray-400 mt-[2px]">business</i>
                                <div>
                                    <span class="block text-xs text-gray-400 mb-[2px]">Empresa vinculada</span>
                                    <a href="{{ route('dashboard.empresas.show', $entidadLegal->empresa) }}"
                                        class="text-sm text-primary-500 hover:underline font-medium">
                                        {{ $entidadLegal->empresa->razon_social }}
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($entidadLegal->telefono)
                            <div class="flex items-start gap-[12px]">
                                <i class="material-symbols-outlined !text-[18px] text-gray-400 mt-[2px]">phone</i>
                                <div>
                                    <span class="block text-xs text-gray-400 mb-[2px]">Teléfono</span>
                                    <span class="text-sm text-black dark:text-white">{{ $entidadLegal->telefono }}</span>
                                </div>
                            </div>
                            @endif

                            @if($entidadLegal->email)
                            <div class="flex items-start gap-[12px]">
                                <i class="material-symbols-outlined !text-[18px] text-gray-400 mt-[2px]">email</i>
                                <div>
                                    <span class="block text-xs text-gray-400 mb-[2px]">Email</span>
                                    <a href="mailto:{{ $entidadLegal->email }}" class="text-sm text-primary-500 hover:underline">{{ $entidadLegal->email }}</a>
                                </div>
                            </div>
                            @endif

                            @if($entidadLegal->sitio_web)
                            <div class="flex items-start gap-[12px]">
                                <i class="material-symbols-outlined !text-[18px] text-gray-400 mt-[2px]">language</i>
                                <div>
                                    <span class="block text-xs text-gray-400 mb-[2px]">Sitio web</span>
                                    <a href="{{ $entidadLegal->sitio_web }}" target="_blank" rel="noopener" class="text-sm text-primary-500 hover:underline truncate block max-w-[300px]">{{ $entidadLegal->sitio_web }}</a>
                                </div>
                            </div>
                            @endif

                            @if($entidadLegal->direccion)
                            <div class="flex items-start gap-[12px]">
                                <i class="material-symbols-outlined !text-[18px] text-gray-400 mt-[2px]">location_on</i>
                                <div>
                                    <span class="block text-xs text-gray-400 mb-[2px]">Dirección</span>
                                    <span class="text-sm text-black dark:text-white">{{ $entidadLegal->direccion }}</span>
                                </div>
                            </div>
                            @endif

                            @if($entidadLegal->distrito)
                            <div class="flex items-start gap-[12px]">
                                <i class="material-symbols-outlined !text-[18px] text-gray-400 mt-[2px]">map</i>
                                <div>
                                    <span class="block text-xs text-gray-400 mb-[2px]">Distrito</span>
                                    <span class="text-sm text-black dark:text-white">
                                        {{ $entidadLegal->distrito->distrito }} &rsaquo;
                                        {{ $entidadLegal->distrito->provincia }} &rsaquo;
                                        {{ $entidadLegal->distrito->departamento }}
                                    </span>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>

                    <!-- Documentos legales -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] flex items-center justify-between pb-[16px] border-b border-gray-100 dark:border-[#172036]">
                            <div class="flex items-center gap-[8px]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">description</i>
                                <h5 class="!mb-0">Documentos Legales</h5>
                            </div>
                            <span class="text-xs text-gray-400 bg-gray-100 dark:bg-[#172036] px-[8px] py-[3px] rounded-full">
                                {{ $entidadLegal->documentosLegales->count() }}
                            </span>
                        </div>

                        @if($entidadLegal->documentosLegales->isEmpty())
                        <p class="text-sm text-gray-400 text-center py-[20px]">No hay documentos vinculados a esta entidad.</p>
                        @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-[#172036]">
                                        <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 font-medium">Título</th>
                                        <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 font-medium">Tipo</th>
                                        <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 font-medium">Estado</th>
                                        <th class="text-left py-[10px] px-[12px] text-xs text-gray-500 font-medium">Vencimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entidadLegal->documentosLegales as $doc)
                                    <tr class="border-b border-gray-50 dark:border-[#172036] hover:bg-gray-50 dark:hover:bg-[#15203c] transition-colors">
                                        <td class="py-[10px] px-[12px] text-black dark:text-white font-medium">{{ $doc->titulo }}</td>
                                        <td class="py-[10px] px-[12px] text-gray-500">{{ $doc->tipoDocumentoLegal->nombre ?? '—' }}</td>
                                        <td class="py-[10px] px-[12px]">
                                            @php
                                                $color = $doc->estadoDocumentoLegal->color ?? 'gray';
                                                $cls = match($color) {
                                                    'green'  => 'text-success-600 bg-success-100 border-success-200',
                                                    'orange' => 'text-orange-600 bg-orange-100 border-orange-200',
                                                    'red'    => 'text-danger-600 bg-danger-100 border-danger-200',
                                                    'blue'   => 'text-primary-600 bg-primary-100 border-primary-200',
                                                    default  => 'text-gray-600 bg-gray-100 border-gray-200',
                                                };
                                            @endphp
                                            <span class="text-xs font-medium border rounded-full px-[8px] py-[2px] {{ $cls }}">
                                                {{ $doc->estadoDocumentoLegal->nombre ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="py-[10px] px-[12px] text-gray-500 text-xs">
                                            {{ $doc->fecha_vencimiento ? $doc->fecha_vencimiento->format('d/m/Y') : '—' }}
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
