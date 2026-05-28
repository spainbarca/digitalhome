<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>{{ $persona->nombres }} {{ $persona->apellido_paterno }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Detalle de Persona</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.personas.index') }}" class="transition-all hover:text-primary-500">Personas</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        {{ $persona->nombres }} {{ $persona->apellido_paterno }}
                    </li>
                </ol>
            </div>

            @php
                $nombreCompleto = trim(implode(' ', array_filter([$persona->nombres, $persona->apellido_paterno, $persona->apellido_materno])));
                $inicial = strtoupper(substr($persona->nombres, 0, 1));
                $sexoLabels = ['M' => 'Masculino', 'F' => 'Femenino', 'otro' => 'Otro'];
            @endphp

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Perfil -->
                <div class="xl:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="text-center mb-[20px]">
                            <div class="w-[90px] h-[90px] rounded-full mx-auto mb-[15px] overflow-hidden bg-primary-50 dark:bg-[#15203c] flex items-center justify-center">
                                @if ($persona->avatar_url)
                                    <img src="{{ $persona->avatar_url }}" class="w-full h-full object-cover" alt="{{ $persona->nombres }}">
                                @else
                                    <span class="text-primary-500 font-bold text-4xl">{{ $inicial }}</span>
                                @endif
                            </div>
                            <h5 class="!mb-[4px]">{{ $nombreCompleto }}</h5>
                            @if ($persona->tipoDocumento && $persona->numero_documento)
                                <p class="text-gray-500 dark:text-gray-400 text-sm !mb-0">
                                    {{ $persona->tipoDocumento->nombre ?? $persona->tipoDocumento->abreviatura ?? '' }}
                                    {{ $persona->numero_documento }}
                                </p>
                            @endif
                            <div class="mt-[12px]">
                                @if ($persona->activo)
                                    <span class="px-[12px] py-[4px] inline-block bg-success-100 dark:bg-[#15203c] text-success-600 rounded-full font-medium text-sm">Activo</span>
                                @else
                                    <span class="px-[12px] py-[4px] inline-block bg-danger-100 dark:bg-[#15203c] text-danger-600 rounded-full font-medium text-sm">Inactivo</span>
                                @endif
                            </div>
                        </div>

                        <div class="border-t border-gray-100 dark:border-[#172036] pt-[20px] flex flex-col gap-[12px]">
                            @if ($persona->email)
                                <div class="flex items-center gap-[10px] text-sm">
                                    <i class="material-symbols-outlined !text-lg text-primary-500 flex-shrink-0">mail</i>
                                    <span class="text-gray-600 dark:text-gray-400 truncate">{{ $persona->email }}</span>
                                </div>
                            @endif
                            @if ($persona->telefono)
                                <div class="flex items-center gap-[10px] text-sm">
                                    <i class="material-symbols-outlined !text-lg text-success-600 flex-shrink-0">phone</i>
                                    <span class="text-gray-600 dark:text-gray-400">{{ $persona->telefono }}</span>
                                </div>
                            @endif
                            @if ($persona->fecha_nacimiento)
                                <div class="flex items-center gap-[10px] text-sm">
                                    <i class="material-symbols-outlined !text-lg text-warning-600 flex-shrink-0">cake</i>
                                    <span class="text-gray-600 dark:text-gray-400">{{ $persona->fecha_nacimiento->format('d M Y') }}</span>
                                </div>
                            @endif
                            @if ($persona->sexo)
                                <div class="flex items-center gap-[10px] text-sm">
                                    <i class="material-symbols-outlined !text-lg text-gray-500 flex-shrink-0">wc</i>
                                    <span class="text-gray-600 dark:text-gray-400">{{ $sexoLabels[$persona->sexo] ?? $persona->sexo }}</span>
                                </div>
                            @endif
                            @if ($persona->tipo_sangre)
                                <div class="flex items-center gap-[10px] text-sm">
                                    <i class="material-symbols-outlined !text-lg text-danger-500 flex-shrink-0">water_drop</i>
                                    <span class="text-gray-600 dark:text-gray-400">Tipo {{ $persona->tipo_sangre }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Acciones -->
                        <div class="border-t border-gray-100 dark:border-[#172036] pt-[20px] mt-[20px] flex flex-col gap-[10px]">
                            <a href="{{ route('dashboard.personas.edit', $persona) }}" class="w-full font-medium text-center transition-all rounded-md py-[10px] px-[16px] bg-primary-500 text-white hover:bg-primary-400 flex items-center justify-center gap-[8px]">
                                <i class="material-symbols-outlined !text-lg">edit</i>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('dashboard.personas.destroy', $persona) }}" onsubmit="return confirm('¿Eliminar a {{ $persona->nombres }}? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full font-medium text-center transition-all rounded-md py-[10px] px-[16px] bg-danger-50 dark:bg-[#15203c] text-danger-500 hover:bg-danger-100 flex items-center justify-center gap-[8px]">
                                    <i class="material-symbols-outlined !text-lg">delete</i>
                                    Eliminar
                                </button>
                            </form>
                            <a href="{{ route('dashboard.personas.index') }}" class="w-full font-medium text-center transition-all rounded-md py-[10px] px-[16px] bg-gray-100 dark:bg-[#15203c] text-black dark:text-white hover:bg-gray-200 flex items-center justify-center gap-[8px]">
                                <i class="material-symbols-outlined !text-lg">arrow_back</i>
                                Volver al listado
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="xl:col-span-2 flex flex-col gap-[25px]">

                    <!-- Datos Personales -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] flex items-center gap-[10px]">
                            <div class="w-[36px] h-[36px] rounded-md bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined !text-lg text-primary-500">badge</i>
                            </div>
                            <h5 class="!mb-0">Datos Personales</h5>
                        </div>
                        <div class="trezo-card-content">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-0">

                                <div class="py-[13px] ltr:pr-[20px] rtl:pl-[20px] border-b border-gray-100 dark:border-[#172036] sm:ltr:border-r sm:rtl:border-l">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide !mb-[4px]">Nombres</p>
                                    <p class="font-medium text-black dark:text-white !mb-0">{{ $persona->nombres ?: '–' }}</p>
                                </div>
                                <div class="py-[13px] sm:ltr:pl-[20px] sm:rtl:pr-[20px] border-b border-gray-100 dark:border-[#172036]">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide !mb-[4px]">Apellido Paterno</p>
                                    <p class="font-medium text-black dark:text-white !mb-0">{{ $persona->apellido_paterno ?: '–' }}</p>
                                </div>
                                <div class="py-[13px] ltr:pr-[20px] rtl:pl-[20px] border-b border-gray-100 dark:border-[#172036] sm:ltr:border-r sm:rtl:border-l">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide !mb-[4px]">Apellido Materno</p>
                                    <p class="font-medium text-black dark:text-white !mb-0">{{ $persona->apellido_materno ?: '–' }}</p>
                                </div>
                                <div class="py-[13px] sm:ltr:pl-[20px] sm:rtl:pr-[20px] border-b border-gray-100 dark:border-[#172036]">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide !mb-[4px]">Fecha de Nacimiento</p>
                                    <p class="font-medium text-black dark:text-white !mb-0">
                                        {{ $persona->fecha_nacimiento ? $persona->fecha_nacimiento->format('d \d\e F \d\e Y') : '–' }}
                                    </p>
                                </div>
                                <div class="py-[13px] ltr:pr-[20px] rtl:pl-[20px] sm:ltr:border-r sm:rtl:border-l">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide !mb-[4px]">Sexo</p>
                                    <p class="font-medium text-black dark:text-white !mb-0">{{ $sexoLabels[$persona->sexo] ?? '–' }}</p>
                                </div>
                                <div class="py-[13px] sm:ltr:pl-[20px] sm:rtl:pr-[20px]">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide !mb-[4px]">Tipo de Sangre</p>
                                    @if ($persona->tipo_sangre)
                                        <span class="px-[8px] py-[3px] inline-block bg-danger-100 dark:bg-[#15203c] text-danger-600 rounded-sm font-bold text-sm">{{ $persona->tipo_sangre }}</span>
                                    @else
                                        <p class="font-medium text-black dark:text-white !mb-0">–</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Documento de Identidad -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] flex items-center gap-[10px]">
                            <div class="w-[36px] h-[36px] rounded-md bg-warning-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined !text-lg text-warning-600">id_card</i>
                            </div>
                            <h5 class="!mb-0">Documento de Identidad</h5>
                        </div>
                        <div class="trezo-card-content">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-0">
                                <div class="py-[13px] ltr:pr-[20px] rtl:pl-[20px] sm:ltr:border-r sm:rtl:border-l">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide !mb-[4px]">Tipo de Documento</p>
                                    <p class="font-medium text-black dark:text-white !mb-0">
                                        {{ $persona->tipoDocumento?->nombre ?? $persona->tipoDocumento?->abreviatura ?? '–' }}
                                    </p>
                                </div>
                                <div class="py-[13px] sm:ltr:pl-[20px] sm:rtl:pr-[20px]">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide !mb-[4px]">Número de Documento</p>
                                    <p class="font-medium text-black dark:text-white !mb-0 font-mono tracking-widest">
                                        {{ $persona->numero_documento ?: '–' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] flex items-center gap-[10px]">
                            <div class="w-[36px] h-[36px] rounded-md bg-success-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                <i class="material-symbols-outlined !text-lg text-success-600">contact_phone</i>
                            </div>
                            <h5 class="!mb-0">Información de Contacto</h5>
                        </div>
                        <div class="trezo-card-content">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-0">
                                <div class="py-[13px] ltr:pr-[20px] rtl:pl-[20px] sm:ltr:border-r sm:rtl:border-l">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide !mb-[4px]">Correo Electrónico</p>
                                    @if ($persona->email)
                                        <a href="mailto:{{ $persona->email }}" class="font-medium text-primary-500 hover:underline !mb-0 block truncate">{{ $persona->email }}</a>
                                    @else
                                        <p class="font-medium text-black dark:text-white !mb-0">–</p>
                                    @endif
                                </div>
                                <div class="py-[13px] sm:ltr:pl-[20px] sm:rtl:pr-[20px]">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide !mb-[4px]">Teléfono</p>
                                    @if ($persona->telefono)
                                        <a href="tel:{{ $persona->telefono }}" class="font-medium text-primary-500 hover:underline !mb-0">{{ $persona->telefono }}</a>
                                    @else
                                        <p class="font-medium text-black dark:text-white !mb-0">–</p>
                                    @endif
                                </div>
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
