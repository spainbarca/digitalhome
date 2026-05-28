<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Nueva Persona</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nueva Persona</h5>
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
                        Nueva
                    </li>
                </ol>
            </div>

            <form method="POST" action="{{ route('dashboard.personas.store') }}">
                @csrf

                <div class="grid grid-cols-1 xl:grid-cols-5 gap-[25px] mb-[25px]">

                    <!-- Datos Personales -->
                    <div class="xl:col-span-3">
                        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                            <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center gap-[10px]">
                                <div class="w-[36px] h-[36px] rounded-md bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                    <i class="material-symbols-outlined !text-lg text-primary-500">person</i>
                                </div>
                                <h5 class="!mb-0">Datos Personales</h5>
                            </div>
                            <div class="trezo-card-content">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] sm:gap-[25px]">

                                    <!-- Nombres -->
                                    <div class="sm:col-span-2">
                                        <label class="mb-[10px] text-black dark:text-white font-medium block">
                                            Nombres <span class="text-danger-500">*</span>
                                        </label>
                                        <input type="text" name="nombres" value="{{ old('nombres') }}"
                                            placeholder="Ej. María Fernanda"
                                            class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('nombres') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                        @error('nombres')
                                            <p class="mt-[6px] text-danger-500 text-sm flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-base">error</i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Apellido Paterno -->
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block">
                                            Apellido Paterno
                                        </label>
                                        <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                                            placeholder="Ej. García"
                                            class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('apellido_paterno') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                        @error('apellido_paterno')
                                            <p class="mt-[6px] text-danger-500 text-sm flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-base">error</i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Apellido Materno -->
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block">
                                            Apellido Materno
                                        </label>
                                        <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}"
                                            placeholder="Ej. López"
                                            class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('apellido_materno') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                        @error('apellido_materno')
                                            <p class="mt-[6px] text-danger-500 text-sm flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-base">error</i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Tipo de Documento -->
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block">
                                            Tipo de Documento
                                        </label>
                                        <select name="tipo_documento_id"
                                            class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('tipo_documento_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[13px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                            <option value="">— Seleccionar —</option>
                                            @foreach ($tiposDocumento as $tipo)
                                                <option value="{{ $tipo->id }}" {{ old('tipo_documento_id') == $tipo->id ? 'selected' : '' }}>
                                                    {{ $tipo->nombre ?? $tipo->abreviatura ?? $tipo->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tipo_documento_id')
                                            <p class="mt-[6px] text-danger-500 text-sm flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-base">error</i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Número de Documento -->
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block">
                                            Número de Documento
                                        </label>
                                        <input type="text" name="numero_documento" value="{{ old('numero_documento') }}"
                                            placeholder="Ej. 12345678"
                                            class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('numero_documento') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                        @error('numero_documento')
                                            <p class="mt-[6px] text-danger-500 text-sm flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-base">error</i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Fecha de Nacimiento -->
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block">
                                            Fecha de Nacimiento
                                        </label>
                                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                                            class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_nacimiento') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                        @error('fecha_nacimiento')
                                            <p class="mt-[6px] text-danger-500 text-sm flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-base">error</i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Sexo -->
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block">
                                            Sexo
                                        </label>
                                        <select name="sexo"
                                            class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('sexo') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[13px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                            <option value="">— Seleccionar —</option>
                                            <option value="M" {{ old('sexo') === 'M' ? 'selected' : '' }}>Masculino</option>
                                            <option value="F" {{ old('sexo') === 'F' ? 'selected' : '' }}>Femenino</option>
                                            <option value="otro" {{ old('sexo') === 'otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        @error('sexo')
                                            <p class="mt-[6px] text-danger-500 text-sm flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-base">error</i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contacto y Salud -->
                    <div class="xl:col-span-2 flex flex-col gap-[25px]">

                        <!-- Información de Contacto -->
                        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                            <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center gap-[10px]">
                                <div class="w-[36px] h-[36px] rounded-md bg-success-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                    <i class="material-symbols-outlined !text-lg text-success-600">contact_phone</i>
                                </div>
                                <h5 class="!mb-0">Contacto</h5>
                            </div>
                            <div class="trezo-card-content">
                                <div class="flex flex-col gap-[20px]">

                                    <!-- Email -->
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block">
                                            Correo Electrónico
                                        </label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            placeholder="Ej. persona@correo.com"
                                            class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('email') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                        @error('email')
                                            <p class="mt-[6px] text-danger-500 text-sm flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-base">error</i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Teléfono -->
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block">
                                            Teléfono
                                        </label>
                                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                                            placeholder="Ej. +51 999 888 777"
                                            class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('telefono') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                        @error('telefono')
                                            <p class="mt-[6px] text-danger-500 text-sm flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-base">error</i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Salud y Estado -->
                        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                            <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center gap-[10px]">
                                <div class="w-[36px] h-[36px] rounded-md bg-danger-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0">
                                    <i class="material-symbols-outlined !text-lg text-danger-500">favorite</i>
                                </div>
                                <h5 class="!mb-0">Salud y Estado</h5>
                            </div>
                            <div class="trezo-card-content">
                                <div class="flex flex-col gap-[20px]">

                                    <!-- Tipo de Sangre -->
                                    <div>
                                        <label class="mb-[10px] text-black dark:text-white font-medium block">
                                            Tipo de Sangre
                                        </label>
                                        <select name="tipo_sangre"
                                            class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('tipo_sangre') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[13px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                            <option value="">— Seleccionar —</option>
                                            @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $grupo)
                                                <option value="{{ $grupo }}" {{ old('tipo_sangre') === $grupo ? 'selected' : '' }}>{{ $grupo }}</option>
                                            @endforeach
                                        </select>
                                        @error('tipo_sangre')
                                            <p class="mt-[6px] text-danger-500 text-sm flex items-center gap-[5px]">
                                                <i class="material-symbols-outlined !text-base">error</i> {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Activo -->
                                    <div class="flex items-center justify-between p-[15px] rounded-md bg-gray-50 dark:bg-[#15203c]">
                                        <div>
                                            <p class="font-medium text-black dark:text-white !mb-0">Persona activa</p>
                                            <p class="text-gray-500 dark:text-gray-400 text-xs !mb-0 mt-[2px]">Visible en el hogar</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="hidden" name="activo" value="0">
                                            <input type="checkbox" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-[44px] h-[24px] bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:ltr:left-[2px] rtl:after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all dark:border-gray-600 peer-checked:bg-primary-500"></div>
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Botones -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="flex items-center gap-[12px]">
                        <a href="{{ route('dashboard.personas.index') }}" class="font-medium inline-block transition-all rounded-md py-[10px] md:py-[12px] px-[20px] md:px-[22px] bg-gray-100 dark:bg-[#15203c] text-black dark:text-white hover:bg-gray-200 dark:hover:bg-[#1e2d4a]">
                            Cancelar
                        </a>
                        <button type="submit" class="font-medium inline-block transition-all rounded-md py-[10px] md:py-[12px] px-[20px] md:px-[22px] bg-primary-500 text-white hover:bg-primary-400">
                            <span class="inline-block relative ltr:pl-[29px] rtl:pr-[29px]">
                                <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2">save</i>
                                Guardar Persona
                            </span>
                        </button>
                    </div>
                </div>

            </form>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
