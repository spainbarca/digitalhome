<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('partials.front.styles')

        <title>Nueva Cuenta de Servicio — {{ $propiedad->alias }}</title>

        @vite('resources/css/app.css')

    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nueva Cuenta de Servicio</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.propiedades.index') }}" class="transition-all hover:text-primary-500">Propiedades</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.propiedades.show', $propiedad) }}" class="transition-all hover:text-primary-500">{{ $propiedad->alias }}</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.propiedades.cuentas.index', $propiedad) }}" class="transition-all hover:text-primary-500">Cuentas de Servicio</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Nueva
                    </li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center justify-between -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0">Datos de la Cuenta de Servicio</h5>
                    </div>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.propiedades.cuentas.store', $propiedad) }}">
                        @csrf

                        @if($errors->any())
                        <div class="mb-[20px] bg-danger-100 border border-danger-400 text-danger-700 px-[20px] py-[12px] rounded-md">
                            <ul class="list-disc ltr:pl-[20px] rtl:pr-[20px] text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                            <!-- Tipo de Servicio (custom select) -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Tipo de Servicio
                                </label>
                                <div class="relative" id="tipoServicioWrapper">
                                    <!-- Trigger -->
                                    <div id="tipoServicioTrigger"
                                        class="h-[55px] flex items-center rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] cursor-pointer select-none transition-all hover:border-primary-500">
                                        <i class="material-symbols-outlined !text-[20px] mr-[8px] text-gray-400" id="tipoServicioIconPreview">category</i>
                                        <span id="tipoServicioLabel" class="text-gray-500 dark:text-gray-400 text-sm flex-1 truncate">Todos los tipos...</span>
                                        <i class="material-symbols-outlined !text-[20px] text-gray-400 transition-transform duration-200" id="tipoServicioChevron">expand_more</i>
                                    </div>
                                    <!-- Valor real (filter, no se valida en servidor) -->
                                    <input type="hidden" name="tipo_servicio_id" id="tipo_servicio_id"
                                        value="{{ old('tipo_servicio_id', '') }}">
                                    <!-- Dropdown -->
                                    <div id="tipoServicioDropdown"
                                        class="hidden absolute z-[50] w-full bg-white dark:bg-[#0c1427] border border-gray-200 dark:border-[#172036] rounded-md shadow-lg mt-[4px]">
                                        <div class="p-[8px] border-b border-gray-100 dark:border-[#172036]">
                                            <input type="text" id="tipoServicioBuscar" placeholder="Buscar tipo..."
                                                class="w-full px-[10px] py-[6px] text-sm border border-gray-200 dark:border-[#172036] rounded-md bg-white dark:bg-[#0c1427] text-black dark:text-white outline-0 focus:border-primary-500 placeholder:text-gray-400">
                                        </div>
                                        <ul id="tipoServicioOpciones" class="max-h-[210px] overflow-y-auto py-[4px]">
                                            <li class="tipo-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="" data-nombre="Todos los tipos" data-icono="category">
                                                <i class="material-symbols-outlined !text-[18px] text-gray-400">category</i>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">Todos los tipos</span>
                                            </li>
                                            @foreach($tiposServicio as $tipo)
                                            <li class="tipo-opcion flex items-center gap-[8px] px-[12px] py-[9px] cursor-pointer hover:bg-primary-50 dark:hover:bg-[#15203c] transition-colors"
                                                data-id="{{ $tipo->id }}"
                                                data-nombre="{{ $tipo->nombre }}"
                                                data-icono="{{ $tipo->icono ?? 'category' }}">
                                                <i class="material-symbols-outlined !text-[18px] text-primary-500">{{ $tipo->icono ?? 'category' }}</i>
                                                <span class="text-sm text-black dark:text-white">{{ $tipo->nombre }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Proveedor -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Proveedor <span class="text-danger-500">*</span>
                                </label>
                                <select name="proveedor_id" id="proveedor_id"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('proveedor_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                    <option value="">Selecciona proveedor...</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}"
                                            data-tipo="{{ $proveedor->tipo_servicio_id }}"
                                            {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                            {{ $proveedor->nombre_comercial ?? '(sin nombre)' }}
                                            @if($proveedor->tipoServicio) — {{ $proveedor->tipoServicio->nombre }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('proveedor_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Número de cuenta / suministro -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Número de Cuenta / Suministro <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="numero_cuenta" value="{{ old('numero_cuenta') }}"
                                    placeholder="Ej: 1234567890"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('numero_cuenta') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('numero_cuenta')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Titular -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Titular del Contrato
                                </label>
                                <select name="titular_user_id"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('titular_user_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                    <option value="">Sin titular asignado</option>
                                    @foreach($hogarUsers as $u)
                                        <option value="{{ $u->id }}" {{ old('titular_user_id') == $u->id ? 'selected' : '' }}>
                                            {{ trim(($u->persona?->nombres ?? '') . ' ' . ($u->persona?->apellido_paterno ?? '')) ?: $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('titular_user_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Estado -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Estado <span class="text-danger-500">*</span>
                                </label>
                                <select name="estado"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('estado') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                    <option value="activa"      {{ old('estado', 'activa') === 'activa'      ? 'selected' : '' }}>Activa</option>
                                    <option value="suspendida"  {{ old('estado') === 'suspendida'  ? 'selected' : '' }}>Suspendida</option>
                                    <option value="cancelada"   {{ old('estado') === 'cancelada'   ? 'selected' : '' }}>Cancelada</option>
                                </select>
                                @error('estado')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Fecha de inicio -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Fecha de Inicio
                                </label>
                                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_inicio') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_inicio')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Notas -->
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Notas
                                </label>
                                <textarea name="notas" rows="3"
                                    placeholder="Información adicional sobre esta cuenta..."
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[12px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('notas') }}</textarea>
                                @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                        </div>

                        <div class="mt-[20px] md:mt-[25px] ltr:text-right rtl:text-left flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.propiedades.cuentas.index', $propiedad) }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Guardar Cuenta
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
            // ── Custom select de Tipo de Servicio ─────────────────────────
            (function () {
                const wrapper  = document.getElementById('tipoServicioWrapper');
                const trigger  = document.getElementById('tipoServicioTrigger');
                const dropdown = document.getElementById('tipoServicioDropdown');
                const chevron  = document.getElementById('tipoServicioChevron');
                const buscar   = document.getElementById('tipoServicioBuscar');
                const hidden   = document.getElementById('tipo_servicio_id');
                const label    = document.getElementById('tipoServicioLabel');
                const iconPrev = document.getElementById('tipoServicioIconPreview');
                const opciones = document.querySelectorAll('.tipo-opcion');

                function abrirDropdown() {
                    dropdown.classList.remove('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                    buscar.value = '';
                    opciones.forEach(li => li.style.display = '');
                    setTimeout(() => buscar.focus(), 50);
                }

                function cerrarDropdown() {
                    dropdown.classList.add('hidden');
                    chevron.style.transform = '';
                }

                function aplicarSeleccion(id, nombre, icono) {
                    hidden.value = id;
                    if (id) {
                        label.textContent = nombre;
                        label.className   = 'text-black dark:text-white text-sm flex-1 truncate';
                        iconPrev.textContent = icono;
                        iconPrev.className   = 'material-symbols-outlined !text-[20px] mr-[8px] text-primary-500';
                    } else {
                        label.textContent = 'Todos los tipos...';
                        label.className   = 'text-gray-500 dark:text-gray-400 text-sm flex-1 truncate';
                        iconPrev.textContent = 'category';
                        iconPrev.className   = 'material-symbols-outlined !text-[20px] mr-[8px] text-gray-400';
                    }
                }

                trigger.addEventListener('click', () => {
                    dropdown.classList.contains('hidden') ? abrirDropdown() : cerrarDropdown();
                });

                buscar.addEventListener('input', () => {
                    const q = buscar.value.toLowerCase();
                    opciones.forEach(li => {
                        li.style.display = li.dataset.nombre.toLowerCase().includes(q) ? '' : 'none';
                    });
                });

                opciones.forEach(li => {
                    li.addEventListener('click', () => {
                        aplicarSeleccion(li.dataset.id, li.dataset.nombre, li.dataset.icono);
                        cerrarDropdown();
                        filtrarProveedores();
                    });
                });

                document.addEventListener('click', e => {
                    if (!wrapper.contains(e.target)) cerrarDropdown();
                });

                // Pre-seleccionar si hay valor inicial (old())
                const initId = hidden.value;
                if (initId) {
                    const m = [...opciones].find(li => li.dataset.id === initId);
                    if (m) aplicarSeleccion(m.dataset.id, m.dataset.nombre, m.dataset.icono);
                }
            })();

            // ── Filtrado de proveedores ────────────────────────────────────
            const todosProveedores = @json($proveedoresJson);
            const oldProveedorId   = "{{ old('proveedor_id') }}";

            function filtrarProveedores() {
                const tipoId  = document.getElementById('tipo_servicio_id').value;
                const select  = document.getElementById('proveedor_id');
                const prevVal = select.value;

                select.innerHTML = '<option value="">Selecciona proveedor...</option>';

                todosProveedores
                    .filter(p => !tipoId || p.tipo === tipoId)
                    .forEach(p => {
                        const opt = document.createElement('option');
                        opt.value       = p.id;
                        opt.textContent = p.nombre + (p.tipo_nombre ? ' — ' + p.tipo_nombre : '');
                        if (p.id === prevVal || p.id === oldProveedorId) opt.selected = true;
                        select.appendChild(opt);
                    });
            }

            // Filtrar en carga si hay tipo prellenado
            if (document.getElementById('tipo_servicio_id').value) {
                filtrarProveedores();
            }
        </script>
    </body>
</html>
