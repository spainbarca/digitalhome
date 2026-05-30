<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Nuevo Sector</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nuevo Sector</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.sectores.index') }}" class="transition-all hover:text-primary-500">Sectores</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nuevo</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[25px] flex items-center justify-between -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">Registrar Sector</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.sectores.store') }}">
                        @csrf

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                            <!-- Nombre -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Nombre <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" maxlength="120"
                                    placeholder="Ej: Tecnología de la Información"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('nombre') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('nombre')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Ícono Remixicon -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Ícono
                                    <span class="text-xs text-gray-400 font-normal ml-[4px]">(clase Remixicon, ej: ri-cpu-line)</span>
                                </label>
                                <div class="relative flex items-center gap-[10px]">
                                    <div class="w-[55px] h-[55px] flex-shrink-0 rounded-md border border-gray-200 dark:border-[#172036] bg-gray-50 dark:bg-[#15203c] flex items-center justify-center">
                                        <i id="iconPreview" class="{{ old('icono', 'ri-building-line') }} text-[24px] text-primary-500"></i>
                                    </div>
                                    <input type="text" name="icono" id="iconoInput" value="{{ old('icono') }}" maxlength="100"
                                        placeholder="ri-building-line"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('icono') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                </div>
                                @error('icono')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Descripción -->
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Descripción</label>
                                <textarea name="descripcion" rows="3" maxlength="300"
                                    placeholder="Breve descripción del sector económico..."
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('descripcion') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[12px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('descripcion') }}</textarea>
                                @error('descripcion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Activo -->
                            <div class="flex items-center gap-[12px] pt-[6px]">
                                <span class="text-black dark:text-white font-medium">Activo</span>
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="activo" value="1" id="activo"
                                        class="sr-only peer" {{ old('activo', '1') ? 'checked' : '' }}>
                                    <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                </label>
                            </div>

                        </div>

                        <!-- Botones -->
                        <div class="mt-[25px] ltr:text-right rtl:text-left flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.sectores.index') }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Registrar Sector
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')

        <script>
            document.getElementById('iconoInput').addEventListener('input', function () {
                const val = this.value.trim() || 'ri-building-line';
                const preview = document.getElementById('iconPreview');
                preview.className = `${val} text-[24px] text-primary-500`;
            });
        </script>
    </body>
</html>
