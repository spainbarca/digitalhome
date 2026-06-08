<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Entidades Legales</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Entidades Legales</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Legal</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Entidades</li>
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

            <!-- Buscador + Nuevo -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-[15px]">
                    <form method="GET" action="{{ route('dashboard.entidades-legales.index') }}" class="flex-1 max-w-md">
                        <div class="relative">
                            <i class="material-symbols-outlined absolute left-[14px] top-1/2 -translate-y-1/2 !text-[20px] text-gray-400">search</i>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre..."
                                class="h-[44px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-gray-50 dark:bg-[#0a0e19] pl-[42px] pr-[14px] block w-full outline-0 placeholder:text-gray-500 focus:border-primary-500 transition-all">
                        </div>
                    </form>
                    <a href="{{ route('dashboard.entidades-legales.create') }}"
                        class="inline-flex items-center gap-[8px] bg-primary-500 text-white py-[10px] px-[20px] rounded-md hover:bg-primary-400 transition-all font-medium text-sm whitespace-nowrap">
                        <i class="material-symbols-outlined !text-[18px]">add</i>
                        Nueva Entidad
                    </a>
                </div>
            </div>

            @if($entidades->isEmpty())
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[40px] rounded-md text-center">
                <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 mb-[12px] block">gavel</i>
                <p class="text-gray-500 dark:text-gray-400">
                    {{ $search ? 'No se encontraron entidades con ese nombre.' : 'Aún no hay entidades legales registradas.' }}
                </p>
                @if(!$search)
                <a href="{{ route('dashboard.entidades-legales.create') }}"
                    class="inline-flex items-center gap-[6px] mt-[16px] bg-primary-500 text-white py-[8px] px-[18px] rounded-md hover:bg-primary-400 transition-all font-medium text-sm">
                    <i class="material-symbols-outlined !text-[18px]">add</i>
                    Registrar primera entidad
                </a>
                @endif
            </div>
            @else

            <!-- Grid de tarjetas -->
            <div class="trezo-card mb-[25px]">
                <div class="trezo-card-content">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-[25px]">
                        @foreach($entidades as $entidad)
                        @php
                            $colores = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500','bg-cyan-500'];
                            $bg = $colores[abs(crc32($entidad->id)) % count($colores)];
                        @endphp
                        <div class="bg-white dark:bg-[#0c1427] p-[10px] rounded-md">
                            <!-- Imagen superior -->
                            @if($entidad->imagen_path)
                                <img src="{{ Storage::url($entidad->imagen_path) }}"
                                    class="rounded-md w-full h-[120px] object-cover" alt="{{ $entidad->nombre }}">
                            @else
                                <div class="{{ $bg }} rounded-md h-[120px] flex items-center justify-center">
                                    <i class="material-symbols-outlined !text-[40px] text-white opacity-40">
                                        {{ $entidad->tipoEntidadLegal->icono ?? 'gavel' }}
                                    </i>
                                </div>
                            @endif

                            <!-- Avatar logo superpuesto + info -->
                            <div class="text-center -mt-[34px]">
                                <div class="relative mb-[12px] inline-block">
                                    @if($entidad->logo_path)
                                        <img src="{{ Storage::url($entidad->logo_path) }}"
                                            class="rounded-full inline-block w-[68px] h-[68px] object-cover ring-2 ring-white dark:ring-[#0c1427]"
                                            alt="{{ $entidad->nombre }}">
                                    @else
                                        <div class="rounded-full inline-flex items-center justify-center w-[68px] h-[68px] {{ $bg }} ring-2 ring-white dark:ring-[#0c1427]">
                                            <span class="text-white font-bold text-[24px]">{{ strtoupper(mb_substr($entidad->nombre, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    @if($entidad->activo)
                                        <span class="absolute bottom-0 ltr:right-0 rtl:left-0 w-[16px] h-[16px] bg-success-500 rounded-full border-2 border-white dark:border-[#0c1427]"></span>
                                    @endif
                                </div>

                                <h3 class="!text-md !font-semibold !mb-[4px] truncate px-[4px]">{{ $entidad->nombre }}</h3>
                                <span class="inline-flex items-center gap-[4px] text-xs text-gray-500 dark:text-gray-400 mb-[6px]">
                                    <i class="material-symbols-outlined !text-[13px]">{{ $entidad->tipoEntidadLegal->icono ?? 'gavel' }}</i>
                                    {{ $entidad->tipoEntidadLegal->nombre }}
                                </span>
                                @if($entidad->empresa)
                                <span class="block text-xs text-primary-500 truncate px-[4px] mb-[4px]">{{ $entidad->empresa->razon_social }}</span>
                                @endif

                                <div class="h-[1px] bg-primary-50 dark:bg-[#172036] my-[12px]"></div>
                                <a href="{{ route('dashboard.entidades-legales.show', $entidad) }}"
                                    class="inline-block rounded-[7px] py-[6px] px-[17px] font-medium text-sm bg-primary-500 text-white transition-all hover:bg-primary-400">
                                    Ver detalle
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            @if($entidades->hasPages())
            <div class="flex justify-center mb-[25px]">
                {{ $entidades->links() }}
            </div>
            @endif

            @endif

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
