@props([
    'livewire' => null,
])

<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}"
    @class([
        'fi min-h-screen',
        'dark' => filament()->hasDarkModeForced(),
    ])
>
    <head>

        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @if ($favicon = filament()->getFavicon())
            <link rel="icon" href="{{ $favicon }}" />
        @endif

        <title>
            {{ filled($title = strip_tags(($livewire ?? null)?->getTitle() ?? '')) ? "{$title} - " : null }}
            {{ filament()->getBrandName() }}
        </title>


        <style>
            [x-cloak=''],
            [x-cloak='x-cloak'],
            [x-cloak='1'] {
                display: none !important;
            }

            @media (max-width: 1023px) {
                [x-cloak='-lg'] {
                    display: none !important;
                }
            }

            @media (min-width: 1024px) {
                [x-cloak='lg'] {
                    display: none !important;
                }
            }
        </style>

        @filamentStyles

        {{ filament()->getTheme()->getHtml() }}
        {{ filament()->getFontHtml() }}

        <style>
            :root {
                --font-family: '{!! filament()->getFontFamily() !!}';
                --sidebar-width: {{ filament()->getSidebarWidth() }};
                --collapsed-sidebar-width: {{ filament()->getCollapsedSidebarWidth() }};
            }
        </style>

        @stack('styles')


        <script>
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    const activeSidebarItem = document.querySelector(
                        '.fi-sidebar-item-active',
                    )

                    if (!activeSidebarItem) {
                        return
                    }

                    const sidebarWrapper =
                        document.querySelector('.fi-sidebar-nav')

                    if (!sidebarWrapper) {
                        return
                    }

                    sidebarWrapper.scrollTo(
                        0,
                        activeSidebarItem.offsetTop - window.innerHeight / 2,
                    )
                }, 0)
            })
        </script>

        @if (! filament()->hasDarkMode())
            <script>
                localStorage.setItem('theme', 'light')
            </script>
        @elseif (filament()->hasDarkModeForced())
            <script>
                localStorage.setItem('theme', 'dark')
            </script>
        @else
            <script>
                const theme = localStorage.getItem('theme') ?? @js(filament()->getDefaultThemeMode()->value)

                if (
                    theme === 'dark' ||
                    (theme === 'system' &&
                        window.matchMedia('(prefers-color-scheme: dark)')
                            .matches)
                ) {
                    document.documentElement.classList.add('dark')
                }
            </script>
        @endif

    </head>

    <body
        {{ $attributes
                ->merge(($livewire ?? null)?->getExtraBodyAttributes() ?? [], escape: false)
                ->class([
                    'fi-body',
                    'fi-panel-' . filament()->getId(),
                    'min-h-screen bg-gray-50 font-normal text-gray-950 antialiased dark:bg-gray-950 dark:text-white',
                ]) }}
    >


<div class="fi-simple-layout flex min-h-screen flex-col items-center">
    <div class="fi-simple-main-ctn flex w-full flex-grow items-center justify-center">
        <main class="fi-simple-main my-16 w-full bg-white px-6 py-12 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 sm:rounded-xl sm:px-12 sm:max-w-lg">
            <div class="fi-simple-page">

                <section class="grid auto-cols-fr gap-y-6">
                    <header class="fi-simple-header flex flex-col items-center">
                        <div style="height: 2rem;" class="fi-logo flex dark:hidden mb-4">
                            <div class="flex items-center justify-between">
                                <img 
                                    src="/assets/logos/va-swm.png"
                                    alt="Service Track" 
                                    loading="lazy" 
                                    style="height: 2.5rem;" 
                                />
                                <h1 class="ml-4 font-bold text-xl intalic">{{ config('app.name'); }}</h1>
                            </div>
                        </div>
                        <div style="height: 2rem;" class="fi-logo hidden dark:flex mb-4">
                            <div class="flex items-center justify-between">
                                <img 
                                    src="/assets/logos/va-swm-dark.png"
                                    alt="Service Track" 
                                    loading="lazy" 
                                    style="height: 2.5rem;" 
                                />
                                <h1 class="ml-4 font-bold text-xl intalic">{{ config('app.name'); }}</h1>
                            </div>
                        </div>
                        <h1 class="fi-simple-header-heading text-center text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                            500 | Server Error
                        </h1>
                        <br>
                        <b><a href="/">Click here to return to home</a></b>
                    </header>
                </section>

            </div>
        </main>
    </div>        
</div>


        @livewire(Filament\Livewire\Notifications::class)


        @filamentScripts(withCore: true)

        @if (config('filament.broadcasting.echo'))
            <script data-navigate-once>
                window.Echo = new window.EchoFactory(@js(config('filament.broadcasting.echo')))

                window.dispatchEvent(new CustomEvent('EchoLoaded'))
            </script>
        @endif

        @stack('scripts')

    </body>
</html>
