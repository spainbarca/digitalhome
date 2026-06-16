<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Capacitaciones por Miembro</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Capacitaciones</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Laboral</li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Capacitaciones</li>
                </ol>
            </div>

            <!-- Cabecera informativa -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="flex items-center gap-[14px]">
                    <span class="w-[52px] h-[52px] rounded-full bg-primary-100 dark:bg-[#1a2d4d] flex items-center justify-center flex-shrink-0">
                        <i class="material-symbols-outlined !text-[28px] text-primary-500">workspace_premium</i>
                    </span>
                    <div>
                        <h5 class="!mb-[2px]">Perfil de Capacitaciones</h5>
                        <p class="text-sm text-gray-500 dark:text-gray-400 !mb-0">
                            Historial de formación y certificaciones por miembro del hogar.
                            Para registrar o editar, usa el módulo
                            <a href="{{ route('dashboard.capacitaciones.index') }}" class="text-primary-500 hover:underline">Gestión de Capacitaciones</a>.
                        </p>
                    </div>
                </div>
            </div>

            @if($miembros->isEmpty())
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[40px] rounded-md text-center">
                    <i class="material-symbols-outlined !text-[64px] text-gray-300 dark:text-gray-600 block mb-[14px]">group</i>
                    <h6 class="text-gray-400 dark:text-gray-500 !mb-[8px]">No hay miembros en el hogar</h6>
                    <p class="text-sm text-gray-400 dark:text-gray-500">Registra los miembros del hogar para ver sus capacitaciones.</p>
                </div>
            @else
                <!-- Grid de cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[20px] md:gap-[25px]">
                    @foreach($miembros as $m)
                    @php
                        $nombre  = trim(($m->user?->persona?->nombres ?? '') . ' ' . ($m->user?->persona?->apellido_paterno ?? '')) ?: $m->apodo ?: '—';
                        $avatar  = $m->user?->persona?->foto_url;
                        $inicial = mb_strtoupper(mb_substr($nombre, 0, 1));
                        $count   = $m->capacitaciones_count;
                    @endphp
                    <div class="trezo-card bg-white dark:bg-[#0c1427] rounded-md border border-gray-100 dark:border-[#172036] hover:shadow-md transition-all flex flex-col">
                        <div class="p-[24px] flex flex-col items-center text-center flex-1">

                            <!-- Avatar -->
                            @if($avatar)
                                <img src="{{ $avatar }}" alt="{{ $nombre }}"
                                     class="w-[80px] h-[80px] rounded-full object-cover border-[3px] border-white dark:border-[#172036] shadow mb-[14px]">
                            @else
                                <span class="w-[80px] h-[80px] rounded-full bg-primary-100 dark:bg-[#1a2d4d] flex items-center justify-center text-2xl font-bold text-primary-700 dark:text-primary-400 mb-[14px] shadow">
                                    {{ $inicial }}
                                </span>
                            @endif

                            <!-- Nombre -->
                            <h6 class="!mb-[4px] text-black dark:text-white font-semibold leading-tight">{{ $nombre }}</h6>

                            @if($m->apodo && $m->apodo !== $nombre)
                                <p class="text-xs text-gray-400 dark:text-gray-500 !mb-[12px]">{{ $m->apodo }}</p>
                            @else
                                <div class="mb-[12px]"></div>
                            @endif

                            <!-- Contador -->
                            <div class="flex items-center gap-[6px] bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400 rounded-full px-[14px] py-[5px] text-sm font-medium mb-[18px]">
                                <i class="material-symbols-outlined !text-[16px]">workspace_premium</i>
                                {{ $count }} {{ $count === 1 ? 'capacitación' : 'capacitaciones' }}
                            </div>

                        </div>

                        <!-- Botón -->
                        <div class="border-t border-gray-100 dark:border-[#172036] p-[16px]">
                            <a href="{{ route('dashboard.capacitaciones-perfil.show', $m) }}"
                               class="w-full inline-flex items-center justify-center gap-[6px] h-[38px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                <i class="material-symbols-outlined !text-[16px]">timeline</i>
                                Ver historial
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
