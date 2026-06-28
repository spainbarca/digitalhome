<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Editar Pedido</title>
        @vite('resources/css/app.css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
        <style>
            .select2-container--default .select2-selection--single{height:42px;border:1px solid #e5e7eb;border-radius:6px;display:flex;align-items:center;padding:0 10px}
            .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:42px;color:inherit}
            .select2-container--default .select2-selection--single .select2-selection__arrow{height:42px;top:0}
            .dark .select2-container--default .select2-selection--single{background:#0c1427;border-color:#172036;color:#fff}
            .dark .select2-container--default .select2-results__option{background:#0c1427;color:#fff}
            .dark .select2-dropdown{background:#0c1427;border-color:#172036}
            .dark .select2-search--dropdown .select2-search__field{background:#15203c;border-color:#172036;color:#fff}
        </style>
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Editar Pedido</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.pedidos.index') }}" class="hover:text-primary-500 transition-colors">Pedidos</a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Editar</li>
                </ol>
            </div>

            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <form method="POST" action="{{ route('dashboard.pedidos.update', $pedido) }}">
                    @csrf @method('PUT')
                    <div class="space-y-[16px]">

                        {{-- Negocio --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">
                                Negocio <span class="text-danger-500">*</span>
                            </label>
                            <select name="negocio_id" id="pedidoNegocio" required
                                class="select2-negocio h-[42px] rounded-md border {{ $errors->has('negocio_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] block w-full outline-0">
                                <option value="">Selecciona negocio…</option>
                                @foreach($negocios as $neg)
                                <option value="{{ $neg->id }}" {{ old('negocio_id', $pedido->negocio_id) == $neg->id ? 'selected' : '' }}>
                                    {{ $neg->nombre ?? $neg->empresa?->razon_social }}
                                </option>
                                @endforeach
                            </select>
                            @error('negocio_id')<p class="text-xs text-danger-500 mt-[5px]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Campos compartidos --}}
                        @include('dashboard.pedidos._form', ['pedido' => $pedido])

                    </div>

                    <div class="flex items-center justify-between mt-[24px] pt-[16px] border-t border-gray-100 dark:border-[#172036]">
                        <a href="{{ route('dashboard.pedidos.show', $pedido) }}"
                            class="px-[16px] py-[8px] rounded-md border border-gray-200 dark:border-[#172036] text-sm text-gray-500 hover:border-primary-500 hover:text-primary-500 transition-all">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-[20px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function fmtProveedor(o) {
                if (!o.id) return o.text;
                const lo = $(o.element).data('logo');
                const media = lo
                    ? `<img src="${lo}" class="w-[20px] h-[20px] rounded object-cover mr-2 flex-shrink-0" alt="">`
                    : `<i class="material-symbols-outlined !text-[16px] mr-2 flex-shrink-0">local_shipping</i>`;
                return $(`<span class="flex items-center">${media}<span>${o.text}</span></span>`);
            }

            $(function () {
                $('#pedidoNegocio').select2({
                    placeholder: 'Selecciona negocio…',
                    allowClear: true,
                    width: '100%',
                });

                $('#pedidoProveedor').select2({
                    placeholder: 'Selecciona proveedor…',
                    templateResult: fmtProveedor,
                    templateSelection: fmtProveedor,
                    escapeMarkup: m => m,
                    allowClear: true,
                    width: '100%',
                });
            });
        </script>
    </body>
</html>
