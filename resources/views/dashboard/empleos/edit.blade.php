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
        <title>Editar Empleo — {{ $empleo->cargo }}</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Editar Empleo</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.empleos.index') }}" class="transition-all hover:text-primary-500">Empleos</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.empleos.show', $empleo) }}" class="transition-all hover:text-primary-500">{{ Str::limit($empleo->cargo, 30) }}</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center justify-between -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">
                        <i class="material-symbols-outlined !text-[20px] align-middle mr-[6px] text-primary-500">edit</i>
                        Editar: {{ $empleo->cargo }}
                    </h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.empleos.update', $empleo) }}">
                        @csrf
                        @method('PUT')

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">person</i>
                            Miembro y Empleador
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">

                            <!-- Miembro -->
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
                                            {{ old('hogar_miembro_id', $empleo->hogar_miembro_id) == $m->id ? 'selected' : '' }}>
                                            {{ $nombreM }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hogar_miembro_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Empleador -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Empleador</label>
                                <select name="empleador_id" id="empleador_id" class="w-full">
                                    <option value="">Sin empleador registrado...</option>
                                    @foreach($empleadores as $emp)
                                        @php $logoEmp = $emp->logo_display_url; @endphp
                                        <option value="{{ $emp->id }}"
                                            data-logo="{{ $logoEmp ?? '' }}"
                                            data-sigla="{{ $emp->sigla_resuelta ?? '' }}"
                                            {{ old('empleador_id', $empleo->empleador_id) == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('empleador_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">work</i>
                            Datos del Cargo
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">

                            <!-- Cargo -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Cargo / Puesto <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="cargo" value="{{ old('cargo', $empleo->cargo) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500"
                                    placeholder="Ej: Analista de Sistemas">
                                @error('cargo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Estado -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Estado <span class="text-danger-500">*</span>
                                </label>
                                <select name="estado_empleo_id" id="estado_empleo_id" class="w-full">
                                    <option value="">Selecciona estado...</option>
                                    @foreach($estados as $est)
                                        <option value="{{ $est->id }}"
                                            data-icono="{{ $est->icono ?? 'flag' }}"
                                            {{ old('estado_empleo_id', $empleo->estado_empleo_id) == $est->id ? 'selected' : '' }}>
                                            {{ $est->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado_empleo_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Modalidad -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Modalidad Laboral</label>
                                <select name="modalidad_laboral_id" id="modalidad_laboral_id" class="w-full">
                                    <option value="">Sin modalidad...</option>
                                    @foreach($modalidades as $mod)
                                        <option value="{{ $mod->id }}"
                                            data-icono="{{ $mod->icono ?? 'work' }}"
                                            {{ old('modalidad_laboral_id', $empleo->modalidad_laboral_id) == $mod->id ? 'selected' : '' }}>
                                            {{ $mod->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('modalidad_laboral_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Descripción del cargo</label>
                                <textarea name="descripcion" rows="1"
                                    class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500"
                                    placeholder="Describe las responsabilidades...">{{ old('descripcion', $empleo->descripcion) }}</textarea>
                                @error('descripcion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">calendar_today</i>
                            Período y Remuneración
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px] mb-[25px]">

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $empleo->fecha_inicio?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_inicio')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de Fin</label>
                                <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $empleo->fecha_fin?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_fin')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Venc. de Contrato</label>
                                <input type="date" name="fecha_vencimiento_contrato" value="{{ old('fecha_vencimiento_contrato', $empleo->fecha_vencimiento_contrato?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_vencimiento_contrato')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Salario</label>
                                <div class="flex gap-[10px]">
                                    <div class="flex-1">
                                        <input type="number" name="salario" value="{{ old('salario', $empleo->salario) }}" step="0.01" min="0"
                                            class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500"
                                            placeholder="0.00">
                                    </div>
                                    <div class="w-[130px]">
                                        <select name="moneda_id" id="moneda_id" class="w-full h-[55px]">
                                            <option value="">Moneda</option>
                                            @foreach($monedas as $mon)
                                                <option value="{{ $mon->id }}"
                                                    {{ old('moneda_id', $empleo->moneda_id) == $mon->id ? 'selected' : '' }}>
                                                    {{ $mon->codigo }} {{ $mon->simbolo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @error('salario')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">stars</i>
                            Logros y Estado
                        </h6>

                        <div class="grid grid-cols-1 gap-[20px] md:gap-[25px] mb-[25px]">
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Logros</label>
                                <textarea name="logros" rows="3"
                                    class="rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500"
                                    placeholder="Describe los logros obtenidos...">{{ old('logros', $empleo->logros) }}</textarea>
                                @error('logros')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex flex-wrap gap-[24px]">
                                <label class="flex items-center gap-[10px] cursor-pointer select-none">
                                    <div class="relative">
                                        <input type="hidden" name="es_actual" value="0">
                                        <input type="checkbox" name="es_actual" value="1" id="es_actual"
                                            {{ old('es_actual', $empleo->es_actual) ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div class="w-[44px] h-[24px] bg-gray-200 dark:bg-[#172036] rounded-full peer peer-checked:bg-primary-500 transition-colors"></div>
                                        <div class="absolute top-[2px] ltr:left-[2px] rtl:right-[2px] w-[20px] h-[20px] bg-white rounded-full shadow transition-transform peer-checked:translate-x-[20px] rtl:peer-checked:-translate-x-[20px]"></div>
                                    </div>
                                    <span class="text-black dark:text-white font-medium text-sm">Empleo actual</span>
                                </label>

                                <label class="flex items-center gap-[10px] cursor-pointer select-none">
                                    <div class="relative">
                                        <input type="hidden" name="activo" value="0">
                                        <input type="checkbox" name="activo" value="1" id="activo"
                                            {{ old('activo', $empleo->activo) ? 'checked' : '' }}
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
                            <a href="{{ route('dashboard.empleos.show', $empleo) }}"
                                class="font-medium inline-block transition-all rounded-md text-sm py-[10px] md:py-[12px] px-[20px] md:px-[22px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="font-medium inline-block transition-all rounded-md text-sm py-[10px] md:py-[12px] px-[20px] md:px-[22px] bg-primary-500 text-white hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[26px] rtl:pr-[26px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Actualizar Empleo
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
        function avatarTemplate(opt) {
            if (!opt.id) return opt.text;
            const avatar = opt.element?.dataset?.avatar;
            const img    = avatar
                ? `<img src="${avatar}" style="width:24px;height:24px;border-radius:50%;object-fit:cover;" />`
                : `<span class="material-symbols-outlined" style="font-size:20px;color:#9ca3af;line-height:1;">person</span>`;
            return $(`<span style="display:flex;align-items:center;gap:8px;">${img}<span>${opt.text}</span></span>`);
        }

        function logoTemplate(opt) {
            if (!opt.id) return opt.text;
            const logo  = opt.element?.dataset?.logo;
            const sigla = opt.element?.dataset?.sigla;
            const letra = opt.text.charAt(0).toUpperCase();
            const img   = logo
                ? `<img src="${logo}" style="width:24px;height:24px;border-radius:4px;object-fit:cover;" />`
                : `<span style="width:24px;height:24px;border-radius:4px;background:#dbeafe;color:#1d4ed8;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">${letra}</span>`;
            const label = sigla ? `${opt.text} - (${sigla})` : opt.text;
            return $(`<span style="display:flex;align-items:center;gap:8px;">${img}<span>${label}</span></span>`);
        }

        function iconoTemplate(opt) {
            if (!opt.id) return opt.text;
            const icono = opt.element?.dataset?.icono ?? 'label';
            return $(`<span style="display:flex;align-items:center;gap:8px;"><span class="material-symbols-outlined" style="font-size:16px;">${icono}</span><span>${opt.text}</span></span>`);
        }

        $(function() {
            $('#hogar_miembro_id').select2({ placeholder: 'Selecciona miembro...', templateResult: avatarTemplate, templateSelection: avatarTemplate });
            $('#empleador_id').select2({ placeholder: 'Sin empleador...', templateResult: logoTemplate, templateSelection: logoTemplate, allowClear: true });
            $('#estado_empleo_id').select2({ placeholder: 'Selecciona estado...', templateResult: iconoTemplate, templateSelection: iconoTemplate });
            $('#modalidad_laboral_id').select2({ placeholder: 'Sin modalidad...', templateResult: iconoTemplate, templateSelection: iconoTemplate, allowClear: true });
            $('#moneda_id').select2({ placeholder: 'Moneda...', allowClear: true });
        });
        </script>
    </body>
</html>
