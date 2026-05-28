<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('partials.front.styles')

        <title>DigitalHome - Iniciar sesión</title>

        @vite('resources/css/app.css')
    </head>
    <body>
        <!-- Light/Dark Mode Button -->
        <button type="button" class="light-dark-toggle leading-none inline-block transition-all text-[#fe7a36] absolute top-[20px] md:top-[25px] ltr:right-[20px] rtl:left-[20px] ltr:md:right-[25px] rtl:md:left-[25px]" id="light-dark-toggle">
            <i class="material-symbols-outlined !text-[20px] md:!text-[22px]">
                light_mode
            </i>
        </button>
        <!-- End Light/Dark Mode Button -->

        <!-- Sign In -->
        <div class="bg-white dark:bg-[#0a0e19] py-[60px] md:py-[80px] lg:py-[135px]">
            <div class="mx-auto px-[12.5px] md:max-w-[720px] lg:max-w-[960px] xl:max-w-[1255px]">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] items-center">
                    <div class="xl:ltr:-mr-[25px] xl:rtl:-ml-[25px] 2xl:ltr:-mr-[45px] 2xl:rtl:-ml-[45px] rounded-[25px] order-2 lg:order-1">
                        <img src="/assets/images/sign-in.jpg" alt="sign-in-image" class="rounded-[25px]">
                    </div>
                    <div class="xl:ltr:pl-[90px] xl:rtl:pr-[90px] 2xl:ltr:pl-[120px] 2xl:rtl:pr-[120px] order-1 lg:order-2">
                        <img src="/assets/images/logo-big.svg" alt="logo" class="inline-block dark:hidden">
                        <img src="/assets/images/white-logo-big.svg" alt="logo" class="hidden dark:inline-block">

                        <div class="my-[17px] md:my-[25px]">
                            <h1 class="!font-semibold !text-[22px] md:!text-xl lg:!text-2xl !mb-[5px] md:!mb-[7px]">
                                ¡Bienvenido de vuelta!
                            </h1>
                            <p class="font-medium lg:text-md text-[#445164] dark:text-gray-400">
                                Inicia sesión con Google o ingresa tus datos
                            </p>
                        </div>

                        {{-- Errores generales --}}
                        @if ($errors->any())
                            <div class="mb-[15px] p-[12px] rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                                <p class="text-red-600 dark:text-red-400 text-sm">
                                    {{ $errors->first() }}
                                </p>
                            </div>
                        @endif

                        {{-- Botón Google --}}
                        <div class="mb-[20px] md:mb-[23px]">
                            <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-[10px] w-full rounded-md transition-all py-[10px] md:py-[12px] px-[15px] text-black dark:text-white border border-[#D6DAE1] bg-white dark:bg-[#0a0e19] dark:border-[#172036] shadow-sm hover:border-primary-500">
                                <img src="/assets/images/icons/google.svg" class="w-[20px]" alt="google">
                                <span class="font-medium text-sm">Continuar con Google</span>
                            </a>
                        </div>

                        {{-- Separador --}}
                        <div class="flex items-center gap-[10px] mb-[20px]">
                            <div class="flex-1 h-px bg-gray-200 dark:bg-[#172036]"></div>
                            <span class="text-sm text-gray-400">o</span>
                            <div class="flex-1 h-px bg-gray-200 dark:bg-[#172036]"></div>
                        </div>

                        {{-- Formulario email/password --}}
                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf

                            <div class="mb-[15px] relative">
                                <label class="mb-[10px] md:mb-[12px] text-black dark:text-white font-medium block">
                                    Correo electrónico
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="h-[55px] rounded-md text-black dark:text-white border @error('email') border-red-500 @else border-gray-200 dark:border-[#172036] @enderror bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                    placeholder="ejemplo@correo.com"
                                    autocomplete="email"
                                    required
                                >
                                @error('email')
                                    <p class="mt-[5px] text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-[15px] relative" id="passwordHideShow">
                                <label class="mb-[10px] md:mb-[12px] text-black dark:text-white font-medium block">
                                    Contraseña
                                </label>
                                <input
                                    type="password"
                                    name="password"
                                    class="h-[55px] rounded-md text-black dark:text-white border @error('password') border-red-500 @else border-gray-200 dark:border-[#172036] @enderror bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                    id="password"
                                    placeholder="Tu contraseña"
                                    autocomplete="current-password"
                                    required
                                >
                                <button class="absolute text-lg ltr:right-[20px] rtl:left-[20px] bottom-[12px] transition-all hover:text-primary-500" id="toggleButton" type="button">
                                    <i class="ri-eye-off-line"></i>
                                </button>
                                @error('password')
                                    <p class="mt-[5px] text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between mb-[20px]">
                                <label class="flex items-center gap-[8px] cursor-pointer">
                                    <input type="checkbox" name="remember" class="w-4 h-4 accent-primary-500">
                                    <span class="text-sm text-[#445164] dark:text-gray-400">Recordarme</span>
                                </label>
                                <a href="{{ route('forgot.password') }}" class="text-primary-500 transition-all font-semibold text-sm hover:underline">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>

                            <button type="submit" class="md:text-md block w-full text-center transition-all rounded-md font-medium py-[12px] px-[25px] text-white bg-primary-500 hover:bg-primary-400">
                                <span class="flex items-center justify-center gap-[5px]">
                                    <i class="material-symbols-outlined">login</i>
                                    Iniciar sesión
                                </span>
                            </button>
                        </form>

                        <p class="mt-[15px] md:mt-[20px]">
                            ¿No tienes cuenta?
                            <a href="{{ route('register') }}" class="text-primary-500 transition-all font-semibold hover:underline">Regístrate</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Sign In -->

        @include('partials.front.scripts')
    </body>
</html>
