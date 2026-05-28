<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Personas del Hogar</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Personas del Hogar</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Personas
                    </li>
                </ol>
            </div>

            <!-- Flash -->
            @if (session('success'))
                <div class="mb-[25px] flex items-center gap-[12px] px-[20px] py-[14px] rounded-md bg-success-50 dark:bg-[#0c1427] text-success-600 border border-success-100 dark:border-[#172036]">
                    <i class="material-symbols-outlined !text-xl flex-shrink-0">check_circle</i>
                    <p class="!mb-0">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Personas -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                    <div class="trezo-card-title">
                        <form method="GET" action="{{ route('dashboard.personas.index') }}" class="relative sm:w-[265px]">
                            <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]">search</i>
                            </label>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar persona..." class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </form>
                    </div>
                    <div class="mt-[15px] sm:mt-0">
                        <a href="{{ route('dashboard.personas.create') }}" class="font-medium inline-block transition-all rounded-md py-[8px] px-[16px] bg-primary-500 text-white hover:bg-primary-400 text-sm">
                            <span class="inline-block relative ltr:pl-[24px] rtl:pr-[24px]">
                                <i class="material-symbols-outlined ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 !text-lg">person_add</i>
                                Nueva Persona
                            </span>
                        </a>
                    </div>
                </div>
                <div class="trezo-card-content">
                    <div class="table-responsive overflow-x-auto">
                        <table class="w-full">
                            <thead class="text-black dark:text-white">
                                <tr>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                        Nombre
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                        Documento
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                        Email
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                        Teléfono
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                        F. Nacimiento
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                        Sexo
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                        Estado
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-black dark:text-white">
                                @forelse ($personas as $persona)
                                    @php
                                        $sexoMap = ['M' => ['label' => 'Masculino', 'cls' => 'bg-primary-100 dark:bg-[#15203c] text-primary-600'], 'F' => ['label' => 'Femenino', 'cls' => 'bg-warning-50 text-warning-700'], 'otro' => ['label' => 'Otro', 'cls' => 'bg-gray-100 dark:bg-[#15203c] text-gray-600']];
                                        $sexo = $sexoMap[$persona->sexo] ?? null;
                                    @endphp
                                    <tr>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                            <div class="flex items-center">
                                                <div class="w-[40px] h-[40px] rounded-full bg-primary-50 dark:bg-[#15203c] flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                    @if ($persona->avatar_url)
                                                        <img src="{{ $persona->avatar_url }}" class="w-full h-full object-cover rounded-full" alt="{{ $persona->nombres }}">
                                                    @else
                                                        <span class="text-primary-500 font-bold text-sm">{{ strtoupper(substr($persona->nombres, 0, 1)) }}</span>
                                                    @endif
                                                </div>
                                                <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span class="block font-medium">{{ $persona->nombres }}</span>
                                                    <span class="text-gray-500 dark:text-gray-400 text-xs">{{ trim($persona->apellido_paterno . ' ' . $persona->apellido_materno) ?: '–' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-gray-500 dark:text-gray-400 ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                            @if ($persona->tipoDocumento)
                                                <span class="text-xs text-gray-400 dark:text-gray-500 block">{{ $persona->tipoDocumento->nombre ?? $persona->tipoDocumento->abreviatura ?? '–' }}</span>
                                            @endif
                                            {{ $persona->numero_documento ?? '–' }}
                                        </td>
                                        <td class="text-gray-500 dark:text-gray-400 ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                            {{ $persona->email ?? '–' }}
                                        </td>
                                        <td class="text-gray-500 dark:text-gray-400 ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                            {{ $persona->telefono ?? '–' }}
                                        </td>
                                        <td class="text-gray-500 dark:text-gray-400 ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                            {{ $persona->fecha_nacimiento ? $persona->fecha_nacimiento->format('d M Y') : '–' }}
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                            @if ($sexo)
                                                <span class="px-[8px] py-[3px] inline-block {{ $sexo['cls'] }} rounded-sm font-medium text-xs">{{ $sexo['label'] }}</span>
                                            @else
                                                <span class="text-gray-400">–</span>
                                            @endif
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                            @if ($persona->activo)
                                                <span class="px-[8px] py-[3px] inline-block bg-success-100 dark:bg-[#15203c] text-success-600 rounded-sm font-medium text-xs">Activo</span>
                                            @else
                                                <span class="px-[8px] py-[3px] inline-block bg-danger-100 dark:bg-[#15203c] text-danger-600 rounded-sm font-medium text-xs">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                            <div class="flex items-center gap-[9px]">
                                                <a href="{{ route('dashboard.personas.show', $persona) }}" class="text-primary-500 leading-none custom-tooltip" data-text="Ver">
                                                    <i class="material-symbols-outlined !text-md">visibility</i>
                                                </a>
                                                <a href="{{ route('dashboard.personas.edit', $persona) }}" class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip" data-text="Editar">
                                                    <i class="material-symbols-outlined !text-md">edit</i>
                                                </a>
                                                <form method="POST" action="{{ route('dashboard.personas.destroy', $persona) }}" class="inline" onsubmit="return confirm('¿Eliminar esta persona? Esta acción no se puede deshacer.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-danger-500 leading-none custom-tooltip" data-text="Eliminar">
                                                        <i class="material-symbols-outlined !text-md">delete</i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-[20px] py-[40px] text-center border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r">
                                            <div class="flex flex-col items-center gap-[12px]">
                                                <i class="material-symbols-outlined text-gray-300 dark:text-gray-600" style="font-size:48px">group</i>
                                                <p class="!mb-0 text-gray-500 dark:text-gray-400">No se encontraron personas en este hogar.</p>
                                                <a href="{{ route('dashboard.personas.create') }}" class="text-primary-500 text-sm hover:underline">Agregar la primera persona</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($personas->total() > 0)
                        <div class="px-[20px] py-[12px] md:py-[14px] rounded-b-md border-l border-r border-b border-gray-100 dark:border-[#172036] sm:flex sm:items-center justify-between">
                            <p class="!mb-0 text-sm">
                                Mostrando {{ $personas->firstItem() }}–{{ $personas->lastItem() }} de {{ $personas->total() }} personas
                            </p>
                            <ol class="mt-[10px] sm:mt-0">
                                <li class="inline-block mx-[1px]">
                                    @if ($personas->onFirstPage())
                                        <span class="w-[31px] h-[31px] flex items-center justify-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                            <i class="material-symbols-outlined !text-base">chevron_left</i>
                                        </span>
                                    @else
                                        <a href="{{ $personas->previousPageUrl() }}" class="w-[31px] h-[31px] flex items-center justify-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            <i class="material-symbols-outlined !text-base">chevron_left</i>
                                        </a>
                                    @endif
                                </li>
                                @foreach ($personas->getUrlRange(1, $personas->lastPage()) as $page => $url)
                                    <li class="inline-block mx-[1px]">
                                        <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] text-center rounded-md border text-sm {{ $page == $personas->currentPage() ? 'border-primary-500 bg-primary-500 text-white' : 'border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500' }}">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endforeach
                                <li class="inline-block mx-[1px]">
                                    @if ($personas->hasMorePages())
                                        <a href="{{ $personas->nextPageUrl() }}" class="w-[31px] h-[31px] flex items-center justify-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            <i class="material-symbols-outlined !text-base">chevron_right</i>
                                        </a>
                                    @else
                                        <span class="w-[31px] h-[31px] flex items-center justify-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                            <i class="material-symbols-outlined !text-base">chevron_right</i>
                                        </span>
                                    @endif
                                </li>
                            </ol>
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
