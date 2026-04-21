<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body
    class="bg-white dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900 text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-9/12 text-sm mb-6 not-has-[nav]:hidden">
        
        @if (Route::has('login'))
            <nav class="flex items-center justify-between gap-4">
                    <x-app-logo :sidebar="false" href="{{ route('home') }}" wire:navigate />
                @auth
                    <a href="{{ route('yourbase') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        YourBase
                    </a>
                @else
                    <div>
                        <a href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    </div>
                @endauth
            </nav>
        @endif
    </header>
    <div
        class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex max-w-9/12 w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
            <div
                class="text-[13px] leading-[20px] flex-1 p-6 lg:p-10 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-2xl">

                <span
                    class="inline-block text-[11px] font-medium tracking-widest uppercase text-green-700 dark:text-green-400 border border-green-700/30 dark:border-green-400/30 rounded-full px-3 py-1 mb-5">
                    Now in beta
                </span>

                <h1
                    class="text-3xl lg:text-5xl font-semibold tracking-tight leading-[1.15] text-[#1a1a00] dark:text-[#EDEDEC] mb-4">
                    Your link in bio,<br> done right.
                </h1>

                <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">
                    One clean URL for every link, project, and profile you want people to find. Fast, minimal, and
                    actually yours.
                </p>

            </div>
        </main>
    </div>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>

</html>