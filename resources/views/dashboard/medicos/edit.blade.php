<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <style>
            .select2-container--default .select2-selection--single {
                height: 55px; border-radius: 6px; border-color: #e5e7eb;
                display: flex; align-items: center; padding: 0 14px;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: normal; padding: 0; color: inherit; display: flex; align-items: center; gap: 8px;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 55px; top: 0;
            }
            .select2-dropdown { border-color: #e5e7eb; border-radius: 6px; }
            .select2-search--dropdown .select2-search__field { border-radius: 4px; border-color: #e5e7eb; outline: none; }
            .select2-results__option { display: flex; align-items: center; gap: 8px; padding: 9px 12px; }
            .select2-container--default .select2-results__option--highlighted { background-color: #f0f6ff; color: #4f88e4; }
            .dark .select2-container--default .select2-selection--single { background-color: #0c1427; border-color: #172036; }
            .dark .select2-dropdown { background-color: #0c1427; border-color: #172036; }
            .dark .select2-results__option { color: #fff; }
            .dark .select2-container--default .select2-results__option--highlighted { background-color: #15203c; }
        </style>
        @php $nombreCompleto = trim($medico->apellidos . ', ' . $medico->nombres); @endphp
        <title>Editar — {{ $nombreCompleto }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Editar Médico</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.medicos.index') }}" class="transition-all hover:text-primary-500">Médicos</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.medicos.show', $medico) }}" class="transition-all hover:text-primary-500">{{ $nombreCompleto }}</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <form method="POST" action="{{ route('dashboard.medicos.update', $medico) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($errors->any())
                <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                    <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                            <!-- Nombres -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Nombres <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="nombres" value="{{ old('nombres', $medico->nombres) }}" maxlength="100"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('nombres') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('nombres')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Apellidos -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Apellidos <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="apellidos" value="{{ old('apellidos', $medico->apellidos) }}" maxlength="100"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('apellidos') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('apellidos')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- CMP -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    CMP
                                    <span class="text-xs text-gray-400 font-normal ml-[4px]">(Colegio Médico del Perú)</span>
                                </label>
                                <input type="text" name="cmp" value="{{ old('cmp', $medico->cmp) }}" maxlength="20"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('cmp') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('cmp')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Especialidad -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Especialidad</label>
                                <select name="especialidad_medica_id" id="selectEspecialidad"
                                    class="w-full {{ $errors->has('especialidad_medica_id') ? 'border-danger-500' : '' }}">
                                    <option value="">Sin especialidad asignada</option>
                                    @foreach($especialidades as $esp)
                                        <option value="{{ $esp->id }}"
                                            data-icono="{{ $esp->icono ?? 'stethoscope' }}"
                                            {{ old('especialidad_medica_id', $medico->especialidad_medica_id) == $esp->id ? 'selected' : '' }}>
                                            {{ $esp->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('especialidad_medica_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Correo Electrónico</label>
                                <input type="email" name="email" value="{{ old('email', $medico->email) }}" maxlength="150"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('email') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('email')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono', $medico->telefono) }}" maxlength="20"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('telefono') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('telefono')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Notas -->
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Notas</label>
                                <textarea name="notas" rows="3" maxlength="2000"
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('notas', $medico->notas) }}</textarea>
                                @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Foto -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Foto <span class="text-xs text-gray-400 font-normal">(PNG/JPG, máx. 2 MB)</span>
                                </label>
                                @if($medico->imagen_path)
                                <div class="mb-[10px] flex items-center gap-[10px]">
                                    <img src="{{ Storage::url($medico->imagen_path) }}" id="imagenPreview"
                                        class="w-[80px] h-[80px] rounded-full object-cover" alt="Foto actual">
                                    <span class="text-xs text-gray-400">Foto actual. Sube una nueva para reemplazarla.</span>
                                </div>
                                @else
                                <div id="imagenPreviewWrap" class="hidden mb-[10px]">
                                    <img id="imagenPreview" src="" alt="Preview" class="w-[80px] h-[80px] rounded-full object-cover">
                                </div>
                                @endif
                                <input type="file" name="imagen" id="imagenInput" accept="image/*"
                                    onchange="previewImagen(this)"
                                    class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                @error('imagen')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Activo -->
                            <div class="flex items-center gap-[12px] pt-[10px] sm:pt-[30px]">
                                <span class="text-black dark:text-white font-medium">Activo</span>
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="activo" value="1" class="sr-only peer" {{ old('activo', $medico->activo) ? 'checked' : '' }}>
                                    <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="trezo-card mb-[25px] bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-content flex justify-end gap-[15px]">
                        <a href="{{ route('dashboard.medicos.show', $medico) }}"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-danger-500 text-white hover:bg-danger-400">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-primary-500 text-white hover:bg-primary-400">
                            <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                Actualizar Médico
                            </span>
                        </button>
                    </div>
                </div>
            </form>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function previewImagen(input) {
                const preview = document.getElementById('imagenPreview');
                const wrap    = document.getElementById('imagenPreviewWrap');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        if (preview) preview.src = e.target.result;
                        if (wrap)    wrap.classList.remove('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function formatEspecialidad(option) {
                if (!option.id) return option.text;
                const icono = $(option.element).data('icono') || 'stethoscope';
                return $(`<span style="display:flex;align-items:center;gap:8px"><span class="material-symbols-outlined" style="font-size:16px;color:#4f88e4">${icono}</span>${option.text}</span>`);
            }

            $(document).ready(function () {
                $('#selectEspecialidad').select2({
                    placeholder: 'Sin especialidad asignada',
                    allowClear: true,
                    templateResult: formatEspecialidad,
                    templateSelection: formatEspecialidad,
                    width: '100%',
                });
            });
        </script>
    </body>
</html>
