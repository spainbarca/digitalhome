<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $proveedoresNegocio->nombre }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        @php
            $prov      = $proveedoresNegocio;
            $empresa   = $prov->empresa;
            $nombre    = $prov->nombre_comercial_resuelto ?? $prov->nombre ?? '—';
            $inicial   = strtoupper(mb_substr($nombre, 0, 1));
            $colores   = ['from-primary-400 to-primary-600','from-orange-400 to-orange-600','from-purple-400 to-purple-600','from-success-400 to-success-600','from-pink-400 to-pink-600'];
            $gradiente = $colores[abs(crc32($prov->id)) % count($colores)];
            $logoSrc   = $prov->logo_resuelto;
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
                        <a href="{{ route('dashboard.proveedores-negocio.index') }}" class="transition-all hover:text-primary-500">Proveedores</a>
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
                @if($prov->banner_path)
                    <img src="{{ asset('storage/' . $prov->banner_path) }}" alt="{{ $nombre }}"
                        class="w-full h-full object-cover"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                    <div class="w-full h-full bg-gradient-to-br {{ $gradiente }} hidden absolute inset-0"></div>
                @else
                    <div class="w-full h-full bg-gradient-to-br {{ $gradiente }}"></div>
                @endif
                <div class="absolute top-[12px] right-[12px] flex gap-[8px]">
                    <a href="{{ route('dashboard.proveedores-negocio.edit', $prov) }}"
                        class="inline-flex items-center gap-[6px] px-[12px] py-[7px] rounded-md bg-white/90 dark:bg-[#0c1427]/90 text-black dark:text-white text-sm font-medium hover:bg-white transition-all shadow-sm">
                        <i class="material-symbols-outlined !text-[16px]">edit</i>
                        Editar
                    </a>
                    <form method="POST" action="{{ route('dashboard.proveedores-negocio.destroy', $prov) }}"
                        onsubmit="return confirm('¿Eliminar este proveedor?')">
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

                        @if($prov->sigla_resuelta)
                            <span class="inline-block text-xs font-mono font-semibold px-[8px] py-[2px] rounded bg-gray-100 dark:bg-[#15203c] text-gray-500 dark:text-gray-400 mb-[8px]">{{ $prov->sigla_resuelta }}</span>
                        @endif

                        @if($prov->condicion_pago)
                        <div class="flex justify-center mb-[12px]">
                            <span class="inline-flex items-center gap-[5px] text-xs font-medium px-[10px] py-[4px] rounded-full bg-primary-50 dark:bg-[#15203c] text-primary-600 dark:text-primary-400">
                                <i class="material-symbols-outlined !text-[13px]">payments</i>
                                {{ ucfirst($prov->condicion_pago) }}
                            </span>
                        </div>
                        @endif

                        <div class="flex justify-center">
                            <span class="inline-flex items-center gap-[4px] text-xs px-[8px] py-[3px] rounded-full font-medium
                                {{ $prov->activo ? 'bg-success-100 text-success-600' : 'bg-danger-100 text-danger-600' }}">
                                <i class="material-symbols-outlined !text-[12px]">{{ $prov->activo ? 'check_circle' : 'cancel' }}</i>
                                {{ $prov->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>

                    <!-- Card datos de contacto -->
                    @if($prov->contacto || $prov->telefono)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">contact_phone</i>
                            Contacto
                        </h6>
                        <ul class="space-y-[12px]">
                            @if($prov->contacto)
                            <li class="flex items-start gap-[10px] text-sm">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400 mt-[1px] flex-shrink-0">person</i>
                                <div>
                                    <p class="text-xs text-gray-400 mb-[2px]">Persona</p>
                                    <span class="text-black dark:text-white">{{ $prov->contacto }}</span>
                                </div>
                            </li>
                            @endif
                            @if($prov->telefono)
                            <li class="flex items-start gap-[10px] text-sm">
                                <i class="material-symbols-outlined !text-[16px] text-gray-400 mt-[1px] flex-shrink-0">phone</i>
                                <div>
                                    <p class="text-xs text-gray-400 mb-[2px]">Teléfono</p>
                                    <span class="text-black dark:text-white font-mono">{{ $prov->telefono }}</span>
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
                                <img src="{{ asset('storage/' . $empresa->logo_url) }}"
                                    class="w-[48px] h-[48px] rounded-md object-cover flex-shrink-0" alt="{{ $empresa->razon_social }}">
                            @else
                                @php $bgEmp = ['bg-primary-500','bg-orange-500','bg-success-500','bg-purple-500','bg-pink-500'][abs(crc32($empresa->id)) % 5]; @endphp
                                <div class="w-[48px] h-[48px] rounded-md {{ $bgEmp }} flex items-center justify-center text-white font-bold text-[20px] flex-shrink-0 select-none">
                                    {{ strtoupper(mb_substr($empresa->razon_social, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <span class="block text-sm font-semibold text-black dark:text-white truncate">{{ $empresa->razon_social }}</span>
                                @if($empresa->ruc)
                                <span class="text-xs text-gray-400 font-mono">RUC: {{ $empresa->ruc }}</span>
                                @endif
                            </div>
                        </div>
                        @if($empresa->sector)
                        <div class="flex items-center gap-[6px] text-xs text-gray-500 dark:text-gray-400">
                            <i class="material-symbols-outlined !text-[14px]">category</i>
                            {{ $empresa->sector->nombre }}
                        </div>
                        @endif
                    </div>
                    @endif

                </div>

                <!-- Columna derecha -->
                <div class="lg:col-span-2">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] rounded-md">
                        <div class="border-b border-gray-100 dark:border-[#172036] px-[20px] md:px-[25px] pt-[20px] md:pt-[25px]">
                            <div class="flex gap-[0px] overflow-x-auto" id="tabsNav">
                                <button type="button"
                                    class="tab-btn px-[16px] py-[10px] text-sm font-medium whitespace-nowrap border-b-2 transition-colors border-primary-500 text-primary-500"
                                    data-tab="info">
                                    <i class="material-symbols-outlined !text-[16px] align-middle mr-[4px]">info</i>
                                    Información
                                </button>
                            </div>
                        </div>

                        <div id="tab-info" class="p-[20px] md:p-[25px]">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-[20px]">
                                <div>
                                    <dt class="text-xs text-gray-400 mb-[4px]">Nombre</dt>
                                    <dd class="text-sm font-medium text-black dark:text-white">{{ $prov->nombre }}</dd>
                                </div>
                                @if($prov->sigla)
                                <div>
                                    <dt class="text-xs text-gray-400 mb-[4px]">Sigla</dt>
                                    <dd class="text-sm font-mono font-semibold text-primary-500">{{ $prov->sigla }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-xs text-gray-400 mb-[4px]">Condición de Pago</dt>
                                    <dd class="text-sm text-black dark:text-white">{{ $prov->condicion_pago ? ucfirst($prov->condicion_pago) : '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-400 mb-[4px]">Estado</dt>
                                    <dd>
                                        <span class="inline-flex items-center gap-[4px] text-xs px-[8px] py-[3px] rounded-full font-medium
                                            {{ $prov->activo ? 'bg-success-100 text-success-600' : 'bg-danger-100 text-danger-600' }}">
                                            {{ $prov->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-400 mb-[4px]">Registrado</dt>
                                    <dd class="text-sm text-black dark:text-white">{{ $prov->created_at->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-400 mb-[4px]">Última actualización</dt>
                                    <dd class="text-sm text-black dark:text-white">{{ $prov->updated_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')

        <script>
            (function () {
                const btns = document.querySelectorAll('.tab-btn');
                btns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        btns.forEach(b => {
                            b.classList.remove('border-primary-500', 'text-primary-500');
                            b.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400', 'hover:text-black', 'dark:hover:text-white');
                        });
                        btn.classList.add('border-primary-500', 'text-primary-500');
                        btn.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                        document.querySelectorAll('[id^="tab-"]').forEach(p => p.classList.add('hidden'));
                        document.getElementById('tab-' + btn.dataset.tab)?.classList.remove('hidden');
                    });
                });
            })();
        </script>
    </body>
</html>
