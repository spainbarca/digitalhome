<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <style>
            .select2-container--default .select2-selection--single { height:55px;border-radius:6px;border-color:#e5e7eb;display:flex;align-items:center;padding:0 14px; }
            .select2-container--default .select2-selection--single .select2-selection__rendered { line-height:normal;padding:0;color:inherit;display:flex;align-items:center;gap:8px; }
            .select2-container--default .select2-selection--single .select2-selection__arrow { height:55px;top:0; }
            .select2-dropdown { border-color:#e5e7eb;border-radius:6px; }
            .select2-search--dropdown .select2-search__field { border-radius:4px;border-color:#e5e7eb;outline:none; }
            .select2-results__option { display:flex;align-items:center;gap:8px;padding:9px 12px; }
            .select2-container--default .select2-results__option--highlighted { background-color:#f0f6ff;color:#4f88e4; }
            .dark .select2-container--default .select2-selection--single { background-color:#0c1427;border-color:#172036; }
            .dark .select2-dropdown { background-color:#0c1427;border-color:#172036; }
            .dark .select2-results__option { color:#fff; }
            .dark .select2-container--default .select2-results__option--highlighted { background-color:#15203c; }
        </style>
        <title>Subir Documento Educativo</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Subir Documento Educativo</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.documentos-educativos.index') }}" class="transition-all hover:text-primary-500">Documentos Educativos</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nuevo</li>
                </ol>
            </div>

            <form method="POST" action="{{ route('dashboard.documentos-educativos.store') }}" enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                    <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                        <i class="material-symbols-outlined !text-[20px] text-primary-500 mr-[8px]">upload_file</i>
                        <h5 class="!mb-0">Datos del Documento</h5>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                        {{-- Título --}}
                        <div class="sm:col-span-2">
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Título <span class="text-danger-500">*</span></label>
                            <input type="text" name="titulo" value="{{ old('titulo') }}" maxlength="200"
                                placeholder="Ej: Libreta de notas 2026-I"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('titulo') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500">
                            @error('titulo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Miembro --}}
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Miembro <span class="text-danger-500">*</span></label>
                            <select name="hogar_miembro_id" id="selMiembro" class="w-full">
                                <option value="">Seleccionar miembro...</option>
                                @foreach($miembros as $m)
                                    @php $lbl = trim(implode(' ', array_filter([$m->user?->persona?->nombres, $m->user?->persona?->apellido_paterno, $m->user?->persona?->apellido_materno]))) ?: ($m->user?->name ?? 'Sin nombre'); @endphp
                                    <option value="{{ $m->id }}" {{ old('hogar_miembro_id', $miembroPresel) == $m->id ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error('hogar_miembro_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Tipo de documento --}}
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Tipo de Documento</label>
                            <select name="tipo_documento_educativo_id" id="selTipo" class="w-full">
                                <option value="">Sin tipo</option>
                                @foreach($tiposDocumento as $td)
                                    <option value="{{ $td->id }}" data-icono="{{ $td->icono ?? 'description' }}"
                                        {{ old('tipo_documento_educativo_id') == $td->id ? 'selected' : '' }}>
                                        {{ $td->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_documento_educativo_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Matrícula vinculada (subordinado al miembro) --}}
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">
                                Matrícula
                                <span class="text-xs text-gray-400 font-normal ml-[4px]">(opcional)</span>
                            </label>
                            <select name="matricula_id" id="selMatricula" class="w-full" disabled>
                                <option value="">Primero selecciona un miembro</option>
                            </select>
                            @error('matricula_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Fecha documento --}}
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha del Documento</label>
                            <input type="date" name="fecha_documento" value="{{ old('fecha_documento') }}"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_documento') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('fecha_documento')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Archivo --}}
                        <div class="sm:col-span-2">
                            <label class="mb-[10px] text-black dark:text-white font-medium block">
                                Archivo
                                <span class="text-xs text-gray-400 font-normal ml-[4px]">(imágenes, PDF, Word, etc. — máx. 10 MB)</span>
                            </label>
                            <div class="relative flex items-center justify-center overflow-hidden rounded-md py-[40px] px-[20px] border-2 border-dashed border-gray-200 dark:border-[#172036] hover:border-primary-400 transition-all">
                                <div class="text-center">
                                    <i class="material-symbols-outlined !text-[40px] text-gray-300 dark:text-gray-600 block mb-[8px]">upload_file</i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <strong class="text-black dark:text-white">Haz clic para seleccionar</strong> o arrastra aquí
                                    </p>
                                    <p id="nombreArchivo" class="text-xs text-primary-500 mt-[6px] font-medium hidden"></p>
                                </div>
                                <input type="file" name="archivo" id="archivoInput"
                                    onchange="mostrarNombreArchivo(this)"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            @error('archivo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Notas --}}
                        <div class="sm:col-span-2">
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Notas</label>
                            <textarea name="notas" rows="2" placeholder="Observaciones..."
                                class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('notas') }}</textarea>
                            @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                <div class="trezo-card mb-[25px] bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="flex justify-end gap-[15px]">
                        <a href="{{ route('dashboard.documentos-educativos.index') }}"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-danger-500 text-white hover:bg-danger-400">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-primary-500 text-white hover:bg-primary-400">
                            <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">upload_file</i>
                                Subir Documento
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
            const endpointBase  = '{{ url('dashboard/documentos-educativos/por-miembro') }}';
            const initMiembro   = '{{ old('hogar_miembro_id', $miembroPresel) }}';
            const initMatricula = '{{ $matriculaPresel?->id ?? old('matricula_id') ?? '' }}';

            function mostrarNombreArchivo(input) {
                const el = document.getElementById('nombreArchivo');
                if (input.files && input.files[0]) {
                    el.textContent = input.files[0].name;
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            }

            function iconOpt(option, defaultIcon) {
                if (!option.id) return option.text;
                const icono = $(option.element).data('icono') || defaultIcon;
                return $(`<span style="display:flex;align-items:center;gap:8px"><span class="material-symbols-outlined" style="font-size:16px;color:#4f88e4">${icono}</span>${option.text}</span>`);
            }

            function initSelMatricula(disabled) {
                try { $('#selMatricula').select2('destroy'); } catch(e) {}
                $('#selMatricula').select2({
                    placeholder : disabled ? 'Primero selecciona un miembro' : 'Sin matrícula vinculada',
                    allowClear  : !disabled,
                    width       : '100%',
                    language    : { noResults: () => 'Sin matrículas registradas para este miembro' },
                });
            }

            function cargarMatriculas(miembroId, preselId) {
                const $sel = $('#selMatricula');
                try { $sel.select2('destroy'); } catch(e) {}

                if (!miembroId) {
                    $sel.prop('disabled', true).empty()
                        .append('<option value="">Primero selecciona un miembro</option>');
                    initSelMatricula(true);
                    return;
                }

                $sel.prop('disabled', true).empty()
                    .append('<option value="">Cargando matrículas...</option>');
                initSelMatricula(true);

                $.getJSON(endpointBase + '/' + miembroId)
                    .done(function(data) {
                        $sel.empty().append('<option value="">Sin matrícula vinculada</option>');
                        $.each(data, function(_, m) {
                            $sel.append(new Option(m.label, m.id, false, m.id === preselId));
                        });
                        $sel.prop('disabled', false);
                        initSelMatricula(false);
                        if (preselId) $sel.val(preselId).trigger('change');
                    })
                    .fail(function() {
                        $sel.empty().append('<option value="">Sin matrícula vinculada</option>');
                        $sel.prop('disabled', false);
                        initSelMatricula(false);
                    });
            }

            $(document).ready(function() {
                $('#selMiembro').select2({ placeholder: 'Seleccionar miembro...', allowClear: true, width: '100%' });
                $('#selTipo').select2({
                    placeholder: 'Sin tipo', allowClear: true, width: '100%',
                    templateResult:    o => iconOpt(o, 'description'),
                    templateSelection: o => iconOpt(o, 'description'),
                });

                initSelMatricula(true);

                if (initMiembro) {
                    $('#selMiembro').val(initMiembro).trigger('change');
                    cargarMatriculas(initMiembro, initMatricula);
                }

                $('#selMiembro').on('select2:select select2:unselect', function() {
                    cargarMatriculas($(this).val() || null, null);
                });
            });
        </script>
    </body>
</html>
