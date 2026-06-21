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
        <title>Nuevo Viaje</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nuevo Viaje</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.viajes.index') }}" class="transition-all hover:text-primary-500">Viajes</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nuevo</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">Registrar Viaje</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.viajes.store') }}" enctype="multipart/form-data">
                        @csrf

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- ── Sección 1: Datos básicos ──────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">luggage</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">1. Datos del viaje</h6>
                                <span class="text-xs text-danger-500">* Requerido</span>
                            </div>
                            <div class="grid grid-cols-12 gap-[20px] md:gap-[25px]">

                                <div class="col-span-12 sm:col-span-8">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Nombre <span class="text-danger-500">*</span>
                                    </label>
                                    <input type="text" name="nombre" value="{{ old('nombre') }}" maxlength="255"
                                        placeholder="Ej: Vacaciones Europa 2026"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('nombre') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('nombre')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div class="col-span-12 sm:col-span-4">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Tipo de viaje</label>
                                    <select name="tipo_viaje_id" id="tipo_viaje_id" class="w-full">
                                        <option value="">Sin tipo</option>
                                        @foreach($tipos as $t)
                                        <option value="{{ $t->id }}"
                                            data-icono="{{ $t->icono ?? 'luggage' }}"
                                            {{ old('tipo_viaje_id') == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo_viaje_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div class="col-span-12 sm:col-span-3">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha inicio</label>
                                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_inicio') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                    @error('fecha_inicio')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div class="col-span-12 sm:col-span-3">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha fin</label>
                                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_fin') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                    @error('fecha_fin')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div class="col-span-12 sm:col-span-3">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Estado</label>
                                    <select name="estado"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('estado') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                        <option value="">Sin estado</option>
                                        @foreach($estados as $val => $label)
                                        <option value="{{ $val }}" {{ old('estado', 'planificado') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('estado')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div class="col-span-12 sm:col-span-3">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Moneda</label>
                                    <select name="moneda_id"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('moneda_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                        <option value="">Sin moneda</option>
                                        @foreach($monedas as $m)
                                        <option value="{{ $m->id }}" {{ old('moneda_id') == $m->id ? 'selected' : '' }}>
                                            {{ $m->simbolo }} {{ $m->codigo }} — {{ $m->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('moneda_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div class="col-span-12 sm:col-span-4">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Presupuesto</label>
                                    <input type="number" step="0.01" min="0" name="presupuesto" value="{{ old('presupuesto') }}"
                                        placeholder="0.00"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('presupuesto') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                    @error('presupuesto')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div class="col-span-12 sm:col-span-8">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Descripción</label>
                                    <input type="text" name="descripcion" value="{{ old('descripcion') }}" maxlength="500"
                                        placeholder="Breve descripción del viaje..."
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('descripcion') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('descripcion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── Sección 2: Portada ────────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">image</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">2. Portada</h6>
                            </div>
                            <div class="max-w-lg">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Imagen de portada
                                    <span class="text-xs text-gray-400 font-normal ml-[4px]">(JPG, PNG, WebP — máx. 10 MB)</span>
                                </label>
                                <input type="file" name="portada" id="portadaInput" accept=".jpg,.jpeg,.png,.webp"
                                    onchange="previewPortada(this)"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-[10px] file:py-[8px] file:px-[14px] file:rounded-md
                                        file:border-0 file:text-sm file:font-medium
                                        file:bg-primary-50 file:text-primary-600
                                        dark:file:bg-[#15203c] dark:file:text-primary-400
                                        hover:file:bg-primary-100 transition-all cursor-pointer">
                                @error('portada')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                <div id="portadaPreview" class="mt-[10px] hidden">
                                    <img id="portadaImg" src="" class="h-[120px] w-auto rounded-md object-cover border border-gray-200 dark:border-[#172036]" alt="Preview">
                                </div>
                            </div>
                        </div>

                        {{-- ── Sección 3: Notas ──────────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">notes</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">3. Notas</h6>
                            </div>
                            <div class="max-w-2xl">
                                <textarea name="notas" rows="3"
                                    placeholder="Información adicional del viaje..."
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500 resize-none">{{ old('notas') }}</textarea>
                                @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-[20px] flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.viajes.index') }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Registrar Viaje
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
        function previewPortada(input) {
            const prev = document.getElementById('portadaPreview');
            const img  = document.getElementById('portadaImg');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { img.src = e.target.result; prev.classList.remove('hidden'); };
                reader.readAsDataURL(input.files[0]);
            } else {
                prev.classList.add('hidden');
            }
        }

        function fmtTipoViaje(opt) {
            if (!opt.id) return opt.text;
            var icono = $(opt.element).data('icono') || 'luggage';
            return $('<span style="display:flex;align-items:center;gap:8px"><i class="material-symbols-outlined" style="font-size:18px;color:#6b7280">' + icono + '</i>' + opt.text + '</span>');
        }

        $(function() {
            $('#tipo_viaje_id').select2({
                width: '100%',
                placeholder: 'Sin tipo',
                allowClear: true,
                templateResult: fmtTipoViaje,
                templateSelection: fmtTipoViaje,
            });
        });
        </script>
    </body>
</html>
