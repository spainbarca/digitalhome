{{--
  Partial compartido: campos del formulario de pedido.
  Variables esperadas:
    $pedido      ?Pedido      — null en create/modal, modelo en edit
    $proveedores Collection   — ProveedorNegocio hogar-scoped
    $monedas     Collection   — todas las monedas
--}}
@php
    $selectedMonedaId = old('moneda_id', $pedido?->moneda_id ?? null);
@endphp

<div>
    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">
        Proveedor <span class="text-danger-500">*</span>
    </label>
    <select name="proveedor_negocio_id" id="pedidoProveedor" required
        class="select2-proveedor h-[42px] rounded-md border {{ $errors->has('proveedor_negocio_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] block w-full outline-0">
        <option value="">Selecciona proveedor…</option>
        @foreach($proveedores as $prov)
        <option value="{{ $prov->id }}"
            data-logo="{{ $prov->logo_resuelto }}"
            data-sigla="{{ $prov->sigla_resuelta }}"
            {{ old('proveedor_negocio_id', $pedido?->proveedor_negocio_id) == $prov->id ? 'selected' : '' }}>
            {{ $prov->sigla_resuelta ? $prov->sigla_resuelta . ' — ' : '' }}{{ $prov->nombre ?? $prov->empresa?->razon_social }}
        </option>
        @endforeach
    </select>
    @error('proveedor_negocio_id')<p class="text-xs text-danger-500 mt-[5px]">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-2 gap-[12px]">
    <div>
        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">N° de pedido / factura</label>
        <input type="text" name="numero" value="{{ old('numero', $pedido?->numero) }}" placeholder="Ej: F001-0001234"
            class="h-[42px] rounded-md border {{ $errors->has('numero') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm">
        @error('numero')<p class="text-xs text-danger-500 mt-[5px]">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Fecha <span class="text-danger-500">*</span></label>
        <input type="date" name="fecha" required value="{{ old('fecha', $pedido?->fecha?->format('Y-m-d')) }}"
            class="h-[42px] rounded-md border {{ $errors->has('fecha') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm">
        @error('fecha')<p class="text-xs text-danger-500 mt-[5px]">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Descripción <span class="text-danger-500">*</span></label>
    <textarea name="descripcion" rows="2" required placeholder="Detalle de los bienes o servicios…"
        class="w-full rounded-md border {{ $errors->has('descripcion') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] py-[10px] text-sm outline-0 focus:border-primary-500">{{ old('descripcion', $pedido?->descripcion) }}</textarea>
    @error('descripcion')<p class="text-xs text-danger-500 mt-[5px]">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-2 gap-[12px]">
    <div>
        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Moneda</label>
        <select name="moneda_id"
            class="h-[42px] rounded-md border {{ $errors->has('moneda_id') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] block w-full outline-0 text-sm text-black dark:text-white px-[14px]">
            <option value="">— moneda —</option>
            @foreach($monedas as $mon)
            <option value="{{ $mon->id }}" {{ ($selectedMonedaId ? $selectedMonedaId == $mon->id : $mon->moneda_local) ? 'selected' : '' }}>
                {{ $mon->simbolo }} {{ $mon->codigo }}
            </option>
            @endforeach
        </select>
        @error('moneda_id')<p class="text-xs text-danger-500 mt-[5px]">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Total</label>
        <input type="number" name="total" step="0.01" min="0" value="{{ old('total', $pedido?->total) }}" placeholder="0.00"
            class="h-[42px] rounded-md border {{ $errors->has('total') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] block w-full outline-0 focus:border-primary-500 text-sm">
        @error('total')<p class="text-xs text-danger-500 mt-[5px]">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-[6px]">Observaciones</label>
    <textarea name="observaciones" rows="2"
        class="w-full rounded-md border {{ $errors->has('observaciones') ? 'border-danger-500' : 'border-gray-200 dark:border-[#172036]' }} bg-white dark:bg-[#0c1427] text-black dark:text-white px-[14px] py-[10px] text-sm outline-0 focus:border-primary-500">{{ old('observaciones', $pedido?->observaciones) }}</textarea>
    @error('observaciones')<p class="text-xs text-danger-500 mt-[5px]">{{ $message }}</p>@enderror
</div>
