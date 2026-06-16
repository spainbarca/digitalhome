<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $capacitacion->nombre }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Detalle de Capacitación</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.capacitaciones.index') }}" class="transition-all hover:text-primary-500">Capacitaciones</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        {{ Str::limit($capacitacion->nombre, 40) }}
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
                $nombreM  = trim(($capacitacion->hogarMiembro?->user?->persona?->nombres ?? '') . ' ' . ($capacitacion->hogarMiembro?->user?->persona?->apellido_paterno ?? '')) ?: $capacitacion->hogarMiembro?->apodo ?? '—';
                $avatarM  = $capacitacion->hogarMiembro?->user?->persona?->foto_url;
                $inicialM = mb_strtoupper(mb_substr($nombreM, 0, 1));
                $instituc = $capacitacion->institucionEducativa?->nombre_referencial ?? $capacitacion->institucion_nombre ?? null;
                $logoIE   = $capacitacion->institucionEducativa?->logo_path ? asset('storage/' . $capacitacion->institucionEducativa->logo_path) : null;
                $ext      = $capacitacion->file_path ? strtolower(pathinfo($capacitacion->file_path, PATHINFO_EXTENSION)) : null;
                $esImagen = in_array($ext, ['jpg','jpeg','png']);
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                {{-- Panel izquierdo --}}
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md h-full">

                        <!-- Icono + nombre -->
                        <div class="bg-primary-50 dark:bg-[#15203c] rounded-md py-[30px] px-[20px] text-center mb-[20px]">
                            <i class="material-symbols-outlined !text-[52px] text-primary-400 block mb-[10px]">school</i>
                            <h5 class="!mb-[8px] text-black dark:text-white font-semibold leading-snug">{{ $capacitacion->nombre }}</h5>
                            @if($capacitacion->tipoCapacitacion)
                                <span class="inline-flex items-center gap-[6px] text-xs font-medium py-[4px] px-[12px] rounded-full bg-white dark:bg-[#0c1427] text-primary-600 dark:text-primary-400">
                                    <i class="material-symbols-outlined !text-[14px]">{{ $capacitacion->tipoCapacitacion->icono ?? 'school' }}</i>
                                    {{ $capacitacion->tipoCapacitacion->nombre }}
                                </span>
                            @endif
                        </div>

                        <!-- Estado -->
                        <div class="flex items-center justify-between mb-[16px]">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Estado</span>
                            @if($capacitacion->activo)
                                <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[3px] px-[10px] rounded-full bg-success-100 dark:bg-[#15203c] text-success-700 dark:text-success-400">
                                    <i class="material-symbols-outlined !text-[12px]">check_circle</i>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-[4px] text-xs font-medium py-[3px] px-[10px] rounded-full bg-gray-100 dark:bg-[#172036] text-gray-500">
                                    <i class="material-symbols-outlined !text-[12px]">cancel</i>
                                    Inactivo
                                </span>
                            @endif
                        </div>

                        <!-- Miembro -->
                        <div class="flex items-center gap-[10px] p-[14px] rounded-md bg-gray-50 dark:bg-[#15203c] mb-[16px]">
                            @if($avatarM)
                                <img src="{{ $avatarM }}" class="w-[40px] h-[40px] rounded-full object-cover flex-shrink-0" alt="{{ $nombreM }}">
                            @else
                                <span class="w-[40px] h-[40px] rounded-full bg-primary-100 dark:bg-[#1a2d4d] flex items-center justify-center text-base font-bold text-primary-700 dark:text-primary-400 flex-shrink-0">
                                    {{ $inicialM }}
                                </span>
                            @endif
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Miembro</p>
                                <p class="text-sm font-medium text-black dark:text-white truncate">{{ $nombreM }}</p>
                            </div>
                        </div>

                        <!-- Empleo vinculado -->
                        @if($capacitacion->empleo)
                        <div class="flex items-center gap-[10px] p-[14px] rounded-md border border-orange-100 dark:border-[#172036] bg-orange-50 dark:bg-[#15203c] mb-[16px]">
                            <i class="material-symbols-outlined !text-[28px] text-orange-400 flex-shrink-0">work</i>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-[2px]">Empleo asociado</p>
                                <a href="{{ route('dashboard.empleos.show', $capacitacion->empleo) }}"
                                   class="text-sm font-medium text-orange-600 dark:text-orange-400 hover:underline truncate block">
                                    {{ $capacitacion->empleo->cargo }}
                                </a>
                                @if($capacitacion->empleo->empleador)
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ $capacitacion->empleo->empleador->empresa?->razon_social ?? $capacitacion->empleo->empleador->nombre ?? '' }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="flex items-center gap-[8px] text-xs text-gray-400 dark:text-gray-500 mb-[16px]">
                            <i class="material-symbols-outlined !text-[16px]">person</i>
                            Capacitación libre (sin empleo)
                        </div>
                        @endif

                        <!-- Acciones -->
                        <div class="flex flex-col gap-[8px] pt-[16px] border-t border-gray-100 dark:border-[#172036]">
                            <a href="{{ route('dashboard.capacitaciones.edit', $capacitacion) }}"
                               class="inline-flex items-center justify-center gap-[6px] w-full py-[9px] px-[16px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                <i class="material-symbols-outlined !text-[16px]">edit</i>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('dashboard.capacitaciones.destroy', $capacitacion) }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="_redirect" value="index">
                                <button type="submit"
                                    onclick="return confirm('¿Eliminar «{{ addslashes($capacitacion->nombre) }}»?')"
                                    class="inline-flex items-center justify-center gap-[6px] w-full py-[9px] px-[16px] rounded-md border border-danger-300 text-danger-500 text-sm font-medium hover:bg-danger-50 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">delete</i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Panel derecho --}}
                <div class="lg:col-span-2 flex flex-col gap-[25px]">

                    {{-- Datos generales --}}
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">info</i>
                            Información General
                        </h6>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-[24px] gap-y-[14px]">
                            <div>
                                <dt class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-[4px]">Institución</dt>
                                <dd class="text-sm text-black dark:text-white font-medium">
                                    @if($logoIE)
                                        <img src="{{ $logoIE }}" class="w-[20px] h-[20px] rounded object-cover inline-block mr-[6px]" alt="">
                                    @endif
                                    {{ $instituc ?? '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-[4px]">Período</dt>
                                <dd class="text-sm text-black dark:text-white font-medium">
                                    {{ $capacitacion->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                    @if($capacitacion->fecha_fin) — {{ $capacitacion->fecha_fin->format('d/m/Y') }} @endif
                                </dd>
                            </div>
                            @if($capacitacion->horas_academicas !== null)
                            <div>
                                <dt class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-[4px]">Horas académicas</dt>
                                <dd class="text-sm text-black dark:text-white font-mono font-medium">{{ $capacitacion->horas_academicas }}h</dd>
                            </div>
                            @endif
                            @if($capacitacion->fecha_vencimiento)
                            <div>
                                <dt class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-[4px]">Vencimiento certificado</dt>
                                <dd class="text-sm font-medium {{ $capacitacion->fecha_vencimiento->isPast() ? 'text-danger-500' : ($capacitacion->fecha_vencimiento->diffInDays(now()) <= 30 ? 'text-warning-600' : 'text-black dark:text-white') }}">
                                    {{ $capacitacion->fecha_vencimiento->format('d/m/Y') }}
                                    @if($capacitacion->fecha_vencimiento->isPast())
                                        <span class="text-xs">(vencido)</span>
                                    @elseif($capacitacion->fecha_vencimiento->diffInDays(now()) <= 30)
                                        <span class="text-xs">(próximo a vencer)</span>
                                    @endif
                                </dd>
                            </div>
                            @endif
                            @if($capacitacion->descripcion)
                            <div class="sm:col-span-2">
                                <dt class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-[4px]">Descripción</dt>
                                <dd class="text-sm text-gray-700 dark:text-gray-300">{{ $capacitacion->descripcion }}</dd>
                            </div>
                            @endif
                            @if($capacitacion->notas)
                            <div class="sm:col-span-2">
                                <dt class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-[4px]">Notas</dt>
                                <dd class="text-sm text-gray-700 dark:text-gray-300">{{ $capacitacion->notas }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    {{-- Certificado --}}
                    @if($capacitacion->file_path || $capacitacion->codigo_certificado || $capacitacion->url_verificacion)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">verified</i>
                            Certificado
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px]">
                            @if($capacitacion->codigo_certificado)
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-[4px]">Código / Folio</p>
                                <p class="text-sm font-mono font-medium text-black dark:text-white">{{ $capacitacion->codigo_certificado }}</p>
                            </div>
                            @endif

                            @if($capacitacion->url_verificacion)
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-[4px]">Verificación online</p>
                                <a href="{{ $capacitacion->url_verificacion }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-[6px] text-sm text-primary-500 hover:text-primary-400 font-medium transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">open_in_new</i>
                                    Verificar credencial
                                </a>
                            </div>
                            @endif
                        </div>

                        @if($capacitacion->file_path)
                        <div class="mt-[16px]">
                            @if($esImagen)
                                <img src="{{ asset('storage/' . $capacitacion->file_path) }}"
                                     class="w-full max-h-[400px] object-contain rounded-md border border-gray-100 dark:border-[#172036]"
                                     alt="Certificado">
                                <div class="mt-[10px] text-center">
                                    <a href="{{ asset('storage/' . $capacitacion->file_path) }}" target="_blank"
                                       class="inline-flex items-center gap-[6px] text-sm text-primary-500 hover:underline">
                                        <i class="material-symbols-outlined !text-[16px]">download</i>
                                        Descargar certificado
                                    </a>
                                </div>
                            @else
                                <div class="flex items-center gap-[14px] p-[16px] bg-gray-50 dark:bg-[#15203c] rounded-md">
                                    <i class="material-symbols-outlined !text-[40px] text-danger-400">picture_as_pdf</i>
                                    <div>
                                        <p class="text-sm font-medium text-black dark:text-white mb-[4px]">Archivo PDF</p>
                                        <a href="{{ asset('storage/' . $capacitacion->file_path) }}" target="_blank"
                                           class="inline-flex items-center gap-[4px] text-sm text-primary-500 hover:underline">
                                            <i class="material-symbols-outlined !text-[15px]">open_in_new</i>
                                            Abrir / Descargar
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif

                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
