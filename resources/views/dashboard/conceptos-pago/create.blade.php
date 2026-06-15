<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Nuevo Concepto de Pago</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nuevo Concepto de Pago</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.prestamos.conceptos-pago.index') }}" class="transition-all hover:text-primary-500">Conceptos de Pago</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Nuevo</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[25px] flex items-center -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">Registrar Concepto de Pago</h5>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.prestamos.conceptos-pago.store') }}" enctype="multipart/form-data">
                        @csrf

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- ── SECCIÓN 1: Categoría ─────────────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">label</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">1. Categoría</h6>
                                <span class="text-xs text-gray-400">Opcional</span>
                            </div>
                            <div class="max-w-lg">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Categoría de Concepto</label>
                                <div class="relative" id="catWrapper">
                                    <div id="catTrigger"
                                        class="h-[55px] flex items-center rounded-md border {{ $errors->has('categoria_concepto_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400" id="catIconPreview">payments</i>
                                        <span id="catLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Sin categoría asignada</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="catChevron">expand_more</i>
                                    </div>
                                    <input type="hidden" name="categoria_concepto_id" id="categoria_concepto_id" value="{{ old('categoria_concepto_id', '') }}">
                                    <div id="catDropdown"
                                        class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <input type="text" id="catBuscar" placeholder="Buscar categoría..."
                                                class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                        </div>
                                        <ul id="catOpciones" class="max-h-[240px] overflow-y-auto py-[4px]">
                                            <li class="cat-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="" data-nombre="Sin categoría asignada" data-icono="payments">
                                                <i class="material-symbols-outlined !text-[18px] text-gray-400">remove_circle_outline</i>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">Sin categoría asignada</span>
                                            </li>
                                            @foreach($categorias as $cat)
                                            <li class="cat-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $cat->id }}"
                                                data-nombre="{{ $cat->nombre }}"
                                                data-icono="{{ $cat->icono ?? 'label' }}">
                                                <i class="material-symbols-outlined !text-[18px] text-primary-500">{{ $cat->icono ?? 'label' }}</i>
                                                <span class="text-sm text-black dark:text-white">{{ $cat->nombre }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @error('categoria_concepto_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- ── SECCIÓN 2: Datos del Concepto ───────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">info</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">2. Datos del Concepto</h6>
                            </div>
                            <div class="grid grid-cols-1 gap-[20px] md:gap-[25px]">

                                <div class="max-w-lg">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Nombre <span class="text-danger-500">*</span>
                                    </label>
                                    <input type="text" name="nombre" value="{{ old('nombre') }}" maxlength="255"
                                        placeholder="Ej: Cuota mensual"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('nombre') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    @error('nombre')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div class="max-w-lg">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Precio referencial (S/)
                                        <span class="text-xs text-gray-400 font-normal ml-[4px]">Opcional</span>
                                    </label>
                                    <input type="number" name="precio_referencial" value="{{ old('precio_referencial') }}"
                                        step="0.01" min="0" placeholder="0.00"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('precio_referencial') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500">
                                    <p class="text-xs text-gray-400 mt-[5px]">
                                        <i class="material-symbols-outlined !text-[13px] align-middle">info</i>
                                        Este valor es referencial y podrá modificarse al registrar cada movimiento.
                                    </p>
                                    @error('precio_referencial')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div class="max-w-lg">
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Descripción
                                        <span class="text-xs text-gray-400 font-normal ml-[4px]">Opcional</span>
                                    </label>
                                    <textarea name="descripcion" rows="3"
                                        placeholder="Información adicional sobre este concepto..."
                                        class="rounded-md text-black dark:text-white border {{ $errors->has('descripcion') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[14px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-primary-500 resize-none">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── SECCIÓN 3: Imagen y Estado ──────────────────────────── --}}
                        <div class="mb-[25px]">
                            <div class="flex items-center gap-[8px] mb-[15px] pb-[12px] border-b border-gray-100 dark:border-[#172036]">
                                <i class="material-symbols-outlined !text-[20px] text-primary-500">image</i>
                                <h6 class="!mb-0 font-semibold text-black dark:text-white">3. Imagen y Estado</h6>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Imagen <span class="text-xs text-gray-400 font-normal">(JPG/PNG/WebP, máx. 2 MB)</span>
                                    </label>
                                    <div id="imagenPreviewWrap" class="hidden mb-[10px]">
                                        <img id="imagenPreview" src="" alt="Preview"
                                            class="w-full max-w-[200px] h-[120px] rounded-md object-cover object-top border border-gray-200 dark:border-[#172036]">
                                    </div>
                                    <input type="file" name="imagen" id="imagenInput" accept="image/*"
                                        onchange="previewFile(this,'imagenPreview','imagenPreviewWrap')"
                                        class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-[12px] file:py-[8px] file:px-[16px] file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-500 hover:file:bg-primary-100 cursor-pointer">
                                    @error('imagen')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>

                                <div class="flex items-center gap-[12px] pt-[10px] sm:pt-[30px]">
                                    <span class="text-black dark:text-white font-medium">Activo</span>
                                    <label class="relative cursor-pointer">
                                        <input type="checkbox" name="activo" value="1" class="sr-only peer" {{ old('activo', '1') ? 'checked' : '' }}>
                                        <div class="w-[42px] h-[24px] bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-primary-500"></div>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-[20px] flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.prestamos.conceptos-pago.index') }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Registrar Concepto
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
            function previewFile(input, imgId, wrapId) {
                const wrap = document.getElementById(wrapId);
                const img  = document.getElementById(imgId);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => { img.src = e.target.result; wrap.classList.remove('hidden'); };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    wrap.classList.add('hidden');
                }
            }

            (function () {
                const wrapper  = document.getElementById('catWrapper');
                const trigger  = document.getElementById('catTrigger');
                const dropdown = document.getElementById('catDropdown');
                const chevron  = document.getElementById('catChevron');
                const buscar   = document.getElementById('catBuscar');
                const hidden   = document.getElementById('categoria_concepto_id');
                const label    = document.getElementById('catLabel');
                const iconPrev = document.getElementById('catIconPreview');
                const opciones = document.querySelectorAll('.cat-opcion');

                function abrir() {
                    dropdown.classList.remove('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                    buscar.value = '';
                    opciones.forEach(li => li.style.display = '');
                    setTimeout(() => buscar.focus(), 50);
                }
                function cerrar() {
                    dropdown.classList.add('hidden');
                    chevron.style.transform = '';
                }
                function aplicar(id, nombre, icono) {
                    hidden.value = id;
                    if (id) {
                        label.textContent    = nombre;
                        label.className      = 'text-black dark:text-white text-sm flex-1 truncate';
                        iconPrev.textContent = icono;
                        iconPrev.className   = 'material-symbols-outlined !text-[20px] mr-[8px] text-primary-500';
                    } else {
                        label.textContent    = 'Sin categoría asignada';
                        label.className      = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                        iconPrev.textContent = 'payments';
                        iconPrev.className   = 'material-symbols-outlined !text-[20px] mr-[8px] text-gray-400';
                    }
                }
                trigger.addEventListener('click', () =>
                    dropdown.classList.contains('hidden') ? abrir() : cerrar()
                );
                buscar.addEventListener('input', () => {
                    const q = buscar.value.toLowerCase();
                    opciones.forEach(li => {
                        li.style.display = li.dataset.nombre.toLowerCase().includes(q) ? '' : 'none';
                    });
                });
                opciones.forEach(li => {
                    li.addEventListener('click', () => {
                        aplicar(li.dataset.id, li.dataset.nombre, li.dataset.icono);
                        cerrar();
                    });
                });
                document.addEventListener('click', e => { if (!wrapper.contains(e.target)) cerrar(); });

                const initId = hidden.value;
                if (initId) {
                    const m = [...opciones].find(li => li.dataset.id === initId);
                    if (m) aplicar(m.dataset.id, m.dataset.nombre, m.dataset.icono);
                }
            })();
        </script>
    </body>
</html>
