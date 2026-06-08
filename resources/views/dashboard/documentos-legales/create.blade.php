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
            .dark .select2-container--default .select2-selection--single { background-color:#0c1427;border-color:#172036;color:#fff; }
            .dark .select2-dropdown { background-color:#0c1427;border-color:#172036; }
            .dark .select2-results__option { color:#fff; }
            .dark .select2-container--default .select2-results__option--highlighted { background-color:#15203c; }
        </style>
        <title>Nuevo Documento Legal</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nuevo Documento Legal</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.documentos-legales.index') }}" class="transition-all hover:text-primary-500">Documentos</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nuevo</li>
                </ol>
            </div>

            <form method="POST" action="{{ route('dashboard.documentos-legales.store') }}" enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                    <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                {{-- ── SECCIÓN 1: Clasificación ─────────────────────────────── --}}
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] flex items-center gap-[8px] -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                        <i class="material-symbols-outlined !text-[20px] text-primary-500">gavel</i>
                        <h5 class="!mb-0">1. Clasificación</h5>
                    </div>
                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                            <!-- Tipo de documento -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Tipo de Documento <span class="text-danger-500">*</span>
                                </label>
                                <select name="tipo_documento_legal_id" id="selTipo" class="w-full">
                                    <option value="">Seleccionar tipo...</option>
                                    @foreach($tipos as $t)
                                        @php $cat = ['personal'=>'Personal','propiedad'=>'Propiedad','seguro'=>'Seguro','contrato'=>'Contrato','denuncia'=>'Denuncia','otro'=>'Otro'][$t->categoria ?? ''] ?? $t->categoria; @endphp
                                        <option value="{{ $t->id }}"
                                            data-icono="{{ $t->icono ?? 'description' }}"
                                            data-categoria="{{ $cat }}"
                                            data-requiere="{{ $t->requiere_vencimiento ? '1' : '0' }}"
                                            {{ old('tipo_documento_legal_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->nombre }} — {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_documento_legal_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Estado -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Estado <span class="text-danger-500">*</span>
                                </label>
                                <select name="estado_documento_legal_id" id="selEstado" class="w-full">
                                    <option value="">Seleccionar estado...</option>
                                    @foreach($estados as $e)
                                        <option value="{{ $e->id }}" data-color="{{ $e->color }}"
                                            {{ old('estado_documento_legal_id') == $e->id ? 'selected' : '' }}>
                                            {{ $e->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado_documento_legal_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── SECCIÓN 2: Datos del Documento ──────────────────────── --}}
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] flex items-center gap-[8px] -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                        <i class="material-symbols-outlined !text-[20px] text-primary-500">article</i>
                        <h5 class="!mb-0">2. Datos del Documento</h5>
                    </div>
                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                            <!-- Título -->
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Título <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="titulo" value="{{ old('titulo') }}" maxlength="255"
                                    placeholder="Ej: Contrato de arrendamiento"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('titulo') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500 transition-all">
                                @error('titulo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Número de documento -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Número de Documento</label>
                                <input type="text" name="numero_documento" value="{{ old('numero_documento') }}" maxlength="100"
                                    placeholder="Ej: 2024-001-A"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('numero_documento') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500 transition-all">
                                @error('numero_documento')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Fecha de emisión -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Emisión</label>
                                <input type="date" name="fecha_emision" value="{{ old('fecha_emision') }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_emision') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500 transition-all">
                                @error('fecha_emision')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Fecha de vencimiento (condicional) -->
                            <div id="bloqueVencimiento" class="{{ old('tipo_documento_legal_id') && $tipos->find(old('tipo_documento_legal_id'))?->requiere_vencimiento ? '' : 'hidden' }}">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Fecha de Vencimiento
                                    <span class="text-danger-500">*</span>
                                </label>
                                <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_vencimiento') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500 transition-all">
                                @error('fecha_vencimiento')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── SECCIÓN 3: Vinculación ───────────────────────────────── --}}
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] flex items-center gap-[8px] -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                        <i class="material-symbols-outlined !text-[20px] text-primary-500">link</i>
                        <h5 class="!mb-0">3. Vinculación</h5>
                        <span class="text-xs text-gray-400">Opcional — miembro, propiedad o entidad emisora</span>
                    </div>
                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px] md:gap-[25px]">

                            <!-- Miembro -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Miembro del Hogar</label>
                                <select name="hogar_miembro_id" id="selMiembro" class="w-full">
                                    <option value="">Sin miembro</option>
                                    @foreach($miembros as $m)
                                        @php $lbl = trim(implode(' ', array_filter([$m->user?->persona?->nombres, $m->user?->persona?->apellido_paterno, $m->user?->persona?->apellido_materno]))) ?: ($m->user?->name ?? 'Sin nombre'); @endphp
                                        <option value="{{ $m->id }}" {{ old('hogar_miembro_id') == $m->id ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                                @error('hogar_miembro_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Propiedad -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Propiedad</label>
                                <select name="propiedad_inmueble_id" id="selPropiedad" class="w-full">
                                    <option value="">Sin propiedad</option>
                                    @foreach($propiedades as $prop)
                                        <option value="{{ $prop->id }}" {{ old('propiedad_inmueble_id') == $prop->id ? 'selected' : '' }}>
                                            {{ $prop->alias }}{{ $prop->direccion ? ' — ' . $prop->direccion : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('propiedad_inmueble_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Entidad legal -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Entidad Emisora</label>
                                <select name="entidad_legal_id" id="selEntidad" class="w-full">
                                    <option value="">Sin entidad</option>
                                    @foreach($entidades as $ent)
                                        <option value="{{ $ent->id }}" {{ old('entidad_legal_id') == $ent->id ? 'selected' : '' }}>
                                            {{ $ent->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('entidad_legal_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── SECCIÓN 4: Archivo y extras ─────────────────────────── --}}
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] flex items-center gap-[8px] -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                        <i class="material-symbols-outlined !text-[20px] text-primary-500">upload_file</i>
                        <h5 class="!mb-0">4. Archivo y Extras</h5>
                    </div>
                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                            <!-- Archivo -->
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Archivo
                                    <span class="text-xs text-gray-400 font-normal ml-[4px]">(PDF, JPG, PNG — máx. 10 MB)</span>
                                </label>
                                <div class="relative flex items-center justify-center overflow-hidden rounded-md py-[40px] px-[20px] border-2 border-dashed border-gray-200 dark:border-[#172036] hover:border-primary-400 transition-all" id="dropZone">
                                    <div class="text-center">
                                        <i class="material-symbols-outlined !text-[40px] text-gray-300 dark:text-gray-600 block mb-[8px]">upload_file</i>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <strong class="text-black dark:text-white">Haz clic para seleccionar</strong> o arrastra aquí
                                        </p>
                                        <p id="nombreArchivo" class="text-xs text-primary-500 mt-[6px] font-medium hidden"></p>
                                    </div>
                                    <input type="file" name="archivo" id="archivoInput"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        onchange="mostrarNombreArchivo(this)"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>
                                @error('archivo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Notas -->
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Notas</label>
                                <textarea name="notas" rows="3" maxlength="2000"
                                    placeholder="Observaciones, detalles adicionales..."
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500 resize-none transition-all">{{ old('notas') }}</textarea>
                                @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Activo -->
                            <div class="flex items-center gap-[12px]">
                                <span class="text-black dark:text-white font-medium">Activo</span>
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="activo" value="1" class="sr-only peer" {{ old('activo', '1') ? 'checked' : '' }}>
                                    <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="trezo-card mb-[25px] bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="flex justify-end gap-[15px]">
                        <a href="{{ route('dashboard.documentos-legales.index') }}"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-danger-500 text-white hover:bg-danger-400">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-primary-500 text-white hover:bg-primary-400">
                            <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                Registrar Documento
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
            function templateTipo(option) {
                if (!option.id) return $('<span>' + option.text + '</span>');
                const icono = $(option.element).data('icono') || 'description';
                const cat   = $(option.element).data('categoria') || '';
                return $('<span class="flex items-center gap-[8px]"><span class="material-symbols-outlined" style="font-size:16px;color:#4f88e4">' + icono + '</span><span>' + option.text + '</span></span>');
            }

            function templateEstado(option) {
                if (!option.id) return $('<span>' + option.text + '</span>');
                const color = $(option.element).data('color') || 'gray';
                const dot = { green:'#22c55e', orange:'#f97316', red:'#ef4444', blue:'#4f88e4', gray:'#9ca3af' }[color] || '#9ca3af';
                return $('<span class="flex items-center gap-[8px]"><span style="width:8px;height:8px;border-radius:50%;background:' + dot + ';display:inline-block;flex-shrink:0"></span><span>' + option.text + '</span></span>');
            }

            $(document).ready(function () {
                $('#selTipo').select2({
                    placeholder: 'Seleccionar tipo...',
                    width: '100%',
                    templateResult:   templateTipo,
                    templateSelection: templateTipo,
                    language: { noResults: () => 'Sin resultados', searching: () => 'Buscando...' },
                });

                $('#selEstado').select2({
                    placeholder: 'Seleccionar estado...',
                    width: '100%',
                    templateResult:   templateEstado,
                    templateSelection: templateEstado,
                    language: { noResults: () => 'Sin resultados', searching: () => 'Buscando...' },
                });

                $('#selMiembro').select2({
                    placeholder: 'Sin miembro',
                    allowClear: true,
                    width: '100%',
                    language: { noResults: () => 'Sin resultados', searching: () => 'Buscando...' },
                });

                $('#selPropiedad').select2({
                    placeholder: 'Sin propiedad',
                    allowClear: true,
                    width: '100%',
                    language: { noResults: () => 'Sin resultados', searching: () => 'Buscando...' },
                });

                $('#selEntidad').select2({
                    placeholder: 'Sin entidad',
                    allowClear: true,
                    width: '100%',
                    language: { noResults: () => 'Sin resultados', searching: () => 'Buscando...' },
                });

                // Mostrar/ocultar fecha_vencimiento según requiere_vencimiento
                $('#selTipo').on('change', function () {
                    const opt = $(this).find(':selected');
                    const requiere = opt.data('requiere') === 1 || opt.data('requiere') === '1';
                    const bloque = document.getElementById('bloqueVencimiento');
                    bloque.classList.toggle('hidden', !requiere);
                    if (!requiere) {
                        document.querySelector('[name="fecha_vencimiento"]').value = '';
                    }
                });
            });

            function mostrarNombreArchivo(input) {
                const p = document.getElementById('nombreArchivo');
                if (input.files && input.files[0]) {
                    p.textContent = input.files[0].name;
                    p.classList.remove('hidden');
                } else {
                    p.classList.add('hidden');
                }
            }
        </script>
    </body>
</html>
