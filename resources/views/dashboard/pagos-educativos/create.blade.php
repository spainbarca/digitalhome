<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Registrar Pago Educativo</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Registrar Pago Educativo</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.pagos-educativos.index') }}" class="transition-all hover:text-primary-500">Pagos Educativos</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nuevo</li>
                </ol>
            </div>

            <form method="POST" action="{{ route('dashboard.pagos-educativos.store') }}" enctype="multipart/form-data">
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
                        <i class="material-symbols-outlined !text-[20px] text-primary-500 mr-[8px]">payments</i>
                        <h5 class="!mb-0">Datos del Pago</h5>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                        {{-- Matrícula --}}
                        <div class="sm:col-span-2">
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Matrícula <span class="text-danger-500">*</span></label>
                            <select name="matricula_id" id="selMatricula"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('matricula_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                                <option value="">Seleccionar matrícula...</option>
                                @foreach($matriculas as $mat)
                                    @php
                                        $matNombreM = trim(implode(' ', array_filter([$mat->hogarMiembro?->user?->persona?->nombres, $mat->hogarMiembro?->user?->persona?->apellido_paterno, $mat->hogarMiembro?->user?->persona?->apellido_materno]))) ?: ($mat->hogarMiembro?->user?->name ?? '');
                                        $matLabel = ($mat->institucionEducativa?->nombre_referencial ?? '(Sin institución)')
                                            . ($matNombreM ? ' — ' . $matNombreM : '')
                                            . ($mat->año_lectivo ? ' · ' . $mat->año_lectivo : '');
                                    @endphp
                                    <option value="{{ $mat->id }}"
                                        data-moneda-id="{{ $mat->moneda_id }}"
                                        data-costo="{{ $mat->costo_mensual }}"
                                        {{ old('matricula_id', $preselMatricula) == $mat->id ? 'selected' : '' }}>
                                        {{ $matLabel }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matricula_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Concepto --}}
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Concepto</label>
                            <input type="text" name="concepto" value="{{ old('concepto') }}" maxlength="200"
                                placeholder="Ej: Pensión mensual"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('concepto') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500">
                            @error('concepto')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Mes correspondiente --}}
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Mes correspondiente</label>
                            <input type="month" name="mes_correspondiente" value="{{ old('mes_correspondiente') }}"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('mes_correspondiente') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('mes_correspondiente')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Moneda --}}
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Moneda</label>
                            <select name="moneda_id" id="selMoneda"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('moneda_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                                <option value="">Sin moneda</option>
                                @foreach($monedas as $mon)
                                    <option value="{{ $mon->id }}" data-simbolo="{{ $mon->simbolo }}"
                                        {{ old('moneda_id') == $mon->id ? 'selected' : '' }}>
                                        {{ $mon->simbolo }} — {{ $mon->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('moneda_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Monto --}}
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Monto <span class="text-danger-500">*</span></label>
                            <div class="relative">
                                <span id="simboloMoneda" class="absolute left-[14px] top-1/2 -translate-y-1/2 text-gray-400 font-medium">S/</span>
                                <input type="number" name="monto" id="inputMonto" value="{{ old('monto') }}" min="0" step="0.01"
                                    placeholder="0.00"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('monto') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] pl-[40px] pr-[17px] block w-full outline-0 placeholder:text-gray-400 focus:border-primary-500">
                            </div>
                            @error('monto')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Estado --}}
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Estado <span class="text-danger-500">*</span></label>
                            <select name="estado"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('estado') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                                <option value="pendiente" {{ old('estado', 'pendiente') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="pagado"    {{ old('estado') === 'pagado'    ? 'selected' : '' }}>Pagado</option>
                                <option value="vencido"   {{ old('estado') === 'vencido'   ? 'selected' : '' }}>Vencido</option>
                            </select>
                            @error('estado')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Fecha vencimiento --}}
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de vencimiento</label>
                            <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_vencimiento') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('fecha_vencimiento')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Fecha pago --}}
                        <div>
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Fecha de pago</label>
                            <input type="date" name="fecha_pago" value="{{ old('fecha_pago') }}"
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_pago') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 focus:border-primary-500">
                            @error('fecha_pago')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Comprobante --}}
                        <div class="sm:col-span-2">
                            <label class="mb-[10px] text-black dark:text-white font-medium block">
                                Comprobante
                                <span class="text-xs text-gray-400 font-normal ml-[4px]">(imagen, PDF, etc. — máx. 10 MB)</span>
                            </label>
                            <div class="relative flex items-center justify-center overflow-hidden rounded-md py-[40px] px-[20px] border-2 border-dashed border-gray-200 dark:border-[#172036] hover:border-primary-400 transition-all">
                                <div class="text-center">
                                    <i class="material-symbols-outlined !text-[40px] text-gray-300 dark:text-gray-600 block mb-[8px]">receipt</i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <strong class="text-black dark:text-white">Haz clic para seleccionar</strong> o arrastra aquí
                                    </p>
                                    <p id="nombreComprobante" class="text-xs text-primary-500 mt-[6px] font-medium hidden"></p>
                                </div>
                                <input type="file" name="comprobante" id="comprobanteInput"
                                    onchange="mostrarNombre(this, 'nombreComprobante')"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            @error('comprobante')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
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
                        <a href="{{ route('dashboard.pagos-educativos.index') }}"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-danger-500 text-white hover:bg-danger-400">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="font-medium inline-block transition-all rounded-md py-[12px] px-[22px] bg-primary-500 text-white hover:bg-primary-400">
                            <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                Registrar Pago
                            </span>
                        </button>
                    </div>
                </div>
            </form>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script>
        const endpointDatosMatricula = '{{ url('dashboard/pagos-educativos/datos-matricula') }}';

        function mostrarNombre(input, elId) {
            const el = document.getElementById(elId);
            if (input.files && input.files[0]) {
                el.textContent = input.files[0].name;
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }

        document.getElementById('selMatricula').addEventListener('change', function () {
            const matriculaId = this.value;
            if (!matriculaId) return;

            fetch(endpointDatosMatricula + '/' + matriculaId)
                .then(r => r.json())
                .then(data => {
                    // Autocompletar moneda
                    if (data.moneda_id) {
                        document.getElementById('selMoneda').value = data.moneda_id;
                    }
                    // Sugerir monto si está vacío
                    const inputMonto = document.getElementById('inputMonto');
                    if (!inputMonto.value && data.costo_mensual) {
                        inputMonto.value = data.costo_mensual;
                    }
                    // Actualizar símbolo
                    if (data.moneda_simbolo) {
                        document.getElementById('simboloMoneda').textContent = data.moneda_simbolo;
                    }
                })
                .catch(() => {});
        });

        document.getElementById('selMoneda').addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const simbolo = opt.dataset.simbolo || 'S/';
            document.getElementById('simboloMoneda').textContent = simbolo;
        });

        // Si hay pre-selección de matrícula al cargar
        window.addEventListener('DOMContentLoaded', function () {
            const sel = document.getElementById('selMatricula');
            if (sel.value) sel.dispatchEvent(new Event('change'));
        });
        </script>
    </body>
</html>
