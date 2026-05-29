<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('partials.front.styles')

        <title>Nueva Propiedad</title>

        @vite('resources/css/app.css')

    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Nueva Propiedad</h5>
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
                        Nueva
                    </li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center justify-between -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0">Datos de la Propiedad</h5>
                    </div>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.propiedades.store') }}" enctype="multipart/form-data">
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

                            <!-- Alias -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Alias <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="alias" value="{{ old('alias') }}"
                                    placeholder="Ej: Casa principal, Dpto. Miraflores"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('alias') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('alias')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Tipo de Inmueble -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Tipo de Inmueble <span class="text-danger-500">*</span>
                                </label>
                                <select name="tipo_inmueble_id"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('tipo_inmueble_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                    <option value="">Selecciona tipo...</option>
                                    @foreach($tiposInmueble as $tipo)
                                        <option value="{{ $tipo->id }}" {{ old('tipo_inmueble_id') == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_inmueble_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Dirección -->
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Dirección <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="direccion" value="{{ old('direccion') }}"
                                    placeholder="Ej: Av. Los Alamos 123, Piso 4"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('direccion') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('direccion')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Referencia -->
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Referencia
                                </label>
                                <input type="text" name="referencia" value="{{ old('referencia') }}"
                                    placeholder="Ej: Frente al parque central"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('referencia') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('referencia')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Ubicación toggle -->
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Ubicación <span class="text-danger-500">*</span>
                                </label>
                                <div class="flex items-center gap-[25px]">
                                    <label class="flex items-center gap-[8px] cursor-pointer">
                                        <input type="radio" name="ubicacion_tipo" value="peru" id="loc_peru"
                                            {{ old('ubicacion_tipo', 'peru') === 'peru' ? 'checked' : '' }}
                                            class="cursor-pointer accent-primary-500 w-[16px] h-[16px]">
                                        <span class="text-black dark:text-white">
                                            <i class="ri-map-pin-line text-primary-500 mr-[2px]"></i>
                                            Perú
                                        </span>
                                    </label>
                                    <label class="flex items-center gap-[8px] cursor-pointer">
                                        <input type="radio" name="ubicacion_tipo" value="extranjero" id="loc_ext"
                                            {{ old('ubicacion_tipo') === 'extranjero' ? 'checked' : '' }}
                                            class="cursor-pointer accent-primary-500 w-[16px] h-[16px]">
                                        <span class="text-black dark:text-white">
                                            <i class="ri-global-line text-primary-500 mr-[2px]"></i>
                                            Extranjero
                                        </span>
                                    </label>
                                </div>
                                @error('ubicacion_tipo')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Sección Perú -->
                            <div id="section_peru" class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-[20px] md:gap-[25px]">
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Departamento</label>
                                    <select id="departamento_id"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                        <option value="">Selecciona departamento...</option>
                                        @foreach($departamentos as $dep)
                                            <option value="{{ $dep->inei }}">{{ $dep->departamento }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Provincia</label>
                                    <select id="provincia_id"
                                        class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                        <option value="">Selecciona provincia...</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        Distrito <span class="text-danger-500">*</span>
                                    </label>
                                    <select name="distrito_inei" id="distrito_inei"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('distrito_inei') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                        <option value="">Selecciona distrito...</option>
                                    </select>
                                    @error('distrito_inei')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <!-- Sección Extranjero -->
                            <div id="section_extranjero" class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]" style="display:none">
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">
                                        País <span class="text-danger-500">*</span>
                                    </label>
                                    <select name="pais_iso2" id="pais_iso2"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('pais_iso2') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                        <option value="">Selecciona país...</option>
                                        @foreach($paises as $pais)
                                            <option value="{{ $pais->iso2 }}" {{ old('pais_iso2') == $pais->iso2 ? 'selected' : '' }}>
                                                {{ $pais->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pais_iso2')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-[10px] text-black dark:text-white font-medium block">Ciudad</label>
                                    <select name="ciudad_id" id="ciudad_id"
                                        class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('ciudad_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[14px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                        <option value="">Selecciona ciudad...</option>
                                    </select>
                                    @error('ciudad_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <!-- Avatar -->
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Imagen de la Propiedad
                                </label>
                                <div class="relative flex items-center justify-center overflow-hidden rounded-md py-[48px] px-[20px] border border-gray-200 dark:border-[#172036]">
                                    <div class="flex items-center justify-center">
                                        <div class="w-[35px] h-[35px] border border-gray-100 dark:border-[#15203c] flex items-center justify-center rounded-md text-primary-500 text-lg ltr:mr-[12px] rtl:ml-[12px]">
                                            <i class="ri-upload-2-line"></i>
                                        </div>
                                        <p class="!leading-[1.5]">
                                            <strong class="text-black dark:text-white">Click para subir imagen</strong><br>
                                            <span class="text-xs text-gray-500">JPG, PNG, WEBP (máx. 2MB)</span>
                                        </p>
                                    </div>
                                    <input type="file" name="avatar" accept="image/*" id="avatarInput"
                                        class="absolute top-0 left-0 right-0 bottom-0 rounded-md z-[1] opacity-0 cursor-pointer">
                                </div>
                                <div id="avatarPreview" class="mt-[10px] hidden">
                                    <img id="avatarPreviewImg" src="#" alt="Vista previa" class="h-[80px] w-[80px] object-cover rounded-md border border-gray-200 dark:border-[#172036]">
                                </div>
                                @error('avatar')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Activo -->
                            <div class="sm:col-span-2">
                                <label class="flex items-center gap-[10px] cursor-pointer">
                                    <input type="hidden" name="activo" value="0">
                                    <input type="checkbox" name="activo" value="1" id="activo"
                                        {{ old('activo', true) ? 'checked' : '' }}
                                        class="cursor-pointer accent-primary-500 w-[16px] h-[16px]">
                                    <span class="text-black dark:text-white font-medium">Propiedad activa</span>
                                </label>
                            </div>

                        </div>

                        <div class="mt-[20px] md:mt-[25px] ltr:text-right rtl:text-left flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.propiedades.index') }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">add</i>
                                    Guardar Propiedad
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
            // Datos para los selectores en cascada
            const allProvincias = @json($provincias->map(fn($p) => ['inei' => $p->inei, 'nombre' => $p->provincia, 'departamento_inei' => $p->departamento_inei]));
            const allDistritos  = @json($distritos->map(fn($d) => ['inei' => $d->inei, 'nombre' => $d->distrito, 'provincia_inei' => $d->provincia_inei]));
            const allCiudades   = @json($ciudades->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre, 'pais_iso2' => $c->pais_iso2]));

            const oldDistritoInei = "{{ old('distrito_inei') }}";
            const oldPaisIso2     = "{{ old('pais_iso2') }}";
            const oldCiudadId     = "{{ old('ciudad_id') }}";

            // Toggle Perú / Extranjero
            function toggleUbicacion() {
                const isPeru = document.getElementById('loc_peru').checked;
                document.getElementById('section_peru').style.display      = isPeru ? '' : 'none';
                document.getElementById('section_extranjero').style.display = isPeru ? 'none' : '';
            }

            document.getElementById('loc_peru').addEventListener('change', toggleUbicacion);
            document.getElementById('loc_ext').addEventListener('change', toggleUbicacion);
            toggleUbicacion();

            // Cascada: Departamento → Provincia
            document.getElementById('departamento_id').addEventListener('change', function () {
                const deptoInei = this.value;
                const provSel   = document.getElementById('provincia_id');
                const distSel   = document.getElementById('distrito_inei');

                provSel.innerHTML = '<option value="">Selecciona provincia...</option>';
                distSel.innerHTML = '<option value="">Selecciona distrito...</option>';

                allProvincias
                    .filter(p => p.departamento_inei === deptoInei)
                    .forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.inei;
                        opt.textContent = p.nombre;
                        provSel.appendChild(opt);
                    });
            });

            // Cascada: Provincia → Distrito
            document.getElementById('provincia_id').addEventListener('change', function () {
                const provInei = this.value;
                const distSel  = document.getElementById('distrito_inei');

                distSel.innerHTML = '<option value="">Selecciona distrito...</option>';

                allDistritos
                    .filter(d => d.provincia_inei === provInei)
                    .forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.inei;
                        opt.textContent = d.nombre;
                        if (d.inei === oldDistritoInei) opt.selected = true;
                        distSel.appendChild(opt);
                    });
            });

            // Cascada: País → Ciudad
            document.getElementById('pais_iso2').addEventListener('change', function () {
                const iso2     = this.value;
                const ciudadSel = document.getElementById('ciudad_id');

                ciudadSel.innerHTML = '<option value="">Selecciona ciudad...</option>';

                allCiudades
                    .filter(c => c.pais_iso2 === iso2)
                    .forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.textContent = c.nombre;
                        if (String(c.id) === oldCiudadId) opt.selected = true;
                        ciudadSel.appendChild(opt);
                    });
            });

            // Vista previa de imagen
            document.getElementById('avatarInput').addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const preview = document.getElementById('avatarPreview');
                    const img     = document.getElementById('avatarPreviewImg');
                    img.src = URL.createObjectURL(file);
                    preview.classList.remove('hidden');
                }
            });
        </script>
    </body>
</html>
