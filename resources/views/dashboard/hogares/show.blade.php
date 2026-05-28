<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $hogar->nombre }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Mi Hogar</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.hogares.index') }}" class="transition-all hover:text-primary-500">Mi Hogar</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Detalle
                    </li>
                </ol>
            </div>

            <!-- Flash -->
            @if (session('success'))
                <div class="mb-[25px] flex items-center gap-[12px] px-[20px] py-[14px] rounded-md bg-success-50 dark:bg-[#0c1427] text-success-600 border border-success-100 dark:border-[#172036]">
                    <i class="material-symbols-outlined !text-xl flex-shrink-0">check_circle</i>
                    <p class="!mb-0">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Card principal -->
            <div class="trezo-card bg-orange-100 dark:bg-[#16223e] mb-[25px] py-[40px] md:py-[50px] px-[20px] md:px-[25px] rounded-md">
                <div class="trezo-card-content">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-[30px] items-stretch">

                        <!-- Izquierda: avatar rectangular -->
                        <div class="md:col-span-2">
                            <div class="rounded-xl overflow-hidden w-full h-[280px] md:h-full bg-orange-50 dark:bg-[#1a2a4a] flex items-center justify-center">
                                @if ($hogar->avatar_url)
                                    <img src="{{ $hogar->avatar_url }}" class="w-full h-full object-cover" alt="{{ $hogar->nombre }}">
                                @else
                                    <i class="material-symbols-outlined text-orange-400" style="font-size:100px">home</i>
                                @endif
                            </div>
                        </div>

                        <!-- Derecha: mini-cards + botones -->
                        <div class="md:col-span-3">

                            <!-- Nombre: ancho completo -->
                            <div class="bg-white dark:bg-[#0c1427] rounded-md py-[17px] px-[20px] border border-gray-100 dark:border-[#172036] mb-[15px]">
                                <span class="block text-gray-500 dark:text-gray-400 text-sm mb-[4px]">Nombre del hogar</span>
                                <h5 class="!mb-0 !font-semibold text-black dark:text-white">{{ $hogar->nombre }}</h5>
                            </div>

                            <!-- Grid 2 columnas -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[15px] mb-[20px]">
                                <div class="bg-white dark:bg-[#0c1427] rounded-md py-[17px] px-[20px] border border-gray-100 dark:border-[#172036]">
                                    <span class="block text-gray-500 dark:text-gray-400 text-sm mb-[4px]">Propietario</span>
                                    <h5 class="!mb-0 !font-semibold text-black dark:text-white">{{ $hogar->owner->name }}</h5>
                                </div>
                                <div class="bg-white dark:bg-[#0c1427] rounded-md py-[17px] px-[20px] border border-gray-100 dark:border-[#172036]">
                                    <span class="block text-gray-500 dark:text-gray-400 text-sm mb-[4px]">Creado el</span>
                                    <h5 class="!mb-0 !font-semibold text-black dark:text-white">{{ $hogar->created_at->format('d M Y') }}</h5>
                                </div>
                                @if ($hogar->descripcion)
                                    <div class="sm:col-span-2 bg-white dark:bg-[#0c1427] rounded-md py-[17px] px-[20px] border border-gray-100 dark:border-[#172036]">
                                        <span class="block text-gray-500 dark:text-gray-400 text-sm mb-[4px]">Descripción</span>
                                        <p class="!mb-0 text-black dark:text-white text-sm">{{ $hogar->descripcion }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-[10px] flex-wrap">
                                <a href="{{ route('dashboard.hogares.miembros.index', $hogar) }}"
                                    class="font-medium inline-block transition-all rounded-md py-[8px] px-[16px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                    <span class="inline-block relative ltr:pl-[24px] rtl:pr-[24px]">
                                        <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 !text-lg">group</i>
                                        Ver Miembros
                                    </span>
                                </a>
                                <a href="{{ route('dashboard.hogares.edit', $hogar) }}"
                                    class="font-medium inline-block transition-all rounded-md py-[8px] px-[16px] border border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white text-sm">
                                    <span class="inline-block relative ltr:pl-[24px] rtl:pr-[24px]">
                                        <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 !text-lg">edit</i>
                                        Editar
                                    </span>
                                </a>
                                <form method="POST" action="{{ route('dashboard.hogares.destroy', $hogar) }}" class="inline"
                                    onsubmit="return confirm('¿Eliminar este hogar? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-medium inline-block transition-all rounded-md py-[8px] px-[16px] border border-danger-500 text-danger-500 hover:bg-danger-500 hover:text-white text-sm">
                                        <span class="inline-block relative ltr:pl-[24px] rtl:pr-[24px]">
                                            <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 !text-lg">delete</i>
                                            Eliminar
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats mini-cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[25px] mb-[25px]">
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-content">
                        <div class="flex items-center gap-[15px]">
                            <div class="w-[54px] h-[54px] rounded-md bg-primary-100 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined text-primary-500 !text-[28px]">group</i>
                            </div>
                            <div>
                                <span class="block text-[28px] font-bold text-black dark:text-white leading-none mb-[4px]">{{ $totalMiembros }}</span>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">Total de miembros</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-content">
                        <div class="flex items-center gap-[15px]">
                            <div class="w-[54px] h-[54px] rounded-md bg-success-100 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined text-success-600 !text-[28px]">how_to_reg</i>
                            </div>
                            <div>
                                <span class="block text-[28px] font-bold text-black dark:text-white leading-none mb-[4px]">{{ $miembrosActivos }}</span>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">Miembros activos</span>
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
