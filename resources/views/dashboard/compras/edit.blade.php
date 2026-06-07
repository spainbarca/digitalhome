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
        <title>Editar Compra</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Editar Compra</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.compras.index') }}" class="transition-all hover:text-primary-500">Compras</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.compras.show', $compra) }}" class="transition-all hover:text-primary-500">{{ Str::limit($compra->concepto, 30) }}</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center justify-between -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                    <h5 class="!mb-0">
                        <i class="material-symbols-outlined !text-[20px] align-middle mr-[6px] text-primary-500">edit</i>
                        Editar Compra
                    </h5>
                    <a href="{{ route('dashboard.compras.show', $compra) }}"
                        class="inline-flex items-center gap-[6px] text-sm text-gray-500 hover:text-primary-500 transition-all">
                        <i class="material-symbols-outlined !text-[16px]">visibility</i>
                        Ver compra
                    </a>
                </div>

                <div class="trezo-card-content">
                    <form method="POST" action="{{ route('dashboard.compras.update', $compra) }}">
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
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">receipt_long</i>
                            Datos de la Compra
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[20px] md:gap-[25px]">

                            <!-- Miembro -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Miembro <span class="text-danger-500">*</span>
                                </label>
                                <select name="miembro_id" id="miembro_id" class="w-full">
                                    <option value="">Selecciona miembro...</option>
                                    @foreach($miembros as $m)
                                        @php
                                            $nombreM = trim(($m->user?->persona?->nombres ?? '') . ' ' . ($m->user?->persona?->apellido_paterno ?? '')) ?: $m->apodo ?: '—';
                                            $avatarM = $m->user?->persona?->avatar_url ?? '';
                                        @endphp
                                        <option value="{{ $m->id }}"
                                            data-avatar="{{ $avatarM }}"
                                            {{ old('miembro_id', $compra->miembro_id) == $m->id ? 'selected' : '' }}>
                                            {{ $nombreM }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('miembro_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Comercio -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Comercio</label>
                                <select name="comercio_id" id="comercio_id" class="w-full">
                                    <option value="">Sin comercio</option>
                                    @foreach($comercios as $com)
                                        @php
                                            $logoEf    = $com->logo_efectivo;
                                            $nombreCom = $com->nombre_referencial ?: $com->empresa?->razon_social ?: '—';
                                        @endphp
                                        <option value="{{ $com->id }}"
                                            data-logo="{{ $logoEf ?? '' }}"
                                            data-ini="{{ mb_strtoupper(mb_substr($nombreCom, 0, 1)) }}"
                                            {{ old('comercio_id', $compra->comercio_id) == $com->id ? 'selected' : '' }}>
                                            {{ $nombreCom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('comercio_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Categoría -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Categoría <span class="text-danger-500">*</span>
                                </label>
                                <select name="categoria_compra_id" id="categoria_compra_id" class="w-full">
                                    <option value="">Selecciona categoría...</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}"
                                            data-icono="{{ $cat->icono ?? 'label' }}"
                                            {{ old('categoria_compra_id', $compra->categoria_compra_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_compra_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Método de Pago -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Método de Pago <span class="text-danger-500">*</span>
                                </label>
                                <select name="metodo_pago_id" id="metodo_pago_id" class="w-full">
                                    <option value="">Selecciona método...</option>
                                    @foreach($metodosPago as $mp)
                                        <option value="{{ $mp->id }}"
                                            data-logo="{{ $mp->logo ? asset('storage/' . $mp->logo) : '' }}"
                                            data-icono="{{ $mp->icono ?? 'payment' }}"
                                            {{ old('metodo_pago_id', $compra->metodo_pago_id) == $mp->id ? 'selected' : '' }}>
                                            {{ $mp->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('metodo_pago_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Moneda -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Moneda <span class="text-danger-500">*</span>
                                </label>
                                <select name="moneda_id" id="moneda_id" class="w-full">
                                    <option value="">Selecciona moneda...</option>
                                    @foreach($monedas as $mon)
                                        <option value="{{ $mon->id }}" {{ old('moneda_id', $compra->moneda_id) == $mon->id ? 'selected' : '' }}>
                                            {{ $mon->simbolo }} — {{ $mon->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('moneda_id')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Fecha de compra -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Fecha de Compra <span class="text-danger-500">*</span>
                                </label>
                                <input type="date" name="fecha_compra" value="{{ old('fecha_compra', $compra->fecha_compra?->format('Y-m-d')) }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('fecha_compra') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all focus:border-primary-500">
                                @error('fecha_compra')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Total -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Total <span class="text-danger-500">*</span>
                                </label>
                                <input type="number" name="total" value="{{ old('total', $compra->total) }}" step="0.01" min="0" placeholder="0.00"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('total') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('total')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Concepto -->
                            <div>
                                <label class="mb-[10px] text-black dark:text-white font-medium block">
                                    Concepto <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" name="concepto" value="{{ old('concepto', $compra->concepto) }}" placeholder="Ej: Compra supermercado"
                                    class="h-[55px] rounded-md text-black dark:text-white border {{ $errors->has('concepto') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                @error('concepto')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                            <!-- Notas -->
                            <div class="sm:col-span-2">
                                <label class="mb-[10px] text-black dark:text-white font-medium block">Notas</label>
                                <textarea name="notas" rows="3" placeholder="Información adicional..."
                                    class="rounded-md text-black dark:text-white border {{ $errors->has('notas') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] px-[17px] py-[12px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500 resize-none">{{ old('notas', $compra->notas) }}</textarea>
                                @error('notas')<p class="text-danger-500 text-xs mt-[5px]">{{ $message }}</p>@enderror
                            </div>

                        </div>

                        <div class="mt-[25px] flex justify-end gap-[15px]">
                            <a href="{{ route('dashboard.compras.show', $compra) }}"
                                class="rounded-md inline-block transition-all font-medium px-[26.5px] py-[12px] bg-danger-500 text-white hover:bg-danger-400">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-block bg-primary-500 text-white py-[12px] px-[26.5px] transition-all rounded-md hover:bg-primary-400">
                                <span class="inline-block relative ltr:pl-[25px] rtl:pr-[25px]">
                                    <i class="material-symbols-outlined !text-[20px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">save</i>
                                    Actualizar Compra
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
        $(function () {
            function fmtIcono(opt) {
                if (!opt.id) return opt.text;
                var ico = $(opt.element).data('icono') || 'label';
                return $('<span style="display:flex;align-items:center;gap:8px"><span class="material-symbols-outlined" style="font-size:18px;color:#4f88e4">' + ico + '</span>' + opt.text + '</span>');
            }

            function fmtComercio(opt) {
                if (!opt.id) return opt.text;
                var logo = $(opt.element).data('logo');
                var ini  = $(opt.element).data('ini') || opt.text.charAt(0).toUpperCase();
                if (logo) return $('<span style="display:flex;align-items:center;gap:8px"><img src="' + logo + '" style="width:26px;height:26px;border-radius:50%;object-fit:cover">' + opt.text + '</span>');
                return $('<span style="display:flex;align-items:center;gap:8px"><span style="width:26px;height:26px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700">' + ini + '</span>' + opt.text + '</span>');
            }

            function fmtMiembro(opt) {
                if (!opt.id) return opt.text;
                var av  = $(opt.element).data('avatar');
                var ini = opt.text.charAt(0).toUpperCase();
                if (av) return $('<span style="display:flex;align-items:center;gap:8px"><img src="' + av + '" style="width:26px;height:26px;border-radius:50%;object-fit:cover">' + opt.text + '</span>');
                return $('<span style="display:flex;align-items:center;gap:8px"><span style="width:26px;height:26px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700">' + ini + '</span>' + opt.text + '</span>');
            }

            function formatMetodoPago(opt) {
                if (!opt.id) return opt.text;
                var $opt  = $(opt.element);
                var logo  = $opt.data('logo');
                var icono = $opt.data('icono');
                var $img;
                if (logo) {
                    $img = $('<img>').attr('src', logo).css({ width: '20px', height: '20px', 'object-fit': 'contain', 'margin-right': '8px', 'border-radius': '50%' });
                } else {
                    $img = $('<span class="material-symbols-outlined" style="font-size:20px;margin-right:8px;vertical-align:middle;color:#4f88e4">').text(icono || 'payments');
                }
                var $span = $('<span>').css({ display: 'inline-flex', 'align-items': 'center' });
                $span.append($img).append($('<span>').text(opt.text));
                return $span;
            }

            $('#miembro_id').select2({ placeholder: 'Selecciona miembro...', allowClear: true, width: '100%', templateResult: fmtMiembro, templateSelection: fmtMiembro });
            $('#comercio_id').select2({ placeholder: 'Sin comercio', allowClear: true, width: '100%', templateResult: fmtComercio, templateSelection: fmtComercio });
            $('#categoria_compra_id').select2({ placeholder: 'Selecciona categoría...', allowClear: true, width: '100%', templateResult: fmtIcono, templateSelection: fmtIcono });
            $('#metodo_pago_id').select2({ placeholder: 'Selecciona método...', allowClear: true, width: '100%', templateResult: formatMetodoPago, templateSelection: formatMetodoPago, escapeMarkup: function(m) { return m; } });
            $('#moneda_id').select2({ placeholder: 'Selecciona moneda...', allowClear: true, width: '100%' });
        });
        </script>
    </body>
</html>
