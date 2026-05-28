<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Mi Hogar</title>
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
                        Mi Hogar
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

            @if ($hogar)

                <!-- Card principal -->
                <div class="trezo-card bg-primary-50 dark:bg-[#16223e] mb-[25px] py-[40px] md:py-[50px] px-[20px] md:px-[25px] rounded-md">
                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-[30px] items-stretch">

                            <!-- Izquierda: avatar rectangular -->
                            <div class="md:col-span-2">
                                <div class="rounded-xl overflow-hidden w-full h-[280px] md:h-full bg-primary-100 dark:bg-[#1a2a4a] flex items-center justify-center">
                                    @if ($hogar->avatar_url)
                                        <img src="{{ $hogar->avatar_url }}" class="w-full h-full object-cover" alt="{{ $hogar->nombre }}">
                                    @else
                                        <i class="material-symbols-outlined text-primary-500" style="font-size:100px">home</i>
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
                                    <a href="{{ route('dashboard.hogares.show', $hogar) }}"
                                        class="font-medium inline-block transition-all rounded-md py-[8px] px-[16px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                        <span class="inline-block relative ltr:pl-[24px] rtl:pr-[24px]">
                                            <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 !text-lg">visibility</i>
                                            Ver Detalle
                                        </span>
                                    </a>
                                    <a href="{{ route('dashboard.hogares.miembros.index', $hogar) }}"
                                        class="font-medium inline-block transition-all rounded-md py-[8px] px-[16px] border border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white text-sm">
                                        <span class="inline-block relative ltr:pl-[24px] rtl:pr-[24px]">
                                            <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 !text-lg">group</i>
                                            Ver Miembros
                                        </span>
                                    </a>
                                    <a href="{{ route('dashboard.hogares.edit', $hogar) }}"
                                        class="font-medium inline-block transition-all rounded-md py-[8px] px-[16px] border border-gray-300 dark:border-[#172036] text-black dark:text-white hover:bg-gray-50 dark:hover:bg-[#15203c] text-sm">
                                        <span class="inline-block relative ltr:pl-[24px] rtl:pr-[24px]">
                                            <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 !text-lg">edit</i>
                                            Editar
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else

                <!-- Estado vacío -->
                <div class="trezo-card bg-primary-50 dark:bg-[#16223e] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-content">
                        <div class="flex flex-col items-center py-[60px] gap-[16px]">
                            <div class="w-[100px] h-[100px] bg-primary-100 dark:bg-[#15203c] rounded-full flex items-center justify-center">
                                <i class="material-symbols-outlined text-primary-400" style="font-size:56px">home</i>
                            </div>
                            <div class="text-center">
                                <h5 class="!mb-[8px] text-black dark:text-white">Aún no tienes un hogar registrado</h5>
                                <p class="!mb-0 text-gray-500 dark:text-gray-400 text-sm">Crea tu hogar para comenzar a gestionar a tus personas y miembros.</p>
                            </div>
                            <a href="{{ route('dashboard.hogares.create') }}"
                                class="font-medium inline-block transition-all rounded-md py-[10px] px-[24px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <span class="inline-block relative ltr:pl-[24px] rtl:pr-[24px]">
                                    <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 !text-lg">add_home</i>
                                    Crear mi Hogar
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

            @endif

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
