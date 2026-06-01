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
        <title>Editar — {{ $documento->titulo }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0 truncate max-w-[60%]">Editar: {{ $documento->titulo }}</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.documentos-medicos.index') }}" class="transition-all hover:text-primary-500">Documentos</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <form method="POST" action="{{ route('dashboard.documentos-medicos.update', $documento) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                @if($errors->any())
                <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                    <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                        <i class="material-symbols-outlined !text-[20px] text-primary-500 mr-[8px]">edit_document</i>
                        <h5 class="!mb-0">Editar Documento</h5>
                    </div>

                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Título <span class="text-danger-500">*</span></label>
                                <input type="text" name="titulo" value="{{ old('titulo', $documento->titulo) }}" maxlength="200"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('titulo') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500">
                                @error('titulo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Miembro <span class="text-danger-500">*</span></label>
                                <select name="hogar_miembro_id" id="selMiembro" class="w-full">
                                    <option value="">Seleccionar miembro...</option>
                                    @foreach($miembros as $m)
                                        @php $lbl = trim(implode(' ', array_filter([$m->user?->persona?->nombres, $m->user?->persona?->apellido_paterno, $m->user?->persona?->apellido_materno]))) ?: ($m->user?->name ?? 'Sin nombre'); @endphp
                                        <option value="{{ $m->id }}" {{ old('hogar_miembro_id', $documento->hogar_miembro_id) == $m->id ? 'selected' : '' }}>{{ trim($lbl) }}</option>
                                    @endforeach
                                </select>
                                @error('hogar_miembro_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Tipo de Documento</label>
                                <select name="tipo_documento_medico_id" id="selTipo" class="w-full">
                                    <option value="">Sin tipo</option>
                                    @foreach($tiposDocumento as $td)
                                        <option value="{{ $td->id }}" data-icono="{{ $td->icono ?? 'description' }}"
                                            {{ old('tipo_documento_medico_id', $documento->tipo_documento_medico_id) == $td->id ? 'selected' : '' }}>
                                            {{ $td->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_documento_medico_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Consulta vinculada (carga via AJAX según miembro seleccionado) -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Consulta Médica <span class="text-xs text-gray-400 font-normal ml-[4px]">(opcional)</span></label>
                                <select name="consulta_medica_id" id="selConsulta" class="w-full" disabled>
                                    <option value="">Primero selecciona un miembro</option>
                                </select>
                                @error('consulta_medica_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha del Documento</label>
                                <input type="date" name="fecha_documento" value="{{ old('fecha_documento', $documento->fecha_documento?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_documento') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                                @error('fecha_documento')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Reemplazar Archivo
                                    <span class="text-xs text-gray-400 font-normal ml-[4px]">(dejar vacío para conservar el actual)</span>
                                </label>
                                @if($documento->archivo_path)
                                <div class="mb-[10px] flex items-center gap-[10px] p-[10px] rounded-md bg-gray-50 dark:bg-[#15203c] border border-gray-100 dark:border-[#172036]">
                                    <i class="material-symbols-outlined !text-[20px] text-primary-500">attach_file</i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $documento->archivo_nombre_original ?? basename($documento->archivo_path) }}</span>
                                    @if($documento->archivo_size)
                                        <span class="text-xs text-gray-400 flex-shrink-0">{{ number_format($documento->archivo_size / 1024, 0) }} KB</span>
                                    @endif
                                    <a href="{{ Storage::url($documento->archivo_path) }}" target="_blank" rel="noopener"
                                        class="ml-auto text-primary-500 hover:underline text-xs flex-shrink-0">Ver actual</a>
                                </div>
                                @endif
                                <div class="relative flex items-center justify-center overflow-hidden rounded-md py-[30px] px-[20px] border-2 border-dashed border-gray-200 dark:border-[#172036] hover:border-primary-400 transition-all">
                                    <div class="text-center">
                                        <i class="material-symbols-outlined !text-[32px] text-gray-300 dark:text-gray-600 block mb-[6px]">upload_file</i>
                                        <p class="text-sm text-gray-500 dark:text-gray-400"><strong class="text-black dark:text-white">Seleccionar nuevo archivo</strong></p>
                                        <p id="nombreArchivo" class="text-xs text-primary-500 mt-[4px] font-medium hidden"></p>
                                    </div>
                                    <input type="file" name="archivo" id="archivoInput" onchange="mostrarNombreArchivo(this)"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>
                                @error('archivo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Notas</label>
                                <textarea name="notas" rows="2"
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('notas', $documento->notas) }}</textarea>
                                @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                <div class="trezo-card mb-[25px] bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="flex justify-end gap-[15px]">
                        <a href="{{ route('dashboard.documentos-medicos.show', $documento) }}"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-danger-500 text-white hover:bg-danger-400">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-primary-500 text-white hover:bg-primary-400">
                            <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                Guardar Cambios
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
            const endpointBase = '{{ url('dashboard/consultas-medicas/por-miembro') }}';

            // Valores actuales del documento (o los del old() si hubo error de validación)
            const initMiembro  = '{{ old('hogar_miembro_id', $documento->hogar_miembro_id ?? '') }}';
            const initConsulta = '{{ old('consulta_medica_id', $documento->consulta_medica_id ?? '') }}';

            function mostrarNombreArchivo(input) {
                const el = document.getElementById('nombreArchivo');
                el.textContent = input.files?.[0]?.name ?? '';
                el.classList.toggle('hidden', !input.files?.[0]);
            }

            function iconOpt(option, defaultIcon) {
                if (!option.id) return option.text;
                const icono = $(option.element).data('icono') || defaultIcon;
                return $(`<span style="display:flex;align-items:center;gap:8px"><span class="material-symbols-outlined" style="font-size:16px;color:#4f88e4">${icono}</span>${option.text}</span>`);
            }

            function initSelConsulta(disabled) {
                try { $('#selConsulta').select2('destroy'); } catch(e) {}
                $('#selConsulta').select2({
                    placeholder : disabled ? 'Primero selecciona un miembro' : 'Sin consulta vinculada',
                    allowClear  : !disabled,
                    width       : '100%',
                    language    : { noResults: () => 'Sin consultas registradas para este miembro' },
                });
            }

            function cargarConsultas(miembroId, preselId) {
                const $sel = $('#selConsulta');
                try { $sel.select2('destroy'); } catch(e) {}

                if (!miembroId) {
                    $sel.prop('disabled', true).empty()
                        .append('<option value="">Primero selecciona un miembro</option>');
                    initSelConsulta(true);
                    return;
                }

                $sel.prop('disabled', true).empty()
                    .append('<option value="">Cargando consultas...</option>');
                initSelConsulta(true);

                $.getJSON(endpointBase + '/' + miembroId)
                    .done(function(data) {
                        $sel.empty().append('<option value="">Sin consulta vinculada</option>');
                        $.each(data, function(_, c) {
                            $sel.append(new Option(c.label, c.id, false, c.id === preselId));
                        });
                        $sel.prop('disabled', false);
                        initSelConsulta(false);
                        if (preselId) $sel.val(preselId).trigger('change');
                    })
                    .fail(function() {
                        $sel.empty().append('<option value="">Sin consulta vinculada</option>');
                        $sel.prop('disabled', false);
                        initSelConsulta(false);
                    });
            }

            $(document).ready(function() {
                $('#selMiembro').select2({ placeholder: 'Seleccionar miembro...', allowClear: true, width: '100%' });
                $('#selTipo').select2({
                    placeholder: 'Sin tipo', allowClear: true, width: '100%',
                    templateResult:     o => iconOpt(o, 'description'),
                    templateSelection:  o => iconOpt(o, 'description'),
                });

                // Consulta: inicia deshabilitada
                initSelConsulta(true);

                // Pre-cargar consultas del miembro actual del documento
                if (initMiembro) {
                    $('#selMiembro').val(initMiembro).trigger('change');
                    cargarConsultas(initMiembro, initConsulta);
                }

                // Reaccionar solo a interacción del usuario
                $('#selMiembro').on('select2:select select2:unselect', function() {
                    cargarConsultas($(this).val() || null, null);
                });
            });
        </script>
    </body>
</html>
