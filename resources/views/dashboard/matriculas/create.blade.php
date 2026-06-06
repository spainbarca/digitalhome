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
        <title>Nueva Matrícula</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nueva Matrícula</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.matriculas.index') }}" class="transition-all hover:text-primary-500">Matrículas</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nueva</li>
                </ol>
            </div>

            <form method="POST" action="{{ route('dashboard.matriculas.store') }}">
                @csrf

                @if($errors->any())
                <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                    <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                {{-- SECCIÓN 1: ¿Quién y dónde? --}}
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[20px] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="font-semibold text-black dark:text-white mb-[18px] pb-[12px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">person_pin</i>
                        1. ¿Quién y dónde?
                    </h6>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Miembro del hogar <span class="text-danger-500">*</span></label>
                            <select name="hogar_miembro_id" id="selMiembro" class="w-full">
                                <option value="">Seleccionar miembro...</option>
                                @foreach($miembros as $m)
                                    @php
                                        $nombreM = trim(implode(' ', array_filter([$m->user?->persona?->nombres, $m->user?->persona?->apellido_paterno, $m->user?->persona?->apellido_materno]))) ?: ($m->user?->name ?? 'Sin nombre');
                                        $avatarM = $m->user?->persona?->avatar_url ?? null;
                                    @endphp
                                    <option value="{{ $m->id }}" data-avatar="{{ $avatarM }}"
                                        {{ old('hogar_miembro_id', $preselMiembro) == $m->id ? 'selected' : '' }}>{{ $nombreM }}</option>
                                @endforeach
                            </select>
                            @error('hogar_miembro_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Institución educativa</label>
                            <select name="institucion_educativa_id" id="selInstitucion" class="w-full">
                                <option value="">Sin institución</option>
                                @foreach($instituciones as $inst)
                                    @php $imgInst = $inst->logo_path ? asset('storage/' . $inst->logo_path) : null; @endphp
                                    <option value="{{ $inst->id }}"
                                        data-img="{{ $imgInst }}"
                                        data-icono="{{ $inst->tipoInstitucionEducativa?->icono ?? 'school' }}"
                                        {{ old('institucion_educativa_id', $preselInst) == $inst->id ? 'selected' : '' }}>
                                        {{ $inst->nombre_referencial ?? '(Sin nombre)' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('institucion_educativa_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 2: Detalles académicos --}}
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[20px] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="font-semibold text-black dark:text-white mb-[18px] pb-[12px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">school</i>
                        2. Detalles académicos
                    </h6>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[20px] md:gap-[25px]">
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Nivel educativo</label>
                            <select name="nivel_educativo_id" id="selNivel" class="w-full">
                                <option value="">Sin nivel</option>
                                @foreach($niveles as $niv)
                                    <option value="{{ $niv->id }}" data-icono="{{ $niv->icono ?? 'layers' }}"
                                        {{ old('nivel_educativo_id') == $niv->id ? 'selected' : '' }}>{{ $niv->nombre }}</option>
                                @endforeach
                            </select>
                            @error('nivel_educativo_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Turno</label>
                            <select name="turno_educativo_id" id="selTurno" class="w-full">
                                <option value="">Sin turno</option>
                                @foreach($turnos as $turno)
                                    <option value="{{ $turno->id }}" data-icono="{{ $turno->icono ?? 'schedule' }}"
                                        {{ old('turno_educativo_id') == $turno->id ? 'selected' : '' }}>{{ $turno->nombre }}</option>
                                @endforeach
                            </select>
                            @error('turno_educativo_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Año lectivo</label>
                            <input type="text" name="año_lectivo" value="{{ old('año_lectivo') }}" placeholder="Ej: 2026, 2026-I"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('año_lectivo') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('año_lectivo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Grado / Ciclo</label>
                            <input type="text" name="grado_ciclo" value="{{ old('grado_ciclo') }}" placeholder="Ej: 3° Primaria"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('grado_ciclo') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('grado_ciclo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 3: Tutor --}}
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[20px] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="font-semibold text-black dark:text-white mb-[18px] pb-[12px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">supervisor_account</i>
                        3. Tutor
                    </h6>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Nombre del tutor</label>
                            <input type="text" name="nombre_tutor" value="{{ old('nombre_tutor') }}" placeholder="Nombre completo del tutor"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('nombre_tutor') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('nombre_tutor')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Teléfono del tutor</label>
                            <input type="text" name="telefono_tutor" value="{{ old('telefono_tutor') }}" placeholder="Ej: +51 999 000 111"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('telefono_tutor') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('telefono_tutor')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 4: Costos y fechas --}}
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[20px] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="font-semibold text-black dark:text-white mb-[18px] pb-[12px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">payments</i>
                        4. Costos y fechas
                    </h6>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[20px] md:gap-[25px]">
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Costo mensual</label>
                            <input type="number" name="costo_mensual" value="{{ old('costo_mensual') }}" step="0.01" min="0" placeholder="0.00"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('costo_mensual') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('costo_mensual')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Moneda</label>
                            <select name="moneda_id" id="selMoneda" class="w-full">
                                <option value="">Sin moneda</option>
                                @foreach($monedas as $mon)
                                    <option value="{{ $mon->id }}" {{ old('moneda_id') == $mon->id ? 'selected' : '' }}>
                                        {{ $mon->codigo }} — {{ $mon->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('moneda_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Día de vencimiento</label>
                            <input type="number" name="dia_vencimiento_pago" value="{{ old('dia_vencimiento_pago') }}" min="1" max="31" placeholder="Ej: 10"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('dia_vencimiento_pago') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('dia_vencimiento_pago')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de inicio</label>
                            <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_inicio') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('fecha_inicio')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de fin</label>
                            <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_fin') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('fecha_fin')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 5: Estado y notas --}}
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[20px] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="font-semibold text-black dark:text-white mb-[18px] pb-[12px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                        <i class="material-symbols-outlined !text-[18px] text-primary-500">flag</i>
                        5. Estado y notas
                    </h6>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Estado de matrícula</label>
                            <select name="estado_matricula_id" id="selEstado" class="w-full">
                                <option value="">Sin estado</option>
                                @foreach($estados as $est)
                                    <option value="{{ $est->id }}"
                                        data-icono="{{ $est->icono ?? 'badge' }}"
                                        data-color="{{ $est->color ?? 'grey' }}"
                                        {{ old('estado_matricula_id') == $est->id ? 'selected' : '' }}>{{ $est->nombre }}</option>
                                @endforeach
                            </select>
                            @error('estado_matricula_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Notas</label>
                            <textarea name="notas" rows="3" maxlength="5000" placeholder="Observaciones adicionales..."
                                class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[13px] block w-full outline-0 focus:border-primary-500">{{ old('notas') }}</textarea>
                            @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end gap-[15px] mb-[25px]">
                    <a href="{{ route('dashboard.matriculas.index') }}"
                        class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                        <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                            <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                            Guardar matrícula
                        </span>
                    </button>
                </div>

            </form>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        $(function () {
            function fmtMiembro(opt) {
                if (!opt.id) return opt.text;
                var av = $(opt.element).data('avatar');
                var ini = opt.text.charAt(0).toUpperCase();
                if (av) return $('<span style="display:flex;align-items:center;gap:8px"><img src="'+av+'" style="width:26px;height:26px;border-radius:50%;object-fit:cover">'+opt.text+'</span>');
                return $('<span style="display:flex;align-items:center;gap:8px"><span style="width:26px;height:26px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700">'+ini+'</span>'+opt.text+'</span>');
            }
            function fmtInst(opt) {
                if (!opt.id) return opt.text;
                var img = $(opt.element).data('img'), ico = $(opt.element).data('icono') || 'school';
                if (img) return $('<span style="display:flex;align-items:center;gap:8px"><img src="'+img+'" style="width:34px;height:24px;border-radius:3px;object-fit:cover">'+opt.text+'</span>');
                return $('<span style="display:flex;align-items:center;gap:8px"><span class="material-symbols-outlined" style="font-size:18px;color:#4f88e4">'+ico+'</span>'+opt.text+'</span>');
            }
            function fmtIcono(opt) {
                if (!opt.id) return opt.text;
                var ico = $(opt.element).data('icono') || 'school';
                return $('<span style="display:flex;align-items:center;gap:8px"><span class="material-symbols-outlined" style="font-size:18px;color:#4f88e4">'+ico+'</span>'+opt.text+'</span>');
            }
            function fmtEstado(opt) {
                if (!opt.id) return opt.text;
                var ico = $(opt.element).data('icono') || 'badge';
                var c   = $(opt.element).data('color') || 'grey';
                var s   = {green:'background:#dcfce7;color:#166534;border:1px solid #86efac',red:'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5',blue:'background:#dbeafe;color:#1e40af;border:1px solid #93c5fd',orange:'background:#ffedd5;color:#9a3412;border:1px solid #fdba74',purple:'background:#f3e8ff;color:#6b21a8;border:1px solid #d8b4fe',grey:'background:#f3f4f6;color:#374151;border:1px solid #d1d5db'}[c]||'background:#f3f4f6;color:#374151;border:1px solid #d1d5db';
                return $('<span style="display:flex;align-items:center;gap:8px">'
                    + '<span class="material-symbols-outlined" style="font-size:16px;color:#4f88e4">'+ico+'</span>'
                    + '<span style="'+s+';padding:2px 10px;border-radius:12px;font-size:12px">'+opt.text+'</span>'
                    + '</span>');
            }
            $('#selMiembro').select2({ placeholder:'Seleccionar miembro...', allowClear:true, width:'100%', templateResult:fmtMiembro, templateSelection:fmtMiembro });
            $('#selInstitucion').select2({ placeholder:'Sin institución', allowClear:true, width:'100%', templateResult:fmtInst, templateSelection:fmtInst });
            $('#selNivel').select2({ placeholder:'Sin nivel', allowClear:true, width:'100%', templateResult:fmtIcono, templateSelection:fmtIcono });
            $('#selTurno').select2({ placeholder:'Sin turno', allowClear:true, width:'100%', templateResult:fmtIcono, templateSelection:fmtIcono });
            $('#selMoneda').select2({ placeholder:'Sin moneda', allowClear:true, width:'100%' });
            $('#selEstado').select2({ placeholder:'Sin estado', allowClear:true, width:'100%', templateResult:fmtEstado, templateSelection:fmtEstado });
        });
        </script>
    </body>
</html>
