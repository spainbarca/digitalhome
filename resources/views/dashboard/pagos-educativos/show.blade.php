<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Detalle de Pago Educativo</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Detalle de Pago Educativo</h5>
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
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">Detalle</li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] bg-success-100 border border-success-400 text-success-700 px-[20px] py-[12px] rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()"><i class="material-symbols-outlined !text-lg">close</i></button>
            </div>
            @endif

            @php
                $fileUrl  = $pago->comprobante_path ? Storage::url($pago->comprobante_path) : null;
                $isImage  = $fileUrl && str_starts_with($pago->comprobante_mime ?? '', 'image/');
                $isPdf    = $fileUrl && $pago->comprobante_mime === 'application/pdf';
                $fileSize = $pago->comprobante_size
                    ? ($pago->comprobante_size >= 1024*1024
                        ? round($pago->comprobante_size / (1024*1024), 2) . ' MB'
                        : round($pago->comprobante_size / 1024, 0) . ' KB')
                    : null;
                $miembroShow = $pago->matricula?->hogarMiembro;
                $nombreMiembroShow = trim(implode(' ', array_filter([
                    $miembroShow?->user?->persona?->nombres,
                    $miembroShow?->user?->persona?->apellido_paterno,
                    $miembroShow?->user?->persona?->apellido_materno,
                ]))) ?: ($miembroShow?->user?->name ?? '—');
                $estadoClass = match($pago->estado) {
                    'pagado'  => 'bg-success-100 text-success-700 border border-success-300',
                    'vencido' => 'bg-danger-100 text-danger-700 border border-danger-300',
                    default   => 'bg-warning-100 text-warning-700 border border-warning-300',
                };
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-[25px] mb-[25px]">

                {{-- Columna izquierda: comprobante --}}
                <div class="lg:col-span-2 flex flex-col gap-[25px]">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">receipt</i>
                            Comprobante
                        </h6>

                        @if($fileUrl)
                            @if($isImage)
                                <img src="{{ $fileUrl }}" alt="Comprobante"
                                    class="w-full h-auto max-h-[300px] object-contain rounded-md border border-gray-100 dark:border-[#172036] mb-[12px]">
                                <a href="{{ $fileUrl }}" target="_blank" rel="noopener"
                                    class="inline-flex items-center gap-[6px] text-sm font-medium text-primary-500 hover:underline">
                                    <i class="material-symbols-outlined !text-[16px]">open_in_new</i>
                                    Ver imagen completa
                                </a>
                            @elseif($isPdf)
                                <iframe src="{{ $fileUrl }}" class="w-full h-[350px] rounded-md border border-gray-100 dark:border-[#172036] mb-[12px]"></iframe>
                                <a href="{{ $fileUrl }}" target="_blank" rel="noopener"
                                    class="inline-flex items-center gap-[6px] text-sm font-medium text-primary-500 hover:underline">
                                    <i class="material-symbols-outlined !text-[16px]">open_in_new</i>
                                    Abrir PDF en nueva pestaña
                                </a>
                            @else
                                <div class="flex items-center gap-[12px] p-[16px] rounded-md bg-gray-50 dark:bg-[#15203c] border border-gray-100 dark:border-[#172036] mb-[12px]">
                                    <i class="material-symbols-outlined !text-[32px] text-primary-500">attach_file</i>
                                    <div>
                                        <span class="block text-sm font-medium text-black dark:text-white">{{ $pago->comprobante_nombre_original ?? basename($pago->comprobante_path) }}</span>
                                        @if($fileSize)<span class="block text-xs text-gray-400 mt-[2px]">{{ $fileSize }}</span>@endif
                                    </div>
                                </div>
                                <a href="{{ $fileUrl }}" download="{{ $pago->comprobante_nombre_original ?? 'comprobante' }}"
                                    class="inline-flex items-center gap-[6px] px-[14px] py-[8px] rounded-md bg-primary-500 text-white text-sm font-medium hover:bg-primary-400 transition-all">
                                    <i class="material-symbols-outlined !text-[16px]">download</i>
                                    Descargar comprobante
                                </a>
                            @endif
                        @else
                            <div class="text-center py-[30px]">
                                <i class="material-symbols-outlined !text-[48px] text-gray-300 dark:text-gray-600 block mb-[8px]">receipt_long</i>
                                <p class="text-sm text-gray-400">Sin comprobante adjunto.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Columna derecha: datos --}}
                <div class="lg:col-span-3 flex flex-col gap-[25px]">

                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[16px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">payments</i>
                            Datos del Pago
                        </h6>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-[16px]">
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Institución</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $pago->matricula?->institucionEducativa?->nombre_referencial ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Estudiante</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $nombreMiembroShow }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Concepto</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $pago->concepto ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Mes correspondiente</span>
                                <span class="block text-sm font-medium text-black dark:text-white font-mono">{{ $pago->mes_correspondiente ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Monto</span>
                                <span class="block text-lg font-bold text-black dark:text-white">
                                    {{ $pago->moneda?->simbolo ?? 'S/' }} {{ number_format($pago->monto, 2) }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Estado</span>
                                <span class="inline-block text-[12px] font-medium px-[10px] py-[3px] rounded-[100px] {{ $estadoClass }}">
                                    {{ ucfirst($pago->estado) }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Fecha de vencimiento</span>
                                <span class="block text-sm font-medium text-black dark:text-white font-mono">{{ $pago->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Fecha de pago</span>
                                <span class="block text-sm font-medium text-black dark:text-white font-mono">{{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}</span>
                            </div>
                            @if($fileSize)
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mb-[4px]">Tamaño comprobante</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $fileSize }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="mt-[16px] pt-[16px] border-t border-gray-100 dark:border-[#172036] flex gap-[10px] flex-wrap">
                            <a href="{{ route('dashboard.pagos-educativos.edit', $pago) }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                                <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">edit</i>
                                    Editar
                                </span>
                            </a>
                            @if($fileUrl)
                            <a href="{{ $fileUrl }}" download="{{ $pago->comprobante_nombre_original ?? 'comprobante' }}" target="_blank"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white text-sm">
                                <span class="inline-block relative ltr:pl-[20px] rtl:pr-[20px]">
                                    <i class="material-symbols-outlined !text-[16px] absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2">download</i>
                                    Descargar
                                </span>
                            </a>
                            @endif
                            <a href="{{ route('dashboard.pagos-educativos.index') }}"
                                class="inline-block transition-all rounded-md font-medium px-[13px] py-[7px] border border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#15203c] text-sm">
                                Volver
                            </a>
                        </div>
                    </div>

                    {{-- Matrícula vinculada --}}
                    @if($pago->matricula)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[14px] pb-[10px] border-b border-gray-100 dark:border-[#172036] flex items-center gap-[8px] text-sm">
                            <i class="material-symbols-outlined !text-[16px] text-primary-500">school</i>
                            Matrícula Vinculada
                        </h6>
                        <div class="grid grid-cols-2 gap-[12px]">
                            <div>
                                <span class="block text-xs text-gray-400 mb-[2px]">Año lectivo</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $pago->matricula->año_lectivo ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-400 mb-[2px]">Grado / Ciclo</span>
                                <span class="block text-sm font-medium text-black dark:text-white">{{ $pago->matricula->grado_ciclo ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="pt-[10px] mt-[10px] border-t border-gray-100 dark:border-[#172036]">
                            <a href="{{ route('dashboard.matriculas.show', $pago->matricula) }}"
                                class="text-sm text-primary-500 hover:underline flex items-center gap-[4px]">
                                <i class="material-symbols-outlined !text-[16px]">open_in_new</i>
                                Ver detalle de la matrícula
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- Notas --}}
                    @if($pago->notas)
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <h6 class="font-semibold text-black dark:text-white mb-[12px] flex items-center gap-[8px]">
                            <i class="material-symbols-outlined !text-[18px] text-primary-500">notes</i>
                            Notas
                        </h6>
                        <p class="text-sm leading-relaxed whitespace-pre-line text-gray-600 dark:text-gray-400">{{ $pago->notas }}</p>
                    </div>
                    @endif

                </div>
            </div>

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
