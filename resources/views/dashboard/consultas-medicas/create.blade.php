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
        <title>Nueva Consulta Médica</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nueva Consulta Médica</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.consultas-medicas.index') }}" class="transition-all hover:text-primary-500">Consultas</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nueva</li>
                </ol>
            </div>

            <form method="POST" action="{{ route('dashboard.consultas-medicas.store') }}">
                @csrf

                @if($errors->any())
                <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                    <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <!-- ── SECCIÓN 1: Miembro + Fecha ────────────────────── -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="font-semibold text-black dark:text-white mb-[20px] pb-[12px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">person</i>
                        1. Miembro y Fecha
                    </h6>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px] md:gap-[25px]">
                        <div class="sm:col-span-1">
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Miembro <span class="text-danger-500">*</span></label>
                            <select name="hogar_miembro_id" id="selMiembro" class="w-full">
                                <option value="">Seleccionar miembro...</option>
                                @foreach($miembros as $m)
                                    @php $label = trim(implode(' ', array_filter([$m->user?->persona?->nombres, $m->user?->persona?->apellido_paterno, $m->user?->persona?->apellido_materno]))) ?: ($m->user?->name ?? 'Sin nombre'); @endphp
                                    <option value="{{ $m->id }}" {{ old('hogar_miembro_id') == $m->id ? 'selected' : '' }}>{{ trim($label) }}</option>
                                @endforeach
                            </select>
                            @error('hogar_miembro_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha <span class="text-danger-500">*</span></label>
                            <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('fecha')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Hora</label>
                            <input type="time" name="hora" value="{{ old('hora') }}"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('hora') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('hora')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- ── SECCIÓN 2: Médico / Centro / Especialidad ──────── -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="font-semibold text-black dark:text-white mb-[20px] pb-[12px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">medical_services</i>
                        2. Médico, Centro y Especialidad
                    </h6>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-[20px] md:gap-[25px]">
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Médico</label>
                            <select name="medico_id" id="selMedico" class="w-full">
                                <option value="">Sin médico asignado</option>
                                @foreach($medicos as $med)
                                    <option value="{{ $med->id }}" data-esp="{{ $med->especialidadMedica?->nombre ?? '' }}"
                                        {{ old('medico_id') == $med->id ? 'selected' : '' }}>
                                        {{ $med->apellidos }}, {{ $med->nombres }}{{ $med->cmp ? ' (CMP: '.$med->cmp.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('medico_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Centro Médico</label>
                            <select name="centro_medico_id" id="selCentro" class="w-full">
                                <option value="">Sin centro asignado</option>
                                @foreach($centrosMedicos as $cm)
                                    @php $nombreCentro = $cm->nombre_referencial ?? $cm->empresa?->razon_social ?? '(Sin nombre)'; @endphp
                                    <option value="{{ $cm->id }}" {{ old('centro_medico_id') == $cm->id ? 'selected' : '' }}>
                                        {{ $nombreCentro }}
                                    </option>
                                @endforeach
                            </select>
                            @error('centro_medico_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Especialidad</label>
                            <select name="especialidad_medica_id" id="selEspecialidad" class="w-full">
                                <option value="">Sin especialidad</option>
                                @foreach($especialidades as $esp)
                                    <option value="{{ $esp->id }}" data-icono="{{ $esp->icono ?? 'stethoscope' }}"
                                        {{ old('especialidad_medica_id') == $esp->id ? 'selected' : '' }}>
                                        {{ $esp->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('especialidad_medica_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- ── SECCIÓN 3: Motivo / Diagnóstico / Tratamiento ─── -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="font-semibold text-black dark:text-white mb-[20px] pb-[12px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">clinical_notes</i>
                        3. Información Clínica
                    </h6>
                    <div class="grid grid-cols-1 gap-[20px] md:gap-[25px]">
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Motivo de Consulta</label>
                            <input type="text" name="motivo" value="{{ old('motivo') }}" maxlength="255"
                                placeholder="Ej: Dolor de cabeza persistente"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('motivo') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500">
                            @error('motivo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Diagnóstico</label>
                                <textarea name="diagnostico" rows="4" placeholder="Diagnóstico médico..."
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('diagnostico') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('diagnostico') }}</textarea>
                                @error('diagnostico')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Tratamiento Indicado</label>
                                <textarea name="tratamiento_indicado" rows="4" placeholder="Tratamiento indicado por el médico..."
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('tratamiento_indicado') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('tratamiento_indicado') }}</textarea>
                                @error('tratamiento_indicado')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Notas adicionales</label>
                            <textarea name="notas" rows="2" placeholder="Observaciones..."
                                class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('notas') }}</textarea>
                            @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- ── SECCIÓN 4: Costo ───────────────────────────────── -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="font-semibold text-black dark:text-white mb-[20px] pb-[12px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">payments</i>
                        4. Costo (Opcional)
                    </h6>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] max-w-lg">
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Moneda</label>
                            <select name="moneda_id" id="selMoneda" class="w-full">
                                <option value="">Sin moneda</option>
                                @foreach($monedas as $mon)
                                    <option value="{{ $mon->id }}" {{ old('moneda_id') == $mon->id ? 'selected' : '' }}>
                                        {{ $mon->simbolo }} {{ $mon->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('moneda_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Costo</label>
                            <input type="number" name="costo" value="{{ old('costo') }}" min="0" step="0.01"
                                placeholder="0.00"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('costo') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500">
                            @error('costo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="trezo-card mb-[25px] bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="flex justify-end gap-[15px]">
                        <a href="{{ route('dashboard.consultas-medicas.index') }}"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-danger-500 text-white hover:bg-danger-400">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-primary-500 text-white hover:bg-primary-400">
                            <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                Registrar Consulta
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
            function iconOpt(option, defaultIcon) {
                if (!option.id) return option.text;
                const icono = $(option.element).data('icono') || defaultIcon;
                return $(`<span style="display:flex;align-items:center;gap:8px"><span class="material-symbols-outlined" style="font-size:16px;color:#4f88e4">${icono}</span>${option.text}</span>`);
            }
            $(document).ready(function() {
                $('#selMiembro').select2({ placeholder: 'Seleccionar miembro...', allowClear: true, width: '100%' });
                $('#selMedico').select2({ placeholder: 'Sin médico', allowClear: true, width: '100%' });
                $('#selCentro').select2({ placeholder: 'Sin centro', allowClear: true, width: '100%' });
                $('#selEspecialidad').select2({
                    placeholder: 'Sin especialidad', allowClear: true, width: '100%',
                    templateResult: o => iconOpt(o, 'stethoscope'),
                    templateSelection: o => iconOpt(o, 'stethoscope'),
                });
                $('#selMoneda').select2({ placeholder: 'Sin moneda', allowClear: true, width: '100%' });
            });
        </script>
    </body>
</html>
