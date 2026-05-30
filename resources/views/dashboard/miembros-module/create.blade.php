<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Agregar Miembro</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Agregar Miembro</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.miembros.index', $hogarSeleccionado ? ['hogar' => $hogarSeleccionado->id] : []) }}"
                           class="transition-all hover:text-primary-500">Miembros</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Agregar
                    </li>
                </ol>
            </div>

            <form method="POST" action="{{ route('dashboard.miembros.store') }}">
                @csrf

                @if($errors->any())
                <div class="mb-[25px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                    <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                        <h5 class="!mb-0">Datos del Miembro</h5>
                    </div>
                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">

                            <!-- Hogar -->
                            <div class="md:col-span-2">
                                <label class="block mb-[10px] text-black dark:text-white font-medium">
                                    Hogar <span class="text-danger-500">*</span>
                                </label>
                                <select name="hogar_id" id="hogar_id_selector"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('hogar_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500 cursor-pointer">
                                    <option value="">Seleccionar hogar...</option>
                                    @foreach($hogares as $hogar)
                                        <option value="{{ $hogar->id }}"
                                            {{ old('hogar_id', $hogarSeleccionado?->id) == $hogar->id ? 'selected' : '' }}>
                                            {{ $hogar->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hogar_id')<p class="mt-[6px] text-danger-500 text-sm">{{ $message }}</p>@enderror
                            </div>

                            <!-- Persona -->
                            <div>
                                <label class="block mb-[10px] text-black dark:text-white font-medium">
                                    Persona <span class="text-danger-500">*</span>
                                </label>
                                @if($hogarSeleccionado && $personas->isEmpty())
                                    <div class="h-[55px] rounded-md border border-gray-200 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c] px-[17px] flex items-center text-gray-500 dark:text-gray-400 text-sm">
                                        No hay personas disponibles para agregar a este hogar.
                                    </div>
                                @elseif(!$hogarSeleccionado)
                                    <div class="h-[55px] rounded-md border border-gray-200 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c] px-[17px] flex items-center text-gray-400 dark:text-gray-500 text-sm">
                                        <i class="material-symbols-outlined !text-[18px] mr-[8px]">info</i>
                                        Selecciona un hogar primero
                                    </div>
                                @else
                                    <select name="persona_id"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('persona_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500 cursor-pointer">
                                        <option value="">Seleccionar persona...</option>
                                        @foreach($personas as $persona)
                                            <option value="{{ $persona->id }}"
                                                {{ old('persona_id', request('persona_id')) == $persona->id ? 'selected' : '' }}>
                                                {{ trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno) }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('persona_id')<p class="mt-[6px] text-danger-500 text-sm">{{ $message }}</p>@enderror
                            </div>

                            <!-- Parentesco -->
                            <div>
                                <label class="block mb-[10px] text-black dark:text-white font-medium">
                                    Parentesco <span class="text-danger-500">*</span>
                                </label>
                                <select name="parentesco_id"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('parentesco_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500 cursor-pointer">
                                    <option value="">Seleccionar parentesco...</option>
                                    @foreach($parentescos as $parentesco)
                                        <option value="{{ $parentesco->id }}"
                                            {{ old('parentesco_id') == $parentesco->id ? 'selected' : '' }}>
                                            {{ $parentesco->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parentesco_id')<p class="mt-[6px] text-danger-500 text-sm">{{ $message }}</p>@enderror
                            </div>

                            <!-- Apodo -->
                            <div class="md:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Apodo <span class="text-gray-400 text-sm font-normal">(opcional)</span>
                                </label>
                                <input type="text" name="apodo" value="{{ old('apodo') }}"
                                    placeholder="Ej: Papá, Mamá, Junior..."
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('apodo') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500 placeholder:text-gray-500 dark:placeholder:text-gray-400">
                            </div>

                            <!-- Es titular -->
                            <div class="md:col-span-2 flex items-center gap-[12px]">
                                <input type="hidden" name="es_titular" value="0">
                                <input type="checkbox" id="es_titular" name="es_titular" value="1"
                                    {{ old('es_titular') ? 'checked' : '' }}
                                    class="w-[18px] h-[18px] rounded border-gray-300 dark:border-[#172036] text-primary-500 focus:ring-primary-500 cursor-pointer">
                                <label for="es_titular" class="text-black dark:text-white font-medium cursor-pointer">
                                    Es titular del hogar (admin)
                                </label>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-[15px]">
                    <button type="submit"
                        class="font-medium inline-block transition-all rounded-md py-[10px] px-[20px] bg-primary-500 text-white hover:bg-primary-400">
                        Agregar Miembro
                    </button>
                    <a href="{{ route('dashboard.miembros.index', $hogarSeleccionado ? ['hogar' => $hogarSeleccionado->id] : []) }}"
                       class="font-medium inline-block transition-all rounded-md py-[10px] px-[20px] border border-gray-200 dark:border-[#172036] text-black dark:text-white hover:bg-gray-50 dark:hover:bg-[#15203c]">
                        Cancelar
                    </a>
                </div>

            </form>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')

        <script>
            // Al cambiar el hogar, recargar la página con ?hogar=UUID para cargar las personas disponibles
            document.getElementById('hogar_id_selector').addEventListener('change', function () {
                const val = this.value;
                if (val) {
                    window.location.href = '{{ route("dashboard.miembros.create") }}?hogar=' + val;
                }
            });
        </script>
    </body>
</html>
