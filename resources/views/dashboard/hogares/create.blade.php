<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Crear Hogar</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Crear Hogar</h5>
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
                        Crear
                    </li>
                </ol>
            </div>

            <form method="POST" action="{{ route('dashboard.hogares.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header mb-[20px] md:mb-[25px]">
                        <h5 class="!mb-0">Datos del Hogar</h5>
                    </div>
                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 gap-[20px]">

                            <!-- Nombre -->
                            <div>
                                <label class="block mb-[10px] text-black dark:text-white font-medium">
                                    Nombre del Hogar <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Casa Familia García" maxlength="100"
                                    class="h-[55px] rounded-md text-black dark:text-white border @error('nombre') border-danger-500 @else border-gray-200 dark:border-[#172036] @enderror bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('nombre')
                                    <p class="mt-[6px] text-danger-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Foto del hogar -->
                            <div>
                                <label class="block mb-[10px] text-black dark:text-white font-medium">
                                    Foto del Hogar <span class="text-gray-400 text-sm">(opcional)</span>
                                </label>
                                <input type="file" name="avatar" accept="image/*"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-[12px] file:py-[8px] file:px-[16px]
                                        file:rounded-md file:border-0 file:text-sm file:font-medium
                                        file:bg-primary-50 file:text-primary-600
                                        hover:file:bg-primary-100 dark:file:bg-[#15203c] dark:file:text-primary-400
                                        border border-gray-200 dark:border-[#172036] rounded-md py-[8px] cursor-pointer">
                                @error('avatar')
                                    <p class="mt-[6px] text-danger-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label class="block mb-[10px] text-black dark:text-white font-medium">
                                    Descripción
                                </label>
                                <textarea name="descripcion" rows="3" placeholder="Descripción opcional del hogar..."
                                    class="rounded-md text-black dark:text-white border @error('descripcion') border-danger-500 @else border-gray-200 dark:border-[#172036] @enderror bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <p class="mt-[6px] text-danger-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-[15px]">
                    <button type="submit" class="font-medium inline-block transition-all rounded-md py-[10px] px-[20px] bg-primary-500 text-white hover:bg-primary-400">
                        Guardar Hogar
                    </button>
                    <a href="{{ route('dashboard.hogares.index') }}" class="font-medium inline-block transition-all rounded-md py-[10px] px-[20px] border border-gray-200 dark:border-[#172036] text-black dark:text-white hover:bg-gray-50 dark:hover:bg-[#15203c]">
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
