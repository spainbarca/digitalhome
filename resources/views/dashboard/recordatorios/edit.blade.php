<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Editar Recordatorio</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Editar Recordatorio</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.recordatorios.index') }}" class="transition-all hover:text-primary-500">Recordatorios</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.recordatorios.show', $recordatorio) }}" class="transition-all hover:text-primary-500">Detalle</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <i class="material-symbols-outlined !text-[20px] text-primary-500 mr-[8px]">edit</i>
                    <h5 class="!mb-0">Editar Recordatorio</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.recordatorios.update', $recordatorio) }}">
                        @csrf
                        @method('PUT')

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- SECCIÓN 1: Información principal --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">info</i>
                            Información principal
                        </h6>

                        <div class="mb-[20px]">
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Título <span class="text-danger-500">*</span></label>
                            <input type="text" name="titulo" value="{{ old('titulo', $recordatorio->titulo) }}" maxlength="150"
                                placeholder="Ej: Renovar SOAT, Pagar luz..."
                                class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('titulo') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                            @error('titulo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-[25px]">
                            <label class="mb-[10px] text-black dark:text-white font-medium block">Descripción</label>
                            <textarea name="descripcion" rows="3" maxlength="500" placeholder="Detalles adicionales..."
                                class="rounded-md text-black dark:text-white border {{ $errors->has('descripcion') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[12px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('descripcion', $recordatorio->descripcion) }}</textarea>
                            @error('descripcion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- SECCIÓN 2: Fechas --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">calendar_today</i>
                            Fechas
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] mb-[25px]">
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">¿Cuándo vence? <span class="text-danger-500">*</span></label>
                                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                                    value="{{ old('fecha_vencimiento', $recordatorio->fecha_vencimiento?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_vencimiento') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                <div id="diasRestantesBadge" class="mt-[6px] min-h-[22px]"></div>
                                @error('fecha_vencimiento')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">¿Cuándo avisarte?</label>
                                <input type="datetime-local" name="fecha_aviso"
                                    value="{{ old('fecha_aviso', $recordatorio->fecha_aviso?->format('Y-m-d\TH:i')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_aviso') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                <p class="text-xs text-gray-400 mt-[5px]">Opcional — si no se llena, se avisa el mismo día de vencimiento</p>
                                @error('fecha_aviso')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- SECCIÓN 3: Configuración --}}
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">settings</i>
                            Configuración
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] mb-[25px]">
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Estado <span class="text-danger-500">*</span></label>
                                <select name="estado"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('estado') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                    <option value="pendiente"  {{ old('estado', $recordatorio->estado) === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                                    <option value="enviado"    {{ old('estado', $recordatorio->estado) === 'enviado'    ? 'selected' : '' }}>Enviado</option>
                                    <option value="descartado" {{ old('estado', $recordatorio->estado) === 'descartado' ? 'selected' : '' }}>Descartado</option>
                                </select>
                                @error('estado')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex flex-col justify-center">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">¿Se repite anualmente?</label>
                                <label class="inline-flex items-center cursor-pointer gap-[10px]">
                                    <div class="relative">
                                        <input type="hidden" name="repetir" value="0">
                                        <input type="checkbox" name="repetir" id="repetir" value="1"
                                               class="sr-only peer"
                                               {{ old('repetir', $recordatorio->repetir) ? 'checked' : '' }}
                                               onchange="toggleRepetir(this.checked)">
                                        <div class="w-[44px] h-[24px] bg-gray-200 dark:bg-gray-600 peer-checked:bg-primary-500 rounded-full transition-all"></div>
                                        <div class="absolute top-[2px] left-[2px] w-[20px] h-[20px] bg-white rounded-full transition-all peer-checked:translate-x-[20px] shadow-sm"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Se repite anualmente</span>
                                </label>
                                <p id="textoRepetir" class="{{ $recordatorio->repetir ? '' : 'hidden' }} text-xs text-primary-500 mt-[6px] flex items-center gap-[4px]">
                                    <i class="material-symbols-outlined !text-[13px]">autorenew</i>
                                    El recordatorio se renovará automáticamente cada año
                                </p>
                            </div>
                        </div>

                        {{-- SECCIÓN 4: Vinculación (si existe) --}}
                        @if($recordatorio->recordable_id)
                        <div class="mb-[25px] p-[14px] rounded-md bg-gray-50 dark:bg-[#15203c] border border-gray-200 dark:border-[#172036] flex items-center gap-[10px]">
                            <i class="material-symbols-outlined !text-[20px] text-primary-500">link</i>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400 mb-[2px]">Vinculado a</p>
                                <p class="text-sm font-medium text-black dark:text-white">{{ class_basename($recordatorio->recordable_type) }} #{{ Str::limit($recordatorio->recordable_id, 8) }}</p>
                            </div>
                            <p class="text-xs text-gray-400 ml-auto">Solo lectura</p>
                        </div>
                        @endif

                        <div class="flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.recordatorios.show', $recordatorio) }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
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
        <script>
        function toggleRepetir(checked) {
            var texto = document.getElementById('textoRepetir');
            if (checked) texto.classList.remove('hidden');
            else texto.classList.add('hidden');
        }

        document.getElementById('fecha_vencimiento').addEventListener('change', function () {
            var badge = document.getElementById('diasRestantesBadge');
            if (!this.value) { badge.innerHTML = ''; return; }
            var hoy   = new Date(); hoy.setHours(0,0,0,0);
            var vence = new Date(this.value + 'T00:00:00');
            var diff  = Math.round((vence - hoy) / 86400000);
            var texto, cls;
            if (diff < 0)       { texto = 'Venció hace ' + Math.abs(diff) + ' días'; cls = 'bg-danger-100 text-danger-700 border border-danger-300'; }
            else if (diff === 0){ texto = '¡Vence hoy!'; cls = 'bg-danger-100 text-danger-700 border border-danger-300'; }
            else if (diff <= 7) { texto = 'Faltan ' + diff + ' días'; cls = 'bg-orange-100 text-orange-700 border border-orange-300'; }
            else if (diff <= 30){ texto = 'Faltan ' + diff + ' días'; cls = 'bg-warning-100 text-warning-700 border border-warning-300'; }
            else                { texto = 'Faltan ' + diff + ' días'; cls = 'bg-success-100 text-success-700 border border-success-300'; }
            badge.innerHTML = '<span class="inline-block text-xs font-medium py-[2px] px-[8px] rounded-xl ' + cls + '">' + texto + '</span>';
        });

        if (document.getElementById('fecha_vencimiento').value) {
            document.getElementById('fecha_vencimiento').dispatchEvent(new Event('change'));
        }
        </script>
    </body>
</html>
