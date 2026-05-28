<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Editar Miembro</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Editar Miembro</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.hogares.miembros.index', $hogar) }}" class="transition-all hover:text-primary-500">Miembros</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Editar
                    </li>
                </ol>
            </div>

            <form method="POST" action="{{ route('dashboard.hogares.miembros.update', [$hogar, $miembro]) }}">
                @csrf
                @method('PUT')
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header mb-[20px] md:mb-[25px]">
                        <h5 class="!mb-0">Datos del Miembro</h5>
                        <p class="!mb-0 text-sm text-gray-500 dark:text-gray-400 mt-[4px]">Hogar: {{ $hogar->nombre }}</p>
                    </div>
                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">

                            <!-- Persona (display only) -->
                            <div>
                                <label class="block mb-[10px] text-black dark:text-white font-medium">
                                    Persona
                                </label>
                                @php $personaActual = $miembro->user?->persona; @endphp
                                <input type="hidden" name="persona_id" value="{{ $personaActual?->id ?? old('persona_id') }}">
                                <div class="h-[55px] rounded-md border border-gray-200 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c] px-[17px] flex items-center text-gray-500 dark:text-gray-400">
                                    {{ $personaActual ? trim($personaActual->nombres . ' ' . $personaActual->apellido_paterno . ' ' . $personaActual->apellido_materno) : '–' }}
                                </div>
                            </div>

                            <!-- Parentesco -->
                            <div>
                                <label class="block mb-[10px] text-black dark:text-white font-medium">
                                    Parentesco <span class="text-danger-500">*</span>
                                </label>
                                <select name="parentesco_id"
                                    class="h-[55px] rounded-md text-black dark:text-white border @error('parentesco_id') border-danger-500 @else border-gray-200 dark:border-[#172036] @enderror bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                                    <option value="">Seleccionar parentesco...</option>
                                    @foreach ($parentescos as $parentesco)
                                        <option value="{{ $parentesco->id }}" {{ old('parentesco_id', $miembro->parentesco_id) == $parentesco->id ? 'selected' : '' }}>
                                            {{ $parentesco->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parentesco_id')
                                    <p class="mt-[6px] text-danger-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Apodo -->
                            <div class="md:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Apodo <span class="text-gray-400 text-sm">(opcional)</span>
                                </label>
                                <input type="text" name="apodo" value="{{ old('apodo', $miembro->apodo) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500"
                                    placeholder="Ej: Papá, Mamá, Junior...">
                            </div>

                            <!-- Es titular -->
                            <div class="md:col-span-2 flex items-center gap-[12px]">
                                <div class="relative inline-flex items-center">
                                    <input type="hidden" name="es_titular" value="0">
                                    <input type="checkbox" id="es_titular" name="es_titular" value="1"
                                        {{ old('es_titular', $miembro->rol === 'admin' ? '1' : '0') == '1' ? 'checked' : '' }}
                                        class="w-[18px] h-[18px] rounded border-gray-300 dark:border-[#172036] text-primary-500 focus:ring-primary-500 cursor-pointer">
                                </div>
                                <label for="es_titular" class="text-black dark:text-white font-medium cursor-pointer">
                                    Es titular del hogar (admin)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-[15px]">
                    <button type="submit" class="font-medium inline-block transition-all rounded-md py-[10px] px-[20px] bg-primary-500 text-white hover:bg-primary-400">
                        Guardar Cambios
                    </button>
                    <a href="{{ route('dashboard.hogares.miembros.index', $hogar) }}" class="font-medium inline-block transition-all rounded-md py-[10px] px-[20px] border border-gray-200 dark:border-[#172036] text-black dark:text-white hover:bg-gray-50 dark:hover:bg-[#15203c]">
                        Cancelar
                    </a>
                </div>
            </form>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
