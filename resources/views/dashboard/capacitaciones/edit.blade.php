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
        <title>Editar Capacitación</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Editar Capacitación</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.capacitaciones.index') }}" class="transition-all hover:text-primary-500">Capacitaciones</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.capacitaciones.show', $capacitacion) }}" class="transition-all hover:text-primary-500">{{ Str::limit($capacitacion->nombre, 30) }}</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center justify-between -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">
                        <i class="material-symbols-outlined !text-[20px] align-middle mr-[6px] text-primary-500">school</i>
                        {{ $capacitacion->nombre }}
                    </h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.capacitaciones.update', $capacitacion) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_redirect" value="show">

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Sección 1: Miembro y Empleo --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">person</i>
                            Miembro y Empleo
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Miembro del Hogar <span class="text-danger-500">*</span>
                                </label>
                                <select name="hogar_miembro_id" id="hogar_miembro_id" class="w-full">
                                    <option value="">Selecciona miembro...</option>
                                    @foreach($miembros as $m)
                                    @php
                                        $nombreM = trim(($m->user?->persona?->nombres ?? '') . ' ' . ($m->user?->persona?->apellido_paterno ?? '')) ?: $m->apodo ?: '—';
                                        $avatarM = $m->user?->persona?->foto_url;
                                    @endphp
                                    <option value="{{ $m->id }}"
                                        data-avatar="{{ $avatarM ?? '' }}"
                                        {{ old('hogar_miembro_id', $capacitacion->hogar_miembro_id) == $m->id ? 'selected' : '' }}>
                                        {{ $nombreM }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('hogar_miembro_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Empleo asociado <span class="text-gray-400 text-xs font-normal">(opcional)</span>
                                </label>
                                <select name="empleo_id" id="empleo_id" class="w-full">
                                    <option value="">Sin empleo asociado</option>
                                    @foreach($empleos as $emp)
                                    @php
                                        $nombreEmp  = $emp->empleador?->empresa?->razon_social ?? $emp->empleador?->nombre ?? null;
                                        $miembroEmp = trim(($emp->hogarMiembro?->user?->persona?->nombres ?? '') . ' ' . ($emp->hogarMiembro?->user?->persona?->apellido_paterno ?? '')) ?: $emp->hogarMiembro?->apodo ?? '—';
                                    @endphp
                                    <option value="{{ $emp->id }}"
                                        data-miembro="{{ $emp->hogar_miembro_id }}"
                                        {{ old('empleo_id', $capacitacion->empleo_id) == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->cargo }}{{ $nombreEmp ? ' — ' . $nombreEmp : '' }} ({{ $miembroEmp }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('empleo_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Sección 2: Datos --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">school</i>
                            Datos de la Capacitación
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Nombre <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="nombre" value="{{ old('nombre', $capacitacion->nombre) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('nombre')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Tipo de Capacitación</label>
                                <select name="tipo_capacitacion_id" id="tipo_capacitacion_id" class="w-full">
                                    <option value="">Sin tipo...</option>
                                    @foreach($tipos as $t)
                                        <option value="{{ $t->id }}"
                                            data-icono="{{ $t->icono ?? 'school' }}"
                                            {{ old('tipo_capacitacion_id', $capacitacion->tipo_capacitacion_id) == $t->id ? 'selected' : '' }}>
                                            {{ $t->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_capacitacion_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Descripción</label>
                                <textarea name="descripcion" rows="2"
                                    class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">{{ old('descripcion', $capacitacion->descripcion) }}</textarea>
                                @error('descripcion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Sección 3: Institución --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">business</i>
                            Institución
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Institución vinculada</label>
                                <select name="institucion_educativa_id" id="institucion_educativa_id" class="w-full">
                                    <option value="">Sin vincular...</option>
                                    @foreach($instituciones as $ie)
                                    <option value="{{ $ie->id }}"
                                        data-logo="{{ $ie->logo_path ? asset('storage/' . $ie->logo_path) : '' }}"
                                        {{ old('institucion_educativa_id', $capacitacion->institucion_educativa_id) == $ie->id ? 'selected' : '' }}>
                                        {{ $ie->nombre_referencial }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('institucion_educativa_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">O nombre libre</label>
                                <input type="text" name="institucion_nombre" value="{{ old('institucion_nombre', $capacitacion->institucion_nombre) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                    placeholder="Ej: Coursera, SENCICO...">
                                @error('institucion_nombre')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Sección 4: Período --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">calendar_today</i>
                            Período
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $capacitacion->fecha_inicio?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_inicio')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Fin</label>
                                <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $capacitacion->fecha_fin?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_fin')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Sección 5: Certificado --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">verified</i>
                            Certificado
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Vencimiento del certificado</label>
                                <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', $capacitacion->fecha_vencimiento?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_vencimiento')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Horas académicas</label>
                                <input type="number" name="horas_academicas" value="{{ old('horas_academicas', $capacitacion->horas_academicas) }}" min="0"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500"
                                    placeholder="Ej: 40">
                                @error('horas_academicas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Código / Folio del certificado</label>
                                <input type="text" name="codigo_certificado" value="{{ old('codigo_certificado', $capacitacion->codigo_certificado) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                    placeholder="Ej: ABC-123456">
                                @error('codigo_certificado')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">URL de verificación</label>
                                <input type="url" name="url_verificacion" value="{{ old('url_verificacion', $capacitacion->url_verificacion) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                    placeholder="https://coursera.org/verify/...">
                                @error('url_verificacion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Reemplazar archivo del certificado
                                    <span class="text-xs text-gray-400 font-normal">(PDF / JPG / PNG — máx. 10 MB; dejar vacío para conservar el actual)</span>
                                </label>
                                @if($capacitacion->file_path)
                                <div class="mb-[10px] flex items-center gap-[8px] text-sm text-gray-600 dark:text-gray-400">
                                    <i class="material-symbols-outlined !text-[16px] text-primary-500">attach_file</i>
                                    <a href="{{ asset('storage/' . $capacitacion->file_path) }}" target="_blank" class="text-primary-500 hover:underline">
                                        Ver archivo actual
                                    </a>
                                </div>
                                @endif
                                <input type="file" name="archivo" accept=".pdf,.jpg,.jpeg,.png"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-[10px] file:py-[10px] file:px-[14px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-600 dark:file:bg-[#15203c] dark:file:text-primary-400 hover:file:bg-primary-100 transition-all cursor-pointer">
                                @error('archivo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Sección 6: Notas y Estado --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">notes</i>
                            Notas y Estado
                        </h6>

                        <div class="grid grid-cols-1 gap-[20px] md:gap-[25px] mb-[25px]">
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Notas</label>
                                <textarea name="notas" rows="3"
                                    class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                    placeholder="Observaciones adicionales...">{{ old('notas', $capacitacion->notas) }}</textarea>
                                @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex flex-wrap gap-[24px]">
                                <label class="flex items-center gap-[10px] cursor-pointer select-none">
                                    <div class="relative">
                                        <input type="hidden" name="activo" value="0">
                                        <input type="checkbox" name="activo" value="1" id="activo"
                                            {{ old('activo', $capacitacion->activo) ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div class="w-[44px] h-[24px] bg-gray-200 dark:bg-[#172036] rounded-full peer peer-checked:bg-success-500 transition-colors"></div>
                                        <div class="absolute top-[2px] ltr:left-[2px] rtl:right-[2px] w-[20px] h-[20px] bg-white rounded-full shadow transition-transform peer-checked:translate-x-[20px] rtl:peer-checked:-translate-x-[20px]"></div>
                                    </div>
                                    <span class="text-black dark:text-white font-medium text-sm">Registro activo</span>
                                </label>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center gap-[12px] pt-[10px] border-t border-gray-100 dark:border-[#172036]">
                            <a href="{{ route('dashboard.capacitaciones.show', $capacitacion) }}"
                                class="font-medium inline-block transition-all rounded-md text-sm py-[10px] md:py-[12px] px-[20px] md:px-[22px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="font-medium inline-block transition-all rounded-md text-sm py-[10px] md:py-[12px] px-[20px] md:px-[22px] bg-primary-500 text-white hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[26px] rtl:pr-[26px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Guardar Cambios
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
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        const empleosData = @json($empleosData);

        function avatarTemplate(opt) {
            if (!opt.id) return opt.text;
            const avatar = opt.element?.dataset?.avatar;
            const img = avatar
                ? `<img src="${avatar}" style="width:24px;height:24px;border-radius:50%;object-fit:cover;">`
                : `<span class="material-symbols-outlined" style="font-size:20px;color:#9ca3af;line-height:1;">person</span>`;
            return $(`<span style="display:flex;align-items:center;gap:8px;">${img}<span>${opt.text}</span></span>`);
        }

        function logoTemplate(opt) {
            if (!opt.id) return opt.text;
            const logo  = opt.element?.dataset?.logo;
            const letra = opt.text.charAt(0).toUpperCase();
            const img   = logo
                ? `<img src="${logo}" style="width:22px;height:22px;border-radius:4px;object-fit:cover;">`
                : `<span style="width:22px;height:22px;border-radius:4px;background:#dbeafe;color:#1d4ed8;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">${letra}</span>`;
            return $(`<span style="display:flex;align-items:center;gap:8px;">${img}<span>${opt.text}</span></span>`);
        }

        function iconoTemplate(opt) {
            if (!opt.id) return opt.text;
            const icono = opt.element?.dataset?.icono ?? 'school';
            return $(`<span style="display:flex;align-items:center;gap:8px;"><span class="material-symbols-outlined" style="font-size:16px;">${icono}</span><span>${opt.text}</span></span>`);
        }

        function workTemplate(opt) {
            if (!opt.id) return opt.text;
            return $(`<span style="display:flex;align-items:center;gap:8px;"><span class="material-symbols-outlined" style="font-size:16px;color:#f97316;">work</span><span>${opt.text}</span></span>`);
        }

        function refreshEmpleoSelect(miembroId) {
            const $sel = $('#empleo_id');
            const currentVal = $sel.val();
            $sel.empty().append('<option value="">Sin empleo asociado</option>');
            empleosData.forEach(e => {
                if (!miembroId || e.miembro_id === miembroId) {
                    const opt = new Option(e.text, e.id, false, e.id === currentVal);
                    $sel.append(opt);
                }
            });
            $sel.trigger('change');
        }

        $(function () {
            $('#hogar_miembro_id').select2({
                placeholder: 'Selecciona miembro...',
                templateResult: avatarTemplate,
                templateSelection: avatarTemplate,
            }).on('change', function () {
                refreshEmpleoSelect($(this).val());
            });

            $('#empleo_id').select2({
                placeholder: 'Sin empleo asociado',
                allowClear: true,
                templateResult: workTemplate,
                templateSelection: workTemplate,
            });

            $('#tipo_capacitacion_id').select2({
                placeholder: 'Sin tipo...',
                allowClear: true,
                templateResult: iconoTemplate,
                templateSelection: iconoTemplate,
            });

            $('#institucion_educativa_id').select2({
                placeholder: 'Sin vincular...',
                allowClear: true,
                templateResult: logoTemplate,
                templateSelection: logoTemplate,
            });

            const initMiembro = $('#hogar_miembro_id').val();
            if (initMiembro) refreshEmpleoSelect(initMiembro);
        });
        </script>
    </body>
</html>
