<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.front.styles')
        <title>Miembros del Hogar</title>
        @vite('resources/css/app.css')
    </head>
    <body>
        @include('partials.dashboard.sidebar')
        @include('partials.dashboard.header')

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

            <!-- Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="!mb-0">Miembros del Hogar</h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        <a href="{{ route('dashboard.') }}" class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] transition-all hover:text-primary-500">
                            <i class="material-symbols-outlined absolute ltr:left-0 rtl:right-0 !text-lg -mt-px text-primary-500 top-1/2 -translate-y-1/2">home</i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item inline-block relative text-sm mx-[11px] ltr:first:ml-0 rtl:first:mr-0 ltr:last:mr-0 rtl:last:ml-0">
                        Miembros
                    </li>
                </ol>
            </div>

            @if(session('success'))
            <div class="mb-[25px] flex items-center gap-[12px] px-[20px] py-[14px] rounded-md bg-success-50 dark:bg-[#0c1427] text-success-600 border border-success-100 dark:border-[#172036]">
                <i class="material-symbols-outlined !text-xl flex-shrink-0">check_circle</i>
                <p class="!mb-0">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-[25px] flex items-center gap-[12px] px-[20px] py-[14px] rounded-md bg-danger-50 dark:bg-[#0c1427] text-danger-600 border border-danger-100 dark:border-[#172036]">
                <i class="material-symbols-outlined !text-xl flex-shrink-0">error</i>
                <p class="!mb-0">{{ session('error') }}</p>
            </div>
            @endif

            @if($hogares->count() > 1)
            <!-- Selector de hogar (solo cuando hay más de uno) -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-[12px] uppercase tracking-wide">Filtrar por hogar</p>
                <div class="flex flex-wrap gap-[8px]">
                    <a href="{{ route('dashboard.miembros.index', $search ? ['search' => $search] : []) }}"
                       class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-[8px] border text-sm font-medium transition-all
                           {{ !$hogarSeleccionado
                               ? 'bg-primary-500 border-primary-500 text-white'
                               : 'border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500' }}">
                        <i class="material-symbols-outlined !text-[16px]">apps</i>
                        Todos
                    </a>
                    @foreach($hogares as $hogar)
                    <a href="{{ route('dashboard.miembros.index', array_filter(['hogar' => $hogar->id, 'search' => $search])) }}"
                       class="inline-flex items-center gap-[6px] px-[14px] py-[7px] rounded-[8px] border text-sm font-medium transition-all
                           {{ $hogarSeleccionado?->id === $hogar->id
                               ? 'bg-primary-500 border-primary-500 text-white'
                               : 'border-gray-200 dark:border-[#172036] text-gray-600 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500' }}">
                        <i class="material-symbols-outlined !text-[16px]">home</i>
                        <span class="max-w-[180px] truncate">{{ $hogar->nombre }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Buscador + botón Agregar -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="sm:flex items-center justify-between gap-[15px]">
                    <form method="GET"
                          action="{{ route('dashboard.miembros.index', $hogarSeleccionado ? ['hogar' => $hogarSeleccionado->id] : []) }}"
                          class="relative sm:w-[265px]">
                        <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                            <i class="material-symbols-outlined !text-[20px]">search</i>
                        </label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar miembro..."
                            class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] rtl:pl-[13px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                    </form>
                    <div class="mt-[15px] sm:mt-0 flex-shrink-0">
                        <a href="{{ route('dashboard.miembros.create', $hogarSeleccionado ? ['hogar' => $hogarSeleccionado->id] : []) }}"
                           class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white">
                            <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">add</i>
                                Agregar Miembro
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Grid de cards de miembros -->
            @if($miembros->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-[25px] mb-[25px]">
                    @foreach($miembros as $miembro)
                        @php $persona = $miembro->user?->persona; @endphp
                        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                            <div class="trezo-card-content">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center min-w-0">
                                        <!-- Avatar -->
                                        <div class="rounded-full w-[65px] h-[65px] border-[2px] border-gray-100 dark:border-[#172036] flex items-center justify-center bg-primary-50 dark:bg-[#15203c] overflow-hidden flex-shrink-0">
                                            @if($persona?->avatar_url)
                                                <img src="{{ $persona->avatar_url }}" alt="{{ $persona->nombres }}" class="w-full h-full object-cover rounded-full">
                                            @else
                                                <span class="text-primary-500 font-bold text-xl">
                                                    {{ strtoupper(substr($persona?->nombres ?? '?', 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <!-- Nombre + parentesco -->
                                        <div class="ltr:ml-[12px] rtl:mr-[12px] min-w-0">
                                            <span class="block text-md mb-[2px] font-medium text-black dark:text-white truncate">
                                                {{ trim(($persona?->nombres ?? '') . ' ' . ($persona?->apellido_paterno ?? '') . ' ' . ($persona?->apellido_materno ?? '')) ?: '–' }}
                                            </span>
                                            <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                @if($miembro->parentesco)
                                                    {{ $miembro->parentesco->nombre }}
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500">Sin parentesco</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Acciones -->
                                    <div class="flex items-center gap-[9px] flex-shrink-0 ltr:ml-[8px] rtl:mr-[8px]">
                                        @if($persona)
                                            <a href="{{ route('dashboard.personas.show', $persona) }}"
                                               class="text-primary-500 leading-none custom-tooltip" data-text="Ver persona">
                                                <i class="material-symbols-outlined !text-md">visibility</i>
                                            </a>
                                        @endif
                                        <a href="{{ route('dashboard.miembros.edit', $miembro) }}"
                                           class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip" data-text="Editar">
                                            <i class="material-symbols-outlined !text-md">edit</i>
                                        </a>
                                        <form method="POST" action="{{ route('dashboard.miembros.destroy', $miembro) }}" class="inline"
                                              onsubmit="return confirm('¿Eliminar a {{ addslashes(trim(($persona?->nombres ?? '') . ' ' . ($persona?->apellido_paterno ?? ''))) ?: 'este miembro' }} del hogar? Esta acción no se puede deshacer.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger-500 leading-none custom-tooltip" data-text="Eliminar">
                                                <i class="material-symbols-outlined !text-md">delete</i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Datos -->
                                <ul class="mt-[17px] space-y-[8px] text-sm">
                                    <li class="flex items-start gap-[6px]">
                                        <span class="text-gray-500 dark:text-gray-400 flex-shrink-0 w-[90px]">Documento:</span>
                                        <span class="text-black dark:text-white">
                                            @if($persona?->tipoDocumento){{ $persona->tipoDocumento->codigo }} · @endif{{ $persona?->numero_documento ?? '–' }}
                                        </span>
                                    </li>
                                    <li class="flex items-start gap-[6px]">
                                        <span class="text-gray-500 dark:text-gray-400 flex-shrink-0 w-[90px]">Teléfono:</span>
                                        <span class="text-black dark:text-white">{{ $persona?->telefono ?? '–' }}</span>
                                    </li>
                                    <li class="flex items-start gap-[6px]">
                                        <span class="text-gray-500 dark:text-gray-400 flex-shrink-0 w-[90px]">Nacimiento:</span>
                                        <span class="text-black dark:text-white">{{ $persona?->fecha_nacimiento?->format('d M Y') ?? '–' }}</span>
                                    </li>
                                    <li class="flex items-center gap-[6px]">
                                        <span class="text-gray-500 dark:text-gray-400 flex-shrink-0 w-[90px]">Rol:</span>
                                        @if($miembro->rol === 'admin')
                                            <span class="px-[7px] py-[2px] inline-block bg-success-100 dark:bg-[#15203c] text-success-600 rounded-sm text-xs font-medium">Titular</span>
                                        @elseif($miembro->rol === 'editor')
                                            <span class="px-[7px] py-[2px] inline-block bg-warning-50 text-warning-700 rounded-sm text-xs font-medium">Editor</span>
                                        @else
                                            <span class="px-[7px] py-[2px] inline-block bg-gray-100 dark:bg-[#15203c] text-gray-600 rounded-sm text-xs font-medium">Miembro</span>
                                        @endif
                                    </li>
                                    @if(!$hogarSeleccionado && $hogares->count() > 1)
                                    <li class="flex items-start gap-[6px] pt-[6px] border-t border-gray-100 dark:border-[#172036]">
                                        <span class="text-gray-500 dark:text-gray-400 flex-shrink-0 w-[90px]">Hogar:</span>
                                        <span class="text-black dark:text-white truncate">{{ $miembro->hogar?->nombre ?? '–' }}</span>
                                    </li>
                                    @endif
                                    @if($miembro->apodo)
                                    <li class="flex items-start gap-[6px]">
                                        <span class="text-gray-500 dark:text-gray-400 flex-shrink-0 w-[90px]">Apodo:</span>
                                        <span class="text-black dark:text-white italic">{{ $miembro->apodo }}</span>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-content flex flex-col items-center py-[60px] gap-[12px]">
                        <i class="material-symbols-outlined text-gray-300 dark:text-gray-600" style="font-size:48px">group</i>
                        <p class="!mb-0 text-gray-500 dark:text-gray-400">
                            @if($search)
                                No se encontraron miembros con "{{ $search }}".
                            @elseif($hogarSeleccionado)
                                No hay miembros registrados en <strong>{{ $hogarSeleccionado->nombre }}</strong>.
                            @else
                                No hay miembros registrados en el hogar.
                            @endif
                        </p>
                        @unless($search)
                            <a href="{{ route('dashboard.miembros.create', $hogarSeleccionado ? ['hogar' => $hogarSeleccionado->id] : []) }}"
                               class="text-primary-500 text-sm hover:underline">
                                Agregar el primer miembro
                            </a>
                        @endunless
                    </div>
                </div>
            @endif

            <!-- Paginación -->
            @if($miembros->total() > 0)
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-content">
                    <div class="sm:flex sm:items-center justify-between">
                        <p class="!mb-0 text-sm">
                            Mostrando {{ $miembros->firstItem() }}–{{ $miembros->lastItem() }} de {{ $miembros->total() }} miembros
                        </p>
                        <ol class="mt-[10px] sm:mt-0">
                            <li class="inline-block mx-[1px]">
                                @if($miembros->onFirstPage())
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </span>
                                @else
                                    <a href="{{ $miembros->previousPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_left</i>
                                    </a>
                                @endif
                            </li>
                            @foreach($miembros->getUrlRange(max(1, $miembros->currentPage()-2), min($miembros->lastPage(), $miembros->currentPage()+2)) as $page => $url)
                            <li class="inline-block mx-[1px]">
                                <a href="{{ $url }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border text-sm {{ $page == $miembros->currentPage() ? 'border-primary-500 bg-primary-500 text-white' : 'border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500' }}">
                                    {{ $page }}
                                </a>
                            </li>
                            @endforeach
                            <li class="inline-block mx-[1px]">
                                @if($miembros->hasMorePages())
                                    <a href="{{ $miembros->nextPageUrl() }}" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i>
                                    </a>
                                @else
                                    <span class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] opacity-40 cursor-not-allowed">
                                        <span class="opacity-0">0</span>
                                        <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">chevron_right</i>
                                    </span>
                                @endif
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            @endif

            <div class="grow"></div>
            @include('partials.dashboard.footer')
        </div>

        @include('partials.front.scripts')
    </body>
</html>
