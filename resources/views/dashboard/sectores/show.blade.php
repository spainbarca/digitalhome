<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $sector->nombre }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">{{ $sector->nombre }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.sectores.index') }}" class="transition-all hover:text-primary-500">Sectores</a>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Columna izquierda: identidad -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md text-center">
                        <div class="flex justify-center mb-[16px]">
                            <div class="w-[96px] h-[96px] rounded-full bg-primary-50 dark:bg-[#15203c] flex items-center justify-center ring-4 ring-primary-100 dark:ring-primary-900/30">
                                <i class="{{ $sector->icono ?? 'ri-building-line' }} text-[40px] text-primary-500"></i>
                            </div>
                        </div>

                        <h5 class="font-semibold text-black dark:text-white mb-[12px]">{{ $sector->nombre }}</h5>

                        <div class="flex flex-wrap justify-center gap-[6px] mb-[16px]">
                            <span class="inline-block text-xs font-medium px-[8px] py-[3px] rounded-[100px] {{ $sector->activo ? 'text-success-600 border border-success-600 bg-success-100' : 'text-danger-600 border border-danger-600 bg-danger-100' }}">
                                {{ $sector->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                            <span class="inline-flex items-center gap-[5px] text-xs font-medium px-[10px] py-[4px] rounded-md bg-primary-50 dark:bg-[#15203c] text-primary-500">
                                <i class="material-symbols-outlined !text-[13px]">business</i>
                                {{ $sector->empresas_count }} {{ Str::plural('empresa', $sector->empresas_count) }}
                            </span>
                        </div>

                        @if($sector->icono)
                        <p class="text-xs text-gray-400 font-mono mb-[16px]">{{ $sector->icono }}</p>
                        @endif

                        <!-- Acciones -->
                        <div class="mt-[20px] pt-[16px] border-t border-gray-100 dark:border-[#172036] flex gap-[10px] justify-center">
                            <a href="{{ route('dashboard.sectores.edit', $sector) }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">edit</i>
                                    Editar
                                </span>
                            </a>
                            <a href="{{ route('dashboard.sectores.index') }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] text-sm">
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: datos -->
                <div class="lg:col-span-2 space-y-[25px]">

                    <!-- Datos del sector -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">info</i>
                            Información del Sector
                        </h6>
                        <div class="grid grid-cols-1 gap-[16px]">
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Nombre</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $sector->nombre }}</span>
                            </div>
                            @if($sector->descripcion)
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Descripción</span>
                                <span class="block text-sm text-black dark:text-white leading-relaxed">{{ $sector->descripcion }}</span>
                            </div>
                            @endif
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Creado</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $sector->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Última actualización</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $sector->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
